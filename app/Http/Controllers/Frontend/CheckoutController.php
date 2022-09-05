<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Jobs\OrderConfirmMail;
use App\Models\Admin\Billing;
use App\Models\Admin\Coupon;
use App\Models\Admin\Order;
use App\Models\Admin\OrderDetails;
use App\Models\Admin\Product;
use App\Models\Admin\Shipping;
use App\Models\Currency;
use App\Models\PaymentPlatform;
use App\Models\SeoSetting;
use App\Resolvers\PaymentPlatformResolver;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Paystack;
use Razorpay\Api\Api;

class CheckoutController extends Controller
{
    private $grand_total;
    private $discount, $order_number, $shipping_charge, $tax, $subtotal;

    // return $this->orderCreateCall($order_number, $shipping_charge, $tax, $subtotal, $this->discount, $this->grand_total, PAYSTACK, $request->razorpay_payment_id);
    protected $paymentPlatformResolver;
    public function __construct(PaymentPlatformResolver $paymentPlatformResolver)
    {
        $this->discount = 0;
        $this->paymentPlatformResolver = $paymentPlatformResolver;
    }
    public function checkoutPage()
    {
        $check = Cart::count();
        if ($check) {
            $data['content'] = Cart::content();
            $data['currencies'] = Currency::all();
            $data['paymentPlatforms'] = PaymentPlatform::where('status', ACTIVE)->get();
            $data['billing'] = Billing::where('User_Id', Auth::id())->first();
            $data['shipping'] = Shipping::where('User_Id', Auth::id())->first();
            $seo = SeoSetting::where('slug', 'checkout')->first();
            $data['title'] = $seo->title;
            $data['description'] = $seo->description;
            $data['keywords'] = $seo->keywords;
            return view('front.pages.checkout.checkout', $data);
        } else {
            return redirect()->back()->with('toast_warning', 'Add at least one product');
        }
    }

    public function thankyouPage()
    {
        $data['title'] = __('Order Confirmed');
        $data['description'] = __('Order Confirmed');
        $data['keywords'] = __('Order Confirmed');
        return view('front.pages.checkout.thankyou', $data);
    }
    public function checkoutOrder(Request $request)
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            try {
                $subtotal = \Cart::subtotal();
                $tax = tax_amount($subtotal, $request->billing_country);
                $shipping_charge = delivery_charge($request->billing_country);
                $this->grand_total = $subtotal + $tax + $shipping_charge;

                if (hasBlillingAddress($user_id) == 1) {
                    $billing_create = $this->updateBillingAddress($request, $user_id);
                } else {
                    $billing_create = $this->createBillingAddress($request, $user_id);
                }
                session()->put('billing_id', $billing_create->id);
                if (hasShippingAddress($user_id) == 1) {
                    $shipping_create = $this->updateShippingAddress($request, $user_id);
                } else {
                    $shipping_create = $this->createShippingAddress($request, $user_id);
                }
                session()->put('shipping_id', $shipping_create->id);
                do {
                    $order_number = $this->generateRandomString(6);
                    $exists_order_number = Order::where('Order_Number', $order_number)->exists();
                } while ($exists_order_number);
                if (Session::has('Coupon_Id')) {
                    $coupon = Coupon::whereId(Session::get('Coupon_Id'))->first();
                    if ($coupon) {
                        $orderCoupon = Order::where('Coupon_Id', $coupon->id)->where('User_Id', $user_id)->count();
                        if ($orderCoupon != 0) {
                            session()->put('couponCode', null);
                            return redirect()->back()->with('toast_error', 'Already used coupon Code');
                        }
                        $this->discount = $coupon->Amount;

                    }
                }

                session()->put('order_number', $order_number);
                session()->put('shipping_charge', $shipping_charge);
                session()->put('subtotal', $subtotal);
                session()->put('discount', $this->discount);
                session()->put('grand_total', $this->grand_total);
                session()->put('tax', $tax);
                if ($request->payment == 'creditcard') {
                    session()->put('payment_method_name', STRIPE);
                    return $this->pay($this->grand_total, $this->discount, 'NGN', 2, $request->payment_method);
                } elseif ($request->payment == 'paypal') {
                    session()->put('payment_method_name', PAYPAL);
                    return $this->pay($this->grand_total, $this->discount, 'NGN', 1, $request->payment_method);
                } elseif ($request->payment == 'COD') {
                    return $this->orderCreateCall($order_number, $shipping_charge, $tax, $subtotal, $this->discount, $this->grand_total, COD);
                } elseif ($request->payment == 'bank') {
                    return $this->orderCreateCall($order_number, $shipping_charge, $tax, $subtotal, $this->discount, $this->grand_total, BANK_TRANSFER, $request->bank_transaction_number);
                } elseif ($request->payment == 'razorpay') {
                    $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
                    $payment = $api->payment->fetch($request->razorpay_payment_id);
                    try
                    {
                        $response = $api->payment->fetch($request->razorpay_payment_id)->capture(array('amount' => $payment['amount']));
                        return $this->orderCreateCall($order_number, $shipping_charge, $tax, $subtotal, $this->discount, $this->grand_total, RAZORPAY, $request->razorpay_payment_id);

                    } catch (\Exception$e) {
                        return redirect()->back()->with('toast_error', 'something went wrong in razorpay.');
                    }
                } else {
                    return redirect()->back()->with('toast_error', 'Payment Failed');
                }
            } catch (\Exception$e) {
                return redirect()->back()->with('toast_error', 'something went wrong');
            }
        } else {
            return redirect()->back()->with('toast_error', 'Please sign in');
        }
    }

    public function guestCheckoutOrder(Request $request)
    {
        $billing_address = [
            'name' => $request->billing_name,
            'email' => $request->billing_email,
            'street' => $request->billing_street_address,
            'state' => $request->billing_state,
            'zipcode' => $request->billing_zipcode,
            'country' => $request->billing_country,
        ];
        $shipping_addresss = [
            'name' => $request->shipping_name,
            'email' => $request->shipping_email,
            'street' => $request->shipping_street_address,
            'state' => $request->shipping_state,
            'zipcode' => $request->shipping_zipcode,
            'country' => $request->shipping_country,
        ];
        Session::put('billing_address', $billing_address);
        Session::put('shipping_address', $shipping_addresss);
        Session::put('checkout_email', $request->billing_email);
        $subtotal = \Cart::subtotal();
        $this->subtotal = $subtotal;
        $tax = tax_amount($subtotal, $request->billing_country);
        $this->tax = $tax;
        $shipping_charge = delivery_charge($request->billing_country);
        $this->shipping_charge = $shipping_charge;
        $this->grand_total = $subtotal + $tax + $shipping_charge;

        do {
            $order_number = $this->generateRandomString(6);
            $exists_order_number = Order::where('Order_Number', $order_number)->exists();
        } while ($exists_order_number);
        $this->order_number = $order_number;
        if (Auth::check() && Session::has('Coupon_Id')) {
            $user_id = Auth::id();
            if (hasBlillingAddress($user_id) == 1) {
                $this->updateBillingAddress($request, $user_id);
            } else {
                $this->createBillingAddress($request, $user_id);
            }

            if (hasShippingAddress($user_id) == 1) {
                $this->updateShippingAddress($request, $user_id);
            } else {
                $this->createShippingAddress($request, $user_id);
            }
            $coupon = Coupon::whereId(Session::get('Coupon_Id'))->first();
            if ($coupon) {
                $orderCoupon = Order::where('Coupon_Id', $coupon->id)->where('User_Id', $user_id)->count();
                if ($orderCoupon != 0) {
                    session()->put('couponCode', null);
                    return redirect()->back()->with('toast_error', 'Already used coupon Code');
                }
                $this->discount = $coupon->Amount;

            }
        }

        session()->put('order_number', $order_number);
        session()->put('shipping_charge', $shipping_charge);
        session()->put('subtotal', $subtotal);
        session()->put('discount', $this->discount);
        session()->put('grand_total', $this->grand_total);
        session()->put('tax', $tax);
        if ($request->payment == 'creditcard') {
            session()->put('payment_method_name', STRIPE);
            return $this->pay($this->grand_total, $this->discount, 'NGN', 2, $request->payment_method);
        } elseif ($request->payment == 'paypal') {
            session()->put('payment_method_name', PAYPAL);
            return $this->pay($this->grand_total, $this->discount, 'NGN', 1, $request->payment_method);
        } elseif ($request->payment == 'COD') {
            return $this->orderCreateCall($order_number, $shipping_charge, $tax, $subtotal, $this->discount, $this->grand_total, COD);
        } elseif ($request->payment == 'bank') {
            return $this->orderCreateCall($order_number, $shipping_charge, $tax, $subtotal, $this->discount, $this->grand_total, BANK_TRANSFER, $request->bank_transaction_number);
        } elseif ($request->payment == 'razorpay') {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $payment = $api->payment->fetch($request->razorpay_payment_id);
            try
            {
                $response = $api->payment->fetch($request->razorpay_payment_id)->capture(array('amount' => $payment['amount']));
                return $this->orderCreateCall($order_number, $shipping_charge, $tax, $subtotal, $this->discount, $this->grand_total, RAZORPAY, $request->razorpay_payment_id);

            } catch (\Exception$e) {
                return redirect()->back()->with('toast_error', 'something went wrong in razorpay.');
            }
        } elseif ($request->payment == 'paystack') {

            $data = [
                "amount" => intval($this->grand_total) * 100,
                "reference" => Paystack::genTranxRef(),
                "email" => $request->billing_email,
                "currency" => "NGN",
                "orderID" => $order_number,
                'metadata' => [
                    'order_number'=> $order_number,
                    'shipping_charge' => $shipping_charge,
                    'tax' => $tax,
                    'subtotal'=> $subtotal,
                    'discount' => $this->discount,
                    'grand_total' => $this->grand_total,
                    'payment_method' => PAYSTACK,

                ]
                ];

            // dd($data);
            // return Paystack::getAuthorizationUrl($data)->redirectNow();
            try
            {
                // $response = $api->payment->fetch($request->razorpay_payment_id)->capture(array('amount'=>$payment['amount']));
                return Paystack::getAuthorizationUrl($data)->redirectNow();
                // return $this->orderCreateCall($order_number, $shipping_charge, $tax, $subtotal, $this->discount, $this->grand_total, PAYSTACK, $request->razorpay_payment_id);

            } catch (\Exception$e) {
                return redirect()->back()->with('toast_error', 'something went wrong in Paystack.');
            }
        } else {
            return redirect()->back()->with('toast_error', 'Payment Failed');
        }
    }

    public function handlePaystack()
    {
        $paymentDetails = Paystack::getPaymentData();
        // dd($paymentDetails);
        if ($paymentDetails['data']['status'] == 'success') {
            // return $this->orderCreateCall($this->order_number, $this->shipping_charge, $this->tax, $this->subtotal, $this->discount, $this->grand_total, PAYSTACK);
            return $this->orderCreateCall($paymentDetails['data']['metadata']['order_number'],
             $paymentDetails['data']['metadata']['shipping_charge'],
             $paymentDetails['data']['metadata']['tax'],
             $paymentDetails['data']['metadata']['subtotal'],
             $paymentDetails['data']['metadata']['discount'],
             $paymentDetails['data']['metadata']['grand_total'],
              PAYSTACK);
        }
        return redirect()->back()->with('toast_error', 'Payment not successful');
    }

    public function pay($amount, $discount, $currency, $payment_platform, $payment_method)
    {
        $value = $amount - $discount;
        $paymentPlatform = $this->paymentPlatformResolver
            ->resolveService($payment_platform);
        session()->put('paymentPlatformId', $payment_platform);
        return $paymentPlatform->handlePayment($value, $currency, $payment_method);
    }

    public function approval()
    {
        if (session()->has('paymentPlatformId')) {

            $paymentPlatform = $this->paymentPlatformResolver
                ->resolveService(session()->get('paymentPlatformId'));

            $payment = $paymentPlatform->handleApproval();

            if ($payment['success'] == true) {
                return $this->orderCreateCall(
                    session()->get('order_number'),
                    session()->get('shipping_charge'),
                    session()->get('tax'),
                    session()->get('subtotal'),
                    session()->get('discount'),
                    session()->get('grand_total'),
                    session()->get('payment_method_name')
                );
            }
            return redirect()->back()->with('toast_error', $payment['message']);
        }
        return redirect()->back()->with('toast_error', 'We can not retrieve payment platform. Please,  try again!');
    }

    public function cancelled()
    {
        return redirect()->back()->with('toast_error', 'Payment cancelled!');
    }

    public function orderCreateCall($order_number, $shipping_charge, $tax, $subtotal, $discount, $grand_total, $payment_method, $txn = null)
    {
        $payment_status = $this->paymentStatus($payment_method);
        $order = $this->orederCreate($order_number, $shipping_charge, $tax, $subtotal, $discount, $grand_total, $payment_method, $payment_status, $txn);
        if ($order['success'] == true) {
            session()->forget('Coupon_Id');
            \Cart::destroy();
            $this->orderConfirmMail();
            return redirect()->route('checkout.thankyou_page')->with('toast_success', 'Order successfully created!');
        }
        return redirect()->back()->with('toast_error', 'Order not accepted');
    }

    public function orderConfirmMail()
    {
        $data['userName'] = 'John Doe';
        $data['userEmail'] = session()->get('checkout_email');
        $data['companyName'] = isset(allsetting()['app_title']) && !empty(allsetting()['app_title']) ? allsetting()['app_title'] : __('Company Name');
        $data['subject'] = __('Order Confirm Mail');
        $data['data'] = session()->get('checkout_number');
        $data['template'] = 'email.order-confirm';
        dispatch(new OrderConfirmMail($data))->onQueue('email-send');
    }

    public function paymentStatus($payment_method)
    {
        if ($payment_method == STRIPE || $payment_method == PAYPAL) {
            return PAYMENT_SUCCESS;
        }
        return PAYMENT_PENDING;
    }

    public function orederCreate($order_number, $shipping_charge, $tax, $subtotal, $discount, $grand_total, $payment_method, $payment_status, $txn = null)
    {
        $data = ['success' => false, 'data' => []];
        $order = Order::create([
            'Order_Number' => $order_number,
            'User_Id' => Auth::check() ? Auth::id() : null,
            'Billing_Id' => session('billing_id'),
            'Shipping_Id' => session('shipping_id'),
            'billing_address' => json_encode(Session::get('billing_address'), true),
            'shipping_address' => json_encode(Session::get('shipping_address'), true),
            'Delivery_Charge' => $shipping_charge,
            'Tax' => $tax,
            'Sub_Total' => $subtotal,
            'Coupon_Id' => Session::get('Coupon_Id'),
            'Coupon_Amount' => $discount,
            'Grand_Total' => $grand_total - $discount,
            'Is_Free_Delivery' => false,
            'Is_Order_Successful' => false,
            'Is_Order_Completed' => false,
            'Payment_Method' => $payment_method,
            'Payment_Status' => $payment_status,
            'Order_Status' => ORDER_PENDING,
            'txn' => $txn != null ? $txn : randomString(8),
        ]);
        if ($order) {
            session()->put('Coupon_Id', null);
            session()->put('couponCode', null);
            session()->put('CouponAmount', 0);
            session()->put('order_id', $order->id);
            session()->put('checkout_number', $order->Order_Number);
            $content = \Cart::content();
            foreach ($content as $item) {
                $this->subQtyProduct($item->id, $item->qty);
                OrderDetails::create([
                    'Order_Id' => $order->id,
                    'Product_Id' => $item->id,
                    'Product_Name' => $item->name,
                    'Image' => $item->options->image,
                    'Price' => $item->price,
                    'Color' => $item->options->color,
                    'Size' => $item->options->size,
                    'Quantity' => $item->qty,
                    'Total_Price' => $item->price * $item->qty,
                ]);
            }
            $data['success'] = true;
        }
        return $data;

    }

    public function subQtyProduct($product_id, $qty)
    {
        $product = Product::whereId($product_id)->first();
        $new_qty = $product->Quantity - $qty;
        if ($new_qty < 1) {
            $nn_qty = 0;
        } else {
            $nn_qty = $new_qty;
        }
        $product->update([
            'Quantity' => $nn_qty,
        ]);
    }

    public function redirectStripePage()
    {
        $data['currencies'] = Currency::all();
        $data['paymentPlatforms'] = PaymentPlatform::all();
        return view('front.pages.checkout.stripe', $data);
    }

    public function createBillingAddress($request, $user_id)
    {
        return Billing::create([
            'User_Id' => $user_id,
            'Name' => $request->billing_name,
            'Email' => $request->billing_email,
            'Street' => $request->billing_street_address,
            'State' => $request->billing_state,
            'Zipcode' => $request->billing_zipcode,
            'Country' => $request->billing_country,
        ]);
    }

    public function updateBillingAddress($request, $user_id)
    {
        $billing = Billing::where('User_Id', $user_id)->first();
        $billing->update([
            'Name' => $request->billing_name,
            'Email' => $request->billing_email,
            'Street' => $request->billing_street_address,
            'State' => $request->billing_state,
            'Zipcode' => $request->billing_zipcode,
            'Country' => $request->billing_country,
        ]);
        return $billing;
    }

    public function createShippingAddress($request, $user_id)
    {
        return Shipping::create([
            'User_Id' => $user_id,
            'Name' => $request->shipping_name,
            'Email' => $request->shipping_email,
            'Street' => $request->shipping_street_address,
            'State' => $request->shipping_state,
            'Zipcode' => $request->shipping_zipcode,
            'Country' => $request->shipping_country,
        ]);
    }

    public function updateShippingAddress($request, $user_id)
    {
        $shipping = Shipping::where('User_Id', $user_id)->first();
        $shipping->update([
            'Name' => $request->shipping_name,
            'Email' => $request->shipping_email,
            'Street' => $request->shipping_street_address,
            'State' => $request->shipping_state,
            'Zipcode' => $request->shipping_zipcode,
            'Country' => $request->shipping_country,
        ]);
        return $shipping;
    }

    public function generateRandomString($length = 20)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getTaxAmount(Request $request)
    {
        $data = [
            'success' => false,
            'tax_rate' => 0,
            'tax_amount' => 0,
            'tax_show' => 0,
            'total_cost' => 0,
            'total_cost_curr' => 0,
            'delivery_charge' => 0,
            'delivery_charge_curr' => 0,
        ];
        $data['tax_rate'] = tax_rate($request->country);
        $data['tax_amount'] = tax_amount(\Cart::subtotal(), $request->country);
        $data['tax_show'] = currencySymbol()[currency()] . currencyConverter(tax_amount(\Cart::subtotal(), $request->country));
        $data['delivery_charge'] = delivery_charge($request->country);
        $data['delivery_charge_curr'] = currencySymbol()[currency()] . currencyConverter(delivery_charge($request->country));
        $data['total_cost'] = \Cart::subtotal() + delivery_charge($request->country) + tax_amount(\Cart::subtotal(), $request->country) - Session::get('CouponAmount');
        $data['total_cost_curr'] = currencySymbol()[currency()] . currencyConverter(\Cart::subtotal() + delivery_charge($request->country) + tax_amount(\Cart::subtotal(), $request->country) - Session::get('CouponAmount'));
        $data['success'] = true;
        return $data;
    }

    public function orderTrack(Request $request)
    {
        $order = Order::where('Order_Number', $request->order_number)->with('order_details', 'order_details.product')->first();
        if (is_null($order)) {
            return redirect()->back()->with('toast_error', __('No order found!'));
        }
        $data['order'] = Order::where('Order_Number', $request->order_number)->with('order_details', 'order_details.product')->first();
        $data['title'] = __('Order Track');
        $data['description'] = __('Order Track');
        $data['keywords'] = __('Order Track');
        return view('front.pages.checkout.order-track', $data);
    }
}

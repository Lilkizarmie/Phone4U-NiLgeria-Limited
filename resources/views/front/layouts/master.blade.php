<!DOCTYPE html>
<html lang="en">
@include('front.layouts.include.head')
@stack('post_css')
<body class="{{session()->has('lang_dir') && session()->get('lang_dir') == 'rtl' ? 'direction-rtl' : 'direction-ltr'}}">
<!-- Preloader Area Start -->
<div id="preloader">
    <div id="status">
        <img src="{{asset(IMG_PRELOADER_PATH.$allsettings['preloader'])}}" alt="img"/>
    </div>
</div>
<!-- Preloader Area End -->

@include('front.layouts.include.desktop_header')

@include('front.layouts.include.mobile_header')

@include('front.layouts.include.mobile_menu')

@include('front.layouts.include.cart_sidebar_menu')

@yield('content')
<div id="AddToCompareListItem" data-url="{{route('compare.list')}}"></div>
<div id="AddToCartIntoSession" data-url="{{ route('add.to.cart') }}"></div>
<div id="productWishlist" data-url="{{route('product.wishlist')}}"></div>
<div id="currency-price-url" data-url="{{route('currency_price')}}"></div>
<div id="currency-symbol-url" data-url="{{route('currency_symbol')}}"></div>
<div id="productImgAsset" data-url="{{asset(ProductImage())}}"></div>

@include('front.layouts.include.footer')

@yield('subscribe_pop_up_modal')

<div class="modal fade common-modal" id="trackOrderModal" tabindex="-1" aria-labelledby="trackOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">{{__('Track Order')}}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('checkout.order_track')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">{{__('Order Number')}}</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" name="order_number" placeholder="{{__('Enter order number')}}">
                    </div>
                    <div class="modal-btn-wrap text-end">
                        <button type="submit" class="primary-btn">{{__('Track')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('front.layouts.include.script')
@stack('post_script')
@include('sweetalert::alert')
</body>
</html>

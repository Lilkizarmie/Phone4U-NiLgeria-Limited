<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryCharge;
use App\Models\Tax;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EcommerceController extends Controller
{
    public function countryTaxList(Request $request)
    {
        if ($request->ajax()) {
            $data = Tax::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<div class="action__buttons">';
                    $btn = $btn.'<a href="javascript:void(0)" class="btn-action" data-bs-toggle="modal" data-bs-target="#editModal'.$data->id.'"><i class="fa-solid fa-pen-to-square"></i></a>';
                    $btn = $btn.'</div>';
                    return $btn;
                })
                ->editColumn('status', function ($data) {
                    if($data->status == ACTIVE) {
                        return '<span class="status active">Active</span>';
                    }else {
                        return '<span class="status blocked">Inactive</span>';
                    }
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        $data['title'] = __('Tax List');
        $data['taxes'] = Tax::get();
        return view('admin.pages.tax.country', $data);
    }

    public function countryTaxStore(Request $request)
    {
        $tax = Tax::where('country', $request->country)->first();
        if(!is_null($tax)) {
            $update = $tax->update([
                'country' => $request->country,
                'percentage' => $request->percentage,
            ]);
            if(!empty($update)) {
                return redirect()->back()->with('toast_success', __('Country tax already exist. It updated!'));
            }
        }else {
            $store = Tax::create([
                'country' => $request->country,
                'percentage' => $request->percentage,
            ]);
            if(!empty($store)) {
                return redirect()->back()->with('toast_success', __('Country tax added!'));
            }
        }
        return redirect()->back()->with('toast_error', __('Something went wrong'));
    }

    public function countryTaxUpdate(Request $request, $id)
    {
        $id = decrypt($id);
        $tax = Tax::where('id', $id)->first();
        if(!is_null($tax)) {
            $update = $tax->update([
                'country' => $request->country,
                'percentage' => $request->percentage,
                'status' => $request->status,
            ]);
            if(!empty($update)) {
                return redirect()->back()->with('toast_success', __('Country tax updated!'));
            }
        }
        return redirect()->back()->with('toast_error', __('Something went wrong'));
    }

    public function countryDCList(Request $request)
    {
        if ($request->ajax()) {
            $data = DeliveryCharge::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<div class="action__buttons">';
                    $btn = $btn.'<a href="javascript:void(0)" class="btn-action" data-bs-toggle="modal" data-bs-target="#editModal'.$data->id.'"><i class="fa-solid fa-pen-to-square"></i></a>';
                    $btn = $btn.'</div>';
                    return $btn;
                })
                ->editColumn('status', function ($data) {
                    if($data->status == ACTIVE) {
                        return '<span class="status active">Active</span>';
                    }else {
                        return '<span class="status blockedr">Inactive</span>';
                    }
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        $data['title'] = __('Delivery Charge List');
        $data['delivery_charges'] = DeliveryCharge::get();
        return view('admin.pages.delivery-charge.country', $data);
    }

    public function countryDCStore(Request $request)
    {
        $tax = DeliveryCharge::where('country', $request->country)->first();
        if(!is_null($tax)) {
            $update = $tax->update([
                'country' => $request->country,
                'charge' => $request->charge,
            ]);
            if(!empty($update)) {
                return redirect()->back()->with('toast_success', __('Delivery charge already exist. It updated!'));
            }
        }else {
            $store = DeliveryCharge::create([
                'country' => $request->country,
                'charge' => $request->charge,
            ]);
            if(!empty($store)) {
                return redirect()->back()->with('toast_success', __('Delivery charge added!'));
            }
        }
        return redirect()->back()->with('toast_error', __('Something went wrong'));
    }

    public function countryDCUpdate(Request $request, $id)
    {
        $id = decrypt($id);
        $tax = DeliveryCharge::where('id', $id)->first();
        if(!is_null($tax)) {
            $update = $tax->update([
                'country' => $request->country,
                'charge' => $request->charge,
                'status' => $request->status,
            ]);
            if(!empty($update)) {
                return redirect()->back()->with('toast_success', __('Country tax updated!'));
            }
        }
        return redirect()->back()->with('toast_error', __('Something went wrong'));
    }
}

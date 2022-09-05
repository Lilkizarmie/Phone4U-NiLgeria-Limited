<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageGalleryRequest;
use App\Models\Admin\ImageGallery;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class ImageGalleryController extends Controller
{
    public function imageGallery(Request $request){
        if ($request->ajax()) {
            $data = ImageGallery::whereNotNull('Image');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<div class="action__buttons">';
                    $btn = $btn.'<a href="' . route('admin.image.gallery.edit', $data->id) . '" class="btn-action"><i class="fa-solid fa-pen-to-square"></i></a>';
                    $btn = $btn.'</div>';
                    return $btn;
                })
                ->editColumn('Image', function ($data) {
                    if($data->Image){
                        $url = asset(ImageGallery() . $data->Image);
                        return '<img src=' . $url . ' border="0" width="70" class="img-rounded" align="center" />';
                    }else{
                        return 'No image';
                    }
                })
                ->rawColumns(['action', 'Image'])
                ->make(true);
        }
        $data['title'] = __('Gallery List');
        return view('admin.pages.about_us_image_gallery.index', $data);
    }

    public function imageGalleryCreate(){
        $data['title'] = __('Gallery Create');
        return view('admin.pages.about_us_image_gallery.create', $data);
    }
    public function imageGalleryStore(ImageGalleryRequest $request){

        if (!empty($request->image_gallery)) {
            $image = fileUpload($request['image_gallery'], ImageGallery());
        } else {
            return redirect()->back()->with('toast_error', __('Image is  required'));
        }
        $gallery = ImageGallery::create([
            'Image' => $image,
        ]);
        if ($gallery) {
            return redirect()->route('admin.image.gallery')->with('toast_success', __('Successfully Stored !'));
        }
        return redirect()->back()->with('toast_error', __('Does not insert  !'));
    }
    public function imageGalleryDelete($id){
        $delete = ImageGallery::where('id', $id);
        if ($delete) {
            $delete->delete();
            return redirect()->route('admin.image.gallery')->with('toast_success', __('Successfully Delete !'));
        }
        return redirect()->route('admin.image.gallery')->with('toast_error', __('Does not Delete !'));
    }
    public function imageGalleryEdit($id){
        $data['title'] = __('Gallery Edit');
        $data['edit'] = ImageGallery::where('id', $id)->first();
        return view('admin.pages.about_us_image_gallery.edit', $data);
    }
    public function imageGalleryUpdate(Request $request){
            $id = $request->id;
            $gallery = ImageGallery::whereId($id)->first();
            if (!empty($request->image_gallery)) {
                $image = fileUpload($request['image_gallery'], ImageGallery());
            } else {
                $image = $gallery->Image;
            }

            $update = $gallery->update([
                'Image' => $image
            ]);
            if ($update) {
                return redirect()->route('admin.image.gallery')->with('toast_success', __('Successfully Update !'));
            }
            return redirect()->back()->with('toast_error', __('Does not Update  !'));
        }
}

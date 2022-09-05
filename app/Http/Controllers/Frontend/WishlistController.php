<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Front\Wishlist;
use App\Models\SeoSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WishlistController extends Controller
{
    public function productWishlist(Request $request){
        if(Session::has('wishlist')){

            $wishlists = Session::get('wishlist');
            if(in_array($request->product_id,$wishlists)){
                $status = 0;
                $mgs = __('This product already added to wishlist');
                return response()->json(['message'=>$mgs,'status'=>$status]);
            }
            array_push($wishlists,$request->product_id);
            Session::put('wishlist',$wishlists);
            $status = 1;
            $mgs = __('Product added to wishlist successfully');

        }else{
            $wishlists = array($request->product_id);
            Session::put('wishlist',$wishlists);
            $status = 1;
            $mgs = __('Product added to wishlist successfully');
        }

        return response()->json(['message'=>$mgs,'status'=>$status,'wishlist_count'=>count($wishlists)]);
    }

    public function Wishlist(){
        $seo = SeoSetting::where('slug', 'compare')->first();
        $data['title'] = $seo->title;
        $data['description'] = $seo->description;
        $data['keywords'] = $seo->keywords;
        if(Session::has('wishlist') && count(Session::get('wishlist')) != 0) {
            $sname = [];
            $sdesc = [];
            $compare = [];
            $ids = Session::get('wishlist');
            foreach($ids as $key => $id){
                $item = Product::whereId($id)->with('colors','sizes','product_tags')->first();
                $items[] = $item;
            }
            $data['wishlist'] = $items;
            return view('front.pages.wishlist.wishlist', $data);
        }else {
            return view('front.pages.wishlist.empty-wishlist', $data);
        }

    }

    public function wishlistDelete(Request $request)
    {
        $ids = Session::get('wishlist');
        $newIds = [];
        foreach($ids as $id){
            if($request->id != $id){
                $newIds[] = $id;
            }
        }


        if(!count($newIds) == 0){
            Session::put('wishlist',$newIds);
            return true;
        }else{
            Session::forget('wishlist');
            return true;
        }
    }

}

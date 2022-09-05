<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Front\CompareList;
use App\Models\Front\Wishlist;
use App\Models\SeoSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CompareListController extends Controller
{
    public function compareList(Request $request)
    {
        if(Session::has('compare')){

            if(count(Session::get('compare')) <2){
                $compares = Session::get('compare');
                if(in_array($request->product_id,$compares)){
                    $status = 0;
                    $mgs = __('This product already added to compare');
                    return response()->json(['message'=>$mgs,'status'=>$status]);
                }
                array_push($compares,$request->product_id);
                Session::put('compare',$compares);
                $status = 1;
                $mgs = __('Compare added successfully');
            }else{
                $compares = Session::get('compare');
                $status = 0;
                $mgs = __('already added 2 compare product');
            }

        }else{
            $compares = array($request->product_id);
            Session::put('compare',$compares);
            $status = 1;
            $mgs = __('Compare added successfully');
        }

        return response()->json(['message'=>$mgs,'status'=>$status,'compare_count'=>count($compares)]);
    }

//    public function compareList(Request $request){
//        $list=CompareList::count('id');
//        if($request->ajax()){
//            if(Auth::check()){
//                if($list <=2){
//                    $id=CompareList::where('Product_Id',$request->product_id)->first();
//                    if($id){
//                        $data=3;
//                        $list=CompareList::count('id');
//                        return response()->json([$data,$list]);
//                    }else{
//                        $data=1;
//                        $id=$request->product_id;
//                        $product=Product::where('id',$id)->first();
//                        $pro=$product->id;
//                        $user=Auth::user()->id;
//                        $compareList=CompareList::create([
//                            'User_Id'=>$user,
//                            'Product_Id'=>$pro,
//                        ]);
//                        if($compareList){
//                            $list=CompareList::count('id');
//                            return response()->json([$data,$list]);
//                        }
//                    }
//                }else{
//                    $data=2;
//                    $list=CompareList::count('id');
//                    return response()->json([$data,$list]);
//                }
//            }
//        }
//    }
    public function Compare(){
        $seo = SeoSetting::where('slug', 'compare')->first();
        $data['title'] = $seo->title;
        $data['description'] = $seo->description;
        $data['keywords'] = $seo->keywords;
        if(Session::has('compare') && count(Session::get('compare')) != 0) {
            $sname = [];
            $sdesc = [];
            $compare = [];
            $ids = Session::get('compare');
            foreach($ids as $key => $id){
                $item = Product::whereId($id)->with('colors','sizes','product_tags')->first();
                $items[] = $item;
            }
            $data['compareList'] = $items;
            return view('front.pages.compare.compare', $data);
        }else {
            return view('front.pages.compare.empty-compare', $data);
        }
    }
//    public function Compare(){
//        return Session::get('compare');
//        $compare_count = CompareList::where('User_Id', Auth::id())->count();
//        $seo = SeoSetting::where('slug', 'compare')->first();
//        $data['title'] = $seo->title;
//        $data['description'] = $seo->description;
//        $data['keywords'] = $seo->keywords;
//        if($compare_count != 0) {
//            $data['compareList'] =CompareList::where('User_Id', Auth::id())->with('products','products.colors','products.sizes','products.product_tags')->get();
//            return view('front.pages.compare.compare', $data);
//        }else {
//            return view('front.pages.compare.empty-compare', $data);
//        }
//    }

    public function compareListDelete(Request $request){
        $ids = Session::get('compare');
        $newIds = [];
        foreach($ids as $id){
            if($request->id != $id){
                $newIds[] = $id;
            }
        }


        if(!count($newIds) == 0){
            Session::put('compare',$newIds);
            return true;
        }else{
            Session::forget('compare');
            return true;
        }
    }

//    public function compareListDelete(Request $request){
//        $id = $request->id;
//        if ($id) {
//            CompareList::where('id',$id)->delete();
//            $data['compareList'] =  CompareList::with('products','products.colors','products.sizes','products.product_tags')->get();
//        }
//        $seo = SeoSetting::where('slug', 'compare')->first();
//        $data['title'] = $seo->title;
//        $data['description'] = $seo->description;
//        $data['keywords'] = $seo->keywords;
//        return view('front.pages.compare.compare_item', $data);
//    }
}

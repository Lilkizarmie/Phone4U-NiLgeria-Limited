@extends('front.layouts.master')
@section('title', isset($title) ? $title : 'Home')
@section('description', isset($description) ? $description : '')
@section('keywords', isset($keywords) ? $keywords : '')
@section('content')
    <!-- breadcrumb area start here  -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="breadcrumb-wrap text-center">
                <h2 class="page-title">{{__('Compare')}}</h2>
                <ul class="breadcrumb-pages">
                    <li class="page-item"><a class="page-item-link" href="{{route('front')}}">{{__('Home')}}</a></li>
                    <li class="page-item">{{__('Compare')}}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end here  -->

    <!-- Checkout-Area -->
    <section class="compare-page-area section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="table_page table-responsive compare-table">
                        <div id="compareListTable">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td class="first-column">{{__('Product')}}</td>
                                    @foreach($compareList as $item)
                                        <td class="product-image-title">
                                            <div class="product-top">
                                                <a href="{{route('single.product',$item->en_Product_Slug)}}" class="image"><img src="{{asset(ProductImage().$item->Primary_Image)}}" alt="Compare Product"></a>
                                            </div>
                                            <div>
                                                <h5><a href="{{route('single.product',$item->en_Product_Slug)}}" class="title">{{langConverter($item->en_Product_Name,$item->fr_Product_Name)}}</a></h5>
                                            </div>
                                        </td>
                                    @endforeach

                                </tr>
                                <tr>
                                    <td class="first-column">{{__('Description')}}</td>
                                    @foreach($compareList as $item)
                                        <td class="pro-desc">
                                            <p>{{langConverter($item->en_About,$item->fr_About)}}</p>
                                        </td>
                                    @endforeach

                                </tr>
                                <tr>
                                    <td class="first-column">{{__('Price')}}</td>
                                    @foreach($compareList as $item)
                                        <td class="pro-price">{{currencySymbol()[currency()]}}{{currencyConverter($item->Discount_Price)}}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="first-column">{{__('Color')}}</td>
                                    @foreach($compareList as $item)
                                        <td class="pro-color">
                                            @foreach($item->colors as $item)
                                                @if ($loop->last)
                                                    {{$item->Name}}
                                                @else
                                                    {{$item->Name}},
                                                @endif
                                            @endforeach
                                        </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="first-column">{{__('Stock')}}</td>
                                    <input type="hidden" name="quantity" value="1" id="product_quantity">
                                    @foreach($compareList as $item)
                                        @if($item->Quantity > 0)
                                            <td class="pro-stock">{{__('In Stock')}}</td>
                                        @else
                                            <td class="pro-stock">{{__('Not Available')}}</td>
                                        @endif
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="first-column">{{__('Add To Cart')}}</td>
                                    @foreach($compareList as $item)
                                        <td class="pro-addtocart"><a href="{{route('single.product',$item->en_Product_Slug)}}" class="primary-btn"><span>{{__('Add To Cart')}}</span></a></td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="first-column">{{__('Delete')}}</td>
                                    @foreach($compareList as $item)
                                        <td class="pro-remove"><button class="bg-transparent border-0 deleteCompareList" data-id="{{$item->id}}" title="Delete Item"><i class="fas fa-times"></i></button></td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="first-column">{{__('Rating')}}</td>
                                    @foreach($compareList as $item)
                                        <td class="pro-ratting">
                                            <!-- This is server side code. User can not modify it. -->
                                            {!!  productReview($item->id) !!}
                                        </td>
                                    @endforeach
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="compareListDelete" data-url="{{ route('compare.delete')}}"></div>
    <div id="AddToCartIntoSession" data-url="{{ route('add.to.cart') }}"></div>
    <div id="productImgAsset" data-url="{{asset(ProductImage())}}"></div>
    @push('post_script')
        <script src="{{asset('frontend/assets/js/pages/compare.js')}}"></script>
    @endpush()
@endsection

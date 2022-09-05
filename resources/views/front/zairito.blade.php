@extends('front.layouts.master')
@section('title', isset($title) ? $title : 'Home')
@section('description', isset($description) ? $description : '')
@section('keywords', isset($keywords) ? $keywords : '')
@section('content')
    <!-- hero-section area start here  -->
    <div class="hero-section">
        <div class="hero-slider">
            @foreach($sliders as $slider)
            <div class="signle-slide" style="background-image: url('{{asset(SliderImage().$slider->Background_Image)}}');">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-6">
                            <div class="hero-slider-content text-center">
                                <h2 class="slider-sub-title">{{langConverter($slider->en_Sub_Title,$slider->fr_Sub_Title)}}</h2>
                                <h1 class="slider-title">{{langConverter($slider->en_Title,$slider->fr_Title)}}</h1>
                                <p class="slider-text">{{langConverter($slider->en_Description,$slider->fr_Description)}}</p>
                                <div class="slider-btn">
                                    <a href="{{route('all.product')}}" class="secondary-btn">{{langConverter($slider->en_Button_Text,$slider->fr_Button_Text)}} <i class="iocn flaticon-right-arrow"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 offset-lg-1 col-6">
                            <div class="hero-slider-image text-center">
                                <img class="hero-image img-fluid" src="{{asset(SliderImage().$slider->Thumbnail)}}" alt="hero-banner-image" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <!-- hero-section area end here  -->

    <!-- brads area start here  -->
    <div class="brads-area">
        <div class="container">
            <div class="brads-slide">
                @foreach($brands as $brand)
                <div class="sigle-brad">
                    <img src="{{asset(BrandImage().$brand->BrandImage)}}" alt="brad image"/>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- brads area start here  -->

    <!-- Popular Categories area start here  -->
    <div class="popular-categories-area section-bg section-top pb-100">
        <div class="container">
            <div class="section-header-area">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="sub-title">{{langConverter(siteContentHomePage('many_goods')->en_Title,siteContentHomePage('many_goods')->fr_Title)}}</h3>
                        <h2 class="section-title">{{langConverter(siteContentHomePage('many_goods')->en_Description_One,siteContentHomePage('many_goods')->fr_Description_One)}}</h2>
                    </div>
                    <div class="col-md-6 align-self-end text-md-end">
                        <a href="{{route('all.product')}}" class="primary-btn">{{__('View All Products')}}</a>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach(Category_Des_Icon() as $item)
                    <div class="col-lg-4 col-md-6">
                        <a class="single-categorie" href="{{route('category.product',$item->id)}}">
                            <div class="categorie-wrap">
                                <div class="categorie-icon">
                                    <i class="{{$item->Category_Icon}}"></i>
                                </div>
                                <div class="categorie-info">
                                    <h3 class="categorie-name">{{langConverter($item->en_Category_Name,$item->fr_Category_Name)}}</h3>
                                    <h4 class="categorie-subtitle">{{ langConverter($item->en_Description,$item->fr_Description) }}</h4>
                                </div>
                            </div>
                            <i class="arrow flaticon-right-arrow"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Popular Categories area end here  -->

    <!-- Featured Products area start here  -->
    <div class="featured-productss-area section-top pb-100">
        <div class="container">
            <div class="section-header-area">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="sub-title">{{langConverter(siteContentHomePage('products')->en_Title,siteContentHomePage('products')->fr_Title)}}</h3>
                        <h2 class="section-title">{{langConverter(siteContentHomePage('products')->en_Description_One,siteContentHomePage('products')->fr_Description_One)}}</h2>
                    </div>
                    <div class="col-md-6 align-self-end text-md-end">
                        <a href="{{route('all.product')}}" class="see-btn">{{__('See All')}}</a>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="single-grid-product">
                                <div class="product-top">
                                    <a href="{{route('single.product',$product->en_Product_Slug)}}"><img class="product-thumbnal" src="{{asset(ProductImage().$product->Primary_Image)}}" alt="{{__('product')}}" /></a>
                                    <div class="product-flags">
                                        @if($product->ItemTag)
                                            <span class="product-flag sale">{{$product->ItemTag}}</span>
                                        @endif
                                        @if($product->Discount )
                                            <span class="product-flag discount">{{ __('-')}}{{$product->Discount}}</span>
                                        @endif
                                    </div>
                                    <ul class="prdouct-btn-wrapper">
                                        <li class="single-product-btn">
                                            <a class="addToWishlist product-btn CompareList" data-id="{{$product->id}}" title="{{__('Add To Compare')}}"><i class="icon flaticon-bar-chart"></i></a>
                                        </li>
                                        <li class="single-product-btn">
                                            <a class="addCompare product-btn MyWishList" data-id="{{$product->id}}"  title="{{__('Add To Wishlist')}}"><i class="icon flaticon-like"></i></a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="product-info text-center">
                                    @foreach($product->product_tags as $ppt)
                                        <h4 class="product-catagory">{{$ppt->tag}}</h4>
                                    @endforeach
                                        <input type="hidden" name="quantity" value="1" id="product_quantity">
                                    <h3 class="product-name"><a class="product-link" href="{{route('single.product',$product->en_Product_Slug)}}">{{langConverter($product->en_Product_Name,$product->fr_Product_Name)}}</a></h3>
                                        <!-- This is server side code. User can not modify it. -->
                                        {!!  productReview($product->id) !!}
                                    <div class="product-price">
                                        <span class="regular-price">{{currencySymbol()[currency()]}}{{currencyConverter($product->Price)}}</span>
                                        <span class="price">{{currencySymbol()[currency()]}}{{currencyConverter($product->Discount_Price)}}</span>
                                    </div>
                                    <a href="javascript:void(0)" title="{{__('Add To Cart')}}" class="add-cart addCart" data-id="{{$product->id}}">{{__('Add To Cart')}} <i class="icon fas fa-plus-circle"></i></a>
                                </div>
                            </div>
                        </div>
                @endforeach

            </div>
        </div>
    </div>
    <!-- Featured Products area end here  -->

    <!-- About Area start here  -->
    
    <!-- About Area  end here  -->

    <!-- Trending Products area start here  -->
    <div class="trending-products-area section-top pb-100">
        <div class="container">
            <div class="section-header-area">
                <div class="row">
                    <div class="col-lg-6">
                        <h3 class="sub-title">{{langConverter(siteContentHomePage('popular_products')->en_Title,siteContentHomePage('popular_products')->fr_Title)}}</h3>
                        <h2 class="section-title">{{ langConverter(siteContentHomePage('popular_products')->en_Description_One,siteContentHomePage('popular_products')->fr_Description_One) }}</h2>
                    </div>
                    <div class="col-lg-6 align-self-end text-lg-end">
                        <div class="primary-tabs">
                            <ul class="nav nav-tabs" id="TrendingProducts" role="tablist">
                                @if ($allsettings['new_arrival'] == ACTIVE)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{$allsettings['new_arrival'] == ACTIVE ? 'active' : ''}}" id="new-arrival-tab" data-bs-toggle="tab" data-bs-target="#new-arrival" type="button" role="tab" aria-controls="new-arrival" aria-selected="true">{{__('NEW ARRIVAL')}}</button>
                                    </li>
                                @endif
                                @if ($allsettings['best_selling'] == ACTIVE)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{$allsettings['new_arrival'] == INACTIVE && $allsettings['best_selling'] == ACTIVE ? 'active' : ''}}" id="best-selling-tab" data-bs-toggle="tab" data-bs-target="#best-selling" type="button" role="tab" aria-controls="best-selling" aria-selected="false">{{__('BEST SELLING')}}</button>
                                    </li>
                                @endif
                                @if ($allsettings['on_sale'] == ACTIVE)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{$allsettings['new_arrival'] == INACTIVE && $allsettings['best_selling'] == INACTIVE && $allsettings['on_sale'] == ACTIVE ? 'active' : ''}}" id="on-sell-tab" data-bs-toggle="tab" data-bs-target="#on-sell" type="button" role="tab" aria-controls="on-sell" aria-selected="false">{{__('ON SALE')}}</button>
                                    </li>
                                @endif
                                @if ($allsettings['featured_items'] == ACTIVE)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{$allsettings['new_arrival'] == INACTIVE && $allsettings['best_selling'] == INACTIVE && $allsettings['on_sale'] == INACTIVE && $allsettings['featured_items'] == ACTIVE ? 'active' : ''}}" id="featured-items-tab" data-bs-toggle="tab" data-bs-target="#featured-items" type="button" role="tab" aria-controls="featured-items" aria-selected="false">{{__('FEATURED ITEMS')}}</button>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content" id="TrendingProductsContent">
                @if ($allsettings['new_arrival'] == ACTIVE)
                    <div class="tab-pane fade {{$allsettings['new_arrival'] == ACTIVE ? 'show active' : ''}}" id="new-arrival" role="tabpanel" aria-labelledby="new-arrival-tab">
                        <div class="row">
                            @foreach($new_arrivals as $product)
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="single-grid-product">
                                            <div class="product-top">
                                                <a href="{{route('single.product',$product->en_Product_Slug)}}"><img class="product-thumbnal" src="{{asset(ProductImage().$product->Primary_Image)}}" alt="{{__('product')}}" /></a>
                                                <div class="product-flags">
                                                    @if($product->ItemTag)
                                                        <span class="product-flag sale">{{$product->ItemTag}}</span>
                                                    @endif
                                                    @if($product->Discount )
                                                        <span class="product-flag discount">{{ __('-')}}{{$product->Discount}}</span>
                                                    @endif
                                                </div>
                                                <ul class="prdouct-btn-wrapper">
                                                    <li class="single-product-btn">
                                                        <a class="addToWishlist product-btn CompareList" data-id="{{$product->id}}" title="{{__('Add To Compare')}}"><i class="icon flaticon-bar-chart"></i></a>
                                                    </li>
                                                    <li class="single-product-btn">
                                                        <a class="addCompare product-btn MyWishList" data-id="{{$product->id}}" title="{{__('Add To Wishlist')}}"><i class="icon flaticon-like"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="product-info text-center">
                                                @foreach($product->product_tags as $pptn)
                                                    <h4 class="product-catagory">{{$pptn->tag}}</h4>
                                                @endforeach
                                                <input type="hidden" name="quantity" value="1" id="product_quantity">
                                                <h3 class="product-name"><a class="product-link" href="{{route('single.product',$product->en_Product_Slug)}}">{{langConverter($product->en_Product_Name,$product->fr_Product_Name)}}</a></h3>
                                                <!-- This is server side code. User can not modify it. -->
                                                {!!  productReview($product->id) !!}
                                                <div class="product-price">
                                                    <span class="regular-price">{{currencySymbol()[currency()]}} {{currencyConverter($product->Price)}}</span>
                                                    <span class="price">{{currencySymbol()[currency()]}} {{currencyConverter($product->Discount_Price)}}</span>
                                                </div>
                                                <a href="javascript:void(0)" title="{{__('Add To Cart')}}" class="add-cart addCart" data-id="{{$product->id}}">{{__('Add To Cart')}} <i class="icon fas fa-plus-circle"></i></a>
                                            </div>
                                        </div>
                                    </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if ($allsettings['best_selling'] == ACTIVE)
                     <div class="tab-pane fade {{$allsettings['new_arrival'] == INACTIVE && $allsettings['best_selling'] == ACTIVE ? 'show active' : ''}}" id="best-selling" role="tabpanel" aria-labelledby="best-selling-tab">
                            <div class="row">
                                @foreach($best_sellings as $product)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="single-grid-product">
                                            <div class="product-top">
                                                <a href="{{route('single.product',$product->en_Product_Slug)}}"><img class="product-thumbnal" src="{{asset(ProductImage().$product->Primary_Image)}}" alt="{{__('product')}}" /></a>
                                                <div class="product-flags">
                                                    @if($product->ItemTag)
                                                        <span class="product-flag sale">{{$product->ItemTag}}</span>
                                                    @endif
                                                    @if($product->Discount )
                                                        <span class="product-flag discount">{{ __('-')}}{{$product->Discount}}</span>
                                                    @endif
                                                </div>
                                                <ul class="prdouct-btn-wrapper">
                                                    <li class="single-product-btn">
                                                        <a class="addToWishlist product-btn CompareList" data-id="{{$product->id}}" title="{{__('Add To Compare')}}"><i class="icon flaticon-bar-chart"></i></a>
                                                    </li>
                                                    <li class="single-product-btn">
                                                        <a class="addCompare product-btn MyWishList" data-id="{{$product->id}}" title="{{__('Add To Wishlist')}}"><i class="icon flaticon-like"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="product-info text-center">
                                                @foreach($product->product_tags as $pptb)
                                                    <h4 class="product-catagory">{{$pptb->tag}}</h4>
                                                @endforeach
                                                <input type="hidden" name="quantity" value="1" id="product_quantity">
                                                <h3 class="product-name"><a class="product-link" href="{{route('single.product',$product->en_Product_Slug)}}">{{langConverter($product->en_Product_Name,$product->fr_Product_Name)}}</a></h3>
                                                <!-- This is server side code. User can not modify it. -->
                                                {!!  productReview($product->id) !!}
                                                <div class="product-price">
                                                    <span class="regular-price">{{currencySymbol()[currency()]}} {{currencyConverter($product->Price)}}</span>
                                                    <span class="price">{{currencySymbol()[currency()]}} {{currencyConverter($product->Discount_Price)}}</span>
                                                </div>
                                                <a href="javascript:void(0)" title="{{__('Add To Cart')}}" class="add-cart addCart" data-id="{{$product->id}}">{{__('Add To Cart')}} <i class="icon fas fa-plus-circle"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                @endif
                @if ($allsettings['on_sale'] == ACTIVE)
                     <div class="tab-pane fade {{$allsettings['new_arrival'] == INACTIVE && $allsettings['best_selling'] == INACTIVE && $allsettings['on_sale'] == ACTIVE ? 'show active' : ''}}" id="on-sell" role="tabpanel" aria-labelledby="on-sell-tab">
                            <div class="row">
                                @foreach($on_sales as $product)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="single-grid-product">
                                            <div class="product-top">
                                                <a href="{{route('single.product',$product->en_Product_Slug)}}}"><img class="product-thumbnal" src="{{asset(ProductImage().$product->Primary_Image)}}" alt="product" /></a>
                                                <div class="product-flags">
                                                    @if($product->ItemTag)
                                                        <span class="product-flag sale">{{$product->ItemTag}}</span>
                                                    @endif
                                                    @if($product->Discount )
                                                        <span class="product-flag discount">{{ __('-')}}{{$product->Discount}}</span>
                                                    @endif
                                                </div>
                                                <ul class="prdouct-btn-wrapper">
                                                    <li class="single-product-btn">
                                                        <a class="addToWishlist product-btn CompareList" data-id="{{$product->id}}" title="Add to compare"><i class="icon flaticon-bar-chart"></i></a>
                                                    </li>
                                                    <li class="single-product-btn">
                                                        <a class="addCompare product-btn MyWishList" data-id="{{$product->id}}" title="Add to wishlist"><i class="icon flaticon-like"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="product-info text-center">
                                                @foreach($product->product_tags as $ppto)
                                                    <h4 class="product-catagory">{{$ppto->tag}}</h4>
                                                @endforeach
                                                <input type="hidden" name="quantity" value="1" id="product_quantity">
                                                <h3 class="product-name"><a class="product-link" href="{{route('single.product',$product->en_Product_Slug)}}">{{langConverter($product->en_Product_Name,$product->fr_Product_Name)}}</a></h3>
                                                <!-- This is server side code. User can not modify it. -->
                                                {!!  productReview($product->id) !!}
                                                <div class="product-price">
                                                    <span class="regular-price">{{currencySymbol()[currency()]}} {{currencyConverter($product->Price)}}</span>
                                                    <span class="price">{{currencySymbol()[currency()]}} {{currencyConverter($product->Discount_Price)}}</span>
                                                </div>
                                                <a href="javascript:void(0)" title="{{__('Add To Cart')}}" class="add-cart addCart" data-id="{{$product->id}}">{{__('Add To Cart')}} <i class="icon fas fa-plus-circle"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                @endif
                @if ($allsettings['featured_items'] == ACTIVE)
                     <div class="tab-pane fade {{$allsettings['new_arrival'] == INACTIVE && $allsettings['best_selling'] == INACTIVE && $allsettings['on_sale'] == INACTIVE && $allsettings['featured_items'] == ACTIVE ? 'show active' : ''}}" id="featured-items" role="tabpanel" aria-labelledby="featured-items-tab">
                            <div class="row">
                                @foreach($featured_products as $product)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="single-grid-product">
                                            <div class="product-top">
                                                <a href="{{route('single.product',$product->en_Product_Slug)}}"><img class="product-thumbnal" src="{{asset(ProductImage().$product->Primary_Image)}}" alt="product" /></a>
                                                <div class="product-flags">
                                                    @if($product->ItemTag)
                                                        <span class="product-flag sale">{{$product->ItemTag}}</span>
                                                    @endif
                                                    @if($product->Discount )
                                                        <span class="product-flag discount">{{ __('-')}}{{$product->Discount}}</span>
                                                    @endif
                                                </div>
                                                <ul class="prdouct-btn-wrapper">
                                                    <li class="single-product-btn">
                                                        <a class="addToWishlist product-btn CompareList" data-id="{{$product->id}}" title="Add to compare"><i class="icon flaticon-bar-chart"></i></a>
                                                    </li>
                                                    <li class="single-product-btn">
                                                        <a class="addCompare product-btn MyWishList" data-id="{{$product->id}}" title="Add to wishlist"><i class="icon flaticon-like"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="product-info text-center">
                                                @foreach($product->product_tags as $pptf)
                                                    <h4 class="product-catagory">{{$pptf->tag}}</h4>
                                                @endforeach
                                                <input type="hidden" name="quantity" value="1" id="product_quantity">
                                                <h3 class="product-name"><a class="product-link" href="{{route('single.product',$product->en_Product_Slug)}}">{{langConverter($product->en_Product_Name,$product->fr_Product_Name)}}</a></h3>
                                                <!-- This is server side code. User can not modify it. -->
                                                {!!  productReview($product->id) !!}
                                                <div class="product-price">
                                                    <span class="regular-price">{{currencySymbol()[currency()]}} {{currencyConverter($product->Price)}}</span>
                                                    <span class="price">{{currencySymbol()[currency()]}} {{currencyConverter($product->Discount_Price)}}</span>
                                                </div>
                                                <a href="javascript:void(0)" title="{{__('Add To Cart')}}" class="add-cart addCart" data-id="{{$product->id}}">{{__('Add To Cart')}} <i class="icon fas fa-plus-circle"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Trending Products area end here  -->
    
    <!-- On Sale Products area Start here -->
    <div class="trending-products-area section-top pb-100">
        <div class="container">
            <div class="section-header-area">
                <div class="row">
                    <div class="col-lg-6">
                        <h3 class="sub-title">{{langConverter(siteContentHomePage('products')->en_Title,siteContentHomePage('products')->fr_Title)}}</h3>
                        <h2 class="section-title">{{langConverter(siteContentHomePage('products')->en_Description_One,siteContentHomePage('products')->fr_Description_One)}}</h2>
                    </div>
                    <div class="col-md-6 align-self-end text-md-end">
                        <a href="{{route('all.product')}}" class="see-btn">{{__('See All')}}</a>
                    </div>
                </div>
            </div>
            <div class="tab-content" id="TrendingProductsContent">
                @if ($allsettings['on_sale'] == ACTIVE)
                     <div class="tab-pane fade {{$allsettings['new_arrival'] == INACTIVE && $allsettings['best_selling'] == INACTIVE && $allsettings['on_sale'] == ACTIVE ? 'show active' : ''}}" id="on-sell" role="tabpanel" aria-labelledby="on-sell-tab">
                            <div class="row">
                                @foreach($on_sales as $product)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="single-grid-product">
                                            <div class="product-top">
                                                <a href="{{route('single.product',$product->en_Product_Slug)}}}"><img class="product-thumbnal" src="{{asset(ProductImage().$product->Primary_Image)}}" alt="product" /></a>
                                                <div class="product-flags">
                                                    @if($product->ItemTag)
                                                        <span class="product-flag sale">{{$product->ItemTag}}</span>
                                                    @endif
                                                    @if($product->Discount )
                                                        <span class="product-flag discount">{{ __('-')}}{{$product->Discount}}</span>
                                                    @endif
                                                </div>
                                                <ul class="prdouct-btn-wrapper">
                                                    <li class="single-product-btn">
                                                        <a class="addToWishlist product-btn CompareList" data-id="{{$product->id}}" title="Add to compare"><i class="icon flaticon-bar-chart"></i></a>
                                                    </li>
                                                    <li class="single-product-btn">
                                                        <a class="addCompare product-btn MyWishList" data-id="{{$product->id}}" title="Add to wishlist"><i class="icon flaticon-like"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="product-info text-center">
                                                @foreach($product->product_tags as $ppto)
                                                    <h4 class="product-catagory">{{$ppto->tag}}</h4>
                                                @endforeach
                                                <input type="hidden" name="quantity" value="1" id="product_quantity">
                                                <h3 class="product-name"><a class="product-link" href="{{route('single.product',$product->en_Product_Slug)}}">{{langConverter($product->en_Product_Name,$product->fr_Product_Name)}}</a></h3>
                                                <!-- This is server side code. User can not modify it. -->
                                                {!!  productReview($product->id) !!}
                                                <div class="product-price">
                                                    <span class="regular-price">{{currencySymbol()[currency()]}} {{currencyConverter($product->Price)}}</span>
                                                    <span class="price">{{currencySymbol()[currency()]}} {{currencyConverter($product->Discount_Price)}}</span>
                                                </div>
                                                <a href="javascript:void(0)" title="{{__('Add To Cart')}}" class="add-cart addCart" data-id="{{$product->id}}">{{__('Add To Cart')}} <i class="icon fas fa-plus-circle"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                @endif
            </div>
        </div>
    </div>
    <!-- On sale Products area end here -->

    <!-- product banenr area start here  -->
    <div class="product-banner pb-100">
        <div class="container">
            <div class="row">
                @foreach($promotion as $promo)
                <div class="col-md-5">
                    <a href="#" class="single-banner"><img class="banner-image" src="{{asset(PromotionImage().$promo->Image_One)}}" alt="product-banner" /></a>
                </div>
                <div class="col-md-7">
                    <a href="#" class="single-banner"><img class="banner-image" src="{{asset(PromotionImage().$promo->Image_Two)}}" alt="product-banner" /></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <!-- product banner area end here  -->
    <!-- Blog area start here  -->
    <div class="blog-area section-top section-bg pb-100">
        <div class="container">
            <div class="section-header-area">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="sub-title">{{langConverter(siteContentHomePage('blogspot')->en_Title,siteContentHomePage('blogspot')->fr_Title)}}</h3>
                        <h2 class="section-title">{{ langConverter(siteContentHomePage('blogspot')->en_Description_One,siteContentHomePage('blogspot')->fr_Description_One) }}</h2>
                    </div>
                    <div class="col-md-6 align-self-end text-md-end">
                        <a href="{{route('blog')}}" class="see-btn">{{__('See All')}}</a>
                    </div>
                </div>
            </div>

            <div class="blog-slide">
                @foreach($blogs as $blog)
                <!-- Blog Item Start -->
                <div class="single-grid-blog">
                    <div class="blog-thumbnail">
                        <a href="{{route('blog.details',$blog->id)}}"><img class="thumbnail-image" src="{{asset(BlogImage().$blog->Small_Image)}}" alt="blog" /></a>
                    </div>
                    <div class="blog-info">
                        <ul class="blog-category">
                            @foreach($blog->tags as $Item)
                                @foreach($Item->Tag as $n)
                                    <li class="single-category"><a class="category-text" href="{{route('blog.details',$blog->id)}}">{{$n}}</a></li>
                                @endforeach
                            @endforeach
                        </ul>
                        <h3 class="blog-title"><a class="blog-link" href="{{route('blog.details',$blog->id)}}">{{langConverter($blog->en_Title,$blog->fr_Title)}}</a></h3>
                        <p class="blog-content">{!! Str::limit(clean(langConverter($blog->en_Description_Two,$blog->fr_Description_Two)),205) !!}</p>
                        <a class="blog-btn" href="{{route('blog.details',$blog->id)}}l">{{__('See Details')}}</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Blog area end here  -->

    <!-- Image Gallery area start here  -->
    
    <!-- Image Gallery area end here  -->

    <!-- Testimonial ara start here  -->
    
    <!-- Testimonial ara end here  -->
@endsection

@section('subscribe_pop_up_modal')
    @if(!session()->has('dontshoW'))
    <!-- Page Load Popup Modal End -->
    <div class="modal fade bd-example-modal-lg theme-modal" id="popUpModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body modal1 modal-bg">
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-7 col-md-12">
                                    <div class="offer_modal_left">
                                        <img src="{{asset(IMG_LOGO_PATH.$allsettings['main_logo'])}}" alt="logo">
                                        <h3>{{__('SUBSCRIBE TO NEWSLETTER')}}</h3>
                                        <p class="m-0">{{__('Subscribe to the Zairito mailing list to receive updates on new arrivals, special offers and our promotions.')}}</p>

                                        <form id="subscribe_form" name="subscribe_form" >
                                            @csrf
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control subscribeModal" name="subscribeval" id="subscribeval" placeholder="{{__('Your email')}}">
                                                <div class="input-group-append">
                                                    <button class="theme-btn-one btn-black-overlay btn_sm border-0 subscribeModal" id="subscribeModal">
                                                        {{__('Subscribe')}}</button>
                                                </div>
                                            </div>
                                            <div class="check_boxed_modal">
                                                <input type="checkbox" id="doNotShowModel" name="dontshowmodal" value="dont_show">
                                                <label for="vehicle1">{{__("Don't show this popup again")}}</label>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-12">
                                    <div class="offer_modal_img d-none d-lg-flex">
                                        <img src="{{asset(IMG_ADVERTISE_PATH.$allsettings['popup_image'])}}" alt="img">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Page Load Popup Modal End -->
    <div id="DoNotSubscribe" data-url="{{ route('do.not.subscribe') }}"></div>
    <div id="SubscribeStore" data-url="{{route('admin.subscribe.store')}}"></div>
    @push('post_script')
        <script src="{{asset('frontend/assets/js/pages/home.js')}}"></script>
    @endpush()
@endsection

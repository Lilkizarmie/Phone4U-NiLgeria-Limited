@extends('admin.master', ['menu' => 'slider'])
@section('title', isset($title) ? $title : '')
@section('title', __('Dashboard'))
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{__('Add Slider')}}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('Slider')}}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="gallery__area bg-style">
                <div class="gallery__content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-one" role="tabpanel" aria-labelledby="nav-one-tab">
                            <form enctype="multipart/form-data" method="POST" action="{{route('admin.slider.store')}}">
                                @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-vertical__item bg-style">
                                        <div class="item-top mb-30">
                                            <h2>{{langString('en', false).':'}}</h2>
                                        </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Title')}}</label>
                                                <input type="text" class="form-control" id="en_title" name="en_title">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Sub Title')}}</label>
                                                <input type="text" class="form-control" id="en_sub_title" name="en_sub_title" >
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Button Text')}}</label>
                                                <input type="text" class="form-control" id="en_btn_text" name="en_btn_text">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Description')}}</label>
                                                <textarea id="description" name="en_description" class="form-control"></textarea>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Thumbnail')}}</label>
                                                <input type="file" class="form-control putImage1" name="thumbnail" id="thumbnail">
                                                <img  src="" id="target1"/>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('BackGround Image')}}</label>
                                                <input type="file" class="form-control putImage2"  name="background_image" id="background_image">
                                                <img  src="" id="target2"/>
                                            </div>
                                            <div class="input__button">
                                                <button type="submit" class="btn btn-blue">{{ __('Add')}}</button>
                                            </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-vertical__item bg-style">
                                        <div class="item-top mb-30">
                                            <h2>{{langString('fr', false).':'}}</h2>
                                        </div>
                                        <div class="input__group mb-25">
                                            <label for="exampleInputEmail1">{{ __('Title')}}</label>
                                            <input type="text" class="form-control" id="fr_title" name="fr_title">
                                        </div>
                                        <div class="input__group mb-25">
                                            <label for="exampleInputEmail1">{{ __('Sub Title')}}</label>
                                            <input type="text" class="form-control" id="fr_sub_title" name="fr_sub_title" >
                                        </div>
                                        <div class="input__group mb-25">
                                            <label for="exampleInputEmail1">{{ __('Button Text')}}</label>
                                            <input type="text" class="form-control" id="fr_btn_text" name="fr_btn_text">
                                        </div>
                                        <div class="input__group mb-25">
                                            <label for="exampleInputEmail1">{{ __('Description')}}</label>
                                            <textarea id="description" name="fr_description" class="form-control"></textarea>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

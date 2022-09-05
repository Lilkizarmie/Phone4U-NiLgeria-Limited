@extends('admin.master', ['menu' => 'site_content', 'submenu' => 'general_settings'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{__('General Settings')}}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('General Settings')}}</li>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-vertical__item bg-style">
                                        <div class="item-top mb-30">
                                            <h2>{{__('Basic Info')}}</h2>
                                        </div>
                                        <form enctype="multipart/form-data" method="POST" action="{{route('admin.general.settings.update_settings')}}">
                                            @csrf
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('App Title')}}</label>
                                                <input type="text" class="form-control" id="app_title" name="app_title" value="{{$allsettings['app_title']}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Default Title')}}</label>
                                                <input type="text" class="form-control" id="title" name="title" value="{{$allsettings['title']}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Footer Title')}}</label>
                                                <input type="text" class="form-control" id="footer_title" name="footer_title" value="{{$allsettings['footer_title']}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Contact Number')}}</label>
                                                <input type="text" class="form-control" id="call_us" name="call_us" value="{{$allsettings['call_us']}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Email')}}</label>
                                                <input type="text" class="form-control" id="email" name="email" value="{{$allsettings['email']}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Address')}}</label>
                                                <input type="text" class="form-control" id="address" name="address" value="{{$allsettings['address']}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('State')}}</label>
                                                <input type="text" class="form-control" id="state" name="state" value="{{$allsettings['state']}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Country')}}</label>
                                                <input type="text" class="form-control" id="country" name="country" value="{{$allsettings['country']}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('News Letter')}}</label>
                                                <input type="text" class="form-control" id="news_letter" name="news_letter" value="{{$allsettings['news_letter']}}" required="">
                                            </div>
                                            <div class="input__button">
                                                <button type="submit" class="btn btn-blue">{{ __('Update')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-vertical__item bg-style">
                                        <div class="item-top mb-30">
                                            <h2>{{__('Media')}}</h2>
                                        </div>
                                        <form enctype="multipart/form-data" method="POST" action="{{route('admin.general.settings.update_settings')}}">
                                            @csrf
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Logo')}}</label>
                                                <input type="file" class="form-control putImage1"  name="main_logo" id="main_logo">
                                                <img src="{{asset(IMG_LOGO_PATH.$allsettings['main_logo'])}}" id="target1" alt="{{__('Image')}}" class="admin_image mt-3"/>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Dark Logo')}}</label>
                                                <input type="file" class="form-control putImage2"  name="footer_logo" id="footer_logo">
                                                <img src="{{asset(IMG_LOGO_PATH.$allsettings['footer_logo'])}}" id="target2" alt="{{__('Image')}}" class="admin_image mt-3"/>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Favicon')}}</label>
                                                <input type="file" class="form-control putImage3"  name="favicon" id="favicon">
                                                <img src="{{asset(IMG_FAVICON_PATH.$allsettings['favicon'])}}" id="target2" alt="{{__('Image')}}" class="admin_image mt-3"/>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Preloader')}}</label>
                                                <input type="file" class="form-control putImage3"  name="preloader" id="preloader">
                                                <img src="{{asset(IMG_PRELOADER_PATH.$allsettings['preloader'])}}" id="target2" alt="{{__('Image')}}" class="admin_image mt-3"/>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('News Letter')}}</label>
                                                <input type="file" class="form-control putImage3"  name="news_letter_img" id="news_letter_img">
                                                <img src="{{asset(IMG_FOOTER_PATH.$allsettings['news_letter_img'])}}" id="target2" alt="{{__('Image')}}" class="admin_image mt-3"/>
                                            </div>
                                            <div class="input__button">
                                                <button type="submit" class="btn btn-blue">{{ __('Update')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-vertical__item bg-style">
                                        <div class="item-top mb-30">
                                            <h2>{{__('SEO')}}</h2>
                                        </div>
                                        <form enctype="multipart/form-data" method="POST" action="{{route('admin.general.settings.update_settings')}}">
                                            @csrf
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Meta Author')}}</label>
                                                <input type="text" class="form-control" id="meta_author" name="meta_author" value="{{$allsettings['meta_author']}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Meta Description')}}</label>
                                                <input type="text" class="form-control" id="meta_description" name="meta_description" value="{{$allsettings['meta_description']}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Meta Keywords')}}</label>
                                                <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{$allsettings['meta_keywords']}}" required="">
                                            </div>
                                            <div class="input__button">
                                                <button type="submit" class="btn btn-blue">{{ __('Update')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-vertical__item bg-style">
                                        <div class="item-top mb-30">
                                            <h2>{{__('Social Login')}}</h2>
                                        </div>
                                        <form enctype="multipart/form-data" method="POST" action="{{route('admin.general.settings.update_social_login')}}">
                                            @csrf
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Google Client ID')}}</label>
                                                <input type="text" class="form-control" id="google_client_id" name="google_client_id" value="{{env('GOOGLE_CLIENT_ID')}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Google Client Secret')}}</label>
                                                <input type="text" class="form-control" id="google_client_secret" name="google_client_secret" value="{{env('GOOGLE_CLIENT_SECRET')}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Google Callback URL')}}</label>
                                                <input type="text" class="form-control" id="google_callback_url" name="google_callback_url" value="{{env('GOOGLE_CALLBACK_URL')}}" required="">
                                            </div>

                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Facebook Client ID')}}</label>
                                                <input type="text" class="form-control" id="facebook_client_id" name="facebook_client_id" value="{{env('FACEBOOK_CLIENT_ID')}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Facebook Client Secret')}}</label>
                                                <input type="text" class="form-control" id="facebook_client_secret" name="facebook_client_secret" value="{{env('FACEBOOK_CLIENT_SECRET')}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Facebook Callback URL')}}</label>
                                                <input type="text" class="form-control" id="facebook_callback_url" name="facebook_callback_url" value="{{env('FACEBOOK_CALLBACK_URL')}}" required="">
                                            </div>
                                            <div class="input__button">
                                                <button type="submit" class="btn btn-blue">{{ __('Update')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-vertical__item bg-style">
                                        <div class="item-top mb-30">
                                            <h2>{{__('Email')}}</h2>
                                        </div>
                                        <form enctype="multipart/form-data" method="POST" action="{{route('admin.general.settings.update_email')}}">
                                            @csrf
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Host')}}</label>
                                                <input type="text" class="form-control" id="mail_host" name="mail_host" value="{{env('MAIL_HOST')}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Port')}}</label>
                                                <input type="text" class="form-control" id="mail_port" name="mail_port" value="{{env('MAIL_PORT')}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Username')}}</label>
                                                <input type="text" class="form-control" id="mail_username" name="mail_username" value="{{env('MAIL_USERNAME')}}" required="">
                                            </div>

                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Password')}}</label>
                                                <input type="text" class="form-control" id="mail_password" name="mail_password" value="{{env('MAIL_PASSWORD')}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('Encryption')}}</label>
                                                <input type="text" class="form-control" id="mail_encryption" name="mail_encryption" value="{{env('MAIL_ENCRYPTION')}}" required="">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title">{{ __('From Address')}}</label>
                                                <input type="text" class="form-control" id="mail_from_address" name="mail_from_address" value="{{env('MAIL_FROM_ADDRESS')}}" required="">
                                            </div>
                                            <div class="input__button">
                                                <button type="submit" class="btn btn-blue">{{ __('Update')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-vertical__item bg-style">
                                        <div class="item-top mb-30">
                                            <h2>{{__('Advertise')}}</h2>
                                        </div>
                                        <form enctype="multipart/form-data" method="POST" action="{{route('admin.general.settings.update_settings')}}">
                                            @csrf
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Menu Thumb')}}</label>
                                                <input type="file" class="form-control putImage3"  name="menu_thumb" id="menu_thumb">
                                                <img src="{{asset(IMG_ADVERTISE_PATH.$allsettings['menu_thumb'])}}" id="target2" alt="{{__('Image')}}" class="admin_image mt-3 img-thumb-zai"/>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="exampleInputEmail1">{{ __('Menu Link')}}</label>
                                                <input type="text" class="form-control"  name="menu_link" id="menu_link" value="{{$allsettings['menu_link']}}">
                                            </div>
                                            <div class="input__button">
                                                <button type="submit" class="btn btn-blue">{{ __('Update')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--Row-->
@endsection

@extends('admin.master', ['menu' => 'payment'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div id="table-url" data-url="{{ route('admin.currency_list') }}"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{__('Payment Gateway')}}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('Payment Gateway')}}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="customers__area bg-style mb-30">
                <div class="customers__table">
                    <table id="BlogTable" class="dataTableHover row-border data-table-filter table-style">
                        <thead>
                        <tr>
                            <th>{{ __('Name')}}</th>
                            <th>{{ __('Image')}}</th>
                            <th>{{ __('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($payment_gateways as $pg)
                            <tr>
                                <td>{{$pg->name}}</td>
                                <td><img src="{{asset(IMG_PAYMENT_GATEWAY.$pg->image)}}" alt="{{$pg->name}}"></td>
                                <td><button class="btn-action " data-bs-toggle="modal" data-bs-target="#createModal{{$pg->id}}"><i class="fa-solid fa-pen-to-square"></i></button></td>
                            </tr>
                        @empty

                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--Row-->

    <!-- Modal -->
    @foreach ($payment_gateways as $pg)
        <div class="modal fade" id="createModal{{$pg->id}}" tabindex="-1" role="dialog" aria-labelledby="createModalTitle{{$pg->id}}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white" id="editModalLongTitle">{{$pg->name}}</h5>
                        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form enctype="multipart/form-data" method="POST" action="{{route('admin.payment_gateway_update', $pg->slug)}}">
                        <div class="modal-body">
                            @csrf
                            <div class="input__group mb-25">
                                <label for="question">{{__('Name')}}</label>
                                <input type="text" name="name" placeholder="{{__('Name')}}" value="{{$pg->name}}">
                            </div>
                            <div class="input__group mb-25">
                                <label for="exampleInputEmail1">{{ __('Thumbnail')}}</label>
                                <input type="file" class="putImage1"  name="image" id="image">
                                <img class="admin_image"  src="{{asset(IMG_PAYMENT_GATEWAY.$pg->image)}}" id="target1"/>
                            </div>
                            @if ($pg->slug == 'paypal')
                                <div class="input__group mb-25">
                                    <label for="exampleInputEmail1">{{ __('Client ID')}}</label>
                                    <input type="text" name="paypal_client_id" value="{{env('PAYPAL_CLIENT_ID')}}">
                                </div>
                                <div class="input__group mb-25">
                                    <label for="exampleInputEmail1">{{ __('Client Secret')}}</label>
                                    <input type="text" name="paypal_client_secret" value="{{env('PAYPAL_CLIENT_SECRET')}}">
                                </div>
                            @endif
                            @if ($pg->slug == 'stripe')
                                <div class="input__group mb-25">
                                    <label for="exampleInputEmail1">{{ __('Key')}}</label>
                                    <input type="text" name="stripe_key" value="{{env('STRIPE_KEY')}}">
                                </div>
                                <div class="input__group mb-25">
                                    <label for="exampleInputEmail1">{{ __('Secret')}}</label>
                                    <input type="text" name="stripe_secret" value="{{env('STRIPE_SECRET')}}">
                                </div>
                            @endif
                            @if ($pg->slug == 'razorpay')
                                <div class="input__group mb-25">
                                    <label for="exampleInputEmail1">{{ __('Key')}}</label>
                                    <input type="text" name="razorpay_key" value="{{env('RAZORPAY_KEY')}}">
                                </div>
                                <div class="input__group mb-25">
                                    <label for="exampleInputEmail1">{{ __('Secret')}}</label>
                                    <input type="text" name="razorpay_secret" value="{{env('RAZORPAY_SECRET')}}">
                                </div>
                            @endif
                            @if ($pg->slug == 'paystack')
                                <div class="input__group mb-25">
                                    <label for="exampleInputEmail1">{{ __('Key')}}</label>
                                    <input type="text" name="paystack_key" value="{{env('PAYSTACK_PUBLIC_KEY')}}">
                                </div>
                                <div class="input__group mb-25">
                                    <label for="exampleInputEmail1">{{ __('Secret')}}</label>
                                    <input type="text" name="paystack_secret" value="{{env('PAYSTACK_SECRET_KEY')}}">
                                </div>
                            @endif
                            @if ($pg->slug == 'bank')
                                <div class="input__group mb-25">
                                    <label for="exampleInputEmail1">{{ __('Bank Name')}}</label>
                                    <input type="text" name="bank_name" value="{{env('BANK_NAME')}}">
                                </div>
                                <div class="input__group mb-25">
                                    <label for="exampleInputEmail1">{{ __('Account number')}}</label>
                                    <input type="text" name="bank_account_number" value="{{env('BANK_ACCOUNT_NUMBER')}}">
                                </div>
                                <div class="input__group mb-25">
                                    <label for="exampleInputEmail1">{{ __('Account Holder Name')}}</label>
                                    <input type="text" name="bank_account_holder_name" value="{{env('BANK_ACCOUNT_HOLDER_NAME')}}">
                                </div>
                            @endif
                            <div class="input__group mb-25">
                                <label>{{__('Visibility')}}</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="status" class="custom-control-input" id="customSwitch1" value="1" {{$pg->status == ACTIVE ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="customSwitch1">{{__('Click here to check/uncheck visibility.')}}</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger me-2" data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@extends('admin.master', ['menu' => 'products', 'submenu' => 'size'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ __('Edit Product Size')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="{{route('admin.dashboard')}}">{{ __('Home')}}</a></li>
                <li class="breadcrumb-item " aria-current="page">{{ __('Product Size')}}</li>
            </ol>
        </div>
        <div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Form Basic -->
                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">{{ __('Product Size')}}</h6>
                            <a href="{{route('admin.product.size')}}" class="btn btn-md btn-primary text-right">{{ __('All Product Sizes')}}</a>
                        </div>
                        <div class="card-body">
                            <form enctype="multipart/form-data" method="POST" action="{{route('admin.product.size.update')}}">
                                @csrf
                                <input type="hidden" name="id" value="{{$edit->id}}">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{ __('Size')}}</label>
                                    <input type="text" class="form-control" id="size" name="size" value="{{$edit->Size}}">
                                </div>
                                <button type="submit" class="btn btn-primary">{{ __('Update')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--Row-->
        </div>
    </div>
@endsection

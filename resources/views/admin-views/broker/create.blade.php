@extends('layouts.admin.app')
@section('title', translate('create rate list'))
@push('css_or_js')
@endpush
@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
            <img src="{{asset('public/assets/admin/img/category.png')}}" class="w--24" alt="">
            </span>
            <span>
            {{translate('rate list')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.broker-rate-list.store')}}" method="post"
                enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('title')}}</label>
                                    <input type="text" name="title" class="form-control"
                                        placeholder="{{ translate('title') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="my-4"> Product Details </h3>
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <h5>Category</h5>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <h5>Product</h5>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <h5>Unit</h5>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <h5>Available Qty</h5>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <h5>Rate</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row g-3">
                    <div class="col-md-8">
                        @foreach($categories as $category)
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <h5>{{$category->name }}</h5>
                                </div>
                            </div>
                            @if(count($category->products) >0)
                            @foreach($category->products as $key =>$product)
                                @if($key > 0)
                                    <div class="col-md-3"></div>
                                @endif

                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <input type="hidden" name="product_id[]" value="{{$product->id}}">
                                        <input type="text"  value="{{$product->name}}" class="form-control"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <select name="unit[]" class="form-control js-select2-custom" required>
                                        <option disabled selected>Select Unit</option>
                                        @foreach(\App\Model\Unit::get() as $unit)
                                            <option value="{{$unit->id}}">{{$unit->title}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <input type="text" name="available_qty[]" class="form-control" required oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                            placeholder="Available Qty" >
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <input type="text" name="rate[]"  class="form-control" required     oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                            placeholder="Rate" >
                                    </div>
                                </div>
                            @endforeach
                            @endif


                            {{-- <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <input type="text" name="title" value="" class="form-control"
                                        placeholder="first product" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <select name="unit" class="form-control js-select2-custom">
                                        <option value="kg">{{translate('kg')}}</option>
                                        <option value="gm">{{translate('gm')}}</option>
                                        <option value="ltr">{{translate('ltr')}}</option>
                                        <option value="pc" >{{translate('pc')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <input type="text" name="title" value="" class="form-control"
                                        placeholder="Available Qty" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <input type="text" name="title" value="" class="form-control"
                                        placeholder="Rate" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <input type="text" name="title" value="" class="form-control"
                                        placeholder="first product" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <select name="unit" class="form-control js-select2-custom">
                                        <option value="kg">{{translate('kg')}}</option>
                                        <option value="gm">{{translate('gm')}}</option>
                                        <option value="ltr">{{translate('ltr')}}</option>
                                        <option value="pc" >{{translate('pc')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <input type="text" name="title" value="" class="form-control"
                                        placeholder="Available Qty" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <input type="text" name="title" value="" class="form-control"
                                        placeholder="Rate" required>
                                </div>
                            </div> --}}
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-12">
                    <div class="btn--container justify-content-end">
                        <a href="{{route('admin.broker-rate-list.index')}}" type="reset" class="btn btn--reset">
                        {{translate('Back')}}</a>
                        <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('script_2')
@endpush
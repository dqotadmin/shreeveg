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
                        <div class="col-md-6">
                            @foreach($categories as $category)
                            <div class="row g-3">
                                <div class="col-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{$category->name }}</label>
                                        
                                    </div>
                                </div>
                                @if(count($category->products) >0)

                                <div class="col-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlSelect1"><span
                                                class="input-label-secondary">*</span></label>
                                                <input type="text" name="title" value="" class="form-control"
                                                placeholder="{{ translate('New banner') }}" required>
                                    </div>
                                </div>
                                @endif
                                <div class="col-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlSelect1"><span
                                                class="input-label-secondary">*</span>product</label>
                                                <input type="text" name="title" value="" class="form-control"
                                                placeholder="first product" required>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlSelect1"><span
                                                class="input-label-secondary">*</span>Unit</label>
                                                <select name="unit" class="form-control js-select2-custom">
                                                    <option value="kg">{{translate('kg')}}</option>
                                                    <option value="gm">{{translate('gm')}}</option>
                                                    <option value="ltr">{{translate('ltr')}}</option>
                                                    <option value="pc" >{{translate('pc')}}</option>
                                                </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlSelect1"><span
                                                class="input-label-secondary">*</span>Available Qty</label>
                                                <input type="text" name="title" value="" class="form-control"
                                                placeholder="Available Qty" required>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlSelect1"><span
                                                class="input-label-secondary">*</span>Rate</label>
                                                <input type="text" name="title" value="" class="form-control"
                                                placeholder="Rate" required>
                                    </div>
                                </div>
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

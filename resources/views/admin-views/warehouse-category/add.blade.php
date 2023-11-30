@extends('layouts.admin.app')

@section('title', translate('Add New Warehouse'))

@push('css_or_js')
<style>

</style>
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
                {{translate('warehouse')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->

    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
            <form action="{{route('admin.warehouse-category.store')}}" method="post" id="timeForm" enctype="multipart/form-data">
                @csrf
                <div class="row g-2">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="tio-user"></i>
                                    {{translate('Grouping')}}
                                </h5>
                            </div>
                            <div class="card-body pt-sm-0 pb-sm-4">
                                <div class="row align-items-end g-4" style="padding-top: 50px;">

                                    <div class="col-sm-6">
                                        <select id="city_code" name="group_name" class="city_code form-control">
                                            <option value="" disabled selected>Select Group</option>
                                            @foreach(Helpers::getAlphabet() as $alphabet)
                                            <option value="{{$alphabet}}">{{$alphabet}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Select Category For Grouping') }}
                                        </label>
                                        <select id="city_code" name="category_id[]"
                                            class="city_code form-control chosen-select" multiple>
                                            @foreach($warehouses->where('status','1') as $key=>$warehouse)
                                            <option value="{{$warehouse['category_id']}}">{{$warehouse->getCategory->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-3">
                                    <div class="btn--container justify-content-end">
                                        <a type="button" href="{{route('admin.warehouse-category.list')}}"
                                            class="btn btn--reset">{{translate('Back')}}</a>
                                        <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('script_2')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet" />


</body>
@endpush
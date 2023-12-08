@extends('layouts.admin.app')
@section('title', translate('rate list'))
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
    <div class="d-print-none pb-2">
        <div class="row align-items-center">
            <div class="col-auto mb-2 mb-sm-0">
                <h1 class="page-header-title">{{$row->title}}</h1>
                <span class="d-block">
                    <i class="tio-date-range"></i> {{translate('date')}} : {{date('d M Y, H:i A '.config('timeformat'),strtotime($row->date_time))}}
                </span>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="btn--container justify-content-end">
            <a href="{{route('admin.broker-rate-list.index')}}" type="reset" class="btn btn--reset">
            {{translate('Back')}}</a>
           
        </div>
    </div>
    <div class="card">
        <div class="card-body">
           
            <table class="table table-striped">
                <thead>
                    <th>#</th>
                 <th>Product</th>
                 <th>Available Qty</th>
                 <th>Unit</th>
                 <th>Rate</th>
                </thead>
                <tbody>
                    @foreach($row->rateListDetail as $key => $value)
                  <tr class="">
                   <td>{{ $key+1}}</td>
                   <td>{{ $value->productDetail->name }}</td>
                   <td>{{ $value->available_qty }}</td>
                   <td>{{ $value->unit }}</td>
                   <td>{{ $value->rate }}</td>
                  </tr>
                  @endforeach
                  
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
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
                  <tr>
                    <th scope="row">3</th>
                    <td colspan="2" class="table-active">Larry the Bird</td>
                    <td>@twitter</td>
                  </tr>
                </tbody>
              </table>

                
            
        </div>
    </div>
</div>
@endsection
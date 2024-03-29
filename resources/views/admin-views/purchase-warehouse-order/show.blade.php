@extends('layouts.admin.app')
@section('title', translate('order detail'))
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
            {{translate('order detail')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->
    <div class="d-print-none pb-2">
        <div class="row align-items-center">
            <div class="col-auto mb-2 mb-sm-0">
                <h1 class="page-header-title">
                    @if($role == 8)
                    {{translate('warehouse')}} : {{$row->warehouseDetail->name}}
                    @else
                    {{translate('broker')}} : {{$row->brokerDetail->f_name.' '.$row->brokerDetail->l_name }}
                    @endif
                </h1>
                <span class="d-block">
                <i class="tio-date-range"></i> {{translate('order_date')}} : {{date('d M Y '.config('timeformat'),strtotime($row->purchase_date))}}
                </span>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="btn--container justify-content-end">
            <a href="{{route('admin.purchase-warehouse-order.index')}}" type="reset" class="btn btn--reset">
            {{translate('Back')}}</a>
        </div>
    </div>
    <div class="row mb-2 g-2">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="resturant-card bg--2">
                <img class="resturant-icon" src="{{asset('/public/assets/admin/img/dashboard/3.png')}}" alt="dashboard"> 
                <div class="for-card-text font-weight-bold  text-uppercase mb-1">{{translate('total amount')}} </div>
                <div class="for-card-count">{{ $row->total_purchase_amt}}</div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="resturant-card bg--3">
                <img class="resturant-icon" src="{{asset('/public/assets/admin/img/dashboard/1.png')}}" alt="dashboard">
                <div class="for-card-text font-weight-bold  text-uppercase mb-1">{{translate('total products')}} </div>
                <div class="for-card-count">{{count($row->purchaseWarehouseOrderDetail)}}</div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <th>#</th>
                    <th>Product</th>
                    <th>Order Qty</th>
                    <th>Unit</th>
                    <th>Rate</th>
                    <th>Total</th>
                </thead>
                <tbody>
                    @foreach($row->purchaseWarehouseOrderDetail as $key => $value)
                    <tr class="">
                        <td>{{ $key+1}}</td>
                        <td>{{ @$value->productDetail->name }}</td>
                        <td>{{ $value->qty }}</td>
                        <td>{{ $value->unit_name }}</td>
                        <td>{{ $value->price_per_unit }}</td>
                        <td>{{ $value->total_price }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if((auth('admin')->user()->admin_role_id == 5) && ($row->status == 'Rejected'))
            <div class="row align-items-center">
                <div class="col-12 mb-2 mb-sm-0" style="text-align: center;">
                    <h4> Reason for Reject</h4>
                    <span>{{$row->broker_comments}}</span>
                </div> 
            </div>
            @endif

            <form action="{{route('admin.wh_receiver_update_status',$row->id)}}" method="post">
                @csrf
                @if((auth('admin')->user()->admin_role_id == 8 && $row->status != 'Rejected' && $row->status != 'Delivered' && $row->status != 'Received'))
                    <div class="col-md-6">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('update status')}}</label>
                        <select name="status" class="form-control">
                            @if(auth('admin')->user()->admin_role_id == 8  &&  $row->status != 'Pending' &&  $row->status != 'Accepted' &&  $row->status != 'Delivered' )
                                <option value="Pending" disabled {{$row->status == 'Pending'?'selected':''}}>Pending</option>
                            @endif
                            @if(auth('admin')->user()->admin_role_id == 8  &&  $row->status != 'Accepted' )
                                <option value="Accepted" {{$row->status == 'Accepted'?'selected':''}}>Accepted</option>
                            @endif
                                <option value="Delivered" {{$row->status == 'Delivered'?'selected':''}}>Delivered</option>
                            @if($row->status != 'Accepted')
                            <option value="Rejected" {{$row->status == 'Rejected'?'selected':''}}>Rejected</option>
                            @endif
                            
                        </select>
                    </div>
                    @if(auth('admin')->user()->admin_role_id == 8 || $row->status == 'Pending' || $row->status == 'Accepted')
                        @if(auth('admin')->user()->admin_role_id == 8 &&  $row->status != 'Delivered')
                        <div class="col-md-6 mt-3">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('broker comments')}}</label>
                            <textarea name="broker_comments" class="form-control" rows="6" placeholder="{{ translate('enter comments if any') }}" >{{$row->broker_comments}}</textarea>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary px-5">{{translate('save')}}</button>
                        </div>

                        @endif
                    @endif
                @endif
                @if((in_array(auth('admin')->user()->admin_role_id ,[1,3,5])) && ($row->status != 'Pending' && $row->status != 'Accepted' && $row->status != 'Received' && $row->status != 'Rejected'))
                    <div class="col-md-6">
                    <label class="input-label" for="exampleFormControlInput1">{{translate('update status')}}</label>
                        <select name="status" class="form-control">
                        
                                <option value="Received" {{$row->status == 'Received'?'selected':''}}>Received</option>
                            </select>
                    </div>
                    <div class="col-md-6">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('comments')}}</label>
                        <textarea name="warehouse_comments" class="form-control" rows="6" placeholder="{{ translate('enter comments if any') }}">{{$row->warehouse_comments}}</textarea>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary px-5">{{translate('save')}}</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
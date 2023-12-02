@extends('layouts.admin.app')
@section('title', translate('Add new banner'))
@push('css_or_js')
@endpush
@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
            <img src="{{asset('public/assets/admin/img/banner.png')}}" class="w--20" alt="">
            </span>
            <span>
            {{translate('store orders')}}
            </span>
        </h1>
    </div>
    
    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
            <div class="btn--container justify-content-end m-2">
                @if(in_array($user->admin_role_id,[3,6]))
                <a type="button"  href="{{route('admin.store.purchase-store-orders.create')}}" class="btn btn--primary">{{translate('Add new')}}</a>
                @endif
            </div>
            </div>
        </div>
    <div class="card">
        <!-- Header -->
        <div class="card-header border-0">
            <div class="card--header justify-content-between max--sm-grow">
                <h5 class="card-title">{{translate('store orders')}} <span class="badge badge-soft-secondary">{{ $rows->total() }}</span></h5>
                <form action="{{url()->current()}}" method="GET">
                    <div class="input-group">
                        <input type="search" name="search" class="form-control"
                            placeholder="{{translate('search')}}" aria-label="Search"
                            value="{{$search}}" autocomplete="off">
                        <div class="input-group-append">
                            <button type="submit" class="input-group-text">
                            {{translate('search')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Header -->
        <!-- Table -->
        <div class="table-responsive datatable-custom">
            <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                <thead class="thead-light">
                    <tr>
                        <th class="border-0">{{translate('#')}}</th>
                        @if(in_array($user->admin_role_id ,[6,1]))
                        <th class="border-0">{{translate('warehouse')}}</th>
                        @endif
                        @if(in_array($user->admin_role_id ,[3,1]))
                        <th class="border-0">{{translate('store')}}</th>
                        @endif
                        <th class="border-0">{{translate('title')}}</th>
                        <th class="border-0">{{translate('invoice no')}}</th>
                        <th class="border-0">{{translate('item')}}</th>
                        <th class="border-0">{{translate('amount')}}</th>
                        <th class="border-0">{{translate('order_date')}}</th>
                        <th class="border-0">{{translate('order_status')}}</th>
                        <th class="text-center border-0">{{translate('action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $key=>$row)
                    <tr>
                        <td>{{$key+1}}</td>
                        @if(in_array($user->admin_role_id ,[6,1]))
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                                {{ $row->warehouseDetail->name }} 
                            </span>
                        </td>
                        @endif
                        @if(in_array($user->admin_role_id ,[3,1]))
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                                {{ $row->storeDetail->name  }} 
                            </span>
                        </td>
                        @endif
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                            {{$row->title }}
                            </span>
                        </td>
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                            {{$row->invoice_number }}
                            </span>
                        </td>
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                                {{count($row->purchaseStoreOrderDetail)}}
                            </span>
                        </td>
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                            {{number_format($row->total_purchase_amt,2) }}
                            </span>
                        </td>
                        <td>
                            {{ date('d-m-Y',strtotime($row->purchase_date))}}
                        </td>
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                            {{$row->status }}
                            </span>
                        </td>
                        <td>
                            <!-- Dropdown -->
                            <div class="btn--container justify-content-center">
                                <a class="action-btn" href="{{route('admin.store.purchase-store-orders.show',[$row->id])}}">
                                <i class="tio-invisible"></i>
                                </a>
                                @if($user->admin_role_id == 5 && $row->status == 'Pending')
                                <a class="action-btn btn--danger btn-outline-danger" href="javascript:"
                                onclick="form_alert('order-{{$row->id}}','{{ translate("Want to delete this") }}')">
                                 <i class="tio-delete-outlined"></i>
                             </a>
                             @endif
                         </div>
                         <form action="{{route('admin.purchase-warehouse-order.destroy',[$row->id])}}"
                               method="post" id="order-{{$row->id}}">
                             @csrf @method('delete')
                         </form>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <table>
                <tfoot>
                    {!! $rows->links() !!}
                </tfoot>
            </table>
        </div>
        @if(count($rows) == 0)
        <div class="text-center p-4">
            <img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">
            <p class="mb-0">{{translate('No_data_to_show')}}</p>
        </div>
        @endif
        <!-- End Table -->
    </div>
</div>
@endsection
@push('script_2')
@endpush
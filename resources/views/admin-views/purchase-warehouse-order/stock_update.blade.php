@extends('layouts.admin.app')
@section('title', translate('warehouse orders'))
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
            {{translate('warehouse orders')}}
            </span>
            
        </h1>
        <?php $currentDate = date('d-M-y', strtotime('today')); ?>
    <a  class="btn btn-info " href="{{route('admin.stock-update')}}">Price Management <strong class="text-dark">{{$currentDate}}</strong> </a>
    
 </div>
    </div>
 <div class="">

    <div class="card">
        <!-- Header -->
        <div class="card-header border-0">
            <div class="card--header justify-content-between max--sm-grow">
                <h5 class="card-title">{{translate('warehouse orders')}} <span class="badge badge-soft-secondary">{{ $rows->total() }}</span></h5>
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
                   
                        <th class="border-0">{{translate('broker')}}</th>
                        <th class="border-0">{{translate('invoice no')}}</th>
                        <th class="border-0">{{translate('item')}}</th>
                        <th class="border-0">{{translate('amount')}}</th>
                        <th class="border-0">{{translate('order_date')}}</th>
                        <th class="border-0">{{translate('order_status')}}</th>
                        <th class="text-center border-0">{{translate('action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    {{$rows}}
                    @foreach($rows as $key=>$row)
                    <tr>
                        <td>{{$key+1}}</td>
                        <!-- <td>
                            <span class="d-block font-size-sm text-body text-trim-25"> -->
                                <!-- {{ ($role == 8 || $role == 1)
                                    ?$row->warehouseDetail->name:$row->brokerDetail->f_name.' '.$row->brokerDetail->l_name  }}  -->
                            <!-- </span>
                        </td> -->
                        
                        @if($role == 8 || $role == 1 )
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">{{@$row->warehouseDetail->name}}
                            </span>
                        </td>
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                                {{$row->receiverName->f_name.' '.$row->receiverName->l_name }}
                            </span>
                        </td>
                        @else
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                                {{$row->brokerDetail->f_name.' '.$row->brokerDetail->l_name }}
                            </span>
                        </td>
                        @endif
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                            {{$row->invoice_number }}
                            </span>
                        </td>
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                                {{count($row->purchaseWarehouseOrderDetail)}}
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
                        @if($row->status == 'Pending')
                        <span class="d-block font-size-sm text-trim-25 text-danger">
                        @elseif($row->status == 'Rejected')
                        <span class="d-block font-size-sm text-trim-25  text-muted" >
                        @elseif($row->status == 'Delivered')
                        <span class="d-block font-size-sm text-trim-25  text-success" >
                        @elseif($row->status == 'Accepted')
                        <span class="d-block font-size-sm text-trim-25  text-info" >
                        @elseif($row->status == 'Received')
                        <span class="d-block font-size-sm text-trim-25  text-primary" >
                        @else
                        <span class="d-block font-size-sm text-body text-trim-25 text-dark">
                        @endif                  
                               <strong>   {{$row->status }}</strong>
                              
                            </span>

                        </td>
                        <td>
                            <!-- Dropdown -->
                            <div class="btn--container justify-content-center">
                                <a class="action-btn" href="{{route('admin.purchase-warehouse-order.show',[$row->id])}}">
                                <i class="tio-invisible"></i>
                                </a>
                                @if($role == 5 && $row->status == 'Pending')
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
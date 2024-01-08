@extends('layouts.admin.app')
@section('title', translate('store_purchase_orders'))
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
            {{translate('donations')}}
            </span>
        </h1>
    </div>
    
    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
            <div class="btn--container justify-content-end m-2">
                
                <a type="button"  href="{{route('admin.product.donations.create')}}" class="btn btn--primary">{{translate('Add new')}}</a>
                
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
                        @if(in_array($user->admin_role_id ,[3]))
                        <th class="border-0">{{translate('warehouse')}}</th>
                        @endif
                        
                        <th class="border-0">{{translate('store')}}</th>
                       
                        <th class="border-0">{{translate('category')}}</th>
                        <th class="border-0">{{translate('product')}}</th>
                        <th class="border-0">{{translate('quantity')}}</th>
                        <th class="border-0">{{translate('date')}}</th>
                       
                        <th class="text-center border-0">{{translate('action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $key=>$row)
                    <tr>
                        <td>{{$key+1}}</td>
                        @if(in_array($user->admin_role_id ,[3]))
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                                {{ $row->warehouseDetail->name }} 
                            </span>
                        </td>
                        @endif
                       
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                                {{ $row->store_id?$row->storeDetail->name:''  }} 
                            </span>
                        </td>
                      
                        
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                            {{$row->productDetail->category->name }}
                            </span>
                        </td>
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                            {{$row->productDetail->name }}<br>
                            ({{$row->productDetail->product_code }})
                            </span>
                        </td>
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                                {{$row->quantity}} {{$row->productDetail->unit->title }}
                            </span>
                        </td>
                        
                        <td>
                            {{ date('d M Y h:i A',strtotime($row->created_at))}}
                        </td>
                        <td>
                       
                       
                       
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
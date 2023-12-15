@extends('layouts.admin.app')

@section('title', translate('Product List'))

@section('content')
<div class="content container-fluid product-list-page">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{asset('public/assets/admin/img/products.png')}}" class="w--24" alt="">
            </span>
            <span>
                {{ translate('product List') }}
                <span class="badge badge-soft-secondary">{{ $products->total() }}</span>
            </span>
        </h1>
    </div>
    <!-- End Page Header -->
    <div class="row gx-2 gx-lg-3">
        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
            <!-- Card -->
            <div class="card">
                <!-- Header -->
                <div class="card-header border-0">
                    <div class="card--header justify-content-start max--sm-grow ">
                        <!-- <form action="{{url()->current()}}" method="GET" class="float-left">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                    placeholder="{{translate('Search_by_ID_or_name')}}" aria-label="Search"
                                    value="{{$search}}" autocomplete="off">
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text">
                                        {{translate('search')}}
                                    </button>
                                </div>
                            </div>
                        </form> -->
                        @if(auth('admin')->user()->admin_role_id == 1)
                        <div class="col-md-3 m-2">
                            <select name="" id="fetch_warehouse_stock" class="form-control">
                                <option value="" disabled selected>{{translate('Select Warehouse')}}</option>
                                @foreach(\App\Model\Warehouse::where('status','1')->where('deleted_at',null)->get()
                                as $warehouse)
                                <option value="{{$warehouse->id}}" id="warehouse_id">{{$warehouse->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif      <?php $condition = ''; 
                            if(auth('admin')->user()->admin_role_id == '1')
                                 $condition =  \App\Model\Store::where('status','1')->where('deleted_at',null)->get();
                            elseif(auth('admin')->user()->admin_role_id == 3)
                                $condition =  \App\Model\Store::where('status','1')->where('deleted_at',null)->where('warehouse_id',auth('admin')->user()->warehouse_id)->get();
                                elseif(auth('admin')->user()->admin_role_id == 6)
                                $condition =  \App\Model\Store::where('status','1')->where('deleted_at',null)->where('id',auth('admin')->user()->store_id)->get();
                            $selected = 'selected';
                           ?>
                             <div class="col-md-3 m-2">
                            <select name="" id="fetch_store_stock" class="form-control">
                                <option value=""  disabled selected>{{translate('Select Store')}}</option>
                            @if($condition)  
                            @foreach($condition as $store)
                                <option value="{{$store->id}}" {{$selected}} id="store_id">{{$store->name}}</option>
                            @endforeach
                            @endif
                            </select>
                        </div>
                      
                    </div>
                    <div id="product_detail"></div>
                    <!-- Unfold -->
                     
                </div>
                <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable product_detail"
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true
                               }'>
                        <thead class="thead-light">
                            <tr>
                                <th>{{translate('#')}}</th>
                                <th>{{translate('product_name')}}</th>
                                <th id="store_id" class="d-none">{{translate('store_id')}}</th>
                            <?php if(auth('admin')->user()->admin_role_id == 1)
                                $display = "d-none";
                                else
                                $display = "d-block"; ?>
                                <th id="stock" class=<?php echo $display ?>>{{translate('stock')}}</th>
                                </tr>
                        </thead>

                        <tbody id="set-rows" class="justify-content-end">
                            @foreach($products as $key=>$product)
                            <tr>
                                <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">{{$products->firstItem()+$key}}
                                </td>
                                <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">
                                    @if(auth('admin')->user()->admin_role_id != 3)
                                    <a href="{{route('admin.product.view',[$product['id']])}}"
                                        class="product-list-media">
                                        @else
                                        <a class="product-list-media">
                                            @endif
                                            @if (!empty(json_decode($product['image'],true)))
                                            <img src="{{asset('storage/app/public/product')}}/{{json_decode($product['image'],true)[0]}}"
                                                onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'">
                                            @else
                                            <img src="{{asset('public/assets/admin/img/400x400/img2.jpg')}}">
                                            @endif
                                            <h6 class="name line--limit-2">
                                                {{\Illuminate\Support\Str::limit($product['name'], 20, $end='...')}}
                                            </h6>
                                        </a>
                                </td>
                                <!-- <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">
                                    <div class="max-85 text-right">
                                        {{ $product['product_code'] }}
                                    </div>
                                </td> -->
                                @if(auth('admin')->user()->admin_role_id == 3)
                                <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">
                                    <?php
                                            $current_stock =  0;

                                        foreach(\App\Model\WarehouseProduct::where('warehouse_id',auth('admin')->user()->warehouse_id)->where('product_id',$product->id)->get() as $stock){
                                           if($stock->total_stock > 0){
                                            $current_stock =  $stock->total_stock;
                                           }
                                        }
                                        echo $current_stock;
                                        ?> /({{@$product->unit['title'] }})
                                </td>
                                @endif

                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="page-area">
                        <table>
                            <tfoot class="border-top">
                                {!! $products->links() !!}
                            </tfoot>
                        </table>
                    </div>
                    @if(count($products)==0)
                    <div class="text-center p-4">
                        <img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}"
                            alt="Image Description">
                        <p class="mb-0">{{translate('No_data_to_show')}}</p>
                    </div>
                    @endif
                </div>
                <!-- End Table -->
            </div>
            <!-- End Card -->
        </div>
    </div>
</div>
</div>

@endsection

@push('script_2')
<script>
    // Function to handle AJAX request
    function fetchStoreStock(storeId) {
        var tbody = $('#set-rows');
        tbody.empty(); // Clear the existing rows

        $.ajax({
            url: '{{url('/')}}/admin/pos/fetch-store-stock/' + storeId,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var tbody = $('#set-rows');
        tbody.empty(); // Clear the existing rows
        if (data.data.length === 0) {
            // Display "No data to show" message
            var noDataHtml = '<div class="row">';
            noDataHtml += '<div class="col-md-12 text-center p-4">';
            noDataHtml += '</div>';
            noDataHtml += '<div class="col-md-12 text-center p-4">';
            noDataHtml += '<img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">';
            noDataHtml += '<p class="mb-0">{{translate('No_data_to_show')}}</p>';
            noDataHtml += '</div>';
            noDataHtml += '</div>';
            tbody.append(noDataHtml);
        }else{
        $.each(data.data, function (index, item) {
            var rowHtml = '<tr>';
            rowHtml += '<td class="pt-1 pb-3  pt-4">' + (index + 1) + '</td>';
            rowHtml += '<td class="pt-1 pb-3  pt-4">' + item.product.name + '</td>'; // Replace with actual property names
            rowHtml += '<td class="pt-1 pb-3  pt-4">' + item.total_stock + '</td>'; // Replace with actual property names
            rowHtml += '</tr>';
            console.log(item)
            tbody.append(rowHtml); });
              
            }// ... Your existing logic for displaying data ...
            }
        });
    }

    // Trigger the AJAX request on page load with the default selected value
    $(document).ready(function () {
        var defaultStoreId = $('#fetch_store_stock').val();
        if (defaultStoreId) {
            // Trigger the AJAX request with the default selected value
            fetchStoreStock(defaultStoreId);
            
            // Additional logic if needed...
            $('#stock').removeClass('d-none');
            $('#store_id').removeClass('d-none');
            $('.page-area').addClass('d-none');
        }
    });

    // Handle the change event for the select element
    $('#fetch_store_stock').on('change', function () {
        var storeId = $(this).val();
        fetchStoreStock(storeId);

        // Additional logic if needed...
        $('#stock').removeClass('d-none');
        $('#store_id').removeClass('d-none');
        $('.page-area').addClass('d-none');
    });
</script>
<!-- 
<script>
        $('#fetch_store_stock').on('selected',function(){
            $('#stock').removeClass('d-none');
            $('#store_id').removeClass('d-none');
            $('.page-area').addClass('d-none');
        var store_id = $(this).val();
        $.ajax({
            url: '{{url('/')}}/admin/pos/fetch-store-stock/'+store_id,
            type:'GET',
            dataType:'json',
            success:function(data){
                var tbody = $('#set-rows');
        tbody.empty(); // Clear the existing rows
        if (data.data.length === 0) {
            // Display "No data to show" message
            var noDataHtml = '<div class="row">';
            noDataHtml += '<div class="col-md-12 text-center p-4">';
            noDataHtml += '</div>';
            noDataHtml += '<div class="col-md-12 text-center p-4">';
            noDataHtml += '<img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">';
            noDataHtml += '<p class="mb-0">{{translate('No_data_to_show')}}</p>';
            noDataHtml += '</div>';
            noDataHtml += '</div>';
            tbody.append(noDataHtml);
        }else{
        $.each(data.data, function (index, item) {
            var rowHtml = '<tr>';
            rowHtml += '<td class="pt-1 pb-3  pt-4">' + (index + 1) + '</td>';
            rowHtml += '<td class="pt-1 pb-3  pt-4">' + item.product.name + '</td>'; // Replace with actual property names
            rowHtml += '<td class="pt-1 pb-3  pt-4">' + item.total_stock + '</td>'; // Replace with actual property names
            rowHtml += '</tr>';
            console.log(item)
            tbody.append(rowHtml); });
              
            }
            }
        });
        });
    </script> -->
    
<script>
        $('#fetch_warehouse_stock').on('change',function(){
            $('#stock').removeClass('d-none');
            $('#store_id').removeClass('d-none');
            $('.page-area').addClass('d-none');
            $('#warehouse_id').removeClass('d-none');
        var store_id = $(this).val();
        $.ajax({
            url: '{{url('/')}}/admin/pos/fetch-warehouse-stock/'+store_id,
            type:'GET',
            dataType:'json',
            success:function(data){
                var tbody = $('#set-rows');
        tbody.empty(); // Clear the existing rows

        if (data.data.length === 0) {
            // Display "No data to show" message
            var noDataHtml = '<div class="row">';
            noDataHtml += '<div class="col-md-12 text-center p-4">';
            noDataHtml += '</div>';
            noDataHtml += '<div class="col-md-12 text-center p-4">';
            noDataHtml += '<img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">';
            noDataHtml += '<p class="mb-0">{{translate('No_data_to_show')}}</p>';
            noDataHtml += '</div>';
            noDataHtml += '</div>';
            tbody.append(noDataHtml);
        } else {
            // Iterate over the data and create rows
            $.each(data.data, function(index, item) {
                var rowHtml = '<tr>';
                rowHtml += '<td class="pt-1 pb-3  pt-4">' + (index + 1) + '</td>';
                if (item.product_detail) {
                    rowHtml += '<td class="pt-1 pb-3  pt-4">' + item.product_detail.name + '</td>';
                }
                rowHtml += '<td class="pt-1 pb-3  pt-4">' + item.total_stock + '</td>'; // Replace with actual property names
                rowHtml += '</tr>';
                console.log(item)
                tbody.append(rowHtml);
            });
        }
    }
        });
        });
    </script>
@endpush
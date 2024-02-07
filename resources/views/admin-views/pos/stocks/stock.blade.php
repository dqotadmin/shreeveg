@extends('layouts.admin.app')

@section('title', translate('stock_list'))

@section('content')
<?php       $admin_role_id = auth('admin')->user()->admin_role_id; ?>
<div class="content container-fluid product-list-page">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{asset('public/assets/admin/img/products.png')}}" class="w--24" alt="">
            </span>
            <span>
                {{ translate('stock_list') }}
                <span class="badge badge-soft-secondary">{{ $products->total() }}</span>
            </span>
        </h1>
    </div>
    <?php $stock_limit_row = \App\Model\BusinessSetting::where('key', 'minimum_stock_limit')->first();
        $stock_limit = $stock_limit_row?$stock_limit_row->value:0; ?>
    <!-- End Page Header -->
    <div class="row gx-2 gx-lg-3">
        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
            <!-- Card -->
            <div class="card">
                <!-- Header -->
                <div class="card-header border-0">
                    <div class="card--header justify-content-start max--sm-grow ">
                      
                        @if($admin_role_id == 1)
                        <div class="col-md-3 m-2">
                            <select name="warehouse_id" id="fetch_warehouse_stock" class=" form-control">
                                <option value="" disabled selected>{{translate('Select Warehouse')}}</option>
                                @foreach(\App\Model\Warehouse::where('status','1')->where('deleted_at',null)->get()
                                as $warehouse)
                                <option value="{{$warehouse->id}}" id="warehouse_id"  >{{$warehouse->name}}</option>
                                @endforeach
                            </select>
   
                        </div>
                        @endif    
                        
                        <div class="col-md-3 m-2">
                            <select   class="form-control store_name" name="store_id" id="fetch_store_stock">
                                    <option disabled selected>--- {{translate('select')}} {{translate('Store')}} ---</option>
                                
                            </select>
                        </div>
                           

                        
                    </div>
                      
                </div>
                    <!-- Unfold -->
                     
             <div id="product_detail"></div>

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
        var warehouse_id = "{{$warehouse_id}}";
        getStore(warehouse_id);
        $('select[name="warehouse_id"]').on('change',function(){
            var warehouse_id = $(this).val();
            getStore(warehouse_id);
           
        });
        function getStore(warehouse_id){
        //    alert(warehouse_id);
            if(warehouse_id){
                $.ajax({
            url: '{{url('/')}}/admin/report/stores/' + warehouse_id,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
            console.log(data);  
            $('.store_name').empty();
            $('.store_name').append('<option value="" disabled selected>--- Select Store ---</option>');
            $.each(data.stores, function (key, value) {
                    $('.store_name').append('<option value="' + value.id + '" >' + value.name + '</option>');
                });
               
            }
        });
            }
        }
    </script>
<script>
    
    // Function to handle AJAX request
    function fetchStoreStock(storeId) {
        // var tbody = $('#set-rows');
        // tbody.empty(); // Clear the existing rows

        $.ajax({
            url: '{{url('/')}}/admin/pos/fetch-store-stock/' + storeId,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#product_detail').html(data.view);
            }
        });
    }

    // Trigger the AJAX request on page load with the default selected value
    $(document).ready(function () {
        var defaultStoreId = $('#fetch_store_stock').val();
        if (defaultStoreId) {
            // Trigger the AJAX request with the default selected value
            fetchStoreStock(defaultStoreId);
        }
    });

    // Handle the change event for the select element
    $('#fetch_store_stock').on('change', function () {
        var storeId = $(this).val();
        fetchStoreStock(storeId);
    });
</script>
 
    
<script>
        $('#fetch_warehouse_stock').on('change',function(){
          
        var warehouse_id = $(this).val();
        $.ajax({
            url: '{{url('/')}}/admin/pos/fetch-warehouse-stock/'+warehouse_id,
            type:'GET',
            dataType:'json',
            success:function(data){
                console.log(data);
                $('#product_detail').html(data.view);

    }
        });
        });
    </script>
@endpush
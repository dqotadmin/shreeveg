@extends('layouts.admin.app')
@section('title', translate('order detail'))
@push('css_or_js')
@endpush
@section('content')
<?php

use App\Model\PurchaseWarehouseOrder;

 $role = auth('admin')->user()->admin_role_id; 
  $currentDate = date('d-M-y', strtotime('today')); ?>
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
                   
                </h1>
                <span class="d-block">
                <i class="tio-date-range"></i> {{translate('order_date')}} :<strong>  {{$currentDate}}</strong>
                </span>
            </div>
        </div>
    </div>
  
    <!-- <div class="row mb-2 g-2">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="resturant-card bg--2">
                <img class="resturant-icon" src="{{asset('/public/assets/admin/img/dashboard/3.png')}}" alt="dashboard"> 
                <div class="for-card-text font-weight-bold  text-uppercase mb-1">{{translate('total amount')}} </div>
                <div class="for-card-count"> total_purchase_amt</div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="resturant-card bg--3">
                <img class="resturant-icon" src="{{asset('/public/assets/admin/img/dashboard/1.png')}}" alt="dashboard">
                <div class="for-card-text font-weight-bold  text-uppercase mb-1">{{translate('total products')}} </div>
                <div class="for-card-count">purchaseWarehouseOrderDetail</div>
            </div>
        </div>
    </div> -->
    <div class="card">
        <div class="card-body"> 
           <?php
           $i=1;
           $role = auth('admin')->user(); 
                $warehouse_id = auth('admin')->user()->warehouse_id; 
               $today = today();
                  $order_id = \App\Model\PurchaseWarehouseOrder::where('warehouse_id',$role->warehouse_id)->whereDate('created_at', $today)->pluck('id')->toArray(); //11,12
                //  $order_id_details = \App\Model\PurchaseWarehouseOrderDetail::whereIn('purchase_warehouse_order_id',$order_id)->get();//11, 12 all data
                 $WarehouseProducts = \App\Model\WarehouseProduct::where('warehouse_id',$warehouse_id)->with('productDetail')->get();//11, 12 all data
   ?>
        <form action="{{route('admin.price-update')}}" method="post">
            @csrf 
            <table class="table table-striped">
                <thead>
                    <th>#</th>
                    <th>Product</th>
                    <th>Broker</th>
                    <!-- <th>Rate</th> -->

                    <th>average rate</th>
                    <th>Margin(%)</th>
                    <th>New rate</th>
                    <th>Old rate</th>
                </thead>
                <tbody>
            
                @foreach($WarehouseProducts as $products)            
                    <tr class="">
                        <td><?php echo $i++; ?></td>
                        <td>{{$products->productDetail->name}}  
                        <input type="hidden" value="{{$products->product_id}}" name="product_id[]" class="">
                        </td>
                        <td>
                        <?php  $data = Helpers::avgprice($products->product_id, $order_id);
                        $avgPrice = 0;
                     ?>
                          
                            @if(isset($data) && count($data) > 0)
                            @php($avgPrice = $data->avg('price_per_unit'))
                                @foreach($data as $val)
                                    {{@$val->purchaseWarehouseOrderList->brokerDetail->f_name}}    {{@$val->purchaseWarehouseOrderList->brokerDetail->l_name}} :   {{@$val->price_per_unit}}
                                    <br>
                                    
                                @endforeach
                                @endif
                               <?php  $price = (round($avgPrice,2)); ?>
                       
                        </td>
                        <td>{{$price}}
                        <input type="hidden" value="{{$avgPrice}}" name="" class="product_rate"> </td>
                       
                        <!-- <td class="" style="width: 180px;">
                            <input type="text"  class="form-control w-50 margin_price" value="40" > 
                        </td> -->
                        <td class="" style="width: 180px;">
                        @if($price)  
                            
                            <input type="number"  class="form-control w-50 avg_price" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');">  
                                <input type="number"  class="form-control w-50 customer_price" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"> 
                            <input type="number"  class="form-control w-50 store_price" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');">
                       
                            @endif
                        </td>
                        <td class="" style="width: 198px;" > 
                            <div class="" style="display: flex;align-items: center;">  <label for="">avg rate:</label>
                            <input type="text" value="{{$price? $price:$products->avg_price}}" name="avg_price[]" class="form-control w-50" required > </div>  
                            <div class="" style="display: flex;align-items: center;">  <label for="">customer rate:</label>
                            <input type="text" value="{{$price? '':$products->customer_price}}" name="customer_price[]"  class="form-control w-50" required> </div>  
                            <div class="" style="display: flex;align-items: center;">  <label for="">store rate:</label>
                            <input type="text" value="{{$price? '':$products->store_price}}" name="store_price[]"  class="form-control w-50" required> </div>  
                        </td>
                        <td class="" > 
                       
                       <div class="" style="display: flex;align-items: center;">  <label for="">avg rate:</label>
                       <h5 style="margin-left: 15px;">{{$products->avg_price}}</h5> </div>  
                       <div class="" style="display: flex;align-items: center;">  <label for="">customer rate:</label>
                       <h5 style="margin-left: 15px;">{{$products->customer_price}}</h5> </div>  
                       <div class="" style="display: flex;align-items: center;">  <label for="">store rate:</label>
                       <h5 style="margin-left: 15px;">{{$products->store_price}}</h5> </div>  
                       </td>
                        
                    </tr>
                @endforeach
                </tbody>
                </table>
        <div class="btn--container justify-content-end">
            <a href="{{route('admin.price-update')}}" type="reset" class="btn btn--reset">
            {{translate('Back')}}</a>
                    <button type="submit" class="btn btn-primary px-5">{{translate('save')}}</button>
        </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('script_2')
<script>
$(document).on("input", ".customer_price", function () {
    var margin = $(this).val();
    var row = $(this).closest('tr');
    var product_rate = row.find('.product_rate');

    var product_rate = parseFloat(product_rate.val());
    console.log(product_rate);
    // Check if marketPrice is a valid number
    if (!isNaN(product_rate)) {
        // Calculate offer price
        var customerPrice = product_rate + (product_rate * margin / 100);

        // Find the offer price input within the same row
        var customer_price = row.find('input[name="customer_price[]"]');

        // Set the offer price value
        customer_price.val(customerPrice.toFixed(2));
    }
});
$(document).on("input", ".avg_price", function () {
    var margin = $(this).val();
    var row = $(this).closest('tr');
    var product_rate = row.find('.product_rate');

    var product_rate = parseFloat(product_rate.val());
 
    // Check if marketPrice is a valid number
    if (!isNaN(product_rate)) {
        // Calculate offer price
        var avgPrice = product_rate + (product_rate * margin / 100);
       

        // Find the offer price input within the same row
        var avg_price = row.find('input[name="avg_price[]"]');
        // Set the offer price value
        avg_price.val(avgPrice.toFixed(2));
      
    }
});
$(document).on("input", ".store_price", function () {
    var margin = $(this).val();
    var row = $(this).closest('tr');
    var product_rate = row.find('.product_rate');

    var product_rate = parseFloat(product_rate.val());
    console.log(product_rate);
    // Check if marketPrice is a valid number
    if (!isNaN(product_rate)) {
        // Calculate offer price
        var storePrice = product_rate + (product_rate * margin / 100);
        // Find the offer price input within the same row
        var store_price = row.find('input[name="store_price[]"]');
        // Set the offer price value
        store_price.val(storePrice.toFixed(2));
    }
});
</script>
@endpush
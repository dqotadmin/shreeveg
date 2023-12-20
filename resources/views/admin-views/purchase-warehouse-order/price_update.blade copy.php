@extends('layouts.admin.app')
@section('title', translate('order detail'))
@push('css_or_js')
@endpush
@section('content')
<?php $role = auth('admin')->user()->admin_role_id; 
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
                <i class="tio-date-range"></i> {{translate('order_date')}} :  {{$currentDate}}
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
           
        <form action="{{route('admin.stock-update')}}" method="post">
            @csrf <table class="table table-striped">
                <thead>
                    <th>#</th>
                    <th>Product</th>
                    <th>Order Qty</th>
                    <th>Product Rate</th>

                    <th>Margin(%)</th>
                    <th>average rate</th>
                    <th>DB average rate</th>
                    <th>Total</th>
                </thead>
                <tbody>
                    
               
                        
                    <tr class="">
                        <td>1</td>
                        <td>rwer</td>
                        <td>werr</td>
                        <td>rewe
                        <input type="hidden" value="re" name="product_rate[]" class="product_rate"> </td>
                        <input type="hidden" value="fdsf" name="product_id[]" class="product_id"> </td>
                        <td class="" style="width: 180px;">
                            <input type="text"  class="form-control w-50 margin_price"> 
                        </td>
                        <td class="" style="width: 198px;" > 
                            <div class="" style="display: flex;align-items: center;">  <label for="">avg rate:</label>
                            <input type="text" value="sfsdfs" name="avg_price[]" class="form-control w-50" required> </div>  
                            <div class="" style="display: flex;align-items: center;">  <label for="">customer rate:</label>
                            <input type="text" value="gdfg" name="customer_price[]"  class="form-control w-50" required> </div>  
                            <div class="" style="display: flex;align-items: center;">  <label for="">store rate:</label>
                            <input type="text" value="rtret" name="store_price[]"  class="form-control w-50" required> </div>  
                        </td>
                        <?php ?>
                        <td class="" > 
                       
                            <div class="" style="display: flex;align-items: center;">  <label for="">avg rate:</label>
                            <h5 style="margin-left: 15px;">tert</h5> </div>  
                            <div class="" style="display: flex;align-items: center;">  <label for="">customer rate:</label>
                            <h5 style="margin-left: 15px;">gdfg</h5> </div>  
                            <div class="" style="display: flex;align-items: center;">  <label for="">store rate:</label>
                            <h5 style="margin-left: 15px;">rtret</h5> </div>  
                        </td>
                        <td>sfsd</td>
                    </tr>
                </tbody>
            </table>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary px-5">{{translate('save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('script_2')
<script>
$(document).on("input", ".margin_price", function () {
    var margin = $(this).val();
    var row = $(this).closest('tr');
    var product_rate = row.find('input[name="product_rate[]"]');

    var product_rate = parseFloat(product_rate.val());
console.log(product_rate);
    // Check if marketPrice is a valid number
    if (!isNaN(product_rate)) {
        // Calculate offer price
        var avgPrice = product_rate + (product_rate * margin / 100);

        // Find the offer price input within the same row
        var offerPriceInput = row.find('input[name="avg_price[]"]');

        // Set the offer price value
        offerPriceInput.val(avgPrice.toFixed(2));

        console.log(offerPriceInput.val());
    }
});
</script>
@endpush
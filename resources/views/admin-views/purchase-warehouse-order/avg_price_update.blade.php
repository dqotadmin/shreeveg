@extends('layouts.admin.app')
@section('title', translate('average_price_management'))
@push('css_or_js')
@endpush
@section('content')
<style>
    .table-striped .form-control{
        border: 0.0625rem solid #cdcdcd !important;
    }
</style>
<?php
    use App\Model\PurchaseWarehouseOrder;
    
    $role = auth('admin')->user()->admin_role_id; 
    $currentDate = date('d-M-y', strtotime('today')); 
    ?>
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <?php $currentDate = date('d-M-y', strtotime('today')); ?>
        <a  class="btn btn-info " href="{{route('admin.price-update',['type'=>'avg_price'])}}">{{translate('average_price_management')}} <strong class="text-dark">{{$currentDate}}</strong> </a>
        <a  class="btn btn-success " href="{{route('admin.price-update',['type'=>'store_price'])}}">{{translate('store_price_management')}} <strong class="text-dark">{{$currentDate}}</strong> </a>
        <h1 class="page-header-title">
            <span class="page-header-icon">
            <img src="{{asset('public/assets/admin/img/category.png')}}" class="w--24" alt="">
            </span>
            <span>
            {{translate('average_price_management')}}
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
                <i class="tio-date-range"></i> {{translate('date')}} :<strong>  {{$currentDate}}</strong>
                </span>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?php
                $i=1;
                $role = auth('admin')->user(); 
                     $warehouse_id = auth('admin')->user()->warehouse_id; 
                    $today = today();
                     //$order_id = \App\Model\PurchaseWarehouseOrder::where('warehouse_id',$role->warehouse_id)->pluck('id')->toArray();
                     $order_id = \App\Model\PurchaseWarehouseOrder::where('warehouse_id',$role->warehouse_id)->whereDate('created_at', $today)->pluck('id')->toArray(); //11,12
                     //  $order_id_details = \App\Model\PurchaseWarehouseOrderDetail::whereIn('purchase_warehouse_order_id',$order_id)->get();//11, 12 all data
                      $WarehouseProducts = \App\Model\WarehouseProduct::where('warehouse_id',$warehouse_id)->with('productDetail')->get();//11, 12 all data
                ?>
            <form action="{{route('admin.price-update')}}" method="post">
                @csrf 
                <table class="table table-striped">
                    <thead>
                        <th>#</th>
                        <th>Category</th>
                        <th>Product</th>
                        <th>Broker</th>
                        <!-- <th>Rate</th> -->
                        <th>average rate</th>
                        <th>
                            <div class=""  style="margin-left: -18px;">
                                <small for=""> margin(%):</small>
                                <select name="" class="form-control" id="margin" style="width: 70px;padding: 3px;">
                                    @for($i=0; $i<=100; $i++)
                                    <option value="{{$i}}" >{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </th>
                        <th>New rate</th>
                        <th>Old rate</th>
                        <th>Last Updated Date</th>
                    </thead>
                    <tbody>
                        @foreach($WarehouseProducts as $key =>$products)            
                        <tr class="child-row">
                            <td><?php echo $key+1; ?></td>
                            <td>{{$products->productDetail->category->name}}  </td>
                            <td>{{$products->productDetail->name}}  <br>({{$products->productDetail->product_code}})
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
                                <input type="hidden" value="{{$avgPrice}}" name="" class="product_rate"> 
                            </td>
                            <td class="" style="width: 180px;">
                                @if($price)  
                                <input type="number"  class="form-control w-50 marginRow" value="" name="margin[]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');">  
                                @endif
                            </td>
                            <td class="" style="width: 198px;" > 
                                {{-- {{$price? $price:$products->avg_price}} --}}
                                <input type="text" value="" name="avg_price[]" class="form-control updatePrice w-50"> 
                            </td>
                            <td class="" >
                                <h5 style="margin-left: 15px;">{{$products->avg_price}}</h5>
                            </td>
                            <td class="" >
                                @if($products->avg_price_updated_date)
                                <?php  $dateColor = 'red';
                                    if (date('Y-m-d', strtotime($products->avg_price_updated_date)) == date('Y-m-d')) {
                                        $dateColor = 'green';
                                    } ?>
                                <h5 style="margin-left: 15px;color:{{$dateColor}}">{{ date('d-M-Y h:i A', strtotime($products->avg_price_updated_date)) }}</h5>
                                @endif
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
            </form>
        </div>
    </div>
</div>
@endsection
@push('script_2')
<script>
    $('#margin').on('change',function () {
        var selectedMargin = $(this).val();
        $('.child-row').each(function () {
            
            var storeMarginElement = $(this).find('.marginRow');
            storeMarginElement.val(selectedMargin);
            var avgPrice = $(this).find('.product_rate').val();
            if(avgPrice){
                var updatedPrice = parseFloat(avgPrice) + (parseFloat(avgPrice) * parseFloat(selectedMargin) / 100);
    
                updatedPrice = updatedPrice.toFixed(2);
                $(this).find('.updatePrice').val(updatedPrice);
            }
    
        });
    });
    $(document).on("input", ".marginRow", function () {
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
    
</script>
@endpush
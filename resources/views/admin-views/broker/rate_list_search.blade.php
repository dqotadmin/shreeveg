@extends('layouts.admin.app')

@section('title', translate('brokers rate list'))

@push('css_or_js')
<style>
    .error{
        color:red;
    }
</style>
@endpush

@section('content')
    <?php  
    $assign_categories = App\Model\WarehouseCategory::where('warehouse_id',auth('admin')->user()->warehouse_id)->pluck('category_id')->toArray();
    $wh_products = \App\Model\Product::whereIn('category_id',$assign_categories)->pluck('id')->toArray();
    $i=1; 
    ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/banner.png')}}" class="w--20" alt="">
                </span>
                <span>
                    {{translate('brokers rate list')}}
                </span>
            </h1>
        </div>
        
        <form action="{{route('admin.broker-rate-list.wh_receiver_product_rate')}}" method="GET">
        <div class="row mb-5">
            <div class="col-md-4">
                    <select name="product_id" id="" class="form-control">
                        <option value="" disabled selected> please select product  </option>
                        @foreach($rows as $row)
                            @foreach($row->rateListDetail as $key => $value)
                                <?php   if (in_array($value->product_id, $wh_products)) {  ?>
                                    <option value="{{ @$value->productDetail->id }}" {{@$value->productDetail->id  == $productID? 'selected': ''}}>{{ @$value->productDetail->name }}</option>
                        
                                <?php } ?>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary px-5 " id="save" >{{translate('search')}}</button>
                </div>
            </div>
        </form>

            <div class="row g-3">
            @foreach($rows as $row)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-uppercase mb-3">{{ $row->brokerDetail->f_name .' '.$row->brokerDetail->l_name}}</h5>
                       
                        <form action="{{route('admin.broker-rate-list.wh_receiver_post_order')}}"
                              method="post">
                            @csrf
                            
                            <input type="hidden" name="broker_rate_list_id" value="{{$row->id}}">
                                <div class="form-group">
                                    <label class="form-label text--title">
                                        <strong>{{translate('name')}}</strong>:{{$row->title}}
                                    </label>
                                    <label class="form-label text--title" style="float: right">
                                        <strong>{{translate('date')}}</strong>:{{ date('d-m-Y H:i A', strtotime($row->date_time))}}
                                    </label>
                                </div>

                                <div class="d-flex flex-wrap mb-4">
                                    <table class="table table-striped">
                                        <thead>
                                            <th>#</th>
                                         <th>Product</th>
                                         <th>Available Qty</th>
                                         <th>Unit</th>
                                         <th>Rate</th>
                                         <th>Order Qty</th>
                                        </thead>
                                        <tbody>
                                       
                                            @foreach($row->rateListDetail as $key => $value)
    
                                            <?php  // if (in_array($value->product_id, $wh_products)) { 
                                                if ($productID== $value->product_id) { ?>
      
                                               
                                                <tr class="">
                                                    <input type="hidden" name="products[]" value="{{($value->product_id)}}">
                                                <td>{{$i++; }}</td>
                                                <td>{{ @$value->productDetail->name }}</td>
                                                <td id="available_quantity">{{ @$value->available_qty }}</td>
                                                <td>{{@$value->unit }}</td>
                                                <td>{{ @    $value->rate }}</td>
                                                <td><input type="text" name="order_qty[]" class="form-control" id="order_qty" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"></td>
                                                </tr>
                                          <?php }
                                            ?>
                                          @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary px-5 " id="save" >{{translate('save')}}</button>
                                </div>
                           
                                
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
            
    </div>

@endsection

@push('script_2')
<script>
 
  $(document).ready(function () {
        // Add a class to the order_qty input for easier selection
        $('input[name="order_qty[]"]').on('input', function () {
            // Get the current row
            var row = $(this).closest('tr');
            console.log('row'+row);
            // Get the values of available_qty and order_qty
            var availableQty = parseFloat(row.find('#available_quantity').text());
            var orderQty = parseFloat($(this).val());
            console.log('availableQty'+availableQty);
            console.log('orderQty'+orderQty);

            // Check if available_qty is less than order_qty
            if (orderQty > availableQty) {
                // Show an alert or any other notification
                alert('Order quantity cannot be greater than available quantity!');
                $(this).val('');
                
                // You can also highlight the row or take any other action here
                row.addClass('error'); // Add a class to highlight the row
            } else {
                // If order_qty is valid, remove any error class
                row.removeClass('error');
            }
        });
        
    });

  </script>

@endpush

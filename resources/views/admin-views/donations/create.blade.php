@extends('layouts.admin.app')
@section('title', translate('donate products'))
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
            {{translate('donate products')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.store.donations.store')}}" method="post"
                enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    
                </div>

                <h3 class="my-4"> Product Details </h3>
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <h5>Category</h5>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <h5>Product</h5>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <h5>Available Stock</h5>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <h5>Donate Qty</h5>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    
                    <div class="col-md-8">
                        @foreach($categories as $category)
                        <?php 
                            $productsIds = \App\Model\Product::where('category_id',$category->category_id)->pluck('id')->toArray();
                            $products = \App\Model\WarehouseProduct::where('warehouse_id',$wahrehouseId)->whereIn('product_id',$productsIds)->get();
                             ?>  
                        <div class="row g-3 mt-5 chkError">
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                               @if((count($products) >0))
                                    <h5>{{@$category->getCategory->name }}</h5>
                                    @endif
                                </div>
                            </div>
                         
                            @if(count($products) >0)
                            @foreach($products as $key =>$product)
                            <?php
                            if($store_id){ //dd($store_id,$product->product_id);
                                $stockRow = \App\Model\StoreProduct::where('store_id',$store_id)->where('product_id',$product->product_id)->first();
                                $stock = $stockRow?$stockRow->total_stock:0;
                            }else {
                                $stock = $product->total_stock;
                            }
                               ?>
                                @if($key > 0)
                                    <div class="col-md-3"></div>
                                @endif
                            <input type="hidden" name="warehouse_id" value="{{$wahrehouseId}}">
                            <input type="hidden" name="store_id" value="{{$store_id}}">
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <input type="hidden" name="product_id[]" value="{{$product->product_id}}">
                                        {{$product->productDetail->name}}<br>
                                        ({{$product->productDetail->product_code}})
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                            {{$stock}} {{$product->productDetail->unit->title}}<br>
                                            
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <input type="hidden" id="available_stock" value="{{$stock}}">
                                        <input type="text" name="qty[]" id="qty" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                            placeholder="Qty" >
                                    </div>
                                </div>
                                
                            @endforeach
                            @endif

                        </div>

                        @endforeach
                    </div>
                    
                </div>
                <div class="col-12">
                    <div class="btn--container justify-content-end">
                        <a href="{{route('admin.store.donations.index')}}" type="reset" class="btn btn--reset">
                        {{translate('Back')}}</a>
                        <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('script_2')
<script>
       
       $(document).ready(function () {
        // Add a class to the order_qty input for easier selection
        $('input[name="qty[]"]').on('input', function () {
            // Get the current row
            var row = $(this).closest('div');
          
            // Get the values of available_qty and order_qty
            var availableQty = parseFloat(row.find('#available_stock').val());
            var orderQty = parseFloat($(this).val());
           

            // Check if available_qty is less than order_qty
            if (orderQty > availableQty) {
                // Show an alert or any other notification
                alert('Order quantity cannot be greater than available quantity!');
                $(this).val('');
                
                // You can also highlight the row or take any other action here
                $(this).parents('.chkError').addClass('error'); // Add a class to highlight the row
            } else {
                // If order_qty is valid, remove any error class
                $(this).removeClass('error');
            }
        });
        
    });

  </script>
  </script>
@endpush
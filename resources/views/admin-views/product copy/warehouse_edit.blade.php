@extends('layouts.admin.app')
@section('title', translate('update_product_price'))
@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="{{asset('public/assets/admin/css/tags-input.min.css')}}" rel="stylesheet">
@endpush
@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between">
            <h1 class="page-header-title text-break">
                <span class="page-header-icon">
                <img src="{{asset('public/assets/admin/img/product.png')}}" alt="">
                </span>
                <span>Product: {{Str::limit($product['name'], 30)}}</span>
            </h1>
        </div>
    </div>
    @php($data = Helpers::get_business_settings('language'))
    @php($default_lang = Helpers::get_default_language())
    <!-- End Page Header -->
    <div class="row review--information-wrapper g-2 mb-2">
        <form action="javascript:" method="post" id="product_form" enctype="multipart/form-data" class="row g-2">
            @csrf
            <div class="col-lg-6">
                <div class="card h-100">
                    <!-- Body -->
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                                <div class="d-flex flex-wrap  food--media  ">
                                    @if (!empty(json_decode($product['single_image'],true)))
                                    @foreach(json_decode($product['single_image'],true) as $img)
                                    <img class="avatar avatar-xxl avatar-4by3 mr-4 mt-4"
                                        src="{{asset('storage/app/public/product/single')}}/{{$img}}">
                                    <a href="{{route('admin.product.remove-single-image',[$product['id'],$img])}}"
                                        class="">
                                    </a>
                                    @endforeach
                                    @else
                                    <img class="avatar avatar-xxl avatar-4by3 mr-4"
                                        src="{{asset('public/assets/admin/img/160x160/img2.jpg')}}">
                                    @endif
                                </div>
                                <div class="category_content">
                                    <div class="mt-5">
                                        <h5 class="card-title">
                                            <span class="card-header-icon">
                                            <i class="tio-category"></i>
                                            </span>
                                            <span>
                                            Category
                                            </span>
                                        </h5>
                                        <div>
                                            <div class="mt-3">
                                                <strong class="text--title">{{translate('category')}} :</strong>
                                                <span>{{@$product->category->name}}</span>
                                            </div>
                                            <div class="mt-2">
                                                <strong class="text--title">{{translate('Product_Code')}} :</strong>
                                                <span>{{$product['product_code']}}</span>
                                            </div>
                                            <div class="mt-2">
                                                <strong class="text--title">{{translate('Unit')}} :</strong>
                                                <span>{{@$product->unit->title}}
                                                ({{@$product->unit->description}})</span>
                                            </div>
                                            <div class="mt-2">
                                                <strong class="text--title">{{translate('Maximum_Order_Quantity')}}
                                                :</strong>
                                                <span>{{$product['maximum_order_quantity']}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-6 mx-auto">
                                <div class="d-block">
                                    <div class="rating--review">
                                        <!-- Static -->
                                        @if($data && array_key_exists('code', $data[0]))
                                        <ul class="nav nav-tabs mb-4">
                                            @foreach($data as $lang)
                                            <li class="nav-item">
                                                <a class="nav-link lang_link {{$lang['code'] == 'en'? 'active':''}}"
                                                    href="#"
                                                    id="{{$lang['code']}}-link">{{Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')'}}</a>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @foreach($data as $lang)
                                        <?php
                                            if(count($product['translations'])){
                                                $translate = [];
                                                foreach($product['translations'] as $t)
                                                {
                                                    if($t->locale == $lang['code'] && $t->key=="name"){
                                                        $translate[$lang['code']]['name'] = $t->value;
                                                    }
                                                    if($t->locale == $lang['code'] && $t->key=="description"){
                                                        $translate[$lang['code']]['description'] = $t->value;
                                                    }
                                            
                                                            }
                                                        }
                                            
                                            ?>
                                        <div class="{{$lang['code'] != 'en'? 'd-none':''}} lang_form"
                                            id="{{$lang['code']}}-form">
                                            <div class="form-group">
                                                <label class="input-label"
                                                    for="{{$lang['code']}}_name">{{translate('name')}}
                                                ({{strtoupper($lang['code'])}})</label>
                                                {{$translate[$lang['code']]['name']??$product['name']}}
                                            </div>
                                            <input type="hidden" value="{{$lang['code']}}">
                                            <div class="form-group mb-0">
                                                <label class="input-label"
                                                    for="{{$lang['code']}}_description">{{translate('short')}}
                                                {{translate('description')}} ({{strtoupper($lang['code'])}})</label>
                                                {{$translate[$lang['code']]['description']??$product['description']}}
                                            </div>
                                        </div>
                                        @endforeach
                                        @else
                                        <div id="english-form">
                                            <div class="form-group">
                                                <label class="input-label"
                                                    for="exampleFormControlInput1">{{translate('name')}} (EN)</label>
                                                {{$product['name']}}
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="input-label"
                                                    for="exampleFormControlInput1">{{translate('short')}}
                                                {{translate('description')}} (EN)</label>
                                                {{ $product['description'] }}
                                            </div>
                                        </div>
                                        @endif
                                        <!-- Static -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="mb-3">{{translate('product')}} {{translate('image')}} <small class="text-danger">* (
                            {{translate('ratio')}} 1:1 )</small>
                        </h5>
                        <div class="product--coba">
                            <div class="row g-2" id="">
                                @if (!empty(json_decode($product['image'],true)))
                                @foreach(json_decode($product['image'],true) as $img)
                                <div class="spartan_item_wrapper position-relative">
                                    <img class="img-150 border rounded p-3"
                                        src="{{asset('storage/app/public/product')}}/{{$img}}">
                                    <a href="{{route('admin.product.remove-image',[$product['id'],$img])}}"
                                        class="spartan__close"> </a>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                            <i class="tio-dollar"></i>
                            </span>
                            <span>
                            {{translate('price_information')}}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="p-2">
                            <div class="row g-3">
                                <div class="col-sm-2">
                                    <div class="form-group mb-0">
                                        <label class="input-label" style="font-weight:bold;"
                                            for="exampleFormControlInput1">{{translate('average_price')}}</label>
                                        <input type="number" min="0" max="100000" value="{{@$warehouse_products->avg_price}}" name="avg_price" step="any"
                                            style="font-weight:bold;" id="discount" class="form-control"
                                            placeholder="{{ translate('5%') }}" readonly required>
                                    </div>
                                </div>
                                <input type="hidden" name="default_unit" value="{{@$product->unit_id}}" id="">
                                <div class="col-sm-2 mt-2">
                                    <div class="form-group mt-5"> /{{@$product->unit->title}}
                                        ({{@$product->unit->description}})
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{translate('store_price')}}</label>
                                        <input type="number" min="0" max="100000"
                                            value="{{@$warehouse_products->store_price}}" name="store_price" step="any"
                                            id="discount" class="form-control"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                            placeholder="{{ translate('store price') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-2 mt-2">
                                    <div class="form-group mt-5"> /{{@$product->unit->title}}
                                        ({{@$product->unit->description}})
                                    </div>
                                </div>
                                <!-- <div class="col-sm-2">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{translate('customer_price')}}</label>
                                        <input type="number" min="0" max="100000"
                                            value="{{@$warehouse_products->customer_price}}" name="customer_price"
                                            step="any" id="discount" class="form-control customer_price"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                            placeholder="{{ translate(' customer price') }}" required>
                                    </div>
                                </div> -->
                                <div class="col-sm-2">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{translate('market_price')}}</label>
                                        <input type="number" min="0" max="100000"
                                            value="{{@$warehouse_products->market_price}}" name="market_price"
                                            step="any" id="discount" class="form-control market_price"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                            placeholder="{{ translate(' customer price') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-2 mt-2">
                                    <div class="form-group mt-5">/{{@$product->unit->title}}
                                        ({{@$product->unit->description}})
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page Header -->
            <!-- End Page Header -->
            @php($data = Helpers::get_business_settings('language'))
            @php($default_lang = Helpers::get_default_language())
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                            <i class="tio-dollar"></i>
                            </span>
                            <span>
                            {{translate('fill_all_prices')}} <div class="error-container" style="color: red"></div>
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php    $product_details_array = @json_decode($warehouse_products->product_details, true); 
                            $i=0;
                            ?>
                        <table class="table table-bordered" id="box-delivery-pair">
                            <tr>
                                <th>{{translate('Quantity')}} /{{@$product->unit->title}}</th>
                                <th> {{translate('market_price')}} <span  class="price">({{@$warehouse_products->market_price}}</span>/{{@$product->unit->title}}) </th>
                                <th> {{translate('margin')}}({{translate('%')}})</th>
                                <th> {{translate('shreeveg_price')}}</th>
                                <th> {{translate('avg_price')}}(1 {{@$product->unit->title}})</th>
                                <th>{{translate('Approx Piece/Weight')}}</th>
                                <th>{{translate('Short Title')}}</th>
                                <th class="d-none"> <button type="button" id="add-delivery-pair"  class="btn btn-outline-success">Add  More</button> 
                                </th>
                            </tr>
                            @if($product_details_array)
                            @foreach($product_details_array as $key => $warehouse)
                            <tr class="row-delivery-pair">
                                <td> <input type="number" value="{{@$product_details_array[$i]['quantity']}}" name="quantity[]"   class="form-control input-delivery-pair quantity" placeholder="{{ translate('Ex : 1') }}"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" > </td>
                                <td>
                                    <input name="market_price[]" readonly   value="{{@$product_details_array[$i]['market_price']}}" class="form-control input-delivery-pair market_price"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"   placeholder="{{ translate('Ex : 10') }}" >
                                    
                                </td>
                                <td>
                                    <input name="margin[]" function value="{{@$product_details_array[$i]['margin']}}" class="form-control input-delivery-pai discount" placeholder="{{ translate('Ex : 1%') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" >
                                </td>
                                <td>
                                    <input name="offer_price[]"   value="{{@$product_details_array[$i]['offer_price']}}"  class="form-control input-delivery-pair" id="offer_price" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"   >
                                </td>
                                <td>
                                    <input name="per_unit_price[]"   value="{{@$product_details_array[$i]['per_unit_price']}}"  class="form-control input-delivery-pair" id="avg_price" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" >
                                </td>
                                <td> <input name="approx_piece[]"   class="form-control input-delivery-pair" value="{{@$product_details_array[$i]['approx_piece']}}"
                                    placeholder="{{ translate('Ex : 1 pieces') }}" >
                                </td>
                                <td>  <input type="text"   name="title[]"     class="form-control input-delivery-pair " value="{{@$product_details_array[$i]['title']}}"
                                    placeholder="{{ translate('Ex : This product is pure organic') }}" >
                                </td>
                                <td class="d-none"><button type="button"  class="remove-delivery-pair btn btn-outline-danger">Remove</button>  </td>
                            </tr>
                            <?php $i++ ?>
                            @endforeach
                            @else
                            @for($line = 1; $line <= 3; $line++)
                            <tr class="row-delivery-pair">
                                <td> <input type="number"  name="quantity[]"   class="form-control input-delivery-pair quantity" placeholder="{{ translate('Ex : 1') }}"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" > </td>
                                <td>
                                    <input name="market_price[]"   value="{{@$product_details_array[$i]['market_price']}}" class="form-control input-delivery-pair market_price"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"   placeholder="{{ translate('Ex : 10') }}" >
                                </td>
                                <td>
                                    <input name="margin[]" function    value="{{@$product_details_array[$i]['margin']}}" class="form-control input-delivery-pai discount" placeholder="{{ translate('Ex : 1%') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" >
                                </td>
                                <td> 
                                    <input name="offer_price[]"   value="{{@$product_details_array[$i]['offer_price']}}"  class="form-control input-delivery-pair" id="offer_price" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                        >
                                </td>
                                <td>
                                    <input name="per_unit_price[]"   value="{{@$product_details_array[$i]['per_unit_price']}}"  class="form-control input-delivery-pair" id="avg_price" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" >
                                </td>
                                <td> 
                                    <input name="approx_piece[]"   class="form-control input-delivery-pair" value="{{@$product_details_array[$i]['approx_piece']}}"
                                        placeholder="{{ translate('Ex : 1 pieces') }}" >
                                </td>
                                <td> <input type="text"   name="title[]"     class="form-control input-delivery-pair "     placeholder="{{ translate('Ex : This product is pure organic') }}" >
                                </td>
                                <td class="d-none"><button type="button"  class="remove-delivery-pair btn btn-outline-danger d-none">Remove</button>  </td>
                            </tr>
                            @endfor
                            @endif
                        </table>
                    </div>
                </div>
            </div>
    </div>
    <div class="col-12">
    <div class="btn--container justify-content-end">
    <a type="reset" href="{{route('admin.product.list')}}" class="btn btn--reset">{{translate('back')}}</a>
    <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
    </div>
    </div>
    </form>
</div>
@endsection
@push('script')
@endpush
@push('script_2')
<script src="{{asset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>
<script>
    // Add more functionality
    $('#add-delivery-pair').on('click', function() {
            var newPair = $('.row-delivery-pair:first').clone();
            newPair.find('input').val('');
            newPair.appendTo('#box-delivery-pair');
    });
    
    // Remove functionality
    $(document).on('click', '.remove-delivery-pair', function() {
        let remainingButtons = $('.remove-delivery-pair').length;
        
        if(remainingButtons ==1){
            $('#add-delivery-pair').trigger('click');
        }
        $(this).closest('.row-delivery-pair').remove();
        
    });
    $(document).on('blur', '.market_price', function () { 
        let oldCustPrice = "{{@$warehouse_products->market_price}}";
        var errorContainer = $('.error-container');
        errorContainer.text('');
        let newCustPrice = $('.market_price').val();
        if(oldCustPrice > 0 && parseFloat(oldCustPrice) != newCustPrice){
            alert('Please re-verify your offer prices!');

            var errorMessage = '<div class="">Invalid market & offer prices</div>';
            errorContainer.html(errorMessage);
           
        }
       
    });
    $(document).on('input','.quantity, .market_price', function(){
        var quantity = $(this).val();
        var market_price = $('.market_price').val();
        var marketPrice = quantity * market_price;
        $('.price').text(market_price);
       
        if((quantity)){
            var row = $(this).closest('tr');
            // Find the discount input within the same row
            var marketPriceInput = row.find('input[name="discount[]"]'); //assign value empty
            var offerPriceInput = row.find('input[name="offer_price[]"]');
            var avgrPriceInput = row.find('input[name="per_unit_price[]"]');
            var quantity = row.find('input[name="quantity[]"]');
            var marketPriceInput = row.find('input[name="market_price[]"]');
         
            marketPriceInput.val('');
            offerPriceInput.val('');
            avgrPriceInput.val('');
            marketPriceInput.val(marketPrice.toFixed(2));
        }else{
            var row = $(this).closest('tr');
            // Find the discount input within the same row
            var marketPriceInput = row.find('input[name="discount[]"]'); //assign value empty
            var offerPriceInput = row.find('input[name="offer_price[]"]');
            var offerPriceInput = row.find('input[name="per_unit_price[]"]');
            var quantity = row.find('input[name="quantity[]"]');
            var marketPriceInput = row.find('input[name="market_price[]"]');
            console.log(quantity);
            marketPriceInput.val('');
            avgrPriceInput.val('');
            offerPriceInput.val('');
            marketPriceInput.val(marketPrice.toFixed(2));
        }
    });
    //function for fill discount then get offer price
    $(document).on("input", ".discount", function () {
        var discount = $(this).val();
        var row = $(this).closest('tr');
    
        // Find the market price input within the same row
        var marketPriceInput = row.find('input[name="market_price[]"]');
        var quantityInput = row.find('input[name="quantity[]"]');
        quantity =  quantityInput.val();
        // Get the market price value
        var marketPrice = parseFloat(marketPriceInput.val());
        
        // Check if marketPrice is a valid number
        if (!isNaN(marketPrice)) {
            // Calculate offer price
            var offerPrice = marketPrice + (marketPrice * discount / 100);
            var avgPrice = offerPrice/quantity;
    
            // Find the offer price input within the same row
            var offerPriceInput = row.find('input[name="offer_price[]"]');
            var avgrPriceInput = row.find('input[name="per_unit_price[]"]');
    
            // Set the offer price value
            offerPriceInput.val(offerPrice.toFixed(2));
            avgrPriceInput.val(avgPrice.toFixed(2));
    
            
        }
    });
        
</script>
<script>
    function getRequest(route, id) {
        $.get({
            url: route,
            dataType: 'json',
            success: function(data) {
                $('#' + id).empty().append(data.options);
            },
        });
    }
</script>
<script> 
    function combination_update() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        $.ajax({
            type: "POST",
            url: '{{route('admin.product.variant-combination')}}',
            data: $('#product_form').serialize(),
            success: function(data) {
                $('#variant_combination').html(data.view);
                if (data.length > 1) {
                    $('#quantity').hide();
                } else {
                    $('#quantity').show();
                }
            }
        });
    }
</script>
{{-- <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script> --}}
<script>
    $('#product_form').on('submit', function() {
    
    
    
        var formData = new FormData(this);
    
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post({
            url: '{{route('admin.product.warehouse-rate-insertupdate',[$product['id']])}}',
            // data: $('#product_form').serialize(),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data.errors) {
                    for (var i = 0; i < data.errors.length; i++) {
                        toastr.error(data.errors[i].message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                } else {
                    toastr.success('{{translate('product updated successfully!')}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    setTimeout(function() {
                        location.href = '{{route('admin.product.list')}}';
                    }, 1000);
                }
            }
        });
    });
</script>
<script>
    function update_qty() {
        var total_qty = 0;
        var qty_elements = $('input[name^="stock_"]');
        for (var i = 0; i < qty_elements.length; i++) {
            total_qty += parseInt(qty_elements.eq(i).val());
        }
        if (qty_elements.length > 0) {
            $('input[name="total_stock"]').attr("readonly", true);
            $('input[name="total_stock"]').val(total_qty);
            console.log(total_qty)
        } else {
            $('input[name="total_stock"]').attr("readonly", false);
        }
    }
</script>
@endpush
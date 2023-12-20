@extends('layouts.admin.app')

@section('title', translate('Update Price product'))

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
                                {{translate('ratio')}} 1:1 )</small></h5>
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
                                        <input type="number" min="0" max="100000" value="20" name="avg_price" step="any"
                                            style="font-weight:bold;" id="discount" class="form-control"
                                            placeholder="{{ translate('5%') }}" readonly required>

                                    </div>
                                </div>
                                <input type="hidden" name="default_unit" value="{{@$product->unit_id}}" id="">
                                <div class="col-sm-2 mt-2">
                                    <div class="form-group mt-5"> /{{@$product->unit->title}}
                                        ({{@$product->unit->description}})</div>
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
                                        ({{@$product->unit->description}})</div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{translate('customer_price')}}</label>
                                        <input type="number" min="0" max="100000"
                                            value="{{@$warehouse_products->customer_price}}" name="customer_price"
                                            step="any" id="discount" class="form-control"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                            placeholder="{{ translate(' customer price') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-2 mt-2">
                                    <div class="form-group mt-5"> /{{@$product->unit->title}}
                                        ({{@$product->unit->description}})</div>
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
                                {{translate('fill_all_prices')}}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php    $product_details_array = @json_decode($warehouse_products->product_details, true); 
                        $i=0;
                       ?>
                        <table class="table table-bordered" id="box-delivery-pair">
                                        <tr>
                                            <th>{{translate('Quantity')}}</th>
                                            <th> {{translate('default_price')}}/{{translate('market_price')}}</th>
                                            <th> {{translate('discount')}}({{translate('%')}})</th>
                                            <th> {{translate('default_price')}}({{translate('offer_price')}})</th>
                                            <th>{{translate('Approx Piece/Weight')}}</th>
                                            <th>{{translate('Short Title')}}</th>
                                            <th> <button type="button" id="add-delivery-pair"
                                                    class="remove-delivery-pair btn btn-outline-success">Add
                                                    More</button> </th>

                                        </tr>
                                        @if( $product_details_array)
                                        @foreach($product_details_array as $key => $warehouse)
                                            <tr class="row-delivery-pair">
                                                <td> <input type="number" min="0" max="10000000000" step="any"  value="{{@$product_details_array[$i]['quantity']}}" name="quantity[]"   class="form-control input-delivery-pair quantity" placeholder="{{ translate('Ex : 1') }}"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" required> </td> 
                                    
                                                <td>
                                                    <input name="market_price[]" min="0" max="100000000" step="any" value="{{@$product_details_array[$i]['market_price']}}" class="form-control input-delivery-pair market_price"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"   placeholder="{{ translate('Ex : 10') }}" required>
                                                </td>
                                                <td>
                                                <input name="discount[]" function min="0" max="100000000" step="any"  value="{{@$product_details_array[$i]['discount']}}" class="form-control input-delivery-pai discount" placeholder="{{ translate('Ex : 1%') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" required>
                                                </td>
                                                <td>
                                                <input name="offer_price[]" min="0" max="100000000" step="any" value="{{@$product_details_array[$i]['offer_price']}}"  class="form-control input-delivery-pair" id="offer_price" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                                           required>
                                                </td>
                                                <td> <input name="approx_piece[]" min="0" max="100000000" step="any" class="form-control input-delivery-pair" value="{{@$product_details_array[$i]['approx_piece']}}"
                                                        placeholder="{{ translate('Ex : 1 pieces') }}" required>
                                                </td>
                                                <td>  <input type="text" min="0" max="100000000" step="any" name="title[]"     class="form-control input-delivery-pair " value="{{@$product_details_array[$i]['title']}}"
                                                        placeholder="{{ translate('Ex : This product is pure organic') }}" required>
                                                </td> 
                                                <td><button type="button"  class="remove-delivery-pair btn btn-outline-danger">Remove</button>  </td>
                                            </tr><?php $i++ ?>
                                       @endforeach
                                       @else
                                       <tr class="row-delivery-pair">
                                                <td> <input type="number" min="0" max="10000000000" step="any"   name="quantity[]"   class="form-control input-delivery-pair" placeholder="{{ translate('Ex : 1') }}"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" required> </td> 
                                    
                                                <td>
                                                <input name="market_price[]" min="0" max="100000000" step="any" value="{{@$product_details_array[$i]['market_price']}}" class="form-control input-delivery-pair market_price"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"   placeholder="{{ translate('Ex : 10') }}" required>

                                                </td>
                                                <td>
                                                <input name="discount[]" function min="0" max="100000000" step="any"  value="{{@$product_details_array[$i]['discount']}}" class="form-control input-delivery-pai discount" placeholder="{{ translate('Ex : 1%') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" required>

                                                </td>
                                                <td> 
                                                <input name="offer_price[]" min="0" max="100000000" step="any" value="{{@$product_details_array[$i]['offer_price']}}"  class="form-control input-delivery-pair" id="offer_price" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                                           required>
                                                           </td>
                                                <td> 
                                                <input name="approx_piece[]" min="0" max="100000000" step="any" class="form-control input-delivery-pair" value="{{@$product_details_array[$i]['approx_piece']}}"
                                                        placeholder="{{ translate('Ex : 1 pieces') }}" required>
                                                </td>
                                                <td> <input type="text" min="0" max="100000000" step="any" name="title[]"     class="form-control input-delivery-pair "     placeholder="{{ translate('Ex : This product is pure organic') }}" required>
                                                </td> 
                                                <td><button type="button"  class="remove-delivery-pair btn btn-outline-danger">Remove</button>  </td>
                                            </tr> 
                                       @endif
                                    </table>

<!-- 
                        <div class="row g-3">
                            <div class="col-sm-2">
                                <div class="form-group mb-0">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{translate('Quantity')}}</label>
                                    <input type="number" min="0" max="10000000000" step="any"
                                        value="{{@$product_details_array[0]['quantity']}}" name="quantity[]"
                                        class="form-control" placeholder="{{ translate('Ex : 1') }}"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" required>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('Unit')}}</label>
                                <select name="unit_id[]" id="" class="form-control js-select2-custom required-select">
                                    <option disabled selected>Please Select Unit</option>

                                    @foreach($units as $unit)
                                    <option value="{{$unit['id']}}" style="text-transform: capitalize;"
                                        {{$unit['id'] == @$product_details_array[0]['unit_id'] ? 'selected' : '';}}>
                                        {{$unit->title}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{translate('default_price')}}/{{translate('offer_price')}}</label>
                                <input name="offer_price[]" min="0" max="100000000" step="any"
                                    value="{{@$product_details_array[0]['offer_price']}}" class="form-control"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                    placeholder="{{ translate('Ex : 10') }}" required>
                            </div>

                            <div class="col-sm-2">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{translate('Approx Piece/Weight')}}</label>
                                <input name="approx_piece[]" min="0" max="100000000" step="any" class="form-control"
                                    value="{{@$product_details_array[0]['approx_piece']}}"
                                    placeholder="{{ translate('Ex : 1 pieces') }}" required>
                            </div>
                            <div class="col-sm-2">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{translate('Short Title')}}</label>
                                <input type="text" min="0" max="100000000" step="any" name="title[]"
                                    class="form-control" value="{{@$product_details_array[0]['title']}}"
                                    placeholder="{{ translate('Ex : This product is pure organic') }}" required>
                            </div>

                        </div> -->
                        <!-- <h4 class="mt-3 mb-3"> {{translate('Second_product_rate')}}</h4> -->
                        <!-- <div class="row g-3">
                            <div class="col-sm-2">
                                <div class="form-group mb-0">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{translate('Quantity')}}</label>
                                    <input type="number" min="0" max="10000000000" step="any"
                                        value="{{@$product_details_array[1]['quantity']}}" name="quantity[]"
                                        class="form-control" placeholder="{{ translate('Ex : 2') }}" required>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('Unit')}}</label>
                                <select name="unit_id[]" id="" class="form-control js-select2-custom required-select">
                                    <option value="" disabled selected>Please Select Unit</option>

                                    @foreach($units as $unit)
                                    <option value="{{$unit->id}}" style="text-transform: capitalize;"
                                        {{$unit['id'] == @$product_details_array[1]['unit_id'] ? 'selected' : '';}}>
                                        {{$unit->title}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-sm-2">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{translate('default_price')}}/{{translate('offer_price')}}</label>
                                <input name="offer_price[]" min="0" max="100000000" step="any"
                                    value="{{@$product_details_array[1]['offer_price']}}" class="form-control"
                                    placeholder="{{ translate('Ex : 20') }}" required>
                            </div>

                            <div class="col-sm-2">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{translate('Approx Piece/Weight')}}</label>
                                <input name="approx_piece[]" min="0" max="100000000" step="any" class="form-control"
                                    value="{{@$product_details_array[1]['approx_piece']}}"
                                    placeholder="{{ translate('Ex : 2 pieces') }}" required>
                            </div>
                            <div class="col-sm-2">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{translate('Short Title')}}</label>
                                <input type="text" min="0" max="100000000" step="any" name="title[]"
                                    value="{{@$product_details_array[1]['title']}}" class="form-control"
                                    placeholder="{{ translate('Ex : This product is pure organic') }}" required>
                            </div>

                        </div> -->
                        <!-- <h4 class="mt-3 mb-3">{{translate('third_product_rate')}}</h4> -->
                        <!-- <div class="row g-3">
                            <div class="col-sm-2">
                                <div class="form-group mb-0">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{translate('Quantity')}}</label>
                                    <input type="number" min="0" max="10000000000" step="any"
                                        value="{{@$product_details_array[1]['quantity']}}" name="quantity[]"
                                        class="form-control" placeholder="{{ translate('Ex : 10') }}" required>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('Unit')}}</label>
                                <select name="unit_id[]" id="" class="form-control js-select2-custom required-select">
                                    <option value="" disabled selected>Please Select Unit</option>

                                    @foreach($units as $unit)
                                    <option value="{{$unit->id}}" style="text-transform: capitalize;"
                                        {{$unit['id'] == @$product_details_array[2]['unit_id'] ? 'selected' : '';}}>

                                        {{$unit->title}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-sm-2">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{translate('default_price')}}/{{translate('offer_price')}}</label>
                                <input name="offer_price[]" min="0" max="100000000" step="any"
                                    value="{{@$product_details_array[2]['offer_price']}}" class="form-control"
                                    placeholder="{{ translate('Ex : 100') }}" required>
                            </div>

                            <div class="col-sm-2">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{translate('Approx Piece/Weight')}}</label>
                                <input name="approx_piece[]" min="0" max="100000000" step="any" class="form-control"
                                    value="{{@$product_details_array[2]['approx_piece']}}"
                                    placeholder="{{ translate('Ex : 10 pieces') }}" required>
                            </div>
                            <div class="col-sm-2">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{translate('Short Title')}}</label>
                                <input type="text" min="0" max="100000000" step="any" name="title[]"
                                    value="{{@$product_details_array[2]['title']}}" class="form-control"
                                    placeholder="{{ translate('Ex : This product is pure organic') }}" required>
                            </div>

                        </div> -->
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
    $(this).closest('.row-delivery-pair').remove();
});


$(document).on('input','.quantity', function(){
    var market_price = $(this).val();
    console.log(market_price);
    if((market_price)){
        var row = $(this).closest('tr');
        // Find the discount input within the same row
        var marketPriceInput = row.find('input[name="discount[]"]'); //assign value empty
        var offerPriceInput = row.find('input[name="offer_price[]"]');
        var market_price = row.find('input[name="market_price[]"]');
        console.log(market_price);
        marketPriceInput.val('');
        offerPriceInput.val('');
    }else{
        var row = $(this).closest('tr');
        // Find the discount input within the same row
        var marketPriceInput = row.find('input[name="discount[]"]');
        var offerPriceInput = row.find('input[name="offer_price[]"]');
        marketPriceInput.val('');
        offerPriceInput.val('');

        console.log('else');
    }
});
//function for fill discount then get offer price
$(document).on("input", ".discount", function () {
    var discount = $(this).val();
    var row = $(this).closest('tr');

    // Find the market price input within the same row
    var marketPriceInput = row.find('input[name="market_price[]"]');

    // Get the market price value
    var marketPrice = parseFloat(marketPriceInput.val());
console.log(marketPrice);
    // Check if marketPrice is a valid number
    if (!isNaN(marketPrice)) {
        // Calculate offer price
        var offerPrice = marketPrice - (marketPrice * discount / 100);

        // Find the offer price input within the same row
        var offerPriceInput = row.find('input[name="offer_price[]"]');

        // Set the offer price value
        offerPriceInput.val(offerPrice.toFixed(2));

        console.log(offerPriceInput.val());
        console.log(discount);
    }
});
    // var $discount = $('#discount'),
    //     $price = $('#offer_price'),
    //     $newPrice = $('#new-price');
    // $discount.on('keypress', function(e) {
    //     alert(5);
    //     $("#offer_price").css("background-color", "pink");

    //     });
//function end
$(".lang_link").click(function(e) {
    e.preventDefault();
    $(".lang_link").removeClass('active');
    $(".lang_form").addClass('d-none');
    $(this).addClass('active');

    let form_id = this.id;
    let lang = form_id.split("-")[0];
    // console.log(lang);
    $("#" + lang + "-form").removeClass('d-none');
    if (lang == 'en') {
        $("#from_part_2").removeClass('d-none');
    } else {
        $("#from_part_2").addClass('d-none');
    }


})
</script>
<script type="text/javascript">
$(function() {
    $("#coba").spartanMultiImagePicker({
        fieldName: 'images[]',
        maxCount: 4,
        rowHeight: '150px',
        groupClassName: '',
        maxFileSize: '',
        placeholderImage: {
            image: '{{asset('/public/assets/admin/img/upload-en.png')}}',
            width: '100%'
        },
        dropFileLabel: "Drop Here",
        onAddRow: function(index, file) {

        },
        onRenderedPreview: function(index) {

        },
        onRemoveRow: function(index) {

        },
        onExtensionErr: function(index, file) {
            toastr.error('Please only input png or jpg type file', {
                CloseButton: true,
                ProgressBar: true
            });
        },
        onSizeErr: function(index, file) {
            toastr.error('File size too big', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    });
});
$(function() {
    $("#single").spartanMultiImagePicker({
        fieldName: 'single_image[]',
        maxCount: 1,
        rowHeight: '150px',
        groupClassName: '',
        maxFileSize: '',
        placeholderImage: {
            image: '{{asset('public/assets/admin/img/upload-en.png')}}',
            width: '100%'
        },
        dropFileLabel: "Drop Here",
        onAddRow: function(index, file) {

            $(document).ready(function() {

            });

        },
        onRemoveRow: function(index) {

        },
        onExtensionErr: function(index, file) {
            toastr.error('{{ translate("Please only input png or jpg type file") }}', {
                CloseButton: true,
                ProgressBar: true
            });
        },
        onSizeErr: function(index, file) {
            toastr.error('{{ translate("File size too big") }}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    });
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
$(document).on('ready', function() {
    $('.js-select2-custom').each(function() {
        var select2 = $.HSCore.components.HSSelect2.init($(this));
    });
});
</script>

<script src="{{asset('public/assets/admin')}}/js/tags-input.min.js"></script>

<script>
$('#choice_attributes').on('change', function() {
    $('#customer_choice_options').html(null);
    $.each($("#choice_attributes option:selected"), function() {
        add_more_customer_choice_option($(this).val(), $(this).text());
    });
});

function add_more_customer_choice_option(i, name) {
    let n = name.split(' ').join('');
    $('#customer_choice_options').append(
        '<div class="row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' + i +
        '"><input type="text" class="form-control" name="choice[]" value="' + n +
        '" placeholder="Choice Title" readonly></div><div class="col-lg-9"><input type="text" class="form-control" name="choice_options_' +
        i +
        '[]" placeholder="Enter choice values" data-role="tagsinput" onchange="combination_update()"></div></div>'
    );
    $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
}

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
                }, 2000);
            }
        }
    });
});
</script>

<script>
$('#discount_type').change(function() {
    if ($('#discount_type').val() == 'percent') {
        $("#discount_symbol").html('(%)')
    } else {
        $("#discount_symbol").html('')
    }
});

$('#tax_type').change(function() {
    if ($('#tax_type').val() == 'percent') {
        $("#tax_symbol").html('(%)')
    } else {
        $("#tax_symbol").html('')
    }
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
@extends('layouts.admin.app')

@section('title', translate('Update product'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="{{asset('public/assets/admin/css/tags-input.min.css')}}" rel="stylesheet">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{asset('public/assets/admin/img/edit.png')}}" class="w--24" alt="">
            </span>
            <span>
                {{translate('product')}} {{translate('update')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->
    <form action="javascript:" method="post" id="product_form" enctype="multipart/form-data" class="row g-2">
        @csrf
        @php($data = Helpers::get_business_settings('language'))
        @php($default_lang = Helpers::get_default_language())

        <div class="col-lg-6">
            <div class="card">
                <div class="card-body pt-2">
                    @if($data && array_key_exists('code', $data[0]))
                    <ul class="nav nav-tabs mb-4">

                        @foreach($data as $lang)
                        <li class="nav-item">
                            <a class="nav-link lang_link {{$lang['code'] == 'en'? 'active':''}}" href="#"
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
                    <div class="{{$lang['code'] != 'en'? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                        <div class="form-group">
                            <label class="input-label" for="{{$lang['code']}}_name">{{translate('name')}}
                                ({{strtoupper($lang['code'])}})</label>
                            <input type="text" {{$lang['status'] == true ? 'required':''}} name="name[]"
                                id="{{$lang['code']}}_name"
                                value="{{$translate[$lang['code']]['name']??$product['name']}}" class="form-control"
                                placeholder="{{translate('New Product')}}">
                        </div>
                        <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                        <div class="form-group mb-0">
                            <label class="input-label" for="{{$lang['code']}}_description">{{translate('short')}}
                                {{translate('description')}} ({{strtoupper($lang['code'])}})</label>
                            <textarea name="description[]" class="form-control h--172px"
                                id="{{$lang['code']}}_hiddenArea">{{$translate[$lang['code']]['description']??$product['description']}}</textarea>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div id="english-form">
                        <div class="form-group">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('name')}} (EN)</label>
                            <input type="text" name="name[]" value="{{$product['name']}}" class="form-control"
                                placeholder="{{translate('New Product')}}" required>
                        </div>
                        <input type="hidden" name="lang[]" value="en">
                        <div class="form-group mb-0">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('short')}}
                                {{translate('description')}} (EN)</label>
                            <textarea name="description[]" class="form-control  h--172px "
                                id="hiddenArea">{{ $product['description'] }}</textarea>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-header-icon">
                            <i class="tio-user"></i>
                        </span>
                        <span>
                            {{translate('category')}}
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="input-label"
                                    for="exampleFormControlSelect1">{{translate('category')}}<span
                                        class="input-label-secondary">*</span></label>
                                <select name="category_id" id="category-id" class="form-control js-select2-custom"
                                    onchange="getRequest('{{url('/')}}/admin/product/get-categories?parent_id='+this.value,'sub-categories')">
                                    <option value="">---{{translate('select')}}---</option>
                                    <?php echo $options; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{translate('Product_Code')}}</label>
                                <input type="text" min="1" step="1" value="{{$product['product_code']}}"
                                    name="product_code" style="text-transform: uppercase;" class="form-control"
                                    placeholder="{{ translate('Product_Code') }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('Unit')}}</label>
                            <select name="unit_id" id="" class="form-control js-select2-custom required-select">
                                <option disabled selected>Please Select Unit</option>
                                @foreach($units as $unit)
                                <option value="{{$unit->id}}" {{ $unit->id == $product->unit_id ? 'selected' : ''}}
                                    style="text-transform: capitalize;">{{$unit->title}} ({{$unit->description}})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{translate('Maximum_Order_Quantity')}}</label>
                                <input type="number" min="1" step="1" value="{{$product['maximum_order_quantity']}}"
                                    name="maximum_order_quantity" class="form-control"
                                    placeholder="{{ translate('Ex : 3') }}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">&nbsp;</label>
                                <div class="d-flex align-items-center mb-2 mb-sm-0">
                                    <h5 class="mb-0 mr-2">{{ translate('Visibility') }}</h5>
                                    <label class="toggle-switch my-0">
                                        <input type="checkbox" class="toggle-switch-input" name="status" value="1"
                                            checked>
                                        <span class="toggle-switch-label mx-auto text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">{{translate('Single product')}} {{translate('image')}} <small class="text-danger">*
                            ( {{translate('ratio')}} 1:1 )</small></h5>
                    <div class="product--single">
                        @if (!empty(json_decode($product['single_image'],true)))
                        @foreach(json_decode($product['single_image'],true) as $img)
                        <div class="row g-2">
                            <div class="spartan_item_wrapper position-relative">
                                <img class="img-150 border rounded p-3"
                                    src="{{asset('storage/app/public/product/single')}}/{{$img}}">
                                <a href="{{route('admin.product.remove-single-image',[$product['id'],$img])}}"
                                    class="spartan__close"><i class="tio-add-to-trash"></i></a>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="row g-2" id="single"></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-3">{{translate('product')}} {{translate('image')}} <small class="text-danger">* (
                            {{translate('ratio')}} 1:1 )</small></h5>
                    <div class="product--coba">
                        <div class="row g-2" id="coba">
                            @if (!empty(json_decode($product['image'],true)))
                            @foreach(json_decode($product['image'],true) as $img)
                            <div class="spartan_item_wrapper position-relative">
                                <img class="img-150 border rounded p-3"
                                    src="{{asset('storage/app/public/product')}}/{{$img}}">
                                <a href="{{route('admin.product.remove-image',[$product['id'],$img])}}"
                                    class="spartan__close"><i class="tio-add-to-trash"></i></a>
                            </div>
                            @endforeach
                            @endif
                        </div>
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
</div>
</form>
</div>

@endsection

@push('script')

@endpush

@push('script_2')
<script src="{{asset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>
<script>
$(".lang_link").click(function(e) {
    e.preventDefault();
    $(".lang_link").removeClass('active');
    $(".lang_form").addClass('d-none');
    $(this).addClass('active');

    let form_id = this.id;
    let lang = form_id.split("-")[0];
    console.log(lang);
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
            image: '{{asset(' / public / assets / admin / img / upload - en.png ')}}',
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
            image: '{{asset('
            public / assets / admin / img / upload - en.png ')}}',
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
        '[]" placeholder="Enter choice values" data-role="tagsinput" onchange="combination_update()"></div></div>');
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
        url: '{{route('admin.product.update',[$product['id']])}}',
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
                toastr.success('{{translate('
                    product updated successfully!')}}', {
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
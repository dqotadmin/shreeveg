@extends('layouts.admin.app')

@section('title', translate('Rate List'))

@section('content')
<div class="content container-fluid product-list-page">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{asset('public/assets/admin/img/products.png')}}" class="w--24" alt="">
            </span>
            <span>
                {{ translate('Rate List') }}
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
                    <div class="card--header max--sm-grow">
                        <div class="row">
                            <div class="col-sm-12" id="category_box">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('Select Category')}}</label>
                                    <select name="parent_id" id="parent_id" class="form-control">
                                        <option value="">---{{translate('Select Category')}}---</option>
                                        <?php echo $options; ?>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="col-sm-6" >
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('discount')}}</label>
                                 <input type="text" class="form-control">
                            </div>
                        </div> -->
                        </div>


                    </div>
                </div>
                <div class="table-responsive datatable-custom">
                    {{-- <table id="columnSearchDatatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table" data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true
                               }'>
                        <thead class="thead-light">
                            <tr>
                                <th>{{translate('#')}}</th>
                                <th>{{translate('product_name')}}</th>
                                <th>{{translate('product_code')}}</th>
                                <th>{{translate('product_category')}}</th>
                                @if(in_array(auth('admin')->user()->admin_role_id ,[3,5]))
                                <th class="">{{translate('stock')}}</th>
                                @endif
                                @if(auth('admin')->user()->admin_role_id != 5)
                                <th class="text-center">{{translate('status')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody id="set-rows">
                            @foreach($products as $key=>$product)
                            <tr>
                                <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">{{$products->firstItem()+$key}}</td>
                                <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">
                                    <a href="{{route('admin.product.view',[$product['id']])}}" class="product-list-media">
                                         
                                        @if (@$product['single_image'] &&
                                        (!empty(json_decode(@$product['single_image'],true))))
                                        @foreach(json_decode(@$product['single_image'],true) as $img)
                                        <img class="" src="{{asset('storage/app/public/product/single')}}/{{$img}}" onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'">

                                        @endforeach
                                        @else
                                        <img src="{{asset('public/assets/admin/img/400x400/img2.jpg')}}">
                                        @endif
                                        <h6 class="name line--limit-2">
                                            {{\Illuminate\Support\Str::limit($product['name'], 20, $end='...')}}
                                        </h6>
                                    </a>
                                </td>
                                <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">
                                    <div class="max-85 text-right">
                                        {{ $product['product_code'] }}
                                    </div>
                                </td>
                                <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">
                                    <div class="max-85 text-left">
                                        @if (!empty($product->category->name))
                                        {{ $product->category->name }}
                                        @endif
                                    </div>
                                </td>
                                @if(in_array(auth('admin')->user()->admin_role_id, [3,5]))
                                <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">
                                    <?php
                                    $current_stock =  0;

                                    foreach (\App\Model\WarehouseProduct::where('warehouse_id', auth('admin')->user()->warehouse_id)->where('product_id', $product->id)->get() as $stock) {
                                        if ($stock->total_stock > 0) {
                                            $current_stock =  $stock->total_stock;
                                        }
                                    }
                                    echo $current_stock;
                                    ?> /({{@$product->unit['title'] }})
                                    <?php $warehouse_id = auth('admin')->user()->warehouse_id;
                                    $product_id = $product['id'];
                                    if (\App\Model\WarehouseProduct::where('warehouse_id', $warehouse_id)->where('product_id', $product_id)->exists('product_details')) {
                                    } else { ?>
                                        <h5 class="m-0">
                                            <small class="text-hover">Please Upload Prices</small>
                                        </h5>
                                    <?php  }
                                    ?>
                                </td>
                                @endif
                                @if(auth('admin')->user()->admin_role_id != 5)

                                <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">
                                    <label class="toggle-switch my-0">
                                        <input type="checkbox" onclick="status_change_alert('{{ route('admin.product.status', [$product->id, $product->status ? 0 : 1]) }}', '{{ $product->status? translate('you want to disable this product'): translate('you want to active this product') }}', event)" class="toggle-switch-input" id="stocksCheckbox{{ $product->id }}" {{ $product->status ? 'checked' : '' }}>
                                        <span class="toggle-switch-label mx-auto text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>
                                @endif
                                <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">
                                    @if( auth('admin')->user()->admin_role_id == 1 )
                                    <!-- Dropdown -->
                                    <div class="btn--container justify-content-center">
                                        <a class="action-btn   btn-outline-info" href="{{route('admin.product.view',[$product['id']])}}">
                                            <i class="tio-invisible"></i>
                                        </a>
                                        <a class="action-btn" href="{{route('admin.product.edit',[$product['id']])}}">
                                            <i class="tio-edit"></i></a>
                                        <a class="action-btn btn--danger btn-outline-danger" href="javascript:" onclick="form_alert('product-{{$product['id']}}','{{ translate("Want to delete this") }}')">
                                            <i class="tio-delete-outlined"></i>
                                        </a>
                                        <form action="{{route('admin.product.delete',[$product['id']])}}" method="post" id="product-{{$product['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                        @endif
                                        @if( auth('admin')->user()->admin_role_id == 3 )

                                        <a class="action-btn" href="{{route('admin.product.warehouse-edit',[$product['id']])}}">
                                            <i class="tio-money"></i></a>
                                        <!-- End Dropdown -->

                                    </div>
                                    @endif

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table> --}}

                    <div class="page-area">
                        <table>
                            <tfoot class="border-top">
                                {!! $products->links() !!}
                            </tfoot>
                        </table>
                    </div>
                    @if(count($products)==0)
                    <div class="text-center p-4">
                        <img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">
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

@endsection

@push('script')

@endpush
@push('script_2')



<script defer>
$(document).on('ready', function () {
    renderWhProducts(0);
    });

    $('#parent_id').on('change', function() {
        var cat_id = $(this).val();
        renderWhProducts(cat_id);
    });

    function renderWhProducts(cat_id){

        $.ajax({
            url: '{{url('/')}}/admin/rate_list/get-product-by-cat/' + cat_id,
            method: 'GET',

            success: function(data) {
                console.log(data);
                $('.datatable-custom').html(data.view);
                $('.footer').addClass(data.d_none_class);
                if (data.length > 1) {
                    $('#quantity').hide();
                } else {
                    $('#quantity').show();
                }
                

    $('.market_price').on('input',function(){
        console.log($(this).val());
        $('.hidden_market_price').val($(this).val());
    })
    function handleFirstDiscountChange(id,classPrefix = ""){



        let discount = parseFloat($(`.${classPrefix}discount_${id}`).val())

        if (!discount) {
            discount = 0;
        }
        const productRate = parseFloat($(`.product_rate_${id}`).text())
        let quantity = parseFloat($(`.${classPrefix}quantity_${id}`).val());
        if (!quantity) {
            quantity = 1;
        }
        let marginToBeAdd = ((quantity * productRate) * discount / 100)
        let offerRate = (quantity * productRate) +marginToBeAdd;
        let perUnitPrice = offerRate/quantity ;
        $(`.${classPrefix}offer_rate_${id}`).val(offerRate.toFixed(2));
        $(`.${classPrefix}per_unit_rate_${id}`).val(perUnitPrice.toFixed(2));
    }
/** @function  globalDynamicHandler
 * step 2 
 * _item = <input type=​"text" class=​"form-control 3_quantity 3_quantity_2" name value onkeyup=​"handleFirstDiscountChange(2,'3_')>
 * i =​ 1  (0,1)
 * value=5 quantity value
 * columnPrefix = '', 2_, 3_
 * @return void
*/
    function globalDynamicHandler(_item,i,value,columnPrefix = "") {
        let _sno = i + 1;
        if (!_item.classList.contains("data_changed")) {
            _item.value = value;
        }
        let discount = parseFloat($(`.${columnPrefix}discount_${_sno}`).val())
        console.log('globalDynamicHandler', discount);

        if (!discount) {
            discount = 0;
        }
        const productRate = parseFloat($(`.product_rate_${_sno}`).text())

        let quantity = parseFloat($(`.${columnPrefix}quantity_${_sno}`).val());
        if (!quantity) {
            quantity = 1;
        }
        let marginToBeAdd = ((quantity * productRate) * discount / 100)
        let offerRate = (quantity * productRate) +marginToBeAdd ;
        let perUnitPrice = offerRate/quantity ;
        $(`.${columnPrefix}offer_rate_${_sno}`).val(offerRate.toFixed(2));
        $(`.${columnPrefix}per_unit_rate_${_sno}`).val(perUnitPrice.toFixed(2));
    }
    /**  @function dynamicHandler()
     * step 1 start from here
     * first its use for 3 times global <td> margin, quantity  
     * we define array lenght 3 for <td> if we want to increase our th then change lenght
     * index value is (0,1,2)
     * we use sno for change index value (1,2,3)
     * 
     *    $(`#global_${sno}_discount`) this is use for first global discount its change all first <td> price value in this we pass
     * global_1_discount and sno  value is 3 times( 3 1)(3 2)(3 3)
     * then we change every product td value then we use this (${sno}_discount) in this we use loop every time 
     * goto step 2
     * return void
     * 
    */

    var dynamicHandler = (update = false) => {
        Array.from({
            length: 3
        }).forEach((item, index) => {
            console.log('dynamicHandler'+index);
       
                let sno = index + 1;
                // let fixDiscount = parseFloat($("#discount").val() || 0)
                // let adminRate = parseFloat($(".offer_rate").val() || 0)

                /** @function first column's global discount, quantity & unit handler */
                $(`#global_${sno}_discount`).on('input', function() {
                    console.log('global_');
                    var global_1_discount = $(this).val();
                    Array.from($(`.${sno}_discount`)).forEach((_item,i)=>globalDynamicHandler(_item,i,global_1_discount, sno > 1 ? `${sno}_`:""));
                    

                });

                $(`#global_${sno}_quantity`).on('input', function() {
                    var global_1_quantity = $(this).val();
                    // console.log(global_1_quantity);
                    Array.from($(`.${sno}_quantity`)).forEach((_item,i)=>globalDynamicHandler(_item,i,global_1_quantity, sno > 1 ? `${sno}_`:""));

                });
                $(`#global_${sno}_unit`).on('change', function() {
                    var global_1_unit = $(this).val();
                    // console.log(global_1_unit);
                    Array.from($(`.${sno}_unit`)).forEach(function(_item) {
                        if (!_item.classList.contains("data_changed")) {
                            _item.value = global_1_unit;
                        }
                    });

                });
                /** @function first column's global discount, quantity & unit handler 
                 * its use is pass data_changed class in every input field when we manually fill input field
                */

                /** @function first column's discount, quantity & unit handler */
                // Array.from($(`.${sno}_discount`)).forEach(function(_item) {
                //     _item.oninput = function(event) {
                //         event.target.classList.add("data_changed");
                //     }
                // });
                // Array.from($(`.${sno}_quantity`)).forEach(function(_item) {
                //     _item.oninput = function(event) {
                //         event.target.classList.add("data_changed");
                //     }
                // });
                // Array.from($(`.${sno}_unit`)).forEach(function(_item) {
                //     _item.oninput = function(event) {
                //         event.target.classList.add("data_changed");
                //     }
                // });
                /** @function first column's discount, quantity & unit handler */
    })
    }

dynamicHandler()
            },
        });
    }
 
    
 </script>
@endpush
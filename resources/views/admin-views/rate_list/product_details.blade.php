@extends('layouts.admin.app')
 @foreach($products as $product)
 @php($whProduct = Helpers::warehouseProductData($product->id))
 @endforeach
 
 </style>
 <div class="table-responsive datatable-custom">
 <form action="{{route('admin.rate_list.store')}}" method="post">
    @csrf
    <table id="columnSearchDatatable"
         class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
         data-hs-datatables-options='{  "order": [],     "orderCellsTop": true }'>
         <thead class="thead-light">
             <tr>
                 <!-- <th>{{translate('#')}}</th> -->
                 <th class="">{{translate('category_name')}}</th>
                 <th>{{translate('product_name')}}</th>
                 <th>{{translate('market_price/unit')}}</th>
                 <!-- <th>{{translate('unit')}}</th> -->
                 <th>{{translate('product_rate')}} <br />
                     <small>(Avg Rate)</small>
                 </th>
          
                 <th width="25%">
                     <div class="row">
                         <div class="row">
                            <div class="col-md-12">
                                 <div>
                                     {{translate('shreeveg Price')}} (first slot):
                                 </div>
                            </div>
                        </div>
                             <div class="row gx-1 align-items-end w-100">
                                <div class="col-3">
                                <div class="">
                                     <small for=""> Quantity:</small>
                                     <input type="text" class="form-control" id="global_1_quantity" value="1"
                                         style="width: 70px;"    >
                                      
                                 </div>
                             </div>
                              <div class="col-3">
                                 <div class=""  >
                                     <small for=""> margin(%):</small>
                                     <select name="" class="form-control" id="global_1_discount" style="width: 70px;padding: 3px;">
                                     @for($i=-100; $i<=100; $i++)
                                            <option value="{{$i}}" >{{$i}}</option>
                                    @endfor
                                    </select>
                                  
                                 </div>
                             </div>
                             <div class="col-auto ml-auto">
                                     <small for=""> 1 unit price</small>
                                 </div>
                             </div>
                            </div>
                     </div>
                 </th>
                 <th width="25%">
                     <div class="row">
                        <div class="row">
                            <div class="col-md-12">
                                 <div>
                                     {{translate('shreeveg Price')}} (second slot):
                                 </div>
                            </div>
                        </div>
                             <div class="row gx-1 align-items-end w-100">
                                <div class="col-3">
                                <div class="">
                                     <small for=""> Quantity:</small>
                                     <input type="text" class="form-control" id="global_2_quantity"
                                     style="width: 70px;" value="3">
                                  
                                 </div>
                             </div>
                             <div class="col-3">
                                 <div class=""  >
                                     <small for=""> margin(%):</small>
                                     <select name="" class="form-control" id="global_2_discount" style="width: 70px;padding: 3px;">
                                     @for($i=-100; $i<=100; $i++)
                                            <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                    </select>
                                      
                                 </div>
                             </div>
                                <div class="col-auto ml-auto">
                                     <small for=""> 1 unit price</small>
                                </div>
                             <!-- <div class="col-md-2">
                                 <div class="" style="margin: 0 -16px;">
                                     <small for=""> Unit:</small>
                                     <select name="" class="form-control"  style="width: 70px;padding: 3px;"
                                         id="global_2_unit">
                                         <option value="" disabled selected>Unit</option>
                                         @foreach(\App\Model\Unit::get() as $unit)
                                         <option value="{{$unit->id}}">{{$unit->title}}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div> -->
                         </div>
                     </div>
                     </div>
                 </th>
                 <th width="25%">
                     <div class="row">
                        <div class="row">
                             <div class="col-md-12">
                                 <div>
                                     {{translate('shreeveg Price')}} ( (third slot)):
                                 </div>
                            </div>
                                
                        </div>
                            <div class="row gx-1 align-items-end w-100">
                            <div class="col-3">
                                 <div class="">
                                     <small for=""> Quantity:</small>
                                     <input type="text" class="form-control" id="global_3_quantity" value="5"
                                     style="width: 70px;">
                                    
                                 </div>
                             </div>
                             <div class="col-3">
                                 <div class="">
                                     <small for=""> margin(%):</small>
                                     <select name="" class="form-control" id="global_3_discount"  style="width: 70px;padding: 3px;">
                                     @for($i=-100; $i<=100; $i++)
                                                <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                 </div>
                             </div>
                             
                             <div class="col-auto ml-auto">
                                     <small for=""> 1 unit price</small>
                             </div>
                            </div>
                           
                     </div>
                 </th>
                 <th>
                 {{translate('rate_updated_date')}} 
                 </th>
             </tr>
         </thead>
         <tbody id="set-rows">
             @foreach($products as $key=>$product)
             <?php $warehouse_id = auth('admin')->user()->warehouse_id;  ?>
             @php($whProduct = Helpers::warehouseProductData($product->id))
             <?php    $product_details_array = @json_decode($whProduct->product_details, true); 
                            $i=0;
                            ?>
              
             <tr>
                 <td class="pt-1 pb-3 ">
                 <div class="max-85 text-left product-category " title="{{ @$product->category->name }}">
                 {!! (nl2br(e(Str::words(@$product->category->name, '2')))) !!}
                     </div>
                 </td>
                 <td class="pt-1 pb-3 ">
                     <div class="max-85 text-center">
                         {{ $product['name'] }} <br><small>({{ $product['product_code'] }} )</small>
                         

                     </div>
                 </td>
                 <td class="pt-1 pb-3 ">
                     <div class="row">
                         <div class="d-flex align-items-center">
                             <input type="text" class="form-control market_price" style="width: 70px; margin: 0 10px;"  value="{{ @$whProduct['market_price'] }}"  name="market_price[]" required>/{{ @$product->unit->title }}
                             <input type="hidden" class="form-control" value="{{ $product['id'] }}" style="width: 70px; margin: 0 10px;" name="product_id[]">
                             <input type="hidden" class="form-control" value="{{ $product['unit_id'] }}" style="width: 70px; margin: 0 10px;" name="unit_id[]">
                            
                         </div>
                     </div>
                 </td>
                 <td class="pt-1 pb-3 ">
                     <span class="product_rate_<?php echo $key + 1; ?>"> {{@$whProduct->avg_price}}</span>{{@$product->unit->title}}
                 </td>
               
                 <td class="pt-1 pb-3 " width="35%">
                     <div class="row customrow">
                    <input type="text" name="1_slot[quantity][]" class="form-control 1_quantity quantity_<?php echo $key + 1; ?>" onkeyup="handleFirstDiscountChange(<?php echo $key + 1; ?>)"  style="width: 70px;  " 
                    value="{{@$product_details_array[0]['quantity']}}" >
                      
                    <input type="text" name="1_slot[margin][]" class="form-control 1_discount discount_<?php echo $key + 1; ?>"   style="width: 70px;"   value="{{@$product_details_array[0]['margin']}}" onkeyup="handleFirstDiscountChange(<?php echo $key + 1; ?>)"  >
                 
                    <input type="text" class="form-control 1_offer_rate offer_rate_<?php echo $key + 1; ?>" name="1_slot[offer_price][]"  style="width: 70px;"  value="{{@$product_details_array[0]['offer_price']}}">

                        <input type="text"  name="1_slot[per_unit_price][]" id="" style="width: 70px;   padding-left: 8px;border: none;" class="per_unit_rate_<?php echo $key + 1; ?>"  value="{{@$product_details_array[0]['per_unit_price']}}">
                     </div>
                 </td>
                 <td class="pt-1 pb-3 ">
                     <div class="row customrow">
                        <input type="text" name="2_slot[quantity][]" class="form-control 2_quantity 2_quantity_<?php echo $key + 1; ?>"  value="{{@$product_details_array[1]['quantity']}}" onkeyup="handleFirstDiscountChange(<?php echo $key + 1; ?>,'2_')"
                                style="width: 70px;  ">
                             
                        <input type="text" name="2_slot[margin][]" class="form-control 2_discount 2_discount_<?php echo $key + 1; ?>" onkeyup="handleFirstDiscountChange(<?php echo $key + 1; ?>,'2_')" style="width: 70px;" value="{{@$product_details_array[1]['margin']}}">
                        
                         <input type="text" class="form-control 2_offer_rate 2_offer_rate_<?php echo $key + 1; ?>" name="2_slot[offer_price][]" value="{{@$product_details_array[1]['offer_price']}}"
                             style="width: 70px;">

                        <input type="text" id="" name="2_slot[per_unit_price][]" style="width: 70px;border: none; padding-left: 8px;" class="2_per_unit_rate_<?php echo $key + 1; ?>" value="{{@$product_details_array[1]['per_unit_price']}}" >
                     </div>
                 </td>
                 <td class="pt-1 pb-3 ">
                     <div class="row customrow">
                        
                     <input type="text" class="form-control 3_quantity 3_quantity_<?php echo $key + 1; ?>" name="3_slot[quantity][]" value="{{@$product_details_array[2]['quantity']}}"  onkeyup="handleFirstDiscountChange(<?php  echo $key + 1; ?>,'3_')"
                             style="width: 70px;    ">

                    <input type="text" class="form-control 3_discount 3_discount_<?php echo $key + 1; ?>" name="3_slot[margin][]" style="width: 70px;" onkeyup="handleFirstDiscountChange(<?php echo $key + 1; ?>,'3_')"  value="{{@$product_details_array[2]['margin']}}">
                         
                    <input type="text" class="form-control 3_offer_rate   3_offer_rate_<?php echo $key + 1; ?>" name="3_slot[offer_price][]"  
                             style="width: 70px;" value="{{@$product_details_array[2]['offer_price']}}">
                             
                    <input type="text"  name="3_slot[per_unit_price][]" id="" style="width: 70px;   border: none; padding-left: 8px;" class="3_per_unit_rate_<?php echo $key + 1; ?>" value="{{@$product_details_array[2]['per_unit_price']}}">
                     </div>
                 </td>
                 <td class="" >
                    @if(@$whProduct->product_rate_updated_date)
                    <?php  $dateColor = 'red';
                        if (date('Y-m-d', strtotime($whProduct->product_rate_updated_date)) == date('Y-m-d')) {
                            $dateColor = 'green';
                        } ?>
                    <h5 style="margin-left: 15px;color:{{$dateColor}}">{{ date('d-M-Y h:i A', strtotime($whProduct->product_rate_updated_date)) }}</h5>
                    @endif
                </td>
             </tr>
             @endforeach
         </tbody>
    </table>
    <div class="col-12">
        <div class="btn--container justify-content-start m-3">
            <a type="reset" href="{{route('admin.rate_list.index')}}" class="btn btn--reset">Back</a>
            <button type="submit" class="btn btn--primary">Update</button>
        </div>
    </div>
</form>
     <div class="page-area">
         <table>
             <tfoot class="border-top">
             </tfoot>
         </table>
     </div>
     @if(count($products)==0)
     <div class="text-center p-4">
         <img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}"
             alt="Image Description">
         <p class="mb-0">{{translate('No_data_to_show')}}</p>
     </div>
     @endif
 </div>


 @push('script_2')
 <script defer>
    

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
console.log('product_detail');
 </script>
 @endpush
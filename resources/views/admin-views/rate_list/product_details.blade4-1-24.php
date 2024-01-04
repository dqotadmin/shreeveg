@extends('layouts.admin.app')
 @foreach($products as $product)
 @php($whProduct = Helpers::warehouseProductData($product->id))
 @endforeach
 <div class="table-responsive datatable-custom">
     <table id="columnSearchDatatable"
         class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
         data-hs-datatables-options='{  "order": [],     "orderCellsTop": true }'>
         <thead class="thead-light">
             <tr>
                 <!-- <th>{{translate('#')}}</th> -->
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
                             <div class="col-md-3">
                                 <div class="">
                                     <small for=""> margin:</small>
                                     <select name="" class="form-control" id="global_1_discount" style="width: 70px;padding: 3px;">
                                     @for($i=-100; $i<=100; $i++)
                                        @if($i !== 0)
                                            <option value="{{$i}}" >{{$i}}</option>
                                        @endif
                                    @endfor
                                    </select>
                                  
                                 </div>
                             </div>
                             <div class="col-md-2">
                                 <div class="" style="margin-left: -25px;">
                                     <small for=""> Quantity:</small>
                                     <input type="text" class="form-control" id="global_1_quantity"
                                         style="width: 70px;"    >
                                      
                                 </div>
                             </div>
                             <div class="col-md-2">
                                 <div class="" style="margin: 0 -16px;">
                                     <small for=""> Unit:</small>
                                     <select name="" class="form-control" id="global_1_unit"
                                         style="width: 70px;padding: 3px;">
                                         <option value="" disabled selected>Unit</option>
                                         @foreach(\App\Model\Unit::get() as $unit)
                                         <option value="{{$unit->id}}">{{$unit->title}}</option>
                                         @endforeach
                                     </select>
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
                             <div class="col-md-3">
                                 <div class="">
                                     <small for=""> margin:</small>
                                     <select name="" class="form-control" id="global_2_discount" style="width: 70px;padding: 3px;">
                                     @for($i=-100; $i<=100; $i++)
                                        @if($i !== 0)
                                            <option value="{{$i}}">{{$i}}</option>
                                        @endif
                                    @endfor
                                    </select>
                                      
                                 </div>
                             </div>

                             <div class="col-md-2">
                                 <div class="" style="margin-left: -25px;">
                                     <small for=""> Quantity:</small>
                                     <input type="text" class="form-control" id="global_2_quantity"
                                     style="width: 70px;" >
                                  
                                 </div>
                             </div>
                             <div class="col-md-2">
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
                             <div class="col-md-3">
                                 <div class=""  >
                                     <small for=""> margin:</small>
                                     <select name="" class="form-control" id="global_3_discount"  style="width: 70px;padding: 3px;">
                                     @for($i=-100; $i<=100; $i++)
                                            @if($i !== 0)
                                                <option value="{{$i}}">{{$i}}</option>
                                            @endif
                                        @endfor
                                    </select>
                                  
                                 </div>
                             </div>
                             <div class="col-md-2">
                                 <div class="" style="margin-left: -25px;">
                                     <small for=""> Quantity:</small>
                                     <input type="text" class="form-control" id="global_3_quantity"
                                     style="width: 70px;">
                                    
                                 </div>
                             </div>
                             <div class="col-md-2">
                                 <div class="" style="margin: 0 -16px;">
                                     <small for=""> Unit:</small>
                                     <select name="" class="form-control" style="width: 70px;padding: 3px;"
                                         id="global_3_unit">
                                         <option value="" disabled selected>Unit</option>
                                         @foreach(\App\Model\Unit::get() as $unit)
                                         <option value="{{$unit->id}}">{{$unit->title}}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                         </div>


                     </div>
                 </th>
             </tr>
         </thead>
         <tbody id="set-rows">
             @foreach($products as $key=>$product)
             <?php $warehouse_id = auth('admin')->user()->warehouse_id;  ?>
             @php($whProduct = Helpers::warehouseProductData($product->id))
             <tr>
                 <!-- <td class="pt-1 pb-3 ">{{$product->id}}</td> -->
                 <td class="pt-1 pb-3 ">
                     <div class="max-85 text-right">
                         {{ $product['name'] }}
                     </div>
                 </td>
                 <td class="pt-1 pb-3 ">
                     <div class="row">
                         <div class="d-flex align-items-center">
                             <input type="text" class="form-control" style="width: 70px; margin: 0 10px;">
                             <select name="" class="form-control" style="width: 78px;">
                                 <option value="">Unit</option>
                                 @foreach(\App\Model\Unit::get() as $unit)
                                 <option value="{{$unit->id}}">{{$unit->title}}</option>
                                 @endforeach
                             </select>
                         </div>
                     </div>
                 </td>
                 <td class="pt-1 pb-3 ">
                     <span class="product_rate_<?php echo $key + 1; ?>"> {{@$whProduct->avg_price}}</span>{{@$product->unit->title}}
                 </td>
                 <td class="pt-1 pb-3 ">
                     <div class="row">
                         <input type="text" class="form-control 1_discount discount_<?php echo $key + 1; ?>" name=""   style="width: 70px;" onkeyup="handleFirstDiscountChange(<?php echo $key + 1; ?>)"  >
                         <input type="text" class="form-control 1_quantity quantity_<?php echo $key + 1; ?>" name="" onkeyup="handleFirstDiscountChange(<?php echo $key + 1; ?>)"  style="width: 70px;    margin: 0 2px;" value="1" >
                        
                         <select name="" class="form-control  1_unit"
                             style="width: 70px;    margin: 0 2px;    padding: 3px;">
                             <option value="">Unit</option>
                             @foreach(\App\Model\Unit::get() as $unit)
                             <option value="{{$unit->id}}">{{$unit->title}}</option>
                             @endforeach
                         </select>
                         <input type="text" class="form-control 1_offer_rate offer_rate_<?php echo $key + 1; ?>" name="discount[]" value="" style="width: 70px;">
                     </div>
                 </td>
                 <td class="pt-1 pb-3 ">
                     <div class="row">
                         <input type="text" class="form-control 2_discount 2_discount_<?php echo $key + 1; ?>" onkeyup="handleFirstDiscountChange(<?php echo $key + 1; ?>,'2_')" name="" style="width: 70px;">
                        
                         <input type="text" class="form-control 2_quantity 2_quantity_<?php echo $key + 1; ?>"  name="" value="" onkeyup="handleFirstDiscountChange(<?php echo $key + 1; ?>,'2_')"
                             style="width: 70px;    margin: 0 2px;">
                         <select name="" class="form-control  2_unit"
                             style="width: 70px;    margin: 0 2px;padding: 3px;">
                             <option value="">Unit</option>
                             @foreach(\App\Model\Unit::get() as $unit)
                             <option value="{{$unit->id}}">{{$unit->title}}</option>
                             @endforeach
                         </select>
                         <input type="text" class="form-control 2_offer_rate 2_offer_rate_<?php echo $key + 1; ?>" name="discount[]" value=""
                             style="width: 70px;">
                     </div>
                 </td>
                 <td class="pt-1 pb-3 ">
                     <div class="row">
                         <input type="text" class="form-control 3_discount 3_discount_<?php echo $key + 1; ?>" name="" style="width: 70px;" onkeyup="handleFirstDiscountChange(<?php echo $key + 1; ?>,'3_')">
                         
                         <input type="text" class="form-control 3_quantity 3_quantity_<?php echo $key + 1; ?>" name="" value='' onkeyup="handleFirstDiscountChange(<?php echo $key + 1; ?>,'3_')"
                             style="width: 70px;    margin: 0 2px;">
                         <select name="" class="form-control 3_unit"
                             style="width: 70px;    margin: 0 2px;padding: 3px;">
                             <option value="">Unit</option>
                             @foreach(\App\Model\Unit::get() as $unit)
                             <option value="{{$unit->id}}">{{$unit->title}}</option>
                             @endforeach
                         </select>
                         <input type="text" class="form-control 3_offer_rate   3_offer_rate_<?php echo $key + 1; ?>" name="discount[]" value=""
                             style="width: 70px;">
                     </div>
                 </td>
             </tr>
             @endforeach
         </tbody>
     </table>

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
// $(document).on("input", "#discount", function() {
//     var discount = parseFloat($(this).val()); // Parse discount as a floating-point number
    

//     var row = $(this).closest('tr');
//     var product_rate = parseFloat($('.product_rate')
//         .text()); // Use parseFloat to convert the product_rate to a number

//     var offerPrice = product_rate + (product_rate * discount / 100);

//     var quantityInput = row.find('input[name="offer_rate[]"]');

//     // Set the offer price value
//     quantityInput.val(offerPrice.toFixed(
//         2)); // Use toFixed to round the result to 2 decimal places and set the value
//     // dynamicHandler(true)

// });
// $(document).on("input", ".offer_rate", function() {
//     var offer_price = parseFloat($(this).val()); // Parse offer_price as a floating-point number

//     var row = $(this).closest('tr');
//     // dynamicHandler(true);

// });
    /**
     * @function handleFirstDiscountChange
     * ye dklglksldkgld
     * @return  void
     */
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
        let offerRate = (quantity * productRate) +marginToBeAdd ;
        $(`.${classPrefix}offer_rate_${id}`).val(offerRate.toFixed(2));
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
        $(`.${columnPrefix}offer_rate_${_sno}`).val(offerRate.toFixed(2));
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
       
                let sno = index + 1;
                // let fixDiscount = parseFloat($("#discount").val() || 0)
                // let adminRate = parseFloat($(".offer_rate").val() || 0)

                /** @function first column's global discount, quantity & unit handler */
                $(`#global_${sno}_discount`).on('input', function() {
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
                /** @function first column's global discount, quantity & unit handler */

                /** @function first column's discount, quantity & unit handler */
                Array.from($(`.${sno}_discount`)).forEach(function(_item) {
                    _item.oninput = function(event) {
                        event.target.classList.add("data_changed");
                    }
                });
                Array.from($(`.${sno}_quantity`)).forEach(function(_item) {
                    _item.oninput = function(event) {
                        event.target.classList.add("data_changed");
                    }
                });
                Array.from($(`.${sno}_unit`)).forEach(function(_item) {
                    _item.oninput = function(event) {
                        event.target.classList.add("data_changed");
                    }
                });
                /** @function first column's discount, quantity & unit handler */
    })
    }

dynamicHandler()
 </script>
 @endpush
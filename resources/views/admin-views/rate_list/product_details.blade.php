 @extends('layouts.admin.app')
 @foreach($products as $product)
 @php($whProduct = Helpers::warehouseProductData($product->id))
 @endforeach
 <div class="table-responsive datatable-custom">
     <table id="columnSearchDatatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table" data-hs-datatables-options='{  "order": [],     "orderCellsTop": true }'>
         <thead class="thead-light">
             <tr>
                 <!-- <th>{{translate('#')}}</th> -->
                 <th>{{translate('product_name')}}</th>
                 <th>{{translate('market_price/unit')}}</th>
                 <!-- <th>{{translate('unit')}}</th> -->
                 <th>{{translate('product_rate')}} <br />
                     <small>(Avg Rate)</small>
                 </th>
                 <th>{{translate('fix(%)')}}</th>
                 <th>{{translate('admin_rate')}}</th>
                 <th>
                     <div class="row">
                         <div class="row">
                             <div class="col-md-12">
                                 <div>
                                     {{translate('shreeveg Price')}} (discount):
                                 </div>
                             </div>
                             <div class="col-md-2">
                                 <div class="">
                                     <small for=""> discount:</small>
                                     <input type="text" class="form-control" id="global_1_discount" style="width: 70px;">
                                 </div>
                             </div>

                             <div class="col-md-2">
                                 <div class="" style="margin-left: 19px;">
                                     <small for=""> Quantity:</small>
                                     <input type="text" class="form-control" id="global_1_quantity" style="width: 70px;">
                                     <!-- <select name=""   class="form-control" id="global_1_quantity" style="width: 70px;">
                                @for($i=1; $i<=100; $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select> -->
                                 </div>
                             </div>
                             <div class="col-md-2">
                                 <div class="" style="margin: 0 40px;">
                                     <small for=""> Unit:</small>
                                     <select name="" class="form-control" id="global_1_unit" style="width: 70px;padding: 3px;">
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
                 <th>
                     <div class="row">
                         <div class="row">
                             <div class="col-md-12">
                                 <div>
                                     {{translate('shreeveg Price')}} (discount):
                                 </div>
                             </div>
                             <div class="col-md-2">
                                 <div class="">
                                     <small for=""> discount:</small>
                                     <input type="text" class="form-control" id="global_2_discount" style="width: 70px;">
                                 </div>
                             </div>

                             <div class="col-md-2">
                                 <div class="" style="margin-left: 19px;">
                                     <small for=""> Quantity:</small>
                                     <input type="text" class="form-control" id="global_2_quantity" style="width: 70px;">
                                     <!-- <select name="" class="form-control" style="width: 70px;" id="global_2_quantity" >
                                 @for($i=1; $i<=100; $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select> -->
                                 </div>
                             </div>
                             <div class="col-md-2">
                                 <div class="" style="margin: 0 40px;">
                                     <small for=""> Unit:</small>
                                     <select name="" class="form-control" style="width: 70px;" id="global_2_unit">
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
                 <th>
                     <div class="row">
                         <div class="row">
                             <div class="col-md-12">
                                 <div>
                                     {{translate('shreeveg Price')}} (discount):
                                 </div>
                             </div>
                             <div class="col-md-2">
                                 <div class="">
                                     <small for=""> discount:</small>
                                     <input type="text" class="form-control" id="global_3_discount" style="width: 70px;">
                                 </div>
                             </div>

                             <div class="col-md-2">
                                 <div class="" style="margin-left: 19px;">
                                     <small for=""> Quantity:</small>
                                     <input type="text" class="form-control" id="global_3_quantity" style="width: 70px;">
                                     <!-- <select name="" class="form-control" style="width: 70px;"  id="global_3_quantity" >
                                @for($i=1; $i<=100; $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select> -->
                                 </div>
                             </div>
                             <div class="col-md-2">
                                 <div class="" style="margin: 0 40px;">
                                     <small for=""> Unit:</small>
                                     <select name="" class="form-control" style="width: 70px;" id="global_3_unit">
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
                     <span class="product_rate"> {{@$whProduct->avg_price}}</span>{{@$product->unit->title}}
                 </td>

                 <td class="pt-1 pb-3 ">
                     <input type="text" class="form-control discount" id="discount" style="width: 70px;">
                 </td>

                 <td class="pt-1 pb-3 ">
                     <input type="text" class="form-control offer_rate" name="offer_rate[]" style="width: 70px;">
                 </td>
                 <td class="pt-1 pb-3 ">
                     <div class="row">
                         <input type="text" class="form-control 1_discount discount_<?php echo $key + 1; ?>" name="" style="width: 70px;">
                         <input type="text" class="form-control 1_quantity quantity_<?php echo $key + 1; ?>" name="" style="width: 70px;    margin: 0 2px;">
                         <!-- <select name="" class="form-control 1_quantity" style="width: 70px;    margin: 0 2px;">
                                @for($i=1; $i<=100; $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                         </select> -->
                         <select name="" class="form-control  1_unit" style="width: 70px;    margin: 0 2px;    padding: 3px;">
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
                         <input type="text" class="form-control 2_discount" name="" style="width: 70px;">
                         <!-- <select name="" class="form-control 2_quantity" style="width: 70px;    margin: 0 2px;">
                                @for($i=1; $i<=100; $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                         </select> -->
                         <input type="text" class="form-control 2_quantity" name="discount[]" value='' style="width: 70px;    margin: 0 2px;">
                         <select name="" class="form-control  2_unit" style="width: 70px;    margin: 0 2px;padding: 3px;">
                             <option value="">Unit</option>
                             @foreach(\App\Model\Unit::get() as $unit)
                             <option value="{{$unit->id}}">{{$unit->title}}</option>
                             @endforeach
                         </select>
                         <input type="text" class="form-control 2_offer_rate" name="discount[]" value="" style="width: 70px;">
                     </div>
                 </td>
                 <td class="pt-1 pb-3 ">
                     <div class="row">
                         <input type="text" class="form-control 3_discount" name="" style="width: 70px;">
                         <!-- <select name="" class="form-control 3_quantity" style="width: 70px;    margin: 0 2px;">
                                @for($i=1; $i<=100; $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                         </select> -->
                         <input type="text" class="form-control 3_quantity" name="discount[]" value='' style="width: 70px;    margin: 0 2px;">
                         <select name="" class="form-control 3_unit" style="width: 70px;    margin: 0 2px;padding: 3px;">
                             <option value="">Unit</option>
                             @foreach(\App\Model\Unit::get() as $unit)
                             <option value="{{$unit->id}}">{{$unit->title}}</option>
                             @endforeach
                         </select>
                         <input type="text" class="form-control 3_offer_rate" name="discount[]" value="" style="width: 70px;">
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
         <img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">
         <p class="mb-0">{{translate('No_data_to_show')}}</p>
     </div>
     @endif
 </div>


 @push('script_2')
 <script defer>
     $(document).on("input", "#discount", function() {
         var discount = parseFloat($(this).val()); // Parse discount as a floating-point number
         console.log(discount);

         var row = $(this).closest('tr');
         var product_rate = parseFloat($('.product_rate')
             .text()); // Use parseFloat to convert the product_rate to a number

         var offerPrice = product_rate + (product_rate * discount / 100);

         var quantityInput = row.find('input[name="offer_rate[]"]');

         // Set the offer price value
         quantityInput.val(offerPrice.toFixed(
             2)); // Use toFixed to round the result to 2 decimal places and set the value
         dynamicHandler(true)

     });
     $(document).on("input", ".offer_rate", function() {
         var offer_price = parseFloat($(this).val()); // Parse offer_price as a floating-point number

         var row = $(this).closest('tr');
         dynamicHandler(true);

     });

     var dynamicHandler = (update = false) => {
         Array.from({
             length: 3
         }).forEach((item, index) => {
             let sno = index + 1;

             let fixDiscount = parseFloat($("#discount").val() || 0)
             let adminRate = parseFloat($(".offer_rate").val() || 0)
             console.log("fixDiscount", fixDiscount)
             console.log("adminRate", adminRate)

             if (update) {

                 Array.from($(`.${sno}_discount`)).forEach(function(item, i) {
                     let _sno = i + 1;
                     console.log("item: ", item)
                     // 1_offer_rate
                     let discount = item.value;
                     console.log("discount: ", discount)

                     let quantity = parseFloat($(`.quantity_${_sno}`).val());

                     console.log("quantity: ", quantity)
                     let offerRate = (quantity * adminRate) /** * discount / 100 */ ;
                     $(`.offer_rate_${_sno}`).val(offerRate)

                 });

             }


             /** @function first column's global discount, quantity & unit handler */
             $(`#global_${sno}_discount`).on('input', function() {
                 var global_1_discount = $(this).val();
                 Array.from($(`.${sno}_discount`)).forEach(function(item) {
                     if (!item.classList.contains("data_changed")) {
                         item.value = global_1_discount;
                     }
                 });

             });
             $(`#global_${sno}_quantity`).on('input', function() {
                 var global_1_quantity = $(this).val();
                 console.log(global_1_quantity);
                 Array.from($(`.${sno}_quantity`)).forEach(function(item) {
                     if (!item.classList.contains("data_changed")) {
                         item.value = global_1_quantity;
                     }
                 });

             });
             $(`#global_${sno}_unit`).on('change', function() {
                 var global_1_unit = $(this).val();
                 console.log(global_1_unit);
                 Array.from($(`.${sno}_unit`)).forEach(function(item) {
                     if (!item.classList.contains("data_changed")) {
                         item.value = global_1_unit;
                     }
                 });

             });
             /** @function first column's global discount, quantity & unit handler */

             /** @function first column's discount, quantity & unit handler */
             Array.from($(`.${sno}_discount`)).forEach(function(item) {
                 item.oninput = function(event) {
                     event.target.classList.add("data_changed");
                     dynamicHandler()
                 }
             });
             Array.from($(`.${sno}_quantity`)).forEach(function(item) {
                 item.oninput = function(event) {
                     event.target.classList.add("data_changed");
                     dynamicHandler()
                 }
             });
             Array.from($(`.${sno}_unit`)).forEach(function(item) {
                 item.oninput = function(event) {
                     event.target.classList.add("data_changed");
                     dynamicHandler()
                 }
             });
             /** @function first column's discount, quantity & unit handler */
         })
     }

     dynamicHandler()
 </script>
 @endpush
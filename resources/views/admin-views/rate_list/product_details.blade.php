 @extends('layouts.admin.app')
     @foreach($products as $product)
 @php($whProduct =  Helpers::warehouseProductData($product->id))
                @endforeach
                      <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable"
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true
                               }'>
                        <thead class="thead-light">
                            <tr>
                                <th>{{translate('#')}}</th>
                                <th>{{translate('product_name')}}</th>
                                <th>{{translate('market_price/unit')}}</th>
                                <!-- <th>{{translate('unit')}}</th> -->
                                <th>{{translate('product_rate')}}</th>
                                <th>{{translate('fix(%)')}}</th>
                                <th>{{translate('admin_rate')}}</th>
                                <th>
                                <div class="row form-group"     style="margin-bottom: 2px;">
                                     {{translate('shreeveg Price')}} 
                                    <!-- <label for=""> (price):</label><input type="text" class="form-control" style="width: 50px;height: 32px;">
                                    <label for=""> (unit):</label><input type="text" class="form-control" style="width: 50px;height: 32px;">-->
                                    <label for=""> (discount):</label><input type="text" class="form-control" value="3" style="width: 50px;height: 32px;"> 
                                    </div>
                                </th>
                                <th>
                                <div class="row form-group"     style="margin-bottom: 2px;">
                                     {{translate('shreeveg Price')}} 
                                    <!-- <label for=""> (price):</label><input type="text" class="form-control" style="width: 50px;height: 32px;">
                                    <label for=""> (unit):</label><input type="text" class="form-control" style="width: 50px;height: 32px;">-->
                                    <label for=""> (discount):</label><input type="text" class="form-control"  value="6" style="width: 50px;height: 32px;">
                                    </div>
                                </th>
                                <th>
                                     <div class="row form-group"     style="margin-bottom: 2px;">
                                     {{translate('shreeveg Price')}} 
                                    <!-- <label for=""> (price):</label><input type="text" class="form-control" style="width: 50px;height: 32px;">
                                    <label for=""> (unit):</label><input type="text" class="form-control" style="width: 50px;height: 32px;">-->
                                    <label for=""> (discount):</label><input type="text" class="form-control"  value="9" style="width: 50px;height: 32px;"> 
                                    </div>
                                </th>
                               
                            </tr>
                        </thead>

                        <tbody id="set-rows">
                        @foreach($products as $product)
                        <?php $warehouse_id = auth('admin')->user()->warehouse_id; 
                        ?>
                            @php($whProduct =  Helpers::warehouseProductData($product->id))
                            <tr>
                                <td class="pt-1 pb-3 ">{{$product->id}}</td>
                                
                                <td class="pt-1 pb-3 ">
                                    <div class="max-85 text-right">
                                        {{ $product['name'] }}
                                    </div>
                                </td>
                                <td class="pt-1 pb-3 ">
                                    <div class="row">
                                    <input type="text" class="form-control" style="width: 70px;">
                                    <select name="" id="" class="form-control" style="width: 70px;">
                                    <option value="">Select Unit</option>
                                    @foreach(\App\Model\Unit::get() as $unit)
                                    <option value="{{$unit->id}}">{{$unit->title}}</option>
                                    @endforeach
                                    </select>
                                    </div>
                                    <!-- {{@$whProduct->customer_price}}/ {{@$product->unit->title}} -->
                                </td>
                                <td class="pt-1 pb-3 ">
                               <span class="product_rate"> {{@$whProduct->avg_price}}</span>{{@$product->unit->title}}
                                </td>
                                
                                <td class="pt-1 pb-3 ">
                                    <input type="text" class="form-control discount" id="discount">
                                </td>
                                
                                <td class="pt-1 pb-3 ">
                                    <input type="text" class="form-control offer_rate " name="offer_rate[]">
                                </td>
                                <td class="pt-1 pb-3 ">
                               <div class="row">     <input type="text" class="form-control offer_rate " name="" style="width: 70px;">
                                    <input type="text" class="form-control offer_rate " name="discount[]" value='3' style="width: 70px;"></div>
                                </td>
                                <td class="pt-1 pb-3 ">
                               <div class="row">     <input type="text" class="form-control offer_rate " name="" style="width: 70px;">
                                    <input type="text" class="form-control offer_rate " name="discount[]" value='6' style="width: 70px;"></div>
                                </td>
                                <td class="pt-1 pb-3 ">
                               <div class="row">     <input type="text" class="form-control offer_rate " name="" style="width: 70px;">
                                    <input type="text" class="form-control offer_rate " name="discount[]" value='9' style="width: 70px;"></div>
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
<script>
$(document).on("input", "#discount", function () {
    var discount = parseFloat($(this).val()); // Parse discount as a floating-point number
    console.log(discount);
    
    var row = $(this).closest('tr');
    var product_rate = parseFloat($('.product_rate').text()); // Use parseFloat to convert the product_rate to a number

    var offerPrice = product_rate + (product_rate * discount / 100);

    var quantityInput = row.find('input[name="offer_rate[]"]');

    // Set the offer price value
    quantityInput.val(offerPrice.toFixed(2)); // Use toFixed to round the result to 2 decimal places and set the value
});
$('.footer').addClass('d-none');
    </script>
    @endpush
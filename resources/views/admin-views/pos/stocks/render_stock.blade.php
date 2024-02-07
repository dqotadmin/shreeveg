<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-borderless table-thead-bordered product--desc-table">
            <thead class="thead-light">
                <tr>
                    <th class="px-4 border-0">
                        <h4 class="m-0 text-capitalize">{{translate('#')}}</h4>
                    </th>

                    <th class="px-4 border-0">
                        <h4 class="m-0 text-capitalize">{{translate('product_name')}}</h4>
                    </th>
                    <th id="store_id" class="d-none">{{translate('store_id')}}</th>
                           
                    <th id="stock" class=<?php   ?>>{{translate('stock')}}</th>


                </tr>
            </thead>
            <tbody>
              
                
                @if(isset($data) &&  count($data) > 0)
                @foreach($data as $key=>$entry)
                <tr>
                   
                    <td>{{$key+1 }}</td>
                    <td>{{ @$entry->productDetail->name}}</td>
                    
                    <td>{{ @$entry['total_stock'] }}</td>
                </tr>
                @endforeach
                @else
                <tr  class="align-items-center">
                    <td  colspan="12" style="text-align: center;">
                <p>No product details available.</p>

                    </td>
                </tr>
                @endif
            </tbody>
        </table>
       
       </div>
       </div>
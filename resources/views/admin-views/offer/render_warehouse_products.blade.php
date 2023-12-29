
<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-borderless table-thead-bordered product--desc-table">
            <thead class="thead-light">
                <tr>
                    <th class="px-4 border-0">
                        <h4 class="m-0 text-capitalize">{{translate('Warehouse')}}</h4>
                    </th>
                    
                    <th class="px-4 border-0">
                        <h4 class="m-0 text-capitalize">{{translate('Market Price')}}</h4>
                    </th>
                    <th class="px-4 border-0">
                        <h4 class="m-0 text-capitalize">{{translate('Stock')}}</h4>
                    </th>
                    

                </tr>
            </thead>
            <tbody>
              
                
                @if(isset($products) &&  count($products) > 0)
               
                @foreach($products as $entry)
                <tr>
                    <td>{{ $entry->warehouseDetail['name'] }}</td>
                    
                    <td>{{ @$entry['customer_price'] }}</td>
                    <td>{{ @$entry['total_stock'] }} {{$entry->unit['title']}}</td>
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
       
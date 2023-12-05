<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-borderless table-thead-bordered product--desc-table">
            <thead class="thead-light">
                <tr>
                    <th class="px-4 border-0">
                        <h4 class="m-0 text-capitalize">{{translate('Quantity')}}</h4>
                    </th>
                    <th class="px-4 border-0">
                        <h4 class="m-0 text-capitalize">{{translate('Unit')}}</h4>
                    </th>
                    <th class="px-4 border-0">
                        <h4 class="m-0 text-capitalize">{{translate('Default Price/Offer Price')}}</h4>
                    </th>
                    <th class="px-4 border-0">
                        <h4 class="m-0 text-capitalize">{{translate('Approx Piece/Weight')}}</h4>
                    </th>
                    <th class="px-4 border-0">
                        <h4 class="m-0 text-capitalize">{{translate('Short Title')}}</h4>
                    </th>

                </tr>
            </thead>
            <tbody>
              
                
                @if(isset($data) &&  count($data) > 0)
                <?php  $productDetailsArray = json_decode($data[0], true);
                ?>
                @foreach($productDetailsArray as $entry)
                <tr>
                    <td>{{ $entry['quantity'] }}</td>
                    <?php $unitRow = \App\Model\Unit::find($entry['unit_id'])->title ?>
                    <td>{{ @$unitRow }}</td>
                    <td>{{ $entry['offer_price'] }}</td>
                    <td>{{ $entry['approx_piece'] }}</td>
                    <td>{{ $entry['title'] }}</td>
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
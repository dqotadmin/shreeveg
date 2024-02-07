@extends('layouts.admin.app')

@section('title', translate('stock_list'))

@section('content')

    <div class="content container-fluid product-list-page">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/products.png') }}" class="w--24" alt="">
                </span>
                <span>
                    {{ translate('stock_list') }}
                    {{-- <span class="badge badge-soft-secondary">{{ $products->total() }}</span> --}}
                </span>
            </h1>
        </div>
        <?php $stock_limit_row = \App\Model\BusinessSetting::where('key', 'minimum_stock_limit')->first();
        $stock_limit = $stock_limit_row ? $stock_limit_row->value : 0; ?>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header border-0">
                        <div class="card--header justify-content-start max--sm-grow ">

                            @if (in_array($authUser->admin_role_id, [1, 3]))
                                <div class="col-md-3 m-2">
                                    <select name="warehouse_id" id="fetch_warehouse_stock" class=" form-control">
                                        @if ($warehouse_id)
                                            <option value="{{ $warehouse_id }}" id="warehouse_id">
                                                {{ \App\Model\Warehouse::find($warehouse_id)->name }}
                                            </option>
                                        @else
                                            @foreach (\App\Model\Warehouse::where('status', '1')->whereHas('getWarehouseHavingProduct')->where('deleted_at', null)->get() as $warehouse)
                                                <option value="{{ $warehouse->id }}" id="warehouse_id">
                                                    {{ $warehouse->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>

                                </div>
                            @endif

                            <div class="col-md-3 m-2">
                                <select class="form-control store_name" name="store_id" id="fetch_store_stock">
                                    <option disabled selected>--- {{ translate('select') }} {{ translate('Store') }} ---
                                    </option>

                                </select>
                            </div>



                        </div>

                    </div>
                    <!-- Unfold -->

                    <div id="product_detail"></div>

                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
    </div>

@endsection

@push('script_2')
    <script>
        var warehouse_stock_url = '{{ url('/') }}/admin/pos/fetch-warehouse-stock/';
        var store_stock_url = '{{ url('/') }}/admin/pos/fetch-store-stock/';

        $(document).ready(function() {
            var defaultStoreId = $('#fetch_store_stock').val();
            var selectedWarehouseId = $('#fetch_warehouse_stock').val();

            if (selectedWarehouseId) {
                getStore(selectedWarehouseId);
                fetchStockData(warehouse_stock_url, selectedWarehouseId);
            }
            if (defaultStoreId) {

                fetchStockData(store_stock_url, defaultStoreId);
            }
        });

        // Handle the change event for the select element
        $('#fetch_store_stock').on('change', function() {
            var storeId = $(this).val();
            fetchStockData(store_stock_url, storeId);
        });

        $('#fetch_warehouse_stock').on('change', function() {

            var warehouse_id = $(this).val();
            fetchStockData(warehouse_stock_url, warehouse_id);

        });

        function fetchStockData(get_url, id) {

            if (get_url && id) {
                $.ajax({
                    url: get_url + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#product_detail').html(data.view);
                    }
                });
            }
        }


        $('select[name="warehouse_id"]').on('change', function() {
            var warehouse_id = $(this).val();
            getStore(warehouse_id);

        });

        function getStore(warehouse_id) {

            if (warehouse_id > 0) {
                console.log(warehouse_id);
                //alert(warehouse_id);
                $.ajax({
                    url: '{{ url('/') }}/admin/report/stores/' + warehouse_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        $('.store_name').empty();
                        $('.store_name').append(
                            '<option value="" disabled selected>--- Select Store ---</option>');
                        $.each(data.stores, function(key, value) {
                            $('.store_name').append('<option value="' + value.id + '" >' + value.name +
                                '</option>');
                        });

                    }
                });
            }
        }
    </script>
@endpush

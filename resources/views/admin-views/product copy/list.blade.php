@extends('layouts.admin.app')

@section('title', translate('Product List'))
@push('css_or_js')

 
@endpush

@section('content')
    <div class="content container-fluid product-list-page">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/products.png')}}" class="w--24" alt="">
                </span>
                <span>
                    {{ translate('product List') }}
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
                        <div class="card--header justify-content-end max--sm-grow">
                            <form action="{{url()->current()}}" method="GET" class="mr-sm-auto">
                                <div class="input-group">
                                    <input id="datatableSearch_" type="search" name="search"
                                        class="form-control"
                                        placeholder="{{translate('Search_by_ID_or_name')}}" aria-label="Search"
                                        value="{{$search}}" autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="submit" class="input-group-text">
                                            {{translate('search')}}
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <!-- Unfold -->
                            <div class="hs-unfold mr-2">
                                <!-- <a class="js-hs-unfold-invoker btn btn-sm btn-outline-primary-2 dropdown-toggle min-height-40" href="javascript:;"
                                    data-hs-unfold-options='{
                                            "target": "#usersExportDropdown",
                                            "type": "css-animation"
                                        }'>
                                    <i class="tio-download-to mr-1"></i> {{ translate('export') }}
                                </a> -->

                                <div id="usersExportDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                            <!--      
                                          <span class="dropdown-header">{{ translate('options') }}</span>
                                    <a id="export-copy" class="dropdown-item" href="javascript:;">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('public/assets/admin') }}/svg/illustrations/copy.svg"
                                            alt="Image Description">
                                        {{ translate('copy') }}
                                    </a>
                                    <a id="export-print" class="dropdown-item" href="javascript:;">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('public/assets/admin') }}/svg/illustrations/print.svg"
                                            alt="Image Description">
                                        {{ translate('print') }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <span class="dropdown-header">{{ translate('download') }}
                                        {{ translate('options') }}</span>
                                    <a id="export-excel" class="dropdown-item" href="{{route('admin.product.bulk-export')}}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('public/assets/admin') }}/svg/components/excel.svg"
                                            alt="Image Description">
                                        {{ translate('excel') }}
                                    </a>
                                    <a id="export-csv" class="dropdown-item" href="javascript:;">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                            alt="Image Description">
                                        .{{ translate('csv') }}
                                    </a>
                                    <a id="export-pdf" class="dropdown-item" href="javascript:;">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('public/assets/admin') }}/svg/components/pdf.svg"
                                            alt="Image Description">
                                        {{ translate('pdf') }}-->
                            <!--                                    </a>-->
                                </div>
                            </div>
                            <!-- End Unfold -->
                            <!-- <div>
                                <a href="{{route('admin.product.limited-stock')}}" class="btn btn--primary-2 min-height-40">{{translate('limited stocks')}}</a>
                            </div> -->
                            @if( auth('admin')->user()->admin_role_id == 1 )
                                <div>
                                    <a href="{{route('admin.product.add-new')}}" class="btn btn-primary min-height-40 py-2">
                                        <i class="tio-add"></i>
                                        {{translate('add new product')}}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
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
                                <th>{{translate('product_code')}}</th>
                                <th>{{translate('product_category')}}</th>
                                
                                @if(in_array(auth('admin')->user()->admin_role_id ,[3,5]))
                                    <th class="">{{translate('stock')}}</th>
                               
                                @else
                                    <th class="text-center">{{translate('status')}}</th>
                                    <th data-searchable="true"  id="column3_search">{{translate('sequence')}}</th>
                                @endif
                                @if(in_array(auth('admin')->user()->admin_role_id ,[3]))
                                <th class="">{{translate('status')}}</th>
                                <th class="text-center">{{translate('show_in_daily_needs')}}</th>
                                @endif

                                @if(auth('admin')->user()->admin_role_id != 5)
                                <th class="text-center">{{translate('action')}}</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($products as $key=>$product)
                                <tr>
                                    <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">{{$products->firstItem()+$key}}</td>
                                    <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">
                                        <a @if(auth('admin')->user()->admin_role_id == '1') href="{{route('admin.product.view',[$product['id']])}}" @endif class="product-list-media">
                                            @if (!empty(json_decode($product['image'],true)))
                                        <img
                                            src="{{asset('storage/app/public/product')}}/{{json_decode($product['image'],true)[0]}}"
                                            onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'">
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
                                            foreach(\App\Model\WarehouseProduct::where('warehouse_id',auth('admin')->user()->warehouse_id)->where('product_id',$product->id)->get() as $stock){
                                                $current_stock =  0;

                                                    if($stock->total_stock > 0){
                                                        $current_stock =  $stock->total_stock;
                                                    }
                                                    //echo $current_stock;
                                                    ?>
                                                    @if($current_stock <= $stock_limit)
                                                    <span class="text-danger">  {{$current_stock}} /({{@$product->unit['title'] }})</span>
                                                    @else
                                                    {{$current_stock}} /({{@$product->unit['title'] }})
                                                    @endif
                                                    <?php $warehouse_id = auth('admin')->user()->warehouse_id; $product_id = $product['id'];
                                                if(\App\Model\WarehouseProduct::where('warehouse_id',$warehouse_id)->where('product_id',$product_id)->exists('product_details')){
                                                } 
                                            }
                                            ?> 
                                        </td>
                              
                                     
                                      
                                      
                                    @else
                                        <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">
                                            <label class="toggle-switch my-0">
                                                <input type="checkbox"
                                                    onclick="status_change_alert('{{ route('admin.product.status', [$product->id, $product->status ? 0 : 1]) }}', '{{ $product->status? translate('you want to disable this product'): translate('you want to active this product') }}', event)"
                                                    class="toggle-switch-input" id="stocksCheckbox{{ $product->id }}"
                                                    {{ $product->status ? 'checked' : '' }}>
                                                <span class="toggle-switch-label mx-auto text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </td>
                                        <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">
                                            <input type="text" name="sequence" class="form-control w-50" value="{{ $product->sequence }}"
                                            oninput="updateSequence('{{ route('admin.product.update-sequence', ['id' => $product->id]) }}', this.value)" 
                                            id="old_val{{$product->id}}">
                                            <input type="hidden" class="form-control" id="product_id" value="{{$product->id}}" style="width: 70px;">
                                        </td>
                                    @endif
                                @if(in_array(auth('admin')->user()->admin_role_id ,[3]))
                                        <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">
                                            <?php
                                            foreach(\App\Model\WarehouseProduct::where('warehouse_id',auth('admin')->user()->warehouse_id)->where('product_id',$product->id)->get() as $stock){
                                            ?>
                                            
                                                <label class="toggle-switch my-0">
                                                    <input type="checkbox"
                                                        onclick="status_change_alert('{{ route('admin.product.status', [$stock->id, $stock->status ? 0 : 1]) }}', '{{ $stock->status? translate('you want to disable this product'): translate('you want to active this product') }}', event)"
                                                        class="toggle-switch-input" id="stocksCheckbox{{ $product->id }}"
                                                        {{ $stock->status ? 'checked' : '' }}>
                                                    <span class="toggle-switch-label mx-auto text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                                <?php
                                                }
                                            ?> 
                                        </td>
                                        <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">
                                        <div class="text-center">
                                            <label class="switch my-0">
                                                <input type="checkbox" class="status" {{ auth('admin')->user()->admin_role_id == 3 ? 'disabled' : '' }}  onchange="daily_needs('{{$product['id']}}','{{$product->daily_needs==1?0:1}}')"
                                                    id="{{$product['id']}}" {{$product->daily_needs == 1?'checked':''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        </td>
                                @endif
                                    
                                    @if(auth('admin')->user()->admin_role_id != 5)
                                    <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">
                                            <!-- Dropdown -->
                                            <div class="btn--container justify-content-center">
                                            @if( auth('admin')->user()->admin_role_id == 1 )
                                                <a class="action-btn   btn-outline-info" href="{{route('admin.product.view',[$product['id']])}}">
                                                    <i class="tio-invisible"></i>
                                                </a>
                                                <a class="action-btn"  href="{{route('admin.product.edit',[$product['id']])}}">
                                                    <i class="tio-edit"></i></a>
                                                <a class="action-btn btn--danger btn-outline-danger" href="javascript:" onclick="form_alert('product-{{$product['id']}}','{{ translate("Want to delete this") }}')">
                                                    <i class="tio-delete-outlined"></i>
                                                </a>
                                                <form action="{{route('admin.product.delete',[$product['id']])}}"
                                                        method="post" id="product-{{$product['id']}}">
                                                    @csrf @method('delete')
                                                </form>
                                                @endif
                                                @if( auth('admin')->user()->admin_role_id == 3 )

                                                <a class="action-btn"  href="{{route('admin.product.warehouse-edit',[$product['id']])}}">
                                                <i class="tio-money"></i></a>
                                                @endif

                                            <!-- End Dropdown -->
                                             
                                            </div>
                                        </td>
                                        @endif
                                
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

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

@push('script_2')
 
<script>
   function updateSequence(url, sequence) {
     fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ sequence }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(data.success); // Handle success response as needed
                Swal.fire({
                            title: 'Alert',
                            html: data.success,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
            } else if (data.error) {
                Swal.fire({
                            title: 'Alert',
                            html: data.error,
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                    // Reload the page after the user clicks "OK"
                        location.reload(true);
                });

                            // $('input[name="sequence"]').val(4);
                        // document.querySelector('input[name="sequence"]').value = 12;
            }
        })
      
    }
</script>
<script>
        function status_change_alert(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ translate("Are you sure?") }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#107980',
                cancelButtonText: '{{ translate("No") }}',
                confirmButtonText: '{{ translate("Yes") }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = url;
                }
            })
        }
</script>

<script>
    function featured_status_change_alert(url, message, e) {
        e.preventDefault();
        Swal.fire({
            title: '{{ translate("Are you sure?") }}',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#107980',
            cancelButtonText: '{{ translate("No") }}',
            confirmButtonText: '{{ translate("Yes") }}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = url;
            }
        })
    }
</script>
<script>
    $('#search-form').on('submit', function () {
        var formData = new FormData(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post({
            url: '{{route('admin.product.search')}}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                $('#set-rows').html(data.view);
                $('.page-area').hide();
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    });
</script>

<script>
    function daily_needs(id, status) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{route('admin.product.daily-needs')}}",
            method: 'POST',
            data: {
                id: id,
                status: status
            },
            success: function () {
                toastr.success('{{ translate("Daily need status updated successfully") }}');
            }
        });
    }
</script>
    <script>
        $(document).on('ready', function () {
    // INITIALIZATION OF DATATABLES
    var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

    // Add searching functionality for the "Sequence" column
    $('#column3_search').on('keyup', function () {
        datatable
            .columns(5) // "Sequence" column index is 1
            .search(this.value)
            .draw();
    });

    // Add searching functionality for the "Status" column
    $('#column1_search').on('change', function () {
        var statusValue = this.value;
        datatable
            .columns(4) // "Status" column index is 0
            .search(statusValue)
            .draw();
    });

    // ... (existing code)

    // INITIALIZATION OF SELECT2
    $('.js-select2-custom').each(function () {
        var select2 = $.HSCore.components.HSSelect2.init($(this));
    });
});
    </script>
@endpush

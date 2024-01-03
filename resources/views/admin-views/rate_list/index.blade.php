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
                    <div class="card--header justify-content-end max--sm-grow">
                        <form action="{{url()->current()}}" method="GET" class="mr-sm-auto">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="search" class="form-control" placeholder="{{translate('Search_by_ID_or_name')}}" aria-label="Search" value="{{$search}}" required autocomplete="off">
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text">
                                        {{translate('search')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-header border-0">
                    <div class="card--header max--sm-grow">
                        <div class="row">
                            <div class="col-sm-12" id="category_box">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('Select Parent Category')}}</label>
                                    <select name="parent_id" id="parent_id" class="form-control">
                                        <option value="">---{{translate('Select Parent Category')}}---</option>
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
                    <table id="columnSearchDatatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table" data-hs-datatables-options='{
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
                                        <!-- @if (!empty(json_decode(@$product['image'],true)))
                                        <img src="{{asset('storage/app/public/product')}}/{{json_decode(@$product['image'],true)[0]}}"
                                            onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'">
                                        @else
                                        <img src="{{asset('public/assets/admin/img/400x400/img2.jpg')}}">
                                        @endif -->
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

@push('script')

@endpush
@push('script_2')



<script>
    $('#parent_id').on('change', function() {
        cat_id = $(this).val();
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
            },
        });
    });
</script>
@endpush
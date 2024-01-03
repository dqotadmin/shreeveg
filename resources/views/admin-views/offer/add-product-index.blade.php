@extends('layouts.admin.app')

@section('title', translate('flash_sale_product'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/flash_sale.png')}}" class="w--20" alt="">
                </span>
                <span>
                    {{translate('flash deal product')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0 text-capitalize">{{$flash_deal['title']}}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.offer.flash.add-product',[$flash_deal['id']])}}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="name" class="title-color text-capitalize">{{ translate('Add new product')}}</label>
                                        <select class="js-example-basic-multiple js-states js-example-responsive form-control h--45px productSelect"  name="product_id">
                                            <option disabled selected>{{ translate('Select Product')}}</option>
                                            @foreach ($products as $key => $product)
                                                <option value="{{ $product->id }}">
                                                    {{$product['name']}} ({{@$product->unit->title}})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if($flash_deal->offer_type == 'other')
                                    <div class="col-md-6 manageQty">
                                        <label for="name" class="title-color text-capitalize">{{ translate('quantity')}}</label>
                                        <input type="text" class="form-control" name="quantity" required>
                                    </div>
                                    <div class="col-md-6 manageQty">
                                        <label for="name" class="title-color text-capitalize">{{ translate('amount')}}</label>
                                        <input type="number" class="form-control" name="amount" required>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div id="product_detail"></div>
                            <div class="d-flex justify-content-end">
                                <div class="btn--container justify-content-end">
                                <a type="button" href="{{route('admin.offer.flash.index')}}" class="btn btn--reset">{{translate('back')}}</a>
                                    <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <h5 class="mb-0 text-capitalize">
                            {{ translate('Product')}} {{ translate('Table')}}
                            <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $flash_deal_products->total() }}</span>
                        </h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100" cellspacing="0">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{ translate('SL')}}</th>
                                <th>{{translate('name')}}</th>
                                @if($flash_deal->offer_type == 'other')
                                <th>{{translate('quantity')}}</th>
                                <th>{{translate('amount')}}</th>
                                @endif
                                <!-- <th>{{ translate('actual_price')}}</th> -->
                                <!-- <th>{{ translate('discount')}}</th> -->
                                <!-- <th>{{ translate('discount_price')}}</th> -->
                                <th class="text-center">{{ translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                          
                            @foreach($datas as $key=>$data)
                           
                            <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$data->product['name']}}</td>
                                    @if($flash_deal->offer_type == 'other')
                                      <td>{{ $data['quantity'] }}</td>
                                      <td>{{ $data['amount'] }}</td>
                                    @endif
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <a  title="{{ trans ('Delete')}}"
                                                class="btn btn-outline-danger btn-sm delete"
                                                id="{{$data['id']}}">
                                                <i class="tio-delete"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <table>
                            <tfoot>
                            {!! $flash_deal_products->links() !!}
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')

    <script>

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    <script>
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{translate('Are_you_sure_remove_this_product')}}?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{translate('Yes')}}, {{translate('delete_it')}}!',
                type: 'warning',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.offer.flash.delete.product')}}",
                        method: 'POST',
                        data: {
                                id: id,
                            },
                        success: function (data) {
                            toastr.success('{{translate('product_removed_successfully')}}');
                             location.reload();
                        },
                    });
                }
            })
        });

        $('.productSelect').on('change',function(){ 
        var product_id = $(this).val();
        var flash_deal_id = "{{$flash_deal->id}}";

        $.ajax({
            url: '{{url('/')}}/admin/offer/flash/product-price/'+flash_deal_id+'/'+product_id,
            type:'GET',
            dataType:'json',
            success:function(data){
                $('#product_detail').html(data.view);

            }
        });
        });
    </script>

@endpush

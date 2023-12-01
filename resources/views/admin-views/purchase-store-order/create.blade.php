@extends('layouts.admin.app')
@section('title', translate('store order'))
@push('css_or_js')
@endpush
@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
            <img src="{{asset('public/assets/admin/img/category.png')}}" class="w--24" alt="">
            </span>
            <span>
            {{translate('store order')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.store.purchase-store-orders.store')}}" method="post"
                enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('title')}}</label>
                                    <input type="text" name="title" class="form-control"
                                        placeholder="{{ translate('title') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="my-4"> Product Details </h3>
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <h5>Category</h5>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <h5>Product</h5>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <h5>Stock</h5>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <h5>Unit</h5>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <h5>Qty</h5>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <h5>Rate</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row g-3">
                    <div class="col-md-8">
                        @foreach($categories as $category)
                        <div class="row g-3">
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <h5>{{$category->getCategory->name }}</h5>
                                </div>
                            </div>
                            <?php //dump($category->category_id);
                            $productsIds = \App\Model\Product::where('category_id',$category->category_id)->pluck('id')->toArray();
                            $products = \App\Model\WarehouseProduct::where('warehouse_id',$wahrehouseId)->whereIn('product_id',$productsIds)->get();
                            ?>
                            @if(count($products) >0)
                            @foreach($products as $key =>$product)
                                @if($key > 0)
                                    <div class="col-md-2"></div>
                                @endif

                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <input type="hidden" name="product_id[]" value="{{$product->id}}">
                                        <input type="text"  value="{{$product->productDetail->name}} " class="form-control"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <input type="text" value="{{$product->total_stock}}" class="form-control"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <select name="unit[]" class="form-control js-select2-custom">
                                            <option value="kg">{{translate('kg')}}</option>
                                            <option value="gm">{{translate('gm')}}</option>
                                            <option value="ltr">{{translate('ltr')}}</option>
                                            <option value="pc" >{{translate('pc')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <input type="text" name="qty[]" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                            placeholder="Qty" >
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <input type="text"  class="form-control" value="{{$product->store_price}}" placeholder="Rate" >
                                    </div>
                                </div>
                            @endforeach
                            @endif

                        </div>
                        @endforeach
                    </div>
                    <div class="col-md-6">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('comments')}}</label>
                        <textarea name="store_comments" class="form-control" rows="6" placeholder="{{ translate('enter comments if any') }}" required></textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="btn--container justify-content-end">
                        <a href="{{route('admin.store.purchase-store-orders.index')}}" type="reset" class="btn btn--reset">
                        {{translate('Back')}}</a>
                        <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('script_2')
@endpush
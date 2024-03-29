@extends('layouts.admin.app')
@section('title', translate('flash_sale'))
@push('css_or_js')
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
            {{translate('flash sale')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{route('admin.offer.flash.store')}}" method="post" id="product_form"
                enctype="multipart/form-data" class="needs-validation form_customer row g-2" novalidate>
                @csrf
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="form-label">{{ translate('Select Offer Type') }}</label>
                        <div class="d-flex flex-wrap align-items-center form-control border">
                            <label class="form-check form--check mr-2 mr-md-4 mb-0">
                            <input type="radio" class="form-check-input offer_type gray-color" name="offer_type"  value="one_rupee"  > 
                            <span class="form-check-label gray-color"> {{ Helpers::set_symbol(1) }}</span>
                            </label>
                            <label class="form-check form--check mb-0">
                            <input type="radio" class="form-check-input offer_type gray-color" name="offer_type"  value="other" checked>
                            <span class="form-check-label gray-color"> {{ translate('Sell') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-3 manageMinPurchase">
                    <div class="form-group mb-0">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('minimum purchase amount')}}</label>
                        <input type="number"  name="min_purchase_amount" value="{{old('min_purchase_amount')}}" class="form-control" placeholder="{{ translate('minimum purchase amount') }}">
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('warehouse')}}</label>
                                    <select name="warehouse_id[]" id="" class="form-control chosen-select custom-select" multiple required> 
                                        @foreach(\App\Model\Warehouse::where('deleted_at',null)->get() as $warehouse)
                                        <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Please enter warehouse.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('title')}}</label>
                                    <input type="text" name="title" value="{{old('title')}}" class="form-control"
                                        placeholder="{{ translate('enter title') }}" maxlength="255" required>
                                        <div class="invalid-feedback">
                                        Please enter title.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{translate('description')}}</label>
                                    <input type="text" name="description" value="{{old('description')}}" class="form-control"
                                        placeholder="{{ translate('enter description') }}" maxlength="255" required>
                                        <div class="invalid-feedback">
                                        Please enter description.
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-0">
                                    <label for="name"
                                        class="title-color font-weight-medium text-capitalize">{{ translate('start_date')}}</label>
                                    <input type="datetime-local" name="start_date" required id="start_date"
                                        class="js-flatpickr form-control flatpickr-custom" placeholder=""  min="<?=date('Y-m-d\Th:i')?>"
                                        data-hs-flatpickr-options=' '>
                                        <div class="invalid-feedback">
                                            Please enter start date.
                                        </div>
                                    <!-- class="js-flatpickr form-control flatpickr-custom" placeholder="{{ \App\CentralLogics\translate('dd/mm/yy') }}" data-hs-flatpickr-options='{"dateFormat": "Y/m/d", "minDate": "today", "enableTime": true, "inline": true, "timeFormat": "h:i K" }' -->
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-0">
                                    <label for="name"
                                        class="title-color font-weight-medium text-capitalize">{{ translate('end_date')}}</label>
                                    <input type="datetime-local" name="end_date" required id="end_date"
                                        class="js-flatpickr form-control flatpickr-custom" placeholder=" " min="<?=date('Y-m-d\Th:i')?>"
                                        data-hs-flatpickr-options=' '>
                                        <div class="invalid-feedback">
                                            Please enter end date.
                                        </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                <label class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control" >
                                    <span class="pr-1 d-flex align-items-center switch--label">
                                    <span class="line--limit-1">
                                        <strong>Send Notification To All User</strong>
                                    </span>
                                        <span class="form-label-secondary text-danger d-flex ml-1" data-toggle="tooltip" data-placement="right" data-original-title="When this field is active  this notification will be visible in  app.">
                                            <img src="http://192.168.29.160/shreeveg/public/assets/admin/img/info-circle.svg" alt="info">
                                        </span>
                                    </span>
                                    <input type="checkbox" name="notification_offer_status" checked class="toggle-switch-input" id="toggle-offer-status">
                                    <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                                </div>
                            </div>
                            <div class="col-12 d-none" id="offer_message">
                                <div class="form-group mb-0">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{translate('notification_offer_message')}}</label>
                                    <input type="text" name="notification_offer_message" value="{{old('notification_offer_message')}}" class="form-control"
                                        placeholder="{{ translate('notification_offer_message') }}" maxlength="500">
                                        <div class="invalid-feedback">
                                        Please enter notification offer message.
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-6 manageType">
                                <div class="form-group mb-0">
                                    <label class="input-label" for="exampleFormControlSelect1">{{translate('discount')}} {{translate('type')}}<span
                                        class="input-label-secondary">*</span></label>
                                    <-- <select name="discount_type" class="form-control" onchange="show_item(this.value)"> -->
                                    <select name="discount_type" class="form-control" >
                                        <option value="percent">{{translate('percent')}}</option>
                                        <option value="amount">{{translate('amount')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 manageType">
                                <div class="form-group mb-0">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('discount_amount')}}</label>
                                    <input type="number"  name="discount_amount" value="{{old('discount_amount')}}" class="form-control" placeholder="{{ translate('discount_amount') }}">
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column justify-content-center h-100">
                            <h5 class="text-center mb-3 text--title text-capitalize">
                                {{translate('image')}}
                                <small class="text-danger">* ( {{translate('ratio')}} 3:1 )</small>
                            </h5>
                            <label class="upload--vertical">
                            <input type="file" name="image" id="customFileEg1" class=""
                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" hidden>
                            <img id="viewer" src="{{asset('public/assets/admin/img/upload-vertical.png')}}"
                                alt="banner image" />
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="btn--container justify-content-end">
                            <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                            <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <!-- Header -->
        <div class="card-header border-0">
            <div class="card--header justify-content-between max--sm-grow">
                <h5 class="card-title">{{translate('Flash Sale List')}} <span
                    class="badge badge-soft-secondary">{{ $flash_deals->total() }}</span></h5>
                <form action="{{url()->current()}}" method="GET">
                    <div class="input-group">
                        <input type="search" name="search" class="form-control"
                            placeholder="{{translate('Search_by_flash_sale_title')}}" aria-label="Search"
                            value="{{$search}}" required autocomplete="off">
                        <div class="input-group-append">
                            <button type="submit" class="input-group-text">
                            {{translate('search')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Header -->
        <!-- Table -->
        <div class="table-responsive datatable-custom">
            <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                <thead class="thead-light">
                    <tr>
                        <th class="border-0">{{translate('#')}}</th>
                        <th class="border-0">{{translate('image')}}</th>
                        <th class="border-0">{{translate('type')}}</th>
                        <th class="border-0">{{translate('title')}}</th>
                        <th class="border-0">{{translate('min. purchase')}}</th>
                        <th class="border-0">{{translate('duration')}}</th>
                        <th class="border-0">{{translate('status')}}</th>
                        <th class="border-0">{{translate('publish')}}</th>
                        <th class="text-center border-0">{{translate('active_products')}}</th>
                        <th class="text-center border-0">{{translate('action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($flash_deals as $key=>$flash_deal)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>
                            <div>
                                <img class="img-vertical-150"
                                    src="{{asset('storage/app/public/offer')}}/{{$flash_deal['image']}}"
                                    onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'">
                            </div>
                        </td>
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                            {{($flash_deal['offer_type']=='other')?'Sell':'1.00₹'}}
                            </span>
                        </td>
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                            {{$flash_deal['title']}}
                            </span>
                        </td>

                        <td>
                            <span class="d-block font-size-sm text-body text-trim-25">
                            {{$flash_deal['min_purchase_amount']}}
                            </span>
                        </td>
                        
                        <td>{{date('d-M-y',strtotime($flash_deal['start_date']))}} -
                            {{date('d-M-y',strtotime($flash_deal['end_date']))}} <br>
                            {{date('h:i A',strtotime($flash_deal['start_date']))}} &nbsp;&nbsp;
                            {{date('h:i A',strtotime($flash_deal['end_date']))}}
                        </td>
                        <td>
                            @if(\Carbon\Carbon::parse($flash_deal['end_date'])->endOfDay()->isPast())
                            <span class="badge badge-soft-danger">{{ translate('expired')}} </span>
                            @else
                            <span class="badge badge-soft-success"> {{ translate('active')}} </span>
                            @endif
                        </td>
                        <td>
                            <label class="toggle-switch my-0">
                            @if(!(\Carbon\Carbon::parse($flash_deal['end_date'])->endOfDay()->isPast()))
                            <input type="checkbox"
                            onclick="status_change_alert('{{ route('admin.offer.flash.status', [$flash_deal->id, $flash_deal->status ? 0 : 1]) }}', '{{ $flash_deal->status? translate('you_want_to_disable_this_deal'): translate('you_want_to_active_this_deal') }}', event)"
                            class="toggle-switch-input" id="stocksCheckbox{{ $flash_deal->id }}"
                            {{ $flash_deal->status ? 'checked' : '' }}>
                            <span class="toggle-switch-label mx-auto text">
                            <span class="toggle-switch-indicator"></span>
                            </span>
                            </label>
                            @endif
                        </td>
                        <td class="text-center">{{ $flash_deal->products_count }}</td>
                        <td>
                            <!-- Dropdown -->
                            <div class="btn--container justify-content-center">
                                <a class="h-30 d-flex gap-2 align-items-center btn btn-soft-info btn-sm border-info"
                                    href="{{route('admin.offer.flash.add-product',[$flash_deal['id']])}}">
                                <img src="{{asset('/public/assets/back-end/img/plus.svg')}}" class="svg" alt="">
                                {{translate('Add Product')}}
                                </a>
                                <a class="action-btn" href="{{route('admin.offer.flash.edit',[$flash_deal['id']])}}">
                                <i class="tio-edit"></i></a>
                                <a class="action-btn btn--danger btn-outline-danger" href="javascript:"
                                onclick="form_alert('deal-{{$flash_deal['id']}}','{{ translate("Want to delete this") }}')">
                                <i class="tio-delete-outlined"></i>
                                </a>
                            </div>
                            <form action="{{route('admin.offer.flash.delete',[$flash_deal['id']])}}" method="post"
                                id="deal-{{$flash_deal['id']}}">
                                @csrf @method('delete')
                            </form>
                            <!-- End Dropdown -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <table>
                <tfoot>
                    {!! $flash_deals->links() !!}
                </tfoot>
            </table>
        </div>
        @if(count($flash_deals) == 0)
        <div class="text-center p-4">
            <img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}"
                alt="Image Description">
            <p class="mb-0">{{translate('No_data_to_show')}}</p>
        </div>
        @endif
        <!-- End Table -->
    </div>
</div>
@endsection
@push('script_2')
<script>
    $(document).ready(function(){
        $('#offer_message').removeClass('d-none');
        $('#toggle-offer-status').change(function(){
            if($(this).prop('checked')){
                $('#offer_message').removeClass('d-none');
            }else{
                $('#offer_message').addClass('d-none');
            }
    });
    });
</script>
<script>
    $(document).on('ready', function() {
        $('.manageMinPurchase').hide();
        $('input[name="min_purchase_amount"]').removeAttr('required');
        // INITIALIZATION OF FLATPICKR
        // =======================================================
        $('.js-flatpickr').each(function() {
            $.HSCore.components.HSFlatpickr.init($(this));
        });
    });
    
    $('input[type=radio][name=offer_type]').change(function() {
        if (this.value == 'one_rupee') {
            $('input[name="min_purchase_amount"]').prop('required', true)
            $('.manageType').hide();
            $('.manageMinPurchase').show();
        } else {
            $('input[name="min_purchase_amount"]').removeAttr('required');
            $('.manageType').show();
            $('.manageMinPurchase').hide();
        }
    });
    
    $('#start_date,#end_date').change(function() {
        let fr = $('#start_date').val();
        let to = $('#end_date').val();
        if (fr != '' && to != '') {
            if (fr > to) {
                $('#start_date').val('');
                $('#end_date').val('');
                toastr.error('Invalid date range!', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        }
    });
    
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function(e) {
                $('#viewer').attr('src', e.target.result);
            }
    
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#customFileEg1").change(function() {
        readURL(this);
    });
</script>
<script>
    function status_change_alert(url, message, e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#107980',
            cancelButtonText: 'No',
            confirmButtonText: 'Yes',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = url;
            }
        })
    }
</script>

@endpush
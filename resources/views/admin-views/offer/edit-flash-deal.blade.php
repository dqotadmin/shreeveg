@extends('layouts.admin.app')

@section('title', translate('flash_deal'))

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
                {{translate('flash deal update')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{route('admin.offer.flash.update', [$flash_deal['id']])}}" method="post" id="product_form" enctype="multipart/form-data" class="needs-validation form_customer row g-2" novalidate>
                @csrf
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="form-label">{{ translate('Select Offer Type') }}</label>
                        <div class="d-flex flex-wrap align-items-center form-control border">
                            <label class="form-check form--check mr-2 mr-md-4 mb-0">
                                <input type="radio" class="form-check-input offer_type" name="offer_type" value="one_rupee" {{$flash_deal['offer_type'] == 'one_rupee'?'checked':''}}>
                                <span class="form-check-label"> {{ Helpers::set_symbol(1) }}</span>
                            </label>
                            <label class="form-check form--check mb-0">
                                <input type="radio" class="form-check-input offer_type" name="offer_type" value="other" {{$flash_deal['offer_type'] == 'other'?'checked':''}}>
                                <span class="form-check-label"> {{ translate('Other') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-3 manageMinPurchase">
                    <div class="form-group mb-0">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('minimum purchase amount')}}</label>
                        <input type="number" name="min_purchase_amount" value="{{$flash_deal['min_purchase_amount']}}" class="form-control" placeholder="{{ translate('minimum purchase amount') }}">
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
                                        <option value="{{$warehouse->id}}" @if(in_array($warehouse->id, $flash_deal['warehouse_id'])) selected @endif>{{$warehouse->name}}</option>
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
                                    <input type="text" name="title" value="{{$flash_deal['title']}}" class="form-control" placeholder="{{ translate('enter title') }}" maxlength="255" required>
                                    <div class="invalid-feedback">
                                        Please enter title.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('description')}}</label>
                                    <input type="text" name="description" class="form-control" value="{{$flash_deal['description']}}" placeholder="{{ translate('enter description') }}" maxlength="255" required>
                                    <div class="invalid-feedback">
                                        Please enter description.
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-0">
                                    <label for="name" class="title-color font-weight-medium text-capitalize">{{ translate('start_date')}}</label>
                                    <input type="datetime-local" name="start_date" required id="start_date" class=" form-control flatpickr-custom" value="{{ date('Y-m-d\TH:i', strtotime($flash_deal['start_date'])) }}">
                                    <div class="invalid-feedback">
                                        Please enter start date.
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-0">
                                    <label for="name" class="title-color font-weight-medium text-capitalize">{{ translate('end_date')}}</label>
                                    <input type="datetime-local" name="end_date" required id="end_date" min="<?= date('Y-m-d\Th:i') ?>" class=" form-control flatpickr-custom" value="{{ date('Y-m-d\TH:i', strtotime($flash_deal['end_date'])) }}">
                                    <div class="invalid-feedback">
                                        Please enter start date.
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
                                    <input type="checkbox" name="notification_offer_status" {{$flash_deal['notification_offer_status'] == 1? 'checked': ''}} class="toggle-switch-input" id="toggle-offer-status">
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
                                    <input type="text" name="notification_offer_message" value="{{$flash_deal['notification_offer_message']}}" class="form-control"
                                        placeholder="{{ translate('notification_offer_message') }}" maxlength="500">
                                        <div class="invalid-feedback">
                                        Please enter notification offer message.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-column justify-content-center h-100">
                                <h5 class="text-center mb-3 text--title text-capitalize">
                                    {{translate('image')}}
                                    <small class="text-danger">* ( {{translate('ratio')}} 3:1 )</small>
                                </h5>
                                <label class="upload--vertical">
                                    <input type="file" name="image" id="customFileEg1" class="" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" hidden>
                                    <img id="viewer" onerror="{{asset('public/assets/admin/img/upload-vertical.png')}}" src="{{asset('storage/app/public/offer')}}/{{$flash_deal['image']}}" alt="offer image" alt="image" />
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="btn--container justify-content-end">
                                <a type="button" href="{{route('admin.offer.flash.index')}}" class="btn btn--reset">{{translate('back')}}</a>
                                <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script_2')
<script>
  
    $(document).ready(function(){
        var notificationOfferStatus = <?php echo $flash_deal['notification_offer_status']; ?>;

        // Set initial visibility based on notification_offer_status
        if (notificationOfferStatus == 1) {
            $('#offer_message').removeClass('d-none');
        } else {
            $('#offer_message').addClass('d-none');
        }

        // Toggle visibility on checkbox change
        $('#toggle-offer-status').change(function(){
            if($(this).prop('checked')){
                $('#offer_message').removeClass('d-none');
            } else {
                $('#offer_message').addClass('d-none');
            }
        });
    });

</script>
<script>
    $(document).on('ready', function() {
        var offerType = "{{$flash_deal['offer_type']}}";
        if (offerType == 'one_rupee') {
            $('.manageType').hide();
            $('input[name="min_purchase_amount"]').prop('required', true)
        } else {
            $('.manageMinPurchase').hide();
            $('.manageType').show();
            $('input[name="min_purchase_amount"]').removeAttr('required');
        }
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
    // $('#start_date,#end_date').change(function () {
    //     let fr = $('#start_date').val();
    //     let to = $('#end_date').val();
    //     if (fr != '' && to != '') {
    //         if (fr > to) {
    //             $('#start_date').val('');
    //             $('#end_date').val('');
    //             toastr.error('Invalid date range!', Error, {
    //                 CloseButton: true,
    //                 ProgressBar: true
    //             });
    //         }
    //     }
    // });

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
@extends('layouts.admin.app')

@section('title', translate('Add new delivery-man'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{asset('public/assets/admin/img/employee.png')}}" class="w--24" alt="mail">
            </span>
            <span>
                {{translate('add new Customer')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->

    <form action="{{route('admin.pos.customer.store')}}" method="post" enctype="multipart/form-data" class="needs-validation form_customer" novalidate>
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <span class="card-header-icon">
                        <i class="tio-user"></i>
                    </span> {{translate('General Information')}}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{translate('First Name')}}</label>
                                <input type="text" name="f_name" value="{{ old('f_name') }}" class="form-control" style="text-transform: capitalize;"
                                    placeholder="{{translate('Ex : First Name')}}" required>
                                    <div class="invalid-feedback">
                                        Please enter first name.
                                    </div>
                            </div>
                            <div class="col-md-12">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{translate('Last Name')}}</label>
                                <input type="text" name="l_name" value="{{ old('l_name') }}" class="form-control"style="text-transform: capitalize;"
                                    placeholder="{{translate('Ex : Last Name')}}" required>
                                    <div class="invalid-feedback">
                                        Please enter last name.
                                    </div>
                            </div>
                        <div class="col-md-12">
                            <div>
                                <label class="input-label" for="exampleFormControlInput1">{{translate('phone')}}</label>
                                <input type="number" pattern="[^e]*"  pattern="[0-9]*" name="phone" value="{{ old('phone') }}" class="form-control" id="phoneInput"
                                    placeholder="{{ translate('Ex : 017********') }}" required pattern="\d*" required>
                                    <div class="invalid-feedback">
                                        Please enter phone number.
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label d-none d-md-block ">
                            &nbsp;
                        </label>
                        <center class="mb-4">
                            <img class="initial-24" id="viewer" style="border: 0.0625rem solid #e7eaf3;"
                                src="{{asset('public/assets/admin/img/upload-vertical.png')}}"
                                alt="Deliveryman thumbnail" />
                        </center>
                        <div class="form-group mb-0">
                            <label class="form-label d-block">
                                {{ translate('Customer Image') }} <span
                                    class="text-danger">{{ translate('(Ratio 1:1)') }}</span>
                            </label>
                            <div class="custom-file">
                                <input type="file" name="image" id="customFileUpload" class="custom-file-input h--45px" style="border: 0.0625rem solid #e7eaf3;"
                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"  >
                                <label class="custom-file-label h--45px" style="border: 0.0625rem solid #e7eaf3;" for="customFileUpload"></label>
                            </div>
                        </div>
                    </div>
                  
                </div>
            </div>
        </div>
</div>


<div class="card mt-3">
    <div class="card-header">
        <h5 class="card-title">
            <span class="card-header-icon">
                <i class="tio-user"></i>
            </span> {{translate('Account Information')}}
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4 col-sm-6">
                <label class="input-label" for="exampleFormControlInput1">{{translate('email')}}</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                    placeholder="{{ translate('Ex : ex@example.com') }}" required>
                    <div class="invalid-feedback">
                        Please enter email-id.
                    </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <label class="input-label" for="exampleFormControlInput1">{{translate('password')}}</label>
                <input type="text" name="password" class="form-control" placeholder="{{ translate('7+ Characters') }}"
                    required>
                    <div class="invalid-feedback">
                        Please enter password.
                    </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <label class="input-label" for="exampleFormControlInput1">{{translate('Confirm password')}}</label>
                <input type="text" name="confirm_password" class="form-control"
                    placeholder="{{ translate('7+ Characters') }}" required>
                    <div class="invalid-feedback">
                        Please enter confirm password.
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="btn--container justify-content-end mt-3">
    <a type="button" href="{{route('admin.customer.list')}}" class="btn btn--reset">{{translate('back')}}</a>
    <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
</div>

</form>
  







@endsection

@push('script_2')
<script>
    $(document).ready(function () {
        $('#phoneInput').on('input', function () {
            $(this).val(function (_, val) {
                return val.replace(/\D/g, ''); // Allow only numeric characters
            });
        });
    });
</script>
<script>
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#viewer').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#customFileUpload").change(function() {
    readURL(this);
});
</script>

<script src="{{asset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>

<script type="text/javascript">
$(function() {
    $("#coba").spartanMultiImagePicker({
        fieldName: 'identity_image[]',
        maxCount: 2,
        rowHeight: '140px',
        groupClassName: 'two__item',
        maxFileSize: '',
        placeholderImage: {
            image: '{{asset('
            public / assets / admin / img / upload - vertical.png ')}}',
            width: '100%'
        },
        dropFileLabel: "Drop Here",
        onAddRow: function(index, file) {

        },
        onRenderedPreview: function(index) {

        },
        onRemoveRow: function(index) {

        },
        onExtensionErr: function(index, file) {
            toastr.error('{{ translate("Please only input png or jpg type file") }}', {
                CloseButton: true,
                ProgressBar: true
            });
        },
        onSizeErr: function(index, file) {
            toastr.error('{{ translate("File size too big") }}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    });
});
</script>
@endpush
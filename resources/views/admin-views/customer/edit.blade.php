@extends('layouts.admin.app')

@section('title', translate('Update Customer'))

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
                {{translate('Update Customer')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->

    <form action="{{route('admin.customer.update',[$customers['id']])}}" method="post" enctype="multipart/form-data">
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
                                <input type="text" name="f_name" value="{{ $customers->f_name }}" class="form-control"
                                    placeholder="{{translate('Ex : First Name')}}" required>
                            </div>
                            <div class="col-md-12">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{translate('Last Name')}}</label>
                                <input type="text" name="l_name" value="{{ $customers->l_name }}" class="form-control"
                                    placeholder="{{translate('Ex : Last Name')}}" required>
                            </div>
                        <div class="col-md-12">
                            <div>
                                <label class="input-label" for="exampleFormControlInput1">{{translate('phone')}}</label>
                                <input type="text" name="phone" value="{{ $customers->phone }}" class="form-control"
                                    placeholder="{{ translate('Ex : 017********') }}" required>
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
                                    <img class="initial-24" id="viewer" onerror="this.src='{{asset('public/assets/admin/img/upload-vertical.png')}}'" src="{{asset('storage/app/public/customer').'/'.$customers['image']}}"alt="Customer thumbnail"/>
                                </center>
                                <div class="form-group mb-0">
                                    <label class="form-label d-block">{{translate('Customer')}} {{translate('image')}} <small class="text-danger">* ( {{translate('ratio')}} 1:1 )</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileUpload" class="custom-file-input h--45px" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label h--45px" for="customFileUpload"></label>
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
                <input type="email" name="email" value="{{ $customers->email }}" class="form-control"
                    placeholder="{{ translate('Ex : ex@example.com') }}" required>
            </div>
            <div class="col-md-4 col-sm-6">
                <label class="input-label" for="exampleFormControlInput1">{{translate('password')}}</label>
                <input type="text" name="password"  class="form-control" placeholder="{{ translate('7+ Characters') }} "   
                     >
            </div>
            <div class="col-md-4 col-sm-6">
                <label class="input-label" for="exampleFormControlInput1">{{translate('Confirm password')}}</label>
                <input type="text" name="confirm_password" class="form-control"
                    placeholder="{{ translate('7+ Characters') }}"  >
            </div>
        </div>
    </div>
</div>

<div class="btn--container justify-content-end mt-3">
    <a type="button" href="{{route('admin.customer.list')}}" class="btn btn--reset">{{translate('back')}}</a>
    <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
</div>

</form>

</div>







@endsection

@push('script_2')
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
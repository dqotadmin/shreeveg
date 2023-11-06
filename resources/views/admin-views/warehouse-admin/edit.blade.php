@extends('layouts.admin.app')

@section('title', translate('Add new Warehouse Admin'))


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
            {{$role->name}}  {{translate('Setup')}}

            </span>
        </h1>
    </div>
    <!-- End Page Header -->
    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
            <div class="card-body pt-sm-0 pb-sm-4">

                <form action="{{route('admin.warehouse-admin-update',[$admins['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <!-- Card -->
                    <div class="card mb-3 mb-lg-5" id="generalDiv">
                        <!-- Profile Cover -->
                        <div class="profile-cover">
                            <div class="profile-cover-img-wrapper"></div>
                        </div>
                        <!-- End Profile Cover -->

                        <!-- Avatar -->
                        <label
                            class="avatar avatar-xxl avatar-circle avatar-border-lg avatar-uploader profile-cover-avatar"
                            for="avatarUploader">
                            <img id="viewer" onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                class="avatar-img"
                                
                                src="{{asset('storage/app/public/admin/warehouse')}}/{{$admins['image']}}"
                                alt="Image">
                            <input type="hidden" name="admin_role_id" value="3">
                            <input type="file" name="image" class="js-file-attach avatar-uploader-input"
                                id="customFileEg1" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                            <label class="avatar-uploader-trigger" for="customFileEg1">
                                <i class="tio-edit avatar-uploader-icon shadow-soft"></i>
                            </label>
                        </label>
                        <!-- End Avatar -->
                    </div>
                    <!-- End Card -->

                    <!-- Card -->
                    <div class="card mb-3 mb-lg-5">
                        <div class="card-header">
                            <h2 class="card-title h4"><i class="tio-info"></i> {{ translate('Basic information') }}</h2>
                        </div>

                        <!-- Body -->
                        <div class="card-body">
                            <!-- Form -->
                            <!-- Form Group -->
                            <div class="row form-group">
                                <label for="firstNameLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('Full name') }} <i
                                        class="tio-help-outlined text-body ml-1" data-toggle="tooltip"
                                        data-placement="top" title="Display name"></i></label>

                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm-down-break">
                                        <input type="text" class="form-control" name="f_name" id="firstNameLabel"
                                            placeholder="{{ translate('Your first name') }}" 
                                            aria-label="Your first name" value="{{$admins->f_name}}">
                                        <input type="text" class="form-control" name="l_name" id="lastNameLabel"
                                            placeholder="{{ translate('Your last name') }}" aria-label="Your last name" value="{{$admins->l_name}}"
                                           >
                                    </div>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('Phone') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                                    <input type="text" class="js-masked-input form-control" name="phone" id="phoneLabel"
                                        placeholder="+x(xxx)xxx-xx-xx" aria-label="+(xxx)xx-xxx-xxxxx" value="{{$admins->phone}}"
                                        data-hs-mask-options='{
                                           "template": "+(880)00-000-00000"
                                         }'>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <div class="row form-group">
                                <label for="newEmailLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('Email') }}</label>

                                <div class="col-sm-9">
                                    <input type="email" class="form-control" name="email" id="newEmailLabel" value="{{$admins->email}}"
                                        placeholder="{{ translate('Enter new email address') }}"
                                        aria-label="Enter new email address">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                            <a href="{{route('admin.warehouse-admin')}}"  type="reset" class="btn btn--reset mr-2">
                                {{translate('Back')}}</a>
                                <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                            </div>

                            <!-- End Form -->
                        </div>
                        <!-- End Body -->
                    </div>
                    <!-- End Card -->

                    <!-- Card -->
                    <!-- <div id="passwordDiv" class="card mb-3 mb-lg-5">
                        <div class="card-header">
                            <h4 class="card-title"><i class="tio-lock"></i> {{ translate('Change your password') }}</h4>
                        </div> -->

                        <!-- Body -->
                        <!-- <div class="card-body"> -->
                            <!-- Form -->


                            <!-- Form Group -->
                            <!-- <div class="row form-group">
                                <label for="newPassword"
                                    class="col-sm-3 col-form-label input-label">{{ translate('New password') }}</label>

                                <div class="col-sm-9">
                                    <input type="password" class="js-pwstrength form-control" name="password"
                                        id="newPassword" placeholder="{{ translate('Enter new password') }}"
                                        aria-label="Enter new password" data-hs-pwstrength-options='{
                                           "ui": {
                                             "container": "#changePasswordForm",
                                             "viewports": {
                                               "progress": "#passwordStrengthProgress",
                                               "verdict": "#passwordStrengthVerdict"
                                             }
                                           }
                                         }' required>

                                    <p id="passwordStrengthVerdict" class="form-text mb-2"></p>

                                    <div id="passwordStrengthProgress"></div>
                                </div>
                            </div> -->
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <!-- <div class="row form-group">
                                <label for="confirmNewPasswordLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('Confirm password') }}</label>

                                <div class="col-sm-9">
                                    <div class="mb-3">
                                        <input type="password" class="form-control" name="confirm_password"
                                            id="confirmNewPasswordLabel"
                                            placeholder="{{ translate('Confirm your new password') }}"
                                            aria-label="Confirm your new password" required>
                                    </div>
                                </div>
                            </div> -->
                            <!-- End Form Group -->

                            <!-- <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">{{ translate('Save Changes') }}</button>
                            </div> -->
                            <!-- End Form -->
                        <!-- </div> -->
                        <!-- End Body -->
                    <!-- </div> -->
                    <!-- End Card -->
                </form>

                    <!-- Sticky Block End Point -->
                    <div id="stickyBlockEndPoint"></div>
            </div>
        </div>
    </div>
</div>
</div>
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

$("#customFileEg1").change(function() {
    readURL(this);
});
</script>

<script>
$("#generalSection").click(function() {
    $("#passwordSection").removeClass("active");
    $("#generalSection").addClass("active");
    $('html, body').animate({
        scrollTop: $("#generalDiv").offset().top
    }, 2000);
});

$("#passwordSection").click(function() {
    $("#generalSection").removeClass("active");
    $("#passwordSection").addClass("active");
    $('html, body').animate({
        scrollTop: $("#passwordDiv").offset().top
    }, 2000);
});
</script>
@endpush
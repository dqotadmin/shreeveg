@extends('layouts.admin.app')

@section('title', translate('Update'))


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
                Update {{$role->name}}

            </span>
        </h1>
    </div>
    <!-- End Page Header -->
    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
            <div class="card-body pt-sm-0 pb-sm-4">

                <form action="{{route('admin.admin-update',[$admins['id']])}}" method="post" enctype="multipart/form-data" class="needs-validation form_customer" novalidate>
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
                                src="{{asset('storage/app/public/admin/warehouse')}}/{{$admins['image']}}" alt="Image">
                            <input type="hidden" name="admin_role_id" value="  {{$admins->admin_role_id}}">
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
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('Role') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                                    <input name="name" id="" class="form-control" value="{{$role->name}}" readonly>
                                    <input name="admin_role_id" type="hidden" class="form-control"
                                        value="{{$role->id}}">

                                </div>
                            </div>
                            @if($role->id == '3' || $role->id == '5' || $role->id == '4')
                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('Warehouse Name') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                                    <select id="state" name="warehouse_id" class="custom-select"
                                        required>
                                        @if($user->admin_role_id == 3)
                                        <option value="{{$user->Warehouse->id}}" selected>{{$user->Warehouse->name}}</option>
                                        @else
                                        <option selected disabled value="">Select Warehouse </option>

                                        @foreach(\App\Model\Warehouse::where('status', 1)->where('deleted_at',
                                        null)->get() as $warehouse)
                                        <option value="{{$warehouse['id']}}"
                                            {{$warehouse->id == $admins->warehouse_id ? 'selected' : '';}}>
                                            {{$warehouse['name']}}</option>
                                        @endforeach
                                        @endif

                                    </select>
                                </div>
                            </div>
                         
                            @elseif($role->id == '6' || $role->id == '7' )
                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('Store Name') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                                    <select id="state" name="store_id" class="form-control js-select2-custom" required>
                                        @if($user->admin_role_id == 6)
                                        <option value="{{$user->Store->id}}">{{$user->Store->name}}</option>
                                        @else
                                        <option value="" disabled selected>Select Store </option>
                                        @foreach(\App\Model\Store::where('status', 1)->where('deleted_at', null)->get()
                                        as $store)
                                        <option value="{{$store['id']}}"
                                            {{$store->id == $admins->store_id ? 'selected' : '';}}>{{$store['name']}}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @endif
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
                                            placeholder="{{ translate('Your last name') }}" aria-label="Your last name"
                                            value="{{$admins->l_name}}">
                                            <div class="invalid-feedback">
                                            Please enter full name.
                                            </div>
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
                                        placeholder="+x(xxx)xxx-xx-xx" aria-label="+(xxx)xx-xxx-xxxxx"
                                        value="{{$admins->phone}}" data-hs-mask-options='{
                                           "template": "+(880)00-000-00000"
                                         }'>
                                         <div class="invalid-feedback">
                                            Please enter phone no.
                                            </div>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <div class="row form-group">
                                <label for="newEmailLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('Email') }}</label>

                                <div class="col-sm-9">
                                    <input type="email" class="form-control" name="email" id="newEmailLabel"
                                        value="{{$admins->email}}"
                                        placeholder="{{ translate('Enter new email address') }}"
                                        aria-label="Enter new email address">
                                        <div class="invalid-feedback">
                                            Please enter email-id.
                                            </div>
                                </div>
                            </div>

                            @if($role->id == 8 )
                            <div class="row form-group">
                                <label class="col-sm-3 col-form-label input-label" for="">{{ translate('address') }}</label>
                                <div class="col-sm-9">
                                    <input type="address" class="form-control" name="addressaddress" id="newaddressLabel"
                                    value="{{$admins->address}}" 
                                    placeholder="{{ translate('Enter new address') }}"
                                    aria-label="Enter address" required>
                                    <div class="invalid-feedback">
                                        Please enter address.
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 col-form-label input-label" for="">{{ translate('broker_type') }}
                                </label>
                                <div class="col-sm-9">
                                    <select id="" name="broker_type" class="form-control custom-select" required>
                                        <option selected  disabled value="">Select broker type </option>
                                        <option value="Farmer" {{ $admins->broker_type == 'Farmer' ? 'selected' : '' }}>Farmer</option>
                                        <option value="Transporter" {{ $admins->broker_type == 'Transporter' ? 'selected' : '' }}>Transporter</option>
                                        <option value="market_seller" {{ $admins->broker_type == 'market_seller' ? 'selected' : '' }}>Market Seller</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select broker type.
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- End Form -->
                        </div>
                        <!-- End Body -->
                    </div>
                    <!-- End Card -->
                    <div id="bankDiv" class="card mb-3 mb-lg-5">
                        <div class="card-header">
                            <h4 class="card-title"><i class="tio-lock"></i> {{ translate('Bank Details') }}
                            </h4>
                        </div>

                        <!-- Body -->
                        <div class="card-body">
                            <!-- Form -->

                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('Account Number') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                                    <input type="text" class="js-masked-input form-control  manually-border-color" name="account_number"
                                        id="phoneLabel" placeholder="Account Number"
                                        value="{{$admins->bankDetail ? $admins->bankDetail->account_number: ''}}">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('Account Holder') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                                    <input type="text" class="js-masked-input form-control  manually-border-color" name="account_holder"
                                        id="phoneLabel" placeholder="Account Holder"
                                        value="{{$admins->bankDetail ? $admins->bankDetail->account_holder: '';}}">
                                </div>
                            </div>

                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('Bank Name') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                                    <input type="text" class="js-masked-input form-control  manually-border-color" name="bank_name"
                                        id="phoneLabel" placeholder="Bank Name"
                                        value="{{$admins->bankDetail ? $admins->bankDetail->bank_name: '';}}">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('IFSC Code') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                                    <input type="text" class="js-masked-input form-control  manually-border-color" name="ifsc_code"
                                        id="phoneLabel" placeholder="IFSC Code"
                                        value="{{$admins->bankDetail ? $admins->bankDetail->ifsc_code: '';}}">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('UPI Id') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                                    <input type="text" class="js-masked-input form-control  manually-border-color" name="upi_id"
                                        id="phoneLabel" placeholder="UPI Id"
                                        value="{{$admins->bankDetail ? $admins->bankDetail->upi_id: '';}}">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('UPI Number') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                                    <input type="text" class="js-masked-input form-control  manually-border-color" name="upi_number"
                                        id="upi_number"
                                        value="{{$admins->bankDetail ? $admins->bankDetail->upi_number : '';}}"
                                        placeholder="UPI Number">
                                </div>
                            </div>

                        </div>

                        <!-- End Body -->
                    </div>

                  <div id="passwordDiv" class="card mb-3 mb-lg-5">
                        <div class="card-header">
                            <h4 class="card-title"><i class="tio-lock"></i> {{ translate('Change Your password') }}
                            </h4>
                        </div>

                        <!-- Body -->
                        <div class="card-body">
                            <!-- Form -->


                            <!-- Form Group -->
                      <!-- Form Group -->
                      <div class="row form-group">
                        <label for="newPassword" class="col-sm-3 col-form-label input-label">
                            {{ translate('New password') }}
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="js-pwstrength form-control" name="password"
                                id="newPassword" placeholder="{{ translate('Enter new password') }}" value="{{$admins->show_password}}"
                                aria-label="Enter new password"
                                data-hs-pwstrength-options='{
                                    "ui": {
                                    "container": "#changePasswordForm",
                                    "viewports": {
                                        "progress": "#passwordStrengthProgress",
                                        "verdict": "#passwordStrengthVerdict"
                                    }
                                    }
                                }' required>
                            <div class="invalid-feedback">
                                Please enter a password.
                            </div>
                            <p id="passwordStrengthVerdict" class="form-text mb-2"></p>
                            <div id="passwordStrengthProgress"></div>
                        </div>
                    </div>

                    <!-- Form Group -->
                    <div class="row form-group">
                        <label for="confirmNewPasswordLabel" class="col-sm-3 col-form-label input-label">
                            {{ translate('Confirm password') }}
                        </label>
                        <div class="col-sm-9">
                            <div class="mb-3">
                                <input type="text   " class="form-control" name="confirm_password"
                                    id="confirmNewPasswordLabel" value="{{$admins->show_password}}"
                                    placeholder="{{ translate('Confirm your new password') }}"
                                    aria-label="Confirm your new password" required
                                    oninput="checkPasswordMatch()">
                                <div class="invalid-feedback">
                                    Please enter the confirm password.
                                </div>
                                <div id="passwordMismatchError" class="text-danger"></div>
                            </div>
                        </div>
                    </div>

                            <!-- End Form Group -->

                       
                            <!-- End Form -->
                        </div>
                        <!-- End Body -->
                    </div>
                    <div class="d-flex justify-content-end">

                        <a href="{{route('admin.admin',['role_id'=>$role->id])}}" type="button"
                            class="btn btn--reset mr-2"> {{translate('Back')}}</a>
                        <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                    </div>
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
<script>
    function checkPasswordMatch() {
        var newPassword = document.getElementById('newPassword').value;
        var confirmNewPassword = document.getElementById('confirmNewPasswordLabel').value;
        var errorDiv = document.getElementById('passwordMismatchError');

        if (newPassword !== confirmNewPassword) {
            errorDiv.innerHTML = 'Passwords do not match.';
            document.getElementById('confirmNewPasswordLabel').setCustomValidity('Passwords do not match');
        } else {
            errorDiv.innerHTML = '';
            document.getElementById('confirmNewPasswordLabel').setCustomValidity('');
        }
    }
</script>
@endpush
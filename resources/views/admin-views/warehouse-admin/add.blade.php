@extends('layouts.admin.app')

@section('title', translate('Add'))


@push('css_or_js')
<style>
    .manually-border-color{
  border: 0.0625rem solid #e7eaf3  !important;
}
</style>
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
                {{translate($role->name)}} 
            </span>
        </h1>
    </div>
    <!-- End Page Header -->
    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
            <div class="card-body pt-sm-0 pb-sm-4">

                <form action="{{route('admin.admin')}}" method="post" enctype="multipart/form-data" class="needs-validation form_customer" novalidate>
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
                                src="{{asset('storage/app/public/admin')}}/{{auth('admin')->user()->image}}"
                                alt="Image">

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
                                    <input name="admin_role_id" type="hidden" class="form-control" value="{{$role->id}}">

                                </div>
                            </div>
                             @if($role->id == '3'   || $role->id == '5' || $role->id == '4')
                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('Warehouse Name') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                      
                                    <select id="state" name="warehouse_id" class="custom-select" required>
                                        <option selected disabled value="">Select Warehouse </option>
                                        @if($user->admin_role_id == 3)
                                        <option value="{{$user->Warehouse->id}}" selected>{{$user->Warehouse->name}}</option>
                                        @else
                                          @foreach(\App\Model\Warehouse::where('status', 1)->where('deleted_at', null)->get() as $warehouse)                                      
                                        <option value="{{$warehouse['id']}}" {{ old('warehouse_id') == $warehouse['id'] ? 'selected' : '' }} >{{$warehouse['name']}}</option>

                                        @endforeach
                                        @endif
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select Warehouse.
                                    </div>
                                </div>
                            </div>
                            @elseif($role->id == '6' || $role->id == '7' )
                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('Store Name') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                                    <select id="state" name="store_id" class="custom-select" required>
                                        @if($user->admin_role_id == 6)
                                        <option value="{{$user->Store->id}}">{{$user->Store->name}}</option>
                                        @elseif($user->admin_role_id == 1)
                                        <option selected disabled value="">Select Store </option>
                                        @foreach(\App\Model\Store::where('status', 1)->where('deleted_at', null)->get() as $store)
                                        <option value="{{$store['id']}}">{{$store['name']}}</option>
                                        @endforeach
                                        @else
                                        <option selected disabled value="">Select Store </option>

                                        @foreach(\App\Model\Store::where('status', 1)->where('warehouse_id', auth('admin')->user()->warehouse_id)->where('deleted_at', null)->get() as $store)
                                        <option value="{{$store['id']}}">{{$store['name']}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select Store.
                                    </div>
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
                                        <input type="text" class="form-control" name="f_name" id="firstNameLabel" style="text-transform: capitalize;"
                                            placeholder="{{ translate('Your first name') }}"
                                            aria-label="Your first name" value="{{old('f_name')}}" required>
                                            
                                        <input type="text" class="form-control" name="l_name" id="lastNameLabel" style="text-transform: capitalize;"
                                            placeholder="{{ translate('Your last name') }}" aria-label="Your last name"
                                            value="{{old('l_name')}}" required>
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
                                        class="input-label-secondary" ></span></label>

                                <div class="col-sm-9">
                                    <input type="number" class="js-masked-input form-control" name="phone" id="phoneLabel" pattern="[^e]*"
                                        placeholder="+x(xxx)xxx-xx-xx"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                        value="{{old('phone')}}" data-hs-mask-options='{
                                           "template": "+(880)00-000-00000"
                                         }' required>
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
                                        value="{{old('email')}}"
                                        placeholder="{{ translate('Enter new email address') }}"
                                        aria-label="Enter new email address" required>
                                        <div class="invalid-feedback">
                                            Please enter email-id.
                                            </div>
                                </div>
                            </div>


                           

                        

                            <div class="row form-group">
                                <label class="col-sm-3 col-form-label input-label" for="">{{ translate('City') }}
                                </label>
                                <div class="col-sm-9">
                                    <select id="" name="city_id" class="form-control custom-select" required>
                                        <option selected  disabled value="">Select city </option>
                                        @foreach(\App\Model\City::where('status', '1')->orderBy('id', 'DESC')->get() as $city)
                                        <option value="{{ $city['id'] }}" {{ old('city_id') == $city['id'] ? 'selected' : '' }}>{{$city['city']}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select city.
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                    <input type="text" class="js-masked-input form-control manually-border-color" name="account_number" id="phoneLabel"
                                        placeholder="Account Number" >
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('Account Holder') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                                    <input type="text" class="js-masked-input form-control manually-border-color" name="account_holder" id="phoneLabel"
                                        placeholder="Account Holder" aria-label=" "
                                     >
                                </div>
                            </div>

                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('Bank Name') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                                    <input type="text" class="js-masked-input form-control manually-border-color" name="bank_name" id="phoneLabel"
                                        placeholder="Bank Name" >
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('IFSC Code') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                                    <input type="text" class="js-masked-input form-control manually-border-color" name="ifsc_code" id="phoneLabel"
                                        placeholder="IFSC Code" >
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('UPI Id') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                                    <input type="text" class="js-masked-input form-control manually-border-color" name="upi_id" id="phoneLabel"
                                        placeholder="UPI Id" >
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('UPI Number') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                                    <input type="text" class="js-masked-input form-control manually-border-color" name="upi_number" id="upi_number"
                                        placeholder="UPI Number" >
                                </div>
                            </div>
 
                        </div>
                        <!-- End Body -->
                    </div>
                    <!-- Card -->
                    <div id="passwordDiv" class="card mb-3 mb-lg-5">
                        <div class="card-header">
                            <h4 class="card-title"><i class="tio-lock"></i> {{ translate('Change your password') }}
                            </h4>
                        </div>

                        <!-- Body -->
                        <div class="card-body">
                            <!-- Form -->


                            <!-- Form Group -->
                            <div class="row form-group">
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
                                        <div class="invalid-feedback">
                                            Please enter password.
                                        </div>
                                    <p id="passwordStrengthVerdict" class="form-text mb-2"></p>

                                    <div id="passwordStrengthProgress"></div>
                                </div>
                              
                            </div>
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div class="row form-group">
                                <label for="confirmNewPasswordLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('Confirm password') }}</label>

                                <div class="col-sm-9">
                                    <div class="mb-3">
                                        <input type="password" class="form-control" name="confirm_password"
                                            id="confirmNewPasswordLabel"
                                            placeholder="{{ translate('Confirm your new password') }}"
                                            aria-label="Confirm your new password" required>
                                            <div class="invalid-feedback">
                                            Please enter confirm password.
                                            </div>
                                    </div>
                                </div>
                            
                            </div>
                            <!-- End Form Group -->

                            <div class="d-flex justify-content-end">
                                <a href="{{route('admin.admin',['role_id'=>$role->id])}}" type="button" class="btn btn--reset mr-2">  {{translate('Back')}}</a>
                                <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                            </div>
                            <!-- End Form -->
                        </div>
                        <!-- End Body -->
                    </div>
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

<script>
        $(document).ready(function() {
            $('#state').change(function() {
                var stateId = $(this).val();
                if (stateId) {
                    $.ajax({
                         url: '{{url('/')}}/admin/get-cities/' + stateId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#city').empty();
                            $('#city').append('<option value="">Select City</option>');
                            $.each(data.cities, function(key, value) {
                                $('#city').append('<option value="' + value.id + '">' + value.city + '</option>');
                            });
                        }
                    });
                } else {
                    $('#city').empty();
                    $('#city').append('<option value="">Select City</option>');
                }
            });
        });
    </script>

@endpush
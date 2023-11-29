@extends('layouts.admin.app')

@section('title', translate('Update Admin')) 


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
                            <input type="hidden" name="admin_role_id" value="  {{$admins->admin_role_id}}">
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
                                    <input name="admin_role_id" type="hidden" class="form-control" value="{{$role->id}}">

                                </div>
                            </div>
                             @if($role->id == '3' || $role->id == '8' || $role->id == '5' || $role->id == '4')
                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ translate('Warehouse Name') }} <span
                                        class="input-label-secondary"></span></label>

                                <div class="col-sm-9">
                                    <select id="state" name="warehouse_id" class="form-control js-select2-custom" required>
                                        <option value="" disabled selected>Select Warehouse </option>
                                        @foreach(\App\Model\Warehouse::orderBy('id', 'DESC')->get() as $warehouse)
                                        <option value="{{$warehouse['id']}}"   {{$warehouse->id == $admins->warehouse_id ? 'selected' : '';}}>{{$warehouse['name']}}</option>
                                        @endforeach
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
                                        <option value="" disabled selected>Select Store </option>
                                        @foreach(\App\Model\Store::orderBy('id', 'DESC')->get() as $store)
                                        <option value="{{$store['id']}}"  {{$store->id == $admins->store_id ? 'selected' : '';}}>{{$store['name']}}</option>
                                        @endforeach
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
                          
                            <a href="{{route('admin.warehouse-admin',['role_id'=>$role->id])}}" type="button" class="btn btn--reset mr-2">  {{translate('Back')}}</a>
                                <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
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
@endpush
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
                    {{translate('add new deliveryman')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <form action="{{route('admin.delivery-man.store')}}" method="post" enctype="multipart/form-data"  class="needs-validation form_customer g-2" novalidate>
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
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="input-label" for="exampleFormControlInput1">{{translate('First Name')}}</label>
                                            <input type="text" name="f_name" value="{{ old('f_name') }}" class="form-control" placeholder="{{translate('Ex : First Name')}}"
                                                   required>
                                            <div class="invalid-feedback">
                                                Please enter first name.
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="input-label" for="exampleFormControlInput1">{{translate('Last Name')}}</label>
                                            <input type="text" name="l_name" value="{{ old('l_name') }}" class="form-control" placeholder="{{translate('Ex : Last Name')}}"
                                                   required>
                                            <div class="invalid-feedback">
                                                Please enter last name.
                                            </div>
                                        </div>
                                    </div>
 
                                </div>
                                <div class="col-md-12">
                                    <div>
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('phone')}}</label>
                                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="{{ translate('Ex : 017********') }}"
                                                required>
                                        <div class="invalid-feedback">
                                            Please enter phone no.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div>
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('Warehouse Name')}}</label>
                                        <select name="warehouse_id" class="custom-select" required>
                                            <option  value="" disabled selected>{{translate('Select Warehouse')}}</option>
                                            @foreach(\App\Model\Warehouse::where('status',1)->where('deleted_at',null)->get() as $warehouse)
                                                <option value="{{$warehouse['id']}}" {{ old('warehouse_id') == $warehouse['id'] ? 'selected' : '' }}>{{$warehouse['name']}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select warehouse.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div>
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('identity')}} {{translate('type')}}</label>
                                        <select name="identity_type" class="form-control">
                                            <option value="passport">{{translate('passport')}}</option>
                                            <option value="driving_license">{{translate('driving')}} {{translate('license')}}</option>
                                            <option value="nid">{{translate('nid')}}</option>
                                            <option value="restaurant_id">{{translate('store Id')}} {{translate('id')}}</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please enter identity type.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div>
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('identity')}} {{translate('number')}}</label>
                                        <input type="text" name="identity_number" class="form-control" value="{{ old('identity_number') }}"
                                                placeholder="{{ translate('Ex : DH-23434-LS') }}" required>
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter identity number.
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
                                    <img class="initial-24" id="viewer"
                                    src="{{asset('public/assets/admin/img/upload-vertical.png')}}" alt="Deliveryman thumbnail"/>
                                </center>
                                <div class="form-group mb-0">
                                    <label class="form-label d-block">
                                        {{ translate('Deliveryman Image') }} <span class="text-danger">{{ translate('(Ratio 1:1)') }}</span>
                                    </label>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileUpload" class="custom-file-input h--45px gray-border" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label h--45px gray-border" for="customFileUpload"></label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label d-block mb-2">
                                    {{ translate('Identity Image') }}
                                </label>
                                <div class="product--coba">
                                    <div class="row g-2" id="coba"></div>
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
                    </span> {{translate('Bank Details')}}
                </h5>
            </div>

            <!-- Body -->
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="input-label" for="exampleFormControlInput1">{{ translate('Account Number') }}
                        </label>
                        <input type="text" name="account_number" value="{{ old('account_number') }}"
                            class="form-control" placeholder="{{translate('Ex : Account Number')}}" required>
                                <div class="invalid-feedback">
                                        Please enter account number.
                                    </div>
                    </div>
                    <div class="col-md-4">
                        <label class="input-label" for="exampleFormControlInput1">{{ translate('Account Holder') }}
                        </label>
                        <input type="text" name="account_holder" value="{{ old('account_holder') }}"
                            class="form-control" placeholder="{{translate('Ex : Account Holder')}}" required>
                                <div class="invalid-feedback">
                                        Please enter account holder.
                                    </div>
                    </div>
                    <div class="col-md-4">
                        <label class="input-label" for="exampleFormControlInput1">{{ translate('Bank Name') }}
                        </label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="form-control"
                            placeholder="{{translate('Ex : Bank Name')}}" required>
                                <div class="invalid-feedback">
                                        Please enter bank name.
                                    </div>
                    </div>
                    <div class="col-md-4">
                        <label class="input-label" for="exampleFormControlInput1">{{ translate('IFSC Code') }}
                        </label>
                        <input type="text" name="ifsc_code" value="{{ old('ifsc_code') }}" class="form-control"
                            placeholder="{{translate('Ex : IFSC Code')}}" required>
                                <div class="invalid-feedback">
                                        Please enter ifsc code.
                                    </div>
                    </div>

                    <div class="col-md-4">
                        <label class="input-label" for="exampleFormControlInput1">{{ translate('UPI Id') }} </label>
                        <input type="text" name="upi_id" value="{{ old('upi_id') }}" class="form-control"
                            placeholder="{{translate('Ex : UPI Id')}}" required>
                                <div class="invalid-feedback">
                                        Please enter upi id.
                                    </div>
                    </div>

                    <div class="col-md-4">
                        <label class="input-label" for="exampleFormControlInput1">{{ translate('UPI Number') }}
                        </label>
                        <input type="text" name="upi_number" value="{{ old('upi_number') }}" class="form-control"
                            placeholder="{{translate('Ex : UPI Number')}}" required>
                                <div class="invalid-feedback">
                                        Please enter upi number.
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
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="{{ translate('Ex : ex@example.com') }}"
                                    required>
                                    <div class="invalid-feedback">
                                        Please enter email.
                                    </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('password')}}</label>
                            <input type="text" name="password" class="form-control" placeholder="{{ translate('7+ Characters') }}" required>
                            <div class="invalid-feedback">
                                        Please enter password.
                                    </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('confirm_password')}}</label>
                            <input type="text" name="password_confirmation" class="form-control" placeholder="{{ translate('7+ Characters') }}" required>
                            <div class="invalid-feedback">
                                        Please enter confirm password.
                                    </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="btn--container justify-content-end mt-3">
            <a type="button" href="{{route('admin.delivery-man.list')}}" class="btn btn--reset">{{translate('back')}}</a>
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

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileUpload").change(function () {
            readURL(this);
        });
    </script>

    <script src="{{asset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>

    <script type="text/javascript">
        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 2,
                rowHeight: '140px',
                groupClassName: 'two__item',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/admin/img/upload-vertical.png')}}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{ translate("Please only input png or jpg type file") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{ translate("File size too big") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
@endpush

@extends('layouts.admin.app')

@section('title', translate('Update Group'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/edit.png')}}" class="w--20" alt="">
                </span>
                <span>
                    {{translate('Update Group')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.business-settings.groups.update',[$row['id']])}}" method="post"
                method="post" enctype="multipart/form-data" class="needs-validation form_customer g-2" novalidate >
                    @csrf @method('put')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('name')}}</label>
                                        <input type="text" name="name" value="{{$row['name']}}" class="form-control"
                                            placeholder="{{ translate('Name') }}" required>
                                            <div class="invalid-feedback">
                                        Please enter name.
                                    </div>
                                    </div>
                                </div>
                               
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <label class="input-label"
                                                for="{{translate('description')}}">{{translate('description')}}</label>
                                            <textarea name="description" class="form-control h--172px " id="" required>{{$row['description']}}</textarea>
                                            <div class="invalid-feedback">
                                        Please enter description.
                                    </div>
                                        </div>
                                       
                                    </div>
                                    
                                    
                                    </div>
                                </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-column justify-content-center h-100">
                                <div class="text-center mb-3">
                                    {{translate('banner')}} {{translate('image')}}
                                    <small class="text-danger">* ( {{translate('ratio')}} 3:1 )</small>
                                </div>
                                <label class="upload--vertical">
                                    <input type="file" name="image" id="customFileEg1" class=""
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" hidden>
                                    <img id="viewer" onerror="this.src='{{asset('public/assets/admin/img/upload-vertical.png')}}'" src="{{asset('storage/app/public/groups')}}/{{$row['image']}}" alt="banner image"/>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="btn--container justify-content-end">
                            <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                            <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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

        $("#customFileEg1").change(function () {
            readURL(this);
        });

        function show_item(type) {
            if (type === 'product') {
                $("#type-product").show();
                $("#type-category").hide();
            } else {
                $("#type-product").hide();
                $("#type-category").show();
            }
        }
    </script>


@endpush

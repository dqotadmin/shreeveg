@extends('layouts.admin.app')

@section('title', translate('Update category'))

@push('css_or_js')
<style>
.bold-text {
    font-weight: bold;
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
                    @if($category->parent_id == 0)
                        {{ translate('Category Update') }}
                    @else
                    {{ translate('Sub Category Update') }}
                    @endif
                </span>
            </h1>
        </div>
        <!-- End Page Header -->


        <div class="card">
            <div class="card-body">

                <form action="{{route('admin.category.update',[$category['id']])}}" method="post" id="timeForm" enctype="multipart/form-data" class="needs-validation form_customer" novalidate>


                    <div class="row align-items-end g-4" style="margin-top: 20px;">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Select Category Type') }}</label>

                                <div class="d-flex flex-wrap align-items-center form-control border">
                                    <label class="form-check form--check mr-2 mr-md-4 mb-0">
                                        <input type="radio" class="form-check-input  gray-border" name="category_type" id="category_type1" value="parent" {{(isset($category['parent_id']) && $category['parent_id']==0)?'checked':''}} > 
                                        <span class="form-check-label  black-color"> {{ translate('Parent Category') }}</span>
                                    </label>
                                    <label class="form-check form--check mb-0">
                                        <input type="radio" class="form-check-input  gray-border" name="category_type" id="category_type2" value="child" {{(isset($category['parent_id']) && $category['parent_id']!=0)?'checked':''}}>
                                        <span class="form-check-label  black-color"> {{ translate('Child Category') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-5 {{(isset($category['parent_id']) && $category['parent_id']!=0)?'':'g'}}" id="category_box">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('Select Parent Category')}}</label>
                                <select name="parent_id" id="parent_id" class="form-control" >
                                    <option value="">---{{translate('Select Parent Category')}}---</option>
                                    <?php echo $options; ?>
                                </select>
                            </div>
                        </div>
                    </div>


                    @csrf
                   

                    <div class="row align-items-end g-4" style="margin-top: 40px;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('name')}}
                                        (EN)</label>
                                    <input type="text" name="name" maxlength="255" value="{{ $category['name']}}" class="form-control"  required>
                                    <div class="invalid-feedback">
                                        Please enter name.
                                    </div>
                                    </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('name')}} (HI)
                                        </label>
                                    <input type="text" name="" maxlength="255" value="{{$category['hn_name']}}"   class="form-control" placeholder="{{ translate('New Category') }}" required>
                                    <div class="invalid-feedback">
                                        Please enter hindi name.
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('title_silver')}}
                                        (EN)</label>
                                    <input type="text" name="title_silver" maxlength="255" value="{{ $category['title_silver']}}" class="form-control  manually-border-color"   placeholder="{{ translate('title_silver') }}" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('title_gold')}}
                                        (EN)</label>
                                    <input type="text" name="title_gold" maxlength="255" value="{{$category['title_gold']}}" class="form-control  manually-border-color"   placeholder="{{ translate('title_gold') }}" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('title_platinum')}}
                                        (EN)</label>
                                    <input type="text" name="title_platinum" maxlength="255" value="{{$category['title_platinum']}}" class="form-control manually-border-color"   placeholder="{{ translate('title_platinum') }}" >
                                </div>
                            </div>
                        </div>
                        <input name="position" value="0" hidden>

                    </div>

                    <div class="row align-items-end g-4">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('Category Code')}}</label>
                                <input type="text" name="category_code" value="{{$category['category_code']}}"
                                        class="form-control input-text-uc" oninvalid="document.getElementById('en-link').click()"
                                        placeholder="{{ translate('Category Code') }}" required>
                                        <div class="invalid-feedback">
                                            Please enter category code.
                                        </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-end g-4">
                        
                        <div class="col-sm-3">
                            <center>
                                <img class="img--105" id="viewer" onerror="this.src='{{asset('public/assets/admin/img/900x400/img1.jpg')}}'"
                                src="{{asset('storage/app/public/category/')}}/{{$category['image']}}" alt="image"/>
                            </center>
                            <label>{{\App\CentralLogics\translate('image')}}</label><small style="color: red">* ( {{\App\CentralLogics\translate('ratio')}} 3:1 )</small>
                            <div class="custom-file">
                                <input type="file" name="image" id="customFileEg1" class="custom-file-input" >
                                <label class="custom-file-label gray-border" for="customFileEg1">{{\App\CentralLogics\translate('choose')}} {{\App\CentralLogics\translate('file')}}</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="btn--container justify-content-end">
                            <a href="{{route('admin.category.list')}}" type="reset" class="btn btn--reset">
                        {{translate('Back')}}</a>
                                <button type="submit" class="btn btn--primary">{{translate('Update')}}</button>
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
       
    </script>
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
    </script>
@endpush

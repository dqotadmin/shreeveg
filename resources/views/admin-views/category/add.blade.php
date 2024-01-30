@extends('layouts.admin.app')

@section('title', translate('Add new category'))

@push('css_or_js')
<style>
.bold-text {
    font-weight: bold;
}

.bold-text-remove {
    padding-left: 30px;
    /* Adjust the padding as needed */
    font-weight: normal;
    /* Adjust the position as needed */
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
                {{translate('Add Category')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->

    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-body pt-sm-0 pb-sm-4">
                    <form action="{{route('admin.category.store')}}" method="post" id="timeForm" enctype="multipart/form-data" class="needs-validation form_customer" novalidate>

                    <div class="row align-items-end g-4" style="margin-top: 20px;">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Select Category Type') }}</label>

                                <div class="d-flex flex-wrap align-items-center form-control border">
                                    <label class="form-check form--check mr-2 mr-md-4 mb-0">
                                        <input type="radio" class="form-check-input gray-border" name="category_type" id="category_type1" value="parent" {{(isset($config) && $config=='left')?'checked':''}} checked> 
                                        <span class="form-check-label black-color"> {{ translate('Category') }}</span>
                                    </label>
                                    <label class="form-check form--check mb-0">
                                        <input type="radio" class="form-check-input gray-border" name="category_type" id="category_type2" value="child" {{(isset($config) && $config=='right')?'checked':''}}>
                                        <span class="form-check-label black-color"> {{ translate('Sub Category') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-5 d-none" id="category_box">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('Select Category')}}</label>
                                <select name="parent_id" id="parent_id" class="form-control" >
                                    <option value="">---{{translate('Select Category')}}---</option>
                                    <?php echo $options; ?>
                                </select>
                            </div>
                        </div>
                    </div>


                    @csrf
                    @php($data = Helpers::get_business_settings('language'))
                    @php($default_lang = Helpers::get_default_language())

                

                    <div class="row align-items-end g-4" style="margin-top: 40px;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('name')}}
                                        (EN)</label>
                                    <input type="text" name="name" maxlength="255" value=""   class="form-control" placeholder="{{ translate('New Category') }}" required>
                                    <div class="invalid-feedback">
                                        Please enter name.
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('name')}} (HI)
                                        </label>
                                    <input type="text" name="hn_name" maxlength="255" value=""   class="form-control" placeholder="{{ translate('New Category') }}" required>
                                    <div class="invalid-feedback">
                                        Please enter hindi name.
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('title_silver')}}
                                        </label>
                                    <input type="text" name="title_silver" maxlength="255"  class="form-control manually-border-color" placeholder="{{ translate('title_silver') }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('title_gold')}}
                                        
                                        <input type="text" name="title_gold" maxlength="255" value="" class="form-control manually-border-color" placeholder="{{ translate('title_gold') }}">
                                    </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('title_platinum')}}
                                        </label>
                                    <input type="text" name="title_platinum" maxlength="255"   class="form-control manually-border-color" placeholder="{{ translate('title_platinum') }}">
                                </div>
                            </div>
                        </div>
                        <input name="position" value="0" hidden>
                    </div>

                    <div class="row align-items-end g-4">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('Category Code')}}</label>
                                <input type="text" name="category_code" value="{{old('category_code')}}"
                                        class="form-control input-text-uc"  
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
                                    src="" alt="image"/>
                            </center>
                            <label>{{\App\CentralLogics\translate('image')}}</label>
                            <!-- <small style="color: red">* ( {{\App\CentralLogics\translate('ratio')}} 3:1 )</small> -->
                            <div class="custom-file">
                                <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                      >
                                <label class="custom-file-label gray-border" for="customFileEg1">{{\App\CentralLogics\translate('choose')}} {{\App\CentralLogics\translate('file')}}</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="btn--container justify-content-end">
                            <a href="{{route('admin.category.list')}}" type="reset" class="btn btn--reset">
                                {{translate('Back')}}</a>
                                <button type="submit" class="btn btn--primary">{{translate('Add')}}</button>
                            </div>
                        </div>
                    </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script_2')


<script>
    $(".lang_link").click(function(e) {
        e.preventDefault();
        $(".lang_link").removeClass('active');
        $(".lang_form").addClass('d-none');
        $(this).addClass('active');

        let form_id = this.id;
        let lang = form_id.split("-")[0];
        $("#" + lang + "-form").removeClass('d-none');
        if (lang == '{{$default_lang}}') {
            $(".from_part_2").removeClass('d-none');
        } else {
            $(".from_part_2").addClass('d-none');
        }
    });

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

    $('input[type=radio][name=category_type]').change(function() {
        if (this.value == 'parent') {
            $('#category_box').addClass('d-none');
        } else {
            $('#category_box').removeClass('d-none');
        }
    });
</script>
<script>
var radios = $('[type="radio"]');

radios.change(function() {
    radios.not(this).prop('checked', false);
});
</script>
@endpush
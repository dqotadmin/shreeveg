@extends('layouts.admin.app')

@section('title', translate('Add new category'))

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
                {{translate('Add Category')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->

    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-body pt-sm-0 pb-sm-4">
                    <form action="{{route('admin.category.store')}}" method="post" enctype="multipart/form-data">

                    <div class="row align-items-end g-4" style="margin-top: 20px;">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Select Category Type') }}</label>

                                <div class="d-flex flex-wrap align-items-center form-control border">
                                    <label class="form-check form--check mr-2 mr-md-4 mb-0">
                                        <input type="radio" class="form-check-input" name="category_type" id="category_type1" value="parent" {{(isset($config) && $config=='left')?'checked':''}} checked> 
                                        <span class="form-check-label"> {{ translate('Parent Category') }}</span>
                                    </label>
                                    <label class="form-check form--check mb-0">
                                        <input type="radio" class="form-check-input" name="category_type" id="category_type2" value="child" {{(isset($config) && $config=='right')?'checked':''}}>
                                        <span class="form-check-label"> {{ translate('Child Category') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-5 d-none" id="category_box">
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
                    @php($data = Helpers::get_business_settings('language'))
                    @php($default_lang = Helpers::get_default_language())

                    @if($data && array_key_exists('code', $data[0]))
                        <ul class="nav nav-tabs d-inline-flex mb--n-30">
                            @foreach($data as $lang)
                            <li class="nav-item">
                                <a class="nav-link lang_link {{$lang['default'] == true? 'active':''}}" href="#"
                                id="{{$lang['code']}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')'}}</a>
                            </li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="row align-items-end g-4" style="margin-top: 40px;">

                        @if($data && array_key_exists('code', $data[0]))

                            @foreach($data as $lang)
                                
                                <div class="row {{$lang['default'] == false ? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" for="exampleFormControlInput1">{{translate('name')}}
                                                ({{strtoupper($lang['code'])}})</label>
                                            <input type="text" name="name[]" maxlength="255" value="" class="form-control" @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif placeholder="{{ translate('New Category') }}" {{$lang['status'] == true ? '':''}}>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" for="exampleFormControlInput1">{{translate('title_silver')}}
                                                ({{strtoupper($lang['code'])}})</label>
                                            <input type="text" name="title_silver[]" maxlength="255" value="" class="form-control" @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif placeholder="{{ translate('title_silver') }}" {{$lang['status'] == true ? '':''}}>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" for="exampleFormControlInput1">{{translate('title_gold')}}
                                                ({{strtoupper($lang['code'])}})</label>
                                            <input type="text" name="title_gold[]" maxlength="255" value="" class="form-control" @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif placeholder="{{ translate('title_gold') }}" {{$lang['status'] == true ? '':''}}>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" for="exampleFormControlInput1">{{translate('title_platinum')}}
                                                ({{strtoupper($lang['code'])}})</label>
                                            <input type="text" name="title_platinum[]" maxlength="255" value="" class="form-control" @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif placeholder="{{ translate('title_platinum') }}" {{$lang['status'] == true ? '':''}}>
                                        </div>
                                    </div>


                                </div>
                                <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                            @endforeach

                            
                        
                        @else
                            <div class="col-sm-6 lang_form" id="{{$default_lang}}-form">
                                <label class="input-label"
                                        for="exampleFormControlInput1">{{translate('name')}}
                                    ({{strtoupper($default_lang)}})</label>
                                <input type="text" name="name[]" value=""
                                        class="form-control" oninvalid="document.getElementById('en-link').click()"
                                        placeholder="{{ translate('New Category') }}" >
                            </div>
                            <input type="hidden" name="lang[]" value="{{$default_lang}}">
                        @endif
                        <input name="position" value="0" hidden>

                    </div>

                    <div class="row align-items-end g-4">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('Category Code')}}</label>
                                <input type="text" name="category_code" value=""
                                        class="form-control input-text-uc" oninvalid="document.getElementById('en-link').click()"
                                        placeholder="{{ translate('Category Code') }}" >
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-end g-4">
                        
                        <div class="col-sm-3">
                            <center>
                                <img class="img--105" id="viewer" onerror="this.src='{{asset('public/assets/admin/img/900x400/img1.jpg')}}'"
                                    src="" alt="image"/>
                            </center>
                            <label>{{\App\CentralLogics\translate('image')}}</label><small style="color: red">* ( {{\App\CentralLogics\translate('ratio')}} 3:1 )</small>
                            <div class="custom-file">
                                <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="customFileEg1">{{\App\CentralLogics\translate('choose')}} {{\App\CentralLogics\translate('file')}}</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="btn--container justify-content-end">
                                <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
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

@endpush
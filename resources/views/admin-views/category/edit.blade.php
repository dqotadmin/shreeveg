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
                {{ translate('Update category') }}
                @else
                {{ translate('Sub Category Update') }}
                @endif
            </span>
        </h1>
    </div>
    <!-- End Page Header -->


    <div class="card">
        <div class="card-body">
            <div class="card-body pt-sm-0 pb-sm-4">
                <form action="{{route('admin.category.update',[$category['id']])}}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @php($data = Helpers::get_business_settings('language'))
                    @php($default_lang = Helpers::get_default_language())

                    @if($data && array_key_exists('code', $data[0]))

                    <ul class="nav nav-tabs d-inline-flex {{$category->parent_id == 0 ? 'mb--n-30' : 'mb-4'}}">
                        @foreach($data as $lang)
                        <li class="nav-item">
                            <a class="nav-link lang_link {{$lang['default'] == true? 'active':''}}" href="#"
                                id="{{$lang['code']}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')'}}</a>
                        </li>
                        @endforeach
                    </ul>

                    @endif
                    <div class="row align-items-end g-4" style="padding-top: 50px;">
                        <h4>Category Type</h4>
                    </div>
                    <div class="d-flex flex-wrap mb-4 align-items-center">
                        <div>
                            <label class="form-check mr-2 mr-md-4">
                                <input class="form-check-input" type="radio" name="parent_id" value="0"
                                    {{$category->parent_id=='0' ? 'checked' : ''}}>
                                <span class="form-check-label text--title pl-2">{{translate('Parent')}}</span>
                            </label>
                        </div>
                        <div>
                            <label class="form-check">
                                <input class="form-check-input" type="radio" id="show_dropdown"
                                    {{$category->parent_id !='0' ? 'checked': ''}}>
                                <span class="form-check-label text--title pl-2">{{translate('Child')}}</span>
                            </label>
                        </div>
                        @if($category->parent_id !='0')
                        <div class="" id="sub_dropdown" style=" margin-left: 20px;">
                            @else
                            <div class="" id="sub_dropdown" style="display:none; margin-left: 20px;">
                                @endif
                                <select class="form-control" name="parent_id">
                                    <option value="" selected disabled>{{translate('Categories')}}</option>
                                    @if(!empty($categories))
                                    @foreach ($categories as $parent_category)
                                    <option value="{{ $parent_category->id }}" class="bold-text"
                                        {{ $parent_category->id === $category->parent_id ? 'selected' : '' }}>
                                        {{ $parent_category->name }}
                                    </option>
                                    @if ($parent_category->childes)
                                    @foreach ($parent_category->childes as $sub_child)
                                    <option value="{{ $sub_child->id }}" class="bold-text-remove"
                                        {{ $sub_child->id === $category->parent_id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&bull;{{ $sub_child->name }}</option>
                                    @if ($sub_child)
                                    @foreach ($sub_child->childes as $sub_sub_child)
                                    <option value="{{ $sub_sub_child->id }}" class="bold-text-remove"
                                        {{ $sub_sub_child->id === $category->parent_id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&bull;{{ $sub_sub_child->name }}</option>
                                    @if ($sub_sub_child)
                                    @foreach ($sub_sub_child->childes as $fourth_sub_child)
                                    <option value="{{ $fourth_sub_child->id }}" class="bold-text-remove"
                                        {{ $fourth_sub_child->id === $category->parent_id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&bull;{{ $fourth_sub_child->name }}</option>
                                    @if ($fourth_sub_child)
                                    @foreach ($fourth_sub_child->childes as $five_sub_child)
                                    <option value="{{ $five_sub_child->id }}" class="bold-text-remove"
                                        {{ $five_sub_child->id === $category->parent_id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&bull;{{ $five_sub_child->name }}</option>
                                    @endforeach
                                    @endif
                                    @endforeach
                                    @endif
                                    @endforeach
                                    @endif
                                    @endforeach
                                    @endif
                                    @endforeach
                                    @endif

                                </select>
                            </div>
                        </div>
                        @if($data && array_key_exists('code', $data[0]))
                        <div class="row align-items-end g-4">
                            @foreach($data as $lang)
                            <?php
                                if (count($category['translations'])) {
                                    $translate = [];
                                    foreach ($category['translations'] as $t) {
                                        if ($t->locale == $lang['code'] && $t->key == "name") {
                                            $translate[$lang['code']]['name'] = $t->value;
                                        }
                                    }
                                }
                            ?>
                            <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form"
                                id="{{$lang['code']}}-form">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('name')}}
                                    ({{strtoupper($lang['code'])}})</label>
                                <input type="text" name="name[]" maxlength="255"
                                    value="{{$lang['code'] == 'en' ? $category['name'] : ($translate[$lang['code']]['name']??'')}}"
                                    class="form-control" @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif
                                    placeholder="{{ translate('New Category') }}"
                                    {{$lang['status'] == true ? 'required':''}}>
                            </div>
                            <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                            @endforeach
                            @else
                            <div class="col-sm-6 lang_form" id="{{$default_lang}}-form">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('name')}}
                                    ({{strtoupper($default_lang)}})</label>
                                <input type="text" name="name[]" value="{{$category['name']}}" class="form-control"
                                    oninvalid="document.getElementById('en-link').click()"
                                    placeholder="{{ translate('New Category') }}" required>
                            </div>
                            <input type="hidden" name="lang[]" value="{{$default_lang}}">
                            @endif

                            <div class="col-sm-6 ">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Category') }}
                                    {{ translate('Code') }}
                                </label>
                                <input type="text" name="category_code" class="form-control"
                                    value="{{$category->category_code}}" placeholder="{{ translate('Ex: abc123') }}"
                                    maxlength="255">
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Title') }}
                                    {{ translate('silver') }}
                                </label>
                                <textarea type="text" name="title_silver" class="form-control"
                                    placeholder="{{ translate('Ex: veg') }}"
                                    maxlength="255">{{$category->title_silver}}</textarea>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Title') }}
                                    {{ translate('Gold') }}
                                </label>
                                <textarea type="text" name="title_gold" class="form-control"
                                    placeholder="{{ translate('Ex: 100% Organick') }}"
                                    maxlength="255">{{$category->title_gold}}</textarea>
                            </div>

                            <div class="col-sm-4 ">
                                <label class="form-label" for="exampleFormControlInput1">{{translate('Title')}}
                                    {{ translate('Platinum') }}
                                </label>
                                <textarea type="text" name="title_platinum" class="form-control"
                                    placeholder="{{ translate('Ex: 100% Organick') }}"
                                    maxlength="255">{{$category->title_platinum}}</textarea>
                            </div>



                            <input name="position" value="0" hidden>
                            <div class="col-sm-6">
                                <center>
                                    <img class="img--105" id="viewer"
                                        onerror="this.src='{{asset('public/assets/admin/img/900x400/img1.jpg')}}'"
                                        src="{{asset('storage/app/public/category')}}/{{$category['image']}}"
                                        alt="image" />
                                </center>
                                <label>{{\App\CentralLogics\translate('image')}}</label><small style="color: red">* (
                                    {{\App\CentralLogics\translate('ratio')}} 3:1 )</small>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label"
                                        for="customFileEg1">{{\App\CentralLogics\translate('choose')}}
                                        {{\App\CentralLogics\translate('file')}}</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="btn--container justify-content-end">
                                    <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                                    <button type="submit" class="btn btn--primary">{{translate('Update')}}</button>
                                </div>
                            </div>
                        </div>
                </form>
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
    console.log(lang);
    $("#" + lang + "-form").removeClass('d-none');
    if (lang == '{{$default_lang}}') {
        $(".from_part_2").removeClass('d-none');
    } else {
        $(".from_part_2").addClass('d-none');
    }
});
</script>
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
$('#show_dropdown').on('click', function() {
    $('#sub_dropdown').css('display', 'block');
});
</script>
<script>
var radios = $('[type="radio"]');

radios.change(function() {
    radios.not(this).prop('checked', false);
});
</script>

@endpush
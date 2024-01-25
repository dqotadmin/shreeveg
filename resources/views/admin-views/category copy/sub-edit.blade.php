@extends('layouts.admin.app')

@section('title', translate('Sub Update Category'))

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
                @if($category->parent_id == 0)
                {{ translate('category Update') }}
                @else
                {{ translate('Sub Category Update') }}
                @endif
            </span>
        </h1>
    </div>
    <!-- End Page Header -->

    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.category.update',[$category['id']])}}" method="post"
                enctype="multipart/form-data">
                @csrf
                @php($data = Helpers::get_business_settings('language'))
                @php($default_lang = Helpers::get_default_language())

                <!-- @if($data && array_key_exists('code', $data[0]))

                            <ul class="nav nav-tabs d-inline-flex {{$category->parent_id == 0 ? 'mb--n-30' : 'mb-4'}}">
                                @foreach($data as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link {{$lang['default'] == true? 'active':''}}" href="#"
                                    id="{{$lang['code']}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')'}}</a>
                                </li>
                                @endforeach
                            </ul>

                    @endif -->

                <div class="row">

                    <div class="col-sm-6 ">
                        <div class="form-group">
                            <label class="form-label" for="exampleFormControlSelectmain_category">{{translate('main')}}
                                {{translate('category')}}
                                <span class="input-label-secondary">*</span></label>
                            <select id="exampleFormControlSelectmain_category" name="parent_id" class="form-control"
                                required>
                                @foreach(\App\Model\Category::where(['position'=>0])->get() as $cat)
                                <option value="{{$cat['id']}}" {{$category['parent_id']==$cat['id']?'selected':''}}>
                                    {{$cat['name']}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @foreach($data as $lang)
                    <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form"
                        id="{{$lang['code']}}-form">
                        <div class="form-group">
                            <label class="form-label" for="exampleFormControlInput1">
                                {{translate('sub_category')}} {{translate('name')}}
                                <!-- ({{strtoupper($lang['code'])}}) -->
                            </label>
                            <input type="text" name="name[]"
                                value="{{$lang['code'] == 'en' ? $category['name'] : ($translate[$lang['code']]['name']??'')}}"
                                class="form-control" maxlength="255" placeholder="{{ translate('New Sub Category') }}"
                                {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                        </div>
                    </div>
                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                    @endforeach

                    @foreach($data as $lang)
                    <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form"
                        id="{{$lang['code']}}-form">
                        <div class="form-group">
                            <label class="form-label" for="exampleFormControlInput1">
                                {{translate('item_code')}}
                                <!-- ({{strtoupper($lang['code'])}}) -->
                            </label>
                            <input type="text" name="item_code[]"
                                value="{{$lang['code'] == 'en' ? $category['item_code'] : ($translate[$lang['code']]['item_code']??'')}}"
                                class="form-control" maxlength="255" placeholder="{{ translate('item_code') }}"
                                {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                        </div>
                    </div>
                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                    @endforeach

                    @foreach($data as $lang)
                    <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form"
                        id="{{$lang['code']}}-form">
                        <div class="form-group">
                            <label class="form-label" for="exampleFormControlInput1">
                                {{translate('title_silver')}}
                                <!-- ({{strtoupper($lang['code'])}}) -->
                            </label>
                            <input type="text" name="title_silver[]"
                                value="{{$lang['code'] == 'en' ? $category['title_silver'] : ($translate[$lang['code']]['title_silver']??'')}}"
                                class="form-control" maxlength="255" placeholder="{{ translate('title_silver') }}"
                                {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                        </div>
                    </div>
                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                    @endforeach


                    @foreach($data as $lang)
                    <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form"
                        id="{{$lang['code']}}-form">
                        <div class="form-group">
                            <label class="form-label" for="exampleFormControlInput1">
                                {{translate('title_gold')}}
                                <!-- ({{strtoupper($lang['code'])}}) -->
                            </label>
                            <input type="text" name="title_gold[]"
                                value="{{$lang['code'] == 'en' ? $category['title_gold'] : ($translate[$lang['code']]['title_gold']??'')}}"
                                class="form-control" maxlength="255" placeholder="{{ translate('title_gold') }}"
                                {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                        </div>
                    </div>
                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                    @endforeach

                    @foreach($data as $lang)
                    <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form"
                        id="{{$lang['code']}}-form">
                        <div class="form-group">
                            <label class="form-label" for="exampleFormControlInput1">
                                {{translate('title_platinum')}}
                                <!-- ({{strtoupper($lang['code'])}}) -->
                            </label>
                            <input type="text" name="title_platinum[]"
                                value="{{$lang['code'] == 'en' ? $category['title_platinum'] : ($translate[$lang['code']]['title_platinum']??'')}}"
                                class="form-control" maxlength="255" placeholder="{{ translate('title_platinum') }}"
                                {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                        </div>
                    </div>
                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                    @endforeach



                    @foreach($data as $lang)
                    <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form"
                        id="{{$lang['code']}}-form">
                        <div class="form-group">
                            <label class="form-label" for="exampleFormControlInput1">
                                {{translate('unit_title')}}
                                <!-- ({{strtoupper($lang['code'])}}) -->
                            </label>
                            <input type="text" name="unit_title[]"
                                value="{{$lang['code'] == 'en' ? $category['unit_title'] : ($translate[$lang['code']]['unit_title']??'')}}"
                                class="form-control" maxlength="255" placeholder="{{ translate('unit_title') }}"
                                {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                        </div>
                    </div>
                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                    @endforeach

                    <div class="col-sm-6 ">

                        <div class="form-group">
                            <label class="form-label"
                                for="exampleFormControlSelectsub_unit_title">{{translate('sub_unit_title')}}
                                <span class="input-label-secondary">*</span></label>
                            <select id="exampleFormControlSelectsub_unit_title" name="sub_unit_title[]"
                                class="form-control" required>
                                @foreach(\App\Model\Unit::where(['position'=>0])->get() as $unit)
                                <option value="{{$unit['id']}}"
                                    {{$category['sub_unit_title']==$unit['id']?'selected':''}}>
                                    {{$unit['title']}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="exampleFormControlSelect1">{{translate('Multiple Unit')}}
                                {{translate('Select')}}  <span class="input-label-secondary">*</span></label>
                            <select id="exampleFormControlSelect1" name="unit[]" class="form-control chosen-select"
                                multiple required>
                                @if($category['unit'])
                                @foreach(\App\Model\Unit::get() as $unit)
                                <option value="{{$unit['id']}}"
                                    {{in_array($unit->id,json_decode($category['unit'],true))?'selected':''}}>
                                    {{$unit['title']}}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6">
                    <div class="form-group">
                        <center>
                            <img class="img--105" id="viewer"
                                onerror="this.src='{{asset('public/assets/admin/img/900x400/img1.jpg')}}'"
                                src="{{asset('storage/app/public/category')}}/{{$category['image']}}" alt="image" />
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
                    </div>
                    <div class="col-12">
                        <div class="btn--container justify-content-end">
                            <a href="{{route('admin.category.add-sub-category')}}" type="reset" class="btn btn--reset">
                                {{translate('Back')}}</a>
                            <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
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
@endpush
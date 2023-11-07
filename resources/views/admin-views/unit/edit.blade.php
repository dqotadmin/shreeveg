@extends('layouts.admin.app')

@section('title', translate('Update Unit'))

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
                {{ translate('Update Unit') }}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->


    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.unit.update',[$units['id']])}}" method="post" enctype="multipart/form-data">
            @csrf
                    @php($data = Helpers::get_business_settings('language'))
                    @php($default_lang = Helpers::get_default_language())

                    @if($data && array_key_exists('code', $data[0]))

                    <ul class="nav nav-tabs d-inline-flex ">
                        @foreach($data as $lang)
                        <li class="nav-item">
                            <a class="nav-link lang_link {{$lang['default'] == true? 'active':''}}" href="#"
                                id="{{$lang['code']}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')'}}</a>
                        </li>
                        @endforeach
                    </ul>
                    <div class="row align-items-end g-4" style="margin-top: 40px;">
                        @foreach ($data as $lang)
                         <?php
                                if (count($units['translations'])) {
                                    $translate = [];
                                    foreach ($units['translations'] as $t) {
                                        if ($t->locale == $lang['code'] && $t->key == "title") {
                                            $translate[$lang['code']]['title'] = $t->value;
                                        }
                                    }
                                }
                            ?><div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                            id="{{ $lang['code'] }}-form">
                            <label class="form-label" for="exampleFormControlInput1">{{ translate('Unit') }}
                                {{ translate('Title') }}
                                ({{ strtoupper($lang['code']) }})
                            </label>
                            <input type="text" name="title[]" class="form-control" 
                            value="{{$lang['code'] == 'en' ? $units['title'] : ($translate[$lang['code']]['name']??'')}}"
                                placeholder="{{ translate('Ex: gm') }}" maxlength="255"
                                {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                        </div>
                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                        @endforeach

                        <div class="col-sm-6">
                            <label class="form-label" for="exampleFormControlInput1">{{ translate('Unit') }}
                                {{ translate('Description') }}
                            </label>
                            <input type="text" name="description" class="form-control" value="{{$units->description}}"
                                placeholder="{{ translate('Ex: gram') }}" maxlength="255">
                        </div>
                        @else
                        <div class="lang_form col-sm-6" id="{{ $default_lang }}-form">
                            <label class="form-label" for="exampleFormControlInput1">{{translate('Unit')}}
                                {{ translate('Title') }}
                                ({{ strtoupper($default_lang) }})</label>
                            <input type="text" name="title[]" class="form-control" maxlength="255" value="{{$units->title}}"
                                placeholder="{{ translate('Ex: gm') }}" required>
                        </div>
                        <input type="hidden" name="lang[]" value="{{ $default_lang }}">


                        @endif
                        <input name="position" value="0" hidden>

                        <div class="col-12">
                            <div class="btn--container justify-content-end">
                                <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
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
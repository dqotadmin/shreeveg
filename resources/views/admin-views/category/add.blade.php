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
                {{translate('Create category')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->

    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-body pt-sm-0 pb-sm-4">
                    <form action="{{route('admin.category.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @php($data = Helpers::get_business_settings('language'))
                        @php($default_lang = Helpers::get_default_language())
                        {{-- @php($default_lang = 'en') --}}
                        @if ($data && array_key_exists('code', $data[0]))
                        {{-- @php($default_lang = json_decode($language)[0]) --}}
                        <ul class="nav nav-tabs d-inline-flex mb--n-30">
                            @foreach ($data as $lang)
                            <li class="nav-item">
                                <a class="nav-link lang_link {{ $lang['default'] == true ? 'active' : '' }}" href="#"
                                    id="{{ $lang['code'] }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang['code']) . '(' . strtoupper($lang['code']) . ')' }}</a>
                            </li>
                            @endforeach
                        </ul>
                        <div class="row align-items-end g-4" style="padding-top: 50px;">
                            <h4>Category Type</h4>
                        </div>
                        <div class="d-flex flex-wrap mb-4 align-items-center">
                            <div>
                                <label class="form-check mr-2 mr-md-4">
                                    <input class="form-check-input" type="radio" name="parent_id" value="0">
                                    <span class="form-check-label text--title pl-2">{{translate('Parent')}}</span>
                                </label>
                            </div>
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="radio" id="show_dropdown" >
                                    <span class="form-check-label text--title pl-2">{{translate('Child')}}</span>
                                </label>
                            </div>
                            <div class="" id="sub_dropdown" style="display:none; margin-left: 20px;">
                                <select  name="parent_id" class="form-control">
                                    <option value="" selected disabled>{{translate('Categories')}}</option>

                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" class="bold-text"
                                        {{ $category->id === old('id') ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                        @if ($category->childes)
                                        @foreach ($category->childes as $sub_child)
                                        <option value="{{ $sub_child->id }}" class="bold-text-remove"
                                            {{ $sub_child->id === old('id') ? 'selected' : '' }}>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&bull;{{ $sub_child->name }}</option>
                                            @if ($sub_child)
                                            @foreach ($sub_child->childes as $sub_sub_child)
                                            <option value="{{ $sub_sub_child->id }}" class="bold-text-remove"
                                                {{ $sub_sub_child->id === old('id') ? 'selected' : '' }}>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;&nbsp;&nbsp;&bull;{{ $sub_sub_child->name }}</option>
                                                @if ($sub_sub_child)
                                                @foreach ($sub_sub_child->childes as $fourth_sub_child)
                                                <option value="{{ $fourth_sub_child->id }}" class="bold-text-remove"
                                                    {{ $fourth_sub_child->id === old('id') ? 'selected' : '' }}>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&bull;{{ $fourth_sub_child->name }}</option>
                                                    @if ($fourth_sub_child)
                                                        @foreach ($fourth_sub_child->childes as $five_sub_child)
                                                        <option value="{{ $five_sub_child->id }}" class="bold-text-remove"
                                                            {{ $five_sub_child->id === old('id') ? 'selected' : '' }}>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&bull;{{ $five_sub_child->name }}</option>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                                @endif
                                            @endforeach
                                            @endif
                                        @endforeach
                                        @endif
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="row align-items-end g-4">
                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('category') }}
                                    {{ translate('name') }}
                                    ({{ strtoupper($lang['code']) }})</label>
                                <input type="text" name="name[]" class="form-control"
                                    placeholder="{{ translate('Ex: Size') }}" maxlength="255"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                            </div>
                            <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            @endforeach
                            @else
                            <div class="lang_form col-sm-6" id="{{ $default_lang }}-form">
                                <label class="form-label" for="exampleFormControlInput1">{{translate('category')}}
                                    {{ translate('name') }}
                                    ({{ strtoupper($default_lang) }})</label>
                                <input type="text" name="name[]" class="form-control" maxlength="255"
                                    placeholder="{{ translate('New Category') }}" required>
                            </div>
                            <input type="hidden" name="lang[]" value="{{ $default_lang }}">
                            @endif
                            <div class="col-sm-6 ">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Category') }}
                                    {{ translate('Code') }}
                                </label>
                                <input type="text" name="category_code" class="form-control"
                                    placeholder="{{ translate('Ex: abc123') }}" maxlength="255">
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Title') }}
                                    {{ translate('silver') }}
                                </label>
                                <textarea type="text" name="title_silver" class="form-control"
                                    placeholder="{{ translate('Ex: veg') }}" maxlength="255"></textarea>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Title') }}
                                    {{ translate('Gold') }}
                                </label>
                                <textarea type="text" name="title_gold" class="form-control"
                                    placeholder="{{ translate('Ex: 100% Organick') }}" maxlength="255"></textarea>
                            </div>

                            <div class="col-sm-4 ">
                                <label class="form-label" for="exampleFormControlInput1">{{translate('Title')}}
                                    {{ translate('Platinum') }}
                                </label>
                                <textarea type="text" name="title_platinum" class="form-control"
                                    placeholder="{{ translate('Ex: 100% Organick') }}" maxlength="255"></textarea>
                            </div>



                            <input name="position" value="0" hidden>
                            <div class="col-sm-6">
                                <div>
                                    <div class="text-center mb-3">
                                        <img id="viewer" class="img--105"
                                            src="{{ asset('public/assets/admin/img/160x160/1.png') }}" alt="image" />
                                    </div>
                                </div>
                                <label
                                    class="form-label text-capitalize">{{ translate('category image') }}</label><small
                                    class="text-danger">* ( {{ translate('ratio') }}
                                    3:1 )</small>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required
                                        oninvalid="document.getElementById('en-link').click()">
                                    <label class="custom-file-label" for="customFileEg1">{{ translate('choose') }}
                                        {{ translate('file') }}</label>
                                </div>

                            </div>
                            <div class="col-12">
                                <div class="btn--container justify-content-end">
                                    <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                                    <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
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
function status_change_alert(url, message, e) {
    e.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: message,
        type: 'warning',
        showCancelButton: true,
        cancelButtonColor: 'default',
        confirmButtonColor: '#107980',
        cancelButtonText: 'No',
        confirmButtonText: 'Yes',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            location.href = url;
        }
    })
}
</script>

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
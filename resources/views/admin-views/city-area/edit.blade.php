@extends('layouts.admin.app')

@section('title', translate('Update area'))

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
                        {{ translate('area Update') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->


        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.area.update',[$cityareas['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <!-- @php($data = Helpers::get_business_settings('language'))
                    @php($default_lang = Helpers::get_default_language()) -->

                  

                    @if($data && array_key_exists('code', $data[0]))
                    <div class="row align-items-end g-4">
                        @foreach($data as $lang)
                            <?php
                                if (count($cityareas['translations'])) {
                                    $translate = [];
                                    foreach ($cityareas['translations'] as $t) {
                                        if ($t->locale == $lang['code'] && $t->key == "title") {
                                            $translate[$lang['code']]['title'] = $t->value;
                                        }
                                    }
                                }
                            ?>
                        @endforeach
                        @foreach ($data as $lang)
                                        <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                                id="{{ $lang['code'] }}-form">
                                            <label class="form-label"
                                                    for="exampleFormControlInput1">{{ translate('City') }} 
                                                <!-- ({{ strtoupper($lang['code']) }}) -->
                                            </label>
                                            <select id="exampleFormControlSelect1" name="city_id[]" class="form-control "  required>
                                            @foreach(\App\Model\City::orderBy('id', 'DESC')->where(['position'=>0])->get() as $city)
                                                <option value="{{$city['id']}}"   {{$city['id']==$cityareas['city_id']?'selected':''}}>{{$city['city']}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    @endforeach
                                    @foreach ($data as $lang)
                                        <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                                id="{{ $lang['code'] }}-form">
                                            <label class="form-label"
                                                    for="exampleFormControlInput1">{{ translate('Area') }} {{ translate('Name') }}
                                                <!-- ({{ strtoupper($lang['code']) }}) -->
                                            </label>
                                            <input type="text" name="area[]" class="form-control" placeholder="{{ translate('Ex:jhotwara') }}" maxlength="255"
                                            value="{{$lang['code'] == 'en' ? $cityareas['area'] : ($translate[$lang['code']]['area']??'')}}" 
                                                      {{$lang['status'] == true ? 'required':''}}
                                                    @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    @endforeach
                                    @foreach ($data as $lang)
                                        <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                                id="{{ $lang['code'] }}-form">
                                            <label class="form-label"
                                                    for="exampleFormControlInput1">{{ translate('latitude') }} {{ translate('code') }}
                                                <!-- ({{ strtoupper($lang['code']) }}) -->
                                            </label>
                                            <input type="text" name="latitude_code[]" class="form-control" placeholder="{{ translate('Ex: 26.958302') }}" maxlength="255"
                                            value="{{$lang['code'] == 'en' ? $cityareas['latitude_code'] : ($translate[$lang['code']]['latitude_code']??'')}}" 
                                                    {{$lang['status'] == true ? 'required':''}}
                                                    @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    @endforeach
                                    @foreach ($data as $lang)
                                        <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                                id="{{ $lang['code'] }}-form">
                                            <label class="form-label"
                                                    for="exampleFormControlInput1">{{ translate('longitude') }} {{ translate('code') }}
                                                <!-- ({{ strtoupper($lang['code']) }}) -->
                                            </label>
                                            <input type="text" name="longitude_code[]" class="form-control"  placeholder="{{ translate('Ex: 75.743347') }}" maxlength="255"
                                            value="{{$lang['code'] == 'en' ? $cityareas['longitude_code'] : ($translate[$lang['code']]['longitude_code']??'')}}" 
                                                    {{$lang['status'] == true ? 'required':''}}
                                                    @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    @endforeach
                                    @foreach ($data as $lang)
                                        <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                                id="{{ $lang['code'] }}-form">
                                            <label class="form-label"
                                                    for="exampleFormControlInput1">{{ translate('radius') }} 
                                                <!-- ({{ strtoupper($lang['code']) }}) -->
                                            </label>
                                            <input type="text" name="radius[]" class="form-control"  placeholder="{{ translate('') }}" maxlength="255"
                                            value="{{$lang['code'] == 'en' ? $cityareas['radius'] : ($translate[$lang['code']]['radius']??'')}}" 
                                                    {{$lang['status'] == true ? 'required':''}}
                                                    @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    @endforeach

                        @else
                            <div class="col-sm-6 lang_form" id="{{$default_lang}}-form">
                                <label class="input-label"
                                        for="exampleFormControlInput1">{{translate('name')}}
                                    ({{strtoupper($default_lang)}})</label>
                                <input type="text" name="name[]" value="{{$cityareas['name']}}"
                                        class="form-control" oninvalid="document.getElementById('en-link').click()"
                                        placeholder="{{ translate('New Category') }}" required>
                            </div>
                            <input type="hidden" name="lang[]" value="{{$default_lang}}">
                        @endif
                        <input name="position" value="0" hidden>
                        
                        <div class="col-12">
                            <div class="btn--container justify-content-end">
                                <a href="{{route('admin.area.add')}}"  type="reset" class="btn btn--reset">
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
        $(".lang_link").click(function(e){
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#"+lang+"-form").removeClass('d-none');
            if(lang == '{{$default_lang}}')
            {
                $(".from_part_2").removeClass('d-none');
            }
            else
            {
                $(".from_part_2").addClass('d-none');
            }
        });
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

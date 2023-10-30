@extends('layouts.admin.app')

@section('title', translate('Add new area'))

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
                    {{translate('area_setup')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <div class="row g-2">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-body pt-sm-0 pb-sm-4">
                        <form action="{{route('admin.area.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @php($data = Helpers::get_business_settings('language'))
                            @php($default_lang = Helpers::get_default_language())
                            {{-- @php($default_lang = 'en') --}}
                            @if ($data && array_key_exists('code', $data[0]))
                                {{-- @php($default_lang = json_decode($language)[0]) --}}
                                <ul class="nav nav-tabs d-inline-flex mb--n-30">
                                    <!-- @foreach ($data as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link lang_link {{ $lang['default'] == true ? 'active' : '' }}" href="#"
                                        id="{{ $lang['code'] }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang['code']) . '(' . strtoupper($lang['code']) . ')' }}</a>
                                    </li>
                                    @endforeach -->
                                </ul>
                                <div class="row align-items-end g-4">
                                @foreach ($data as $lang)
                                        <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                                id="{{ $lang['code'] }}-form">
                                            <label class="form-label"
                                                    for="exampleFormControlInput1">{{ translate('City') }} 
                                                <!-- ({{ strtoupper($lang['code']) }}) -->
                                            </label>
                                            <select id="exampleFormControlSelect1" name="city_id[]" class="form-control "  required>
                                        <option value="" disabled selected>Select City</option>
                                            @foreach(\App\Model\City::orderBy('id', 'DESC')->where(['position'=>0])->where(['state_id'=>19])->get() as $city)
                                                <option value="{{$city['id']}}">{{$city['city']}}</option>
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
                                            <input type="text" name="latitude_code[]" id="latitude" class="form-control" placeholder="{{ translate('Ex: 26.958302') }}" maxlength="255"
                                                    {{$lang['status'] == true ? 'required':''}}
                                                    @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif readonly>
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
                                            <input type="text" name="longitude_code[]" class="form-control" id="longitude" placeholder="{{ translate('Ex: 75.743347') }}" maxlength="255"
                                                    {{$lang['status'] == true ? 'required':''}}
                                                    @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif readonly>
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
                                                    {{$lang['status'] == true ? 'required':''}}
                                                    @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    @endforeach

                                    <div class="col-md-6" id="location_map_div">
                                            <input id="pac-input" class="controls rounded" data-toggle="tooltip"
                                                   data-placement="right"
                                                   data-original-title="{{ translate('search_your_location_here') }}"
                                                   type="text" placeholder="{{ translate('search_here') }}" />
                                            <div id="location_map_canvas" class="overflow-hidden rounded" style="height: 100%"></div>
                                        </div> 
                                    <input name="position" value="0" hidden>
                              
                                    <div class="col-12">
                                        <div class="btn--container justify-content-end">
                                            <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                                            <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
<script src="https://maps.googleapis.com/maps/api/js?key={{ \App\Model\BusinessSetting::where('key', 'map_api_client_key')->first()?->value }}&libraries=places&v=3.45.8"></script>
<script></script>
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

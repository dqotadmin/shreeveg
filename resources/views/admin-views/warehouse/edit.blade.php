@extends('layouts.admin.app')

@section('title', translate('Update unit'))

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
                        {{ translate('city Update') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->


        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.warehouse.update',[$warehouses['id']])}}" method="post" enctype="multipart/form-data">
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
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Select City') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <select id="exampleFormControlSelect1" name="city_id[]" class="form-control "  >
                                    @foreach(\App\Model\City::orderBy('id',
                                    'DESC')->where(['position'=>0])->get() as $city)
                                 
                                    <option value="{{$city['id']}}"    {{$warehouses['city_id'] == $city['id'] ? 'selected':''}}>{{$city['city']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            @endforeach
                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label"
                                    for="exampleFormControlInput1">{{ translate('Warehouse Name') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <input type="text" name="name[]" class="form-control"
                                value="{{$lang['code'] == 'en' ? $warehouses['name'] : ($translate[$lang['code']]['name']??'')}}"
                                   maxlength="255"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                            </div>
                            <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            @endforeach

                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label"
                                    for="exampleFormControlInput1">{{ translate('Warehouse Code') }}
                                </label>
                                <a href="javascript:void(0)" class="float-right c1 fz-12"
                                    onclick="generateCode()">{{translate('generate_code')}}</a>
                                <input type="text" name="code[]" class="form-control" id="codegenerate"
                                value="{{$lang['code'] == 'en' ? $warehouses['code'] : ($translate[$lang['code']]['code']??'')}}"
                                    placeholder="{{\Illuminate\Support\Str::random(8)}}"  >
                            </div>
                            <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            @endforeach

                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label"
                                    for="exampleFormControlInput1">{{ translate('Warehouse Address') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <input type="text" name="address[]" class="form-control"
                                   maxlength="255"
                                value="{{$lang['code'] == 'en' ? $warehouses['address'] : ($translate[$lang['code']]['address']??'')}}"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            </div>
                            @endforeach

                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Owner Name') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <input type="text" name="owner_name[]" class="form-control"
                                value="{{$lang['code'] == 'en' ? $warehouses['owner_name'] : ($translate[$lang['code']]['owner_name']??'')}}"
                                    maxlength="255" style="text-tranform:capitalize"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            </div>
                            @endforeach
                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Owner Contact Number') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <input type="number" name="owner_number[]" class="form-control"
                                    maxlength="255"
                                    value="{{$lang['code'] == 'en' ? $warehouses['owner_number'] : ($translate[$lang['code']]['owner_number']??'')}}"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            </div>
                            @endforeach

                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Owner Second Contact Number') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <input type="number" name="owner_second_number[]" class="form-control"
                                value="{{$lang['code'] == 'en' ? $warehouses['owner_second_number'] : ($translate[$lang['code']]['owner_second_number']??'')}}"
                                    maxlength="255"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            </div>
                            @endforeach

                         

                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Select Area') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <select id="exampleFormControlSelect1" name="area_id[]" class="form-control "  >
                                    @foreach(\App\Model\CityArea::orderBy('id',
                                    'DESC')->where(['position'=>0])->get() as $cityarea)
                                    <option value="{{$cityarea['id']}}" {{$warehouses['area_id'] == $cityarea['id'] ? 'selected': ''}}>{{$cityarea['area']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            @endforeach

                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Pincode') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <input type="text" name="pin_code[]" class="form-control"
                                    maxlength="255"
                                value="{{$lang['code'] == 'en' ? $warehouses['pin_code'] : ($translate[$lang['code']]['pin_code']??'')}}"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            </div>
                            @endforeach


                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('BRN Number') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <input type="text" name="brn_number[]" class="form-control"
                                value="{{$lang['code'] == 'en' ? $warehouses['brn_number'] : ($translate[$lang['code']]['brn_number']??'')}}"
                                    maxlength="255"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            </div>
                            @endforeach

                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('MSME Number') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <input type="text" name="msme_number[]" class="form-control"
                                    maxlength="255"
                                value="{{$lang['code'] == 'en' ? $warehouses['msme_number'] : ($translate[$lang['code']]['msme_number']??'')}}"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            </div>
                            @endforeach

                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label" for=" ">{{ translate('Email') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <input type="email" name="email[]" class="form-control"
                                value="{{$lang['code'] == 'en' ? $warehouses['email'] : ($translate[$lang['code']]['email']??'')}}"
                                    maxlength="255"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            </div>
                            @endforeach

                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Title') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <input type="text" name="title[]" class="form-control"
                                value="{{$lang['code'] == 'en' ? $warehouses['title'] : ($translate[$lang['code']]['title']??'')}}"
                                     maxlength="255"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            </div>
                            @endforeach

                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Warehouse Open Time') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <input type="time" name="open_time[]" class="form-control"
                                value="{{$lang['code'] == 'en' ? $warehouses['open_time'] : ($translate[$lang['code']]['open_time']??'')}}"
                                    maxlength="255"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            </div>
                            @endforeach

                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Warehouse Close Time') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <input type="time" name="close_time[]" class="form-control"
                                value="{{$lang['code'] == 'en' ? $warehouses['close_time'] : ($translate[$lang['code']]['close_time']??'')}}"
                                    maxlength="255"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            </div>
                            @endforeach

                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('User Id') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <input type="text" name="user_id[]" class="form-control"
                                value="{{$lang['code'] == 'en' ? $warehouses['user_id'] : ($translate[$lang['code']]['user_id']??'')}}"
                                    maxlength="255"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            </div>
                            @endforeach

                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Password') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <input type="password" name="password[]" class="form-control"
                                value="{{$lang['code'] == 'en' ? $warehouses['password'] : ($translate[$lang['code']]['password']??'')}}"
                                    maxlength="255"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            </div>
                            @endforeach
                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Location') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <input type="text" name="map_location[]" class="form-control"
                                value="{{$lang['code'] == 'en' ? $warehouses['map_location'] : ($translate[$lang['code']]['map_location']??'')}}"
                                    maxlength="255"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            </div>
                            @endforeach
                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('latitude') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <input type="text" name="latitude[]" class="form-control"
                                value="{{$lang['code'] == 'en' ? $warehouses['latitude'] : ($translate[$lang['code']]['latitude']??'')}}"
                                    maxlength="255"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            </div>
                            @endforeach
                            @foreach ($data as $lang)
                            <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                id="{{ $lang['code'] }}-form">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('longitude') }}
                                    <!-- ({{ strtoupper($lang['code']) }}) -->
                                </label>
                                <input type="text" name="longitude[]" class="form-control"
                                value="{{$lang['code'] == 'en' ? $warehouses['longitude'] : ($translate[$lang['code']]['longitude']??'')}}"
                                    maxlength="255"
                                    {{$lang['status'] == true ? 'required':''}} @if($lang['status']==true)
                                    oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                            </div>
                            @endforeach


                            <input name="position" value="0" hidden>

                            <div class="col-12">
                                <div class="btn--container justify-content-end">
                                    <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                                    <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                                </div>
                            </div>
                        </div>
                        @endif
                    </form>   </div>

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

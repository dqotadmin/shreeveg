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
                        {{ translate('warehouse Update setup ') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->


        <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
                <form action="{{route('admin.warehouse.update',[$warehouses['id']])}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row g-2">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="tio-user"></i>
                                    {{translate('Warehouse information')}}
                                </h5>
                            </div>
                            <div class="card-body pt-sm-0 pb-sm-4">
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
                                    <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Select City') }}
                                            <!-- ({{ strtoupper($lang['code']) }}) -->
                                        </label>
                                        <select id="exampleFormControlSelect1" name="city_id[]" class="form-control ">
                                            @foreach(\App\Model\City::orderBy('id',
                                            'DESC')->where(['state_id'=>19])->get() as $city)
                                            <option value="{{$city['id']}}" {{$warehouses->city_id ==$city->id ? 'selected': ''}}>{{$city['city']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    @endforeach
                                    @foreach ($data as $lang)
                                    <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Warehouse Name') }}
                                            <!-- ({{ strtoupper($lang['code']) }}) -->
                                        </label>
                                        <input type="text" name="name[]" class="form-control" maxlength="255" value="{{$warehouses->name}}"
                                            {{$lang['status'] == true ? '':''}} @if($lang['status']==true)
                                            oninvalid="document.getElementById('{{$lang['code']}}-link').click()"
                                            @endif>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    @endforeach
                                    @foreach ($data as $lang)
                                    <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Warehouse Code') }}
                                        </label>
                                        <a href="javascript:void(0)" class="float-right c1 fz-12"
                                            onclick="generateCode()">{{translate('generate_code')}}</a>
                                        <input type="text" name="code[]" class="form-control" value="{{$warehouses->code}}"
                                            id="codegenerate" placeholder="{{\Illuminate\Support\Str::random(8)}}">
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    @endforeach

                                    @foreach ($data as $lang)
                                    <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Warehouse Address') }}
                                            <!-- ({{ strtoupper($lang['code']) }}) -->
                                        </label>
                                        <textarea type="text" name="address[]" class="form-control"
                                            maxlength="255" {{$lang['status'] == true ? '':''}} 
                                            @if($lang['status']==true)
                                            oninvalid="document.getElementById('{{$lang['code']}}-link').click()"
                                            @endif> {{$warehouses->address}}</textarea>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="tio-user"></i>
                                    {{translate('Warehouse Time Slot')}}
                                </h5>
                            </div>
                            <div class="card-body pt-sm-0 pb-sm-4">
                                <div class="row align-items-end g-4  mt-3">
                                    @foreach ($data as $lang)
                                    <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Warehouse Open Time') }}
                                        </label>
                                        <input type="time" name="open_time[]" class="form-control"
                                            maxlength="255" {{$lang['status'] == true ? '':''}}
                                            @if($lang['status']==true) value="{{$warehouses->open_time}}"
                                            oninvalid="document.getElementById('{{$lang['code']}}-link').click()"
                                            @endif>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    </div>
                                    @endforeach
                                    @foreach ($data as $lang)
                                    <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Warehouse Close Time') }}
                                        </label>
                                        <input type="time" name="close_time[]" class="form-control"
                                            maxlength="255" {{$lang['status'] == true ? '':''}} value="{{$warehouses->close_time}}"
                                            @if($lang['status']==true) 
                                            oninvalid="document.getElementById('{{$lang['code']}}-link').click()"
                                            @endif>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="tio-user"></i>
                                    {{translate('Business information')}}
                                </h5>
                            </div>
                            <div class="card-body pt-sm-0 pb-sm-4">
                                <div class="row align-items-end g-4  mt-3">


                                    @foreach ($data as $lang)
                                    <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('BRN Number') }}
                                            <!-- ({{ strtoupper($lang['code']) }}) -->
                                        </label>
                                        <input type="text" name="brn_number[]" class="form-control" maxlength="255"
                                            {{$lang['status'] == true ? '':''}} @if($lang['status']==true) value="{{$warehouses->brn_number}}"
                                            oninvalid="document.getElementById('{{$lang['code']}}-link').click()"
                                            @endif>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    </div>
                                    @endforeach

                                    @foreach ($data as $lang)
                                    <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('MSME Number') }}
                                            <!-- ({{ strtoupper($lang['code']) }}) -->
                                        </label>
                                        <input type="text" name="msme_number[]" class="form-control" maxlength="255" value="{{$warehouses->msme_number}}"
                                            {{$lang['status'] == true ? '':''}} @if($lang['status']==true)
                                            oninvalid="document.getElementById('{{$lang['code']}}-link').click()"
                                            @endif>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>    <div class="col-sm-12">
                        <div class="card"> 
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="tio-poi"></i>
                                    {{translate('Warehouse location')}}
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="form-group mb-0">
                                                    <label class="form-label text-capitalize" for="latitude">{{ translate('latitude') }}
                                                        <i class="tio-info-outined"
                                                            data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ translate('click_on_the_map_select_your_default_location') }}">
                                                        </i> 
                                                    </label>
                                                    <input type="text" id="latitude" name="latitude" class="form-control"
                                                            placeholder="{{ translate('Ex:') }} 23.8118428" value="{{$warehouses->latitude}}"
                                                            value="{{ old('latitude') }}"  readonly>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group mb-0">
                                                    <label class="form-label text-capitalize" for="longitude">{{ translate('longitude') }}
                                                        <i class="tio-info-outined"
                                                            data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ translate('click_on_the_map_select_your_default_location') }}">
                                                        </i>
                                                    </label>
                                                    <input type="text" step="0.1" name="longitude" class="form-control"
                                                            placeholder="{{ translate('Ex:') }} 90.356331" id="longitude" value="{{$warehouses->longitude}}"
                                                            value="{{ old('longitude') }}"  readonly>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group mb-0">
                                                    <label class="input-label">
                                                        {{translate('coverage (km)')}}
                                                        <i class="tio-info-outined"
                                                            data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ translate('This value is the radius from your branch location, and customer can order inside  the circle calculated by this radius. The coverage area value must be less or equal than 1000.') }}">
                                                        </i>
                                                    </label>
                                                    <input type="number" name="coverage"  value="{{$warehouses->coverage}}" min="1" max="1000" class="form-control" placeholder="{{ translate('Ex : 3') }}" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="location_map_div">
                                        <input id="pac-input" class="controls rounded" data-toggle="tooltip"
                                                data-placement="right" name="map_location"
                                                data-original-title="{{ translate('search_your_location_here') }}"
                                                type="text" placeholder="{{ translate('search_here') }}" />
                                        <div id="location_map_canvas" class="overflow-hidden rounded" style="height: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="tio-clock"></i>
                                    {{translate('order revised time slot')}}
                                </h5>
                            </div>
                            <div class="card-body pt-sm-0 pb-sm-4">
                                <div class="row align-items-end g-4  mt-3">
                                <table class="table table-bordered" id="order_revise">
                                    <tr>
                                        <th>Open Time</th>
                                        <th> Close Time</th>
                                        <th>  </th>
                                    </tr>

                                        <?php
                                        $time =  $warehouses->revise_open_time;
                                      $t =   implode(', ', $time);
                                   echo $t;
                                   die;
                                        ?>
                                        <td><input type="time"   value="{{$warehouses->revise_open_time}}" name="revise_open_time[]"class="form-control" /></td>
                                        <td><input type="time"  value="{{$warehouses->revise_close_time}}" name="revise_close_time[]"class="form-control" /></td>
                                        <td><button type="button" name="add" id="order_revise-ar" class="btn btn-outline-primary">Add More</button></td>
                                    </tr>
                                </table>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="tio-clock"></i>
                                    {{translate('delivery order time slot')}}
                                </h5>
                            </div>
                            <div class="card-body pt-sm-0 pb-sm-4">
                                <div class="row align-items-end g-4  mt-3">
                                <table class="table table-bordered" id="delivery_order">
                                    <tr>
                                        <th>Open Time</th>
                                        <th> Close Time</th>
                                        <th>  </th>
                                    </tr>
                                    <tr>
                                        <td><input type="time" name="delivery_open_time[]"class="form-control" /></td>
                                        <td><input type="time" name="delivery_close_time[]"class="form-control" /></td>
                                        <td><button type="button" name="add" id="delivery_order-ar" class="btn btn-outline-primary">Add More</button></td>
                                    </tr>
                                </table>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="tio-clock"></i>
                                    {{translate('order cancel time slot')}}
                                </h5>
                            </div>
                            <div class="card-body pt-sm-0 pb-sm-4">
                                <div class="row align-items-end g-4  mt-3">
                                <table class="table table-bordered" id="order_cancel">
                                    <tr>
                                        <th>Open Time</th>
                                        <th> Close Time</th>
                                        <th>  </th>
                                    </tr>
                                    <tr>
                                        <td><input type="time" name="order_cancel_open_time[]"class="form-control" /></td>
                                        <td><input type="time" name="order_cancel_close_time[]"class="form-control" /></td>
                                        <td><button type="button" name="add" id="order_cancel-ar" class="btn btn-outline-primary">Add More</button></td>
                                    </tr>
                                </table>

                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="tio-clock"></i>
                                    {{translate('Pre-order time slot')}}
                                </h5>
                            </div>
                            <div class="card-body pt-sm-0 pb-sm-4">
                                <div class="row align-items-end g-4  mt-3">
                                <table class="table table-bordered" id="pre_order">
                                    <tr>
                                        <th>Open Time</th>
                                        <th> Close Time</th>
                                        <th>  </th>
                                    </tr>
                                    <tr>
                                        <td><input type="time" name="pre_order_open_time[]"class="form-control" /></td>
                                        <td><input type="time" name="pre_order_close_time[]"class="form-control" /></td>
                                        <td><button type="button" name="add" id="pre_order-ar" class="btn btn-outline-primary">Add More</button></td>
                                    </tr>
                                </table>

                                    <input name="position" value="0" hidden>

                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="card">
                             <div class="col-12">
                                <div class="btn--container justify-content-end">
                                    <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                                    <button type="submit"
                                        class="btn btn--primary">{{translate('submit')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </form>
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

@extends('layouts.admin.app')

@section('title', translate('Add new Unit'))

@push('css_or_js')
<style>

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
                {{translate('warehouse_setup')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->

    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
            <form action="{{route('admin.warehouse.store')}}" method="post" enctype="multipart/form-data">
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
                                <input type="hidden" id="prev_id" value="{{$prevId}}">
                                <div class="row align-items-end g-4">
                                    @foreach ($data as $lang)
                                    <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Select Warehouse Admin') }}
                                            <!-- ({{ strtoupper($lang['code']) }}) -->
                                        </label>
                                        <select name="city_id[]" class=" form-control">
                                            <option value="" disabled selected>Select Name</option>
                                            @foreach(\App\Model\Admin::orderBy('id',
                                            'DESC')->where('admin_role_id',3)->get() as $admin)
                                            <option value="{{$admin['id']}}">{{$admin['f_name']}} {{$admin['l_name']}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    @endforeach

                                    @foreach ($data as $lang)
                                    <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Select City') }}
                                            <!-- ({{ strtoupper($lang['code']) }}) -->
                                        </label>
                                        <select id="city_code" name="city_id[]" class="city_code form-control">
                                            <option value="" disabled selected>Select City</option>
                                            @foreach(\App\Model\City::orderBy('id',
                                            'DESC')->where(['state_id'=>19])->get() as $city)
                                            <option value="{{$city['id']}}" id="city_alpha_code_{{$city['id']}} "
                                                data-val="<?php echo $city->city_code; ?>">{{$city['city']}}</option>
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
                                        <input type="text" name="name[]" class="form-control" maxlength="255"
                                            {{$lang['status'] == true ? '':''}} @if($lang['status']==true)
                                            oninvalid="document.getElementById('{{$lang['code']}}-link').click()"
                                            @endif>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    @endforeach
                                    <!-- <p>Data Attribute Value: <span id="dataAttributeValue"></span></p> -->
                                    @foreach ($data as $lang)
                                    <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Warehouse Code') }}
                                        </label>
                                        <!-- <a href="javascript:void(0)" class="float-right c1 fz-12"
                                            onclick="generateCode()">{{translate('generate_code')}}</a> -->
                                        <input type="text" name="code[]" class="form-control" readonly value=""
                                            id="dataAttributeValue"
                                            placeholder="{{\Illuminate\Support\Str::random(8)}}">
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
                                        <textarea type="text" name="address[]" class="form-control" maxlength="255"
                                            {{$lang['status'] == true ? '':''}} @if($lang['status']==true)
                                            oninvalid="document.getElementById('{{$lang['code']}}-link').click()"
                                            @endif></textarea>
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
                                        <input type="time" name="open_time[]" class="form-control" maxlength="255"
                                            {{$lang['status'] == true ? '':''}} @if($lang['status']==true)
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
                                        <input type="time" name="close_time[]" class="form-control" maxlength="255"
                                            {{$lang['status'] == true ? '':''}} @if($lang['status']==true)
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
                                            {{$lang['status'] == true ? '':''}} @if($lang['status']==true)
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
                                        <input type="text" name="msme_number[]" class="form-control" maxlength="255"
                                            {{$lang['status'] == true ? '':''}} @if($lang['status']==true)
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
                                                    <label class="form-label text-capitalize"
                                                        for="latitude">{{ translate('latitude') }}
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ translate('click_on_the_map_select_your_default_location') }}">
                                                        </i>
                                                    </label>
                                                    <input type="text" id="latitude" name="latitude"
                                                        class="form-control"
                                                        placeholder="{{ translate('Ex:') }} 23.8118428"
                                                        value="{{ old('latitude') }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group mb-0">
                                                    <label class="form-label text-capitalize"
                                                        for="longitude">{{ translate('longitude') }}
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ translate('click_on_the_map_select_your_default_location') }}">
                                                        </i>
                                                    </label>
                                                    <input type="text" step="0.1" name="longitude" class="form-control"
                                                        placeholder="{{ translate('Ex:') }} 90.356331" id="longitude"
                                                        value="{{ old('longitude') }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group mb-0">
                                                    <label class="input-label">
                                                        {{translate('coverage (km)')}}
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ translate('This value is the radius from your branch location, and customer can order inside  the circle calculated by this radius. The coverage area value must be less or equal than 1000.') }}">
                                                        </i>
                                                    </label>
                                                    <input type="number" name="coverage" min="1" max="1000"
                                                        class="form-control" placeholder="{{ translate('Ex : 3') }}"
                                                        value="{{ old('coverage') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="location_map_div">
                                        <input id="pac-input" class="controls rounded" data-toggle="tooltip"
                                            data-placement="right" name="map_location"
                                            data-original-title="{{ translate('search_your_location_here') }}"
                                            type="text" placeholder="{{ translate('search_here') }}" />
                                        <div id="location_map_canvas" class="overflow-hidden rounded"
                                            style="height: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="tio-user"></i>
                                    {{translate('Owner information')}}
                                </h5>
                            </div>
                            <div class="card-body pt-sm-0 pb-sm-4">
                                <div class="row align-items-end g-4  mt-3"> -->
                    <!-- 
                                    @foreach ($data as $lang)
                                    <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Owner Name') }}
                                            
                                        </label>
                                        <input type="text" name="owner_name[]" class="form-control" maxlength="255"
                                            style="text-tranform:capitalize" {{$lang['status'] == true ? '':''}}
                                            @if($lang['status']==true)
                                            oninvalid="document.getElementById('{{$lang['code']}}-link').click()"
                                            @endif>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    </div>
                                    @endforeach
                                    @foreach ($data as $lang)
                                    <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Owner Contact Number') }}
                                            
                                        </label>
                                        <input type="number" name="owner_number[]" class="form-control" maxlength="255"
                                            {{$lang['status'] == true ? '':''}} @if($lang['status']==true)
                                            oninvalid="document.getElementById('{{$lang['code']}}-link').click()"
                                            @endif>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    </div>
                                    @endforeach

                                    @foreach ($data as $lang)
                                    <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Owner Second Contact Number') }}
                                            
                                        </label>
                                        <input type="number" name="owner_second_number[]" class="form-control"
                                            maxlength="255" {{$lang['status'] == true ? '':''}}
                                            @if($lang['status']==true)
                                            oninvalid="document.getElementById('{{$lang['code']}}-link').click()"
                                            @endif>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    </div>
                                    @endforeach

                                    @foreach ($data as $lang)
                                    <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Pincode') }}
                                            
                                        </label>
                                        <input type="text" name="pin_code[]" class="form-control" maxlength="255"
                                            {{$lang['status'] == true ? '':''}} @if($lang['status']==true)
                                            oninvalid="document.getElementById('{{$lang['code']}}-link').click()"
                                            @endif>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    </div>
                                    @endforeach

                                

                                    @foreach ($data as $lang)
                                    <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label" for=" ">{{ translate('Email') }}
                                            
                                        </label>
                                        <input type="email" name="email[]" class="form-control" maxlength="255"
                                            {{$lang['status'] == true ? '':''}} @if($lang['status']==true)
                                            oninvalid="document.getElementById('{{$lang['code']}}-link').click()"
                                            @endif>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    </div>
                                    @endforeach

                                    @foreach ($data as $lang)
                                    <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Title') }}
                                            
                                        </label>
                                        <input type="text" name="title[]" class="form-control" maxlength="255"
                                            {{$lang['status'] == true ? '':''}} @if($lang['status']==true)
                                            oninvalid="document.getElementById('{{$lang['code']}}-link').click()"
                                            @endif>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    </div>
                                    @endforeach -->


                    <!-- @foreach ($data as $lang)
                                    <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('User Id') }}
                                        </label>
                                        <input type="text" name="user_id[]" class="form-control" maxlength="255"
                                            {{$lang['status'] == true ? '':''}} @if($lang['status']==true)
                                            oninvalid="document.getElementById('{{$lang['code']}}-link').click()"
                                            @endif>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    </div>
                                    @endforeach -->
                    <!-- 
                                    @foreach ($data as $lang)
                                    <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Password') }}
                                        </label>
                                        <input type="password" name="password[]" class="form-control" maxlength="255"
                                            {{$lang['status'] == true ? '':''}} @if($lang['status']==true)
                                            oninvalid="document.getElementById('{{$lang['code']}}-link').click()"
                                            @endif>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    </div>
                                    @endforeach -->

                    <!-- </div>
                            </div>
                        </div>
                    </div> -->
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
                                            <th> </th>
                                        </tr>
                                        <tr>
                                            <td><input type="time" name="revise_time[revise_open_time][]" class="form-control" />
                                            </td>
                                            <td><input type="time" name="revise_time[revise_close_time][]" class="form-control" />
                                            </td>
                                            <td><button type="button" name="add" id="order_revise-ar"
                                                    class="btn btn-outline-primary">Add More</button></td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-sm-6">
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
                                            <th> </th>
                                        </tr>
                                        <tr>
                                            <td><input type="time" name="delivery_open_time[]" class="form-control" />
                                            </td>
                                            <td><input type="time" name="delivery_close_time[]" class="form-control" />
                                            </td>
                                            <td><button type="button" name="add" id="delivery_order-ar"
                                                    class="btn btn-outline-primary">Add More</button></td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- <div class="col-sm-6">
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
                                            <th> </th>
                                        </tr>
                                        <tr>
                                            <td><input type="time" name="order_cancel_open_time[]"
                                                    class="form-control" /></td>
                                            <td><input type="time" name="order_cancel_close_time[]"
                                                    class="form-control" /></td>
                                            <td><button type="button" name="add" id="order_cancel-ar"
                                                    class="btn btn-outline-primary">Add More</button></td>
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
                                            <th> </th>
                                        </tr>
                                        <tr>
                                            <td><input type="time" name="pre_order_open_time[]" class="form-control" />
                                            </td>
                                            <td><input type="time" name="pre_order_close_time[]" class="form-control" />
                                            </td>
                                            <td><button type="button" name="add" id="pre_order-ar"
                                                    class="btn btn-outline-primary">Add More</button></td>
                                        </tr>
                                    </table>

                                    <input name="position" value="0" hidden>


                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="col-12">
                                <div class="btn--container justify-content-end">
                                    <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                                    <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>

@endsection

@push('script_2')
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ \App\Model\BusinessSetting::where('key', 'map_api_client_key')->first()?->value }}&libraries=places&v=3.45.8">
</script>

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
function generateCode() {
    let code = Math.random().toString(34).substring(2, 12);
    $('#codegenerate').val(code)
}
</script>
<script>
$(document).ready(function() {
    function initAutocomplete() {
        var myLatLng = {

            lat: 23.811842872190343,
            lng: 90.356331
        };
        const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
            center: {
                lat: 23.811842872190343,
                lng: 90.356331
            },
            zoom: 13,
            mapTypeId: "roadmap",
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
        });

        marker.setMap(map);
        var geocoder = geocoder = new google.maps.Geocoder();
        google.maps.event.addListener(map, 'click', function(mapsMouseEvent) {
            var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
            var coordinates = JSON.parse(coordinates);
            var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
            marker.setPosition(latlng);
            map.panTo(latlng);

            document.getElementById('latitude').value = coordinates['lat'];
            document.getElementById('longitude').value = coordinates['lng'];


            geocoder.geocode({
                'latLng': latlng
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                        document.getElementById('address').innerHtml = results[1]
                            .formatted_address;
                    }
                }
            });
        });
        // Create the search box and link it to the UI element.
        const input = document.getElementById("pac-input");
        const searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
        // Bias the SearchBox results towards current map's viewport.
        map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
        });
        let markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }
            // Clear out the old markers.
            markers.forEach((marker) => {
                marker.setMap(null);
            });
            markers = [];
            // For each place, get the icon, name and location.
            const bounds = new google.maps.LatLngBounds();
            places.forEach((place) => {
                if (!place.geometry || !place.geometry.location) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                var mrkr = new google.maps.Marker({
                    map,
                    title: place.name,
                    position: place.geometry.location,
                });
                google.maps.event.addListener(mrkr, "click", function(event) {
                    document.getElementById('latitude').value = this.position.lat();
                    document.getElementById('longitude').value = this.position.lng();
                });

                markers.push(mrkr);

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
    };
    initAutocomplete();
});


$('.__right-eye').on('click', function() {
    if ($(this).hasClass('active')) {
        $(this).removeClass('active')
        $(this).find('i').removeClass('tio-invisible')
        $(this).find('i').addClass('tio-hidden-outlined')
        $(this).siblings('input').attr('type', 'password')
    } else {
        $(this).addClass('active')
        $(this).siblings('input').attr('type', 'text')


        $(this).find('i').addClass('tio-invisible')
        $(this).find('i').removeClass('tio-hidden-outlined')
    }
})
</script>
<script type="text/javascript">
$("#order_revise-ar").click(function() {
    $("#order_revise").append(
        '<tr><td><input type="time" name="revise_time[revise_open_time][]" class="form-control" /></td><td><input type="time" name="revise_time[revise_close_time][]"class="form-control" /></td><td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td></tr>'
    );
});
$(document).on('click', '.remove-input-field', function() {
    $(this).parents('tr').remove();
});
</script>

<script type="text/javascript">
$("#order_cancel-ar").click(function() {
    $("#order_cancel").append(
        '<tr><td><input type="time" name="order_cancel_open_time[]" class="form-control" /></td><td><input type="time" name="order_cancel_close_time[]"class="form-control" /></td><td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td></tr>'
    );
});
$(document).on('click', '.remove-input-field', function() {
    $(this).parents('tr').remove();
});
</script>

<script type="text/javascript">
$("#pre_order-ar").click(function() {
    $("#pre_order").append(
        '<tr><td><input type="time" name="pre_order_open_time[]" class="form-control" /></td><td><input type="time" name="pre_order_close_time[]"class="form-control" /></td><td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td></tr>'
    );
});
$(document).on('click', '.remove-input-field', function() {
    $(this).parents('tr').remove();
});
</script>

<script type="text/javascript">
$("#delivery_order-ar").click(function() {
    $("#delivery_order").append(
        '<tr><td><input type="time" name="delivery_open_time[]" class="form-control" /></td><td><input type="time" name="delivery_close_time[]"class="form-control" /></td><td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td></tr>'
    );
});
$(document).on('click', '.remove-input-field', function() {
    $(this).parents('tr').remove();
});
</script>
<script>
$('.city_code').on('change', function() {

    var city_code = $(this).val();
    var selectedOption = $(this).find("option:selected");

    // Get the data attribute value
    var dataAttributeValue = selectedOption.attr("data-val");
    var prev_id = $('#prev_id').val();

    // Display the data attribute value in the span
    $("#dataAttributeValue").css('text-transform', 'uppercase');
    $("#dataAttributeValue").val('RJ' + dataAttributeValue + prev_id);
})
</script>


@endpush
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
                {{translate('area')}}
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
                        <div class="row align-items-end g-4" style="padding-top:50px ;">
                            <div class="col-sm-6 ">
                                <label class="form-label">{{ translate('City') }}
                                </label>
                                <select id=" " name="city_id" class="form-control " required>
                                    <option value="" disabled selected>Select City</option>
                                    @foreach(\App\Model\City::orderBy('id', 'DESC')->where(['state_id'=>19])->get() as $city)
                                    <option value="{{$city['id']}}">{{$city['city']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">{{ translate('Area') }} {{ translate('Name') }}
                                </label>
                                <input type="text" name="area" class="form-control"
                                    placeholder="{{ translate('Ex:jhotwara') }}" maxlength="255">
                            </div>

                            <div class="col-sm-4">
                                <label class="form-label">{{ translate('latitude') }} {{ translate('code') }} </label>
                                    <input type="text" name="latitude_code" id="latitude" class="form-control"
                                        placeholder="{{ translate('Ex: 24.958302') }}" maxlength="255">
                            </div>

                            <div class="col-sm-4">
                                <label class="form-label">{{ translate('longitude') }} {{ translate('code') }}
                                </label>
                                <input type="text" name="longitude_code" class="form-control" id="longitude"
                                    placeholder="{{ translate('Ex: 75.743347') }}" maxlength="255">
                            </div>

                            <div class="col-sm-4">
                                <label class="form-label">{{ translate('radius') }}
                                </label>
                                <input type="text" name="radius" class="form-control"
                                    placeholder="{{ translate('') }}" maxlength="255">
                            </div>

                            <div class="col-md-6" id="location_map_div">
                                <input id="pac-input" class="controls rounded" data-toggle="tooltip"
                                    data-placement="right"
                                    data-original-title="{{ translate('search_your_location_here') }}" type="text"
                                    placeholder="{{ translate('search_here') }}" />
                                <div id="location_map_canvas" class="overflow-hidden rounded" style="height: 100%">
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
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ \App\Model\BusinessSetting::where('key', 'map_api_client_key')->first()?->value }}&libraries=places&v=3.45.8">
</script>
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

@endpush
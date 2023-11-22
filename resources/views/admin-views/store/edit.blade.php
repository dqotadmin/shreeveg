@extends('layouts.admin.app')

@section('title', translate('Update Store'))

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
                {{translate('Update Store')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->
    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-body pt-sm-0 pb-sm-4">
                    <form action="{{route('admin.store.update',[$stores['id']])}}" method="post"
                        enctype="multipart/form-data">
                        @csrf




                        <div class="row align-items-end g-4" style="margin-top: 40px;">
                            <div class="col-sm-6">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Owner') }}
                                    {{ translate('Name') }} </label>
                                <select name="owner_id" id="get_city_name_by_owner" class="form-control">
                                    <option value="" disabled selected>Select Owner Name</option>
                                    @foreach(App\Model\Admin::where('admin_role_id',6)->get() as $store)
                                    <option value="{{$store->id}}" city-val="{{$store->city->city}}"
                                        state-id="{{$store->state_id}}" city-id="{{$store->city_id}}"
                                        {{$stores->owner_id == $store->id ? 'selected' : '';}}>
                                        {{$store->f_name}} {{$store->l_name}}</option>
                                    @endforeach
                                </select>
                            </div>
 
                            <div class="col-sm-6">
                                <label class="form-label" for=" "> {{ translate('City') }} </label>
                                <select name="city_id" id="click_on_city" class="get_city  form-control">
                                    <option value="{{$stores->city_id}}" >{{$stores->city->city}}</option>
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label" for="">{{ translate('Warehouse') }}
                                </label>
                                <select name="warehouse_id" id="" class="get_warehouse form-control">
                                <option value="{{$stores->warehouse_id}}" >{{$stores->warehouse->name}}</option>
                                </select>
                            </div>


                            <div class="col-sm-6 ">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Store') }}
                                    {{ translate('Name') }} </label>
                                <input type="text" name="name" class="form-control" value="{{$stores->name}}"
                                    placeholder="{{ translate('Ex: kheer murli') }}" maxlength="255">
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Store') }}
                                    {{ translate('Code') }} </label>
                                <input type="text" name="code" class="form-control" value="{{$stores->code}}"
                                    placeholder="{{ translate('Ex: St123') }}" maxlength="255">
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Shop') }}
                                    {{ translate('Licence') }} </label>
                                <input type="text" name="shop_licence" class="form-control"
                                    value="{{$stores->shop_licence}}" placeholder="{{ translate('Ex: 1234676') }}"
                                    maxlength="255">
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label" for="exampleFormControlInput1">
                                    {{ translate('Area Pin Code') }} </label>
                                <input type="text" name="pin_code" class="form-control" value="{{$stores->pin_code}}"
                                    placeholder="{{ translate('Ex: 311001') }}" maxlength="255">
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label" for="exampleFormControlInput1"> {{ translate('BRN No.') }}
                                </label>
                                <input type="text" name="brn_number" class="form-control"
                                    value="{{$stores->brn_number}}" placeholder="{{ translate('Ex: 5689643564') }}"
                                    maxlength="255">
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label" for="exampleFormControlInput1"> {{ translate('MSME No.') }}
                                </label>
                                <input type="text" name="msme_number" class="form-control"
                                    value="{{$stores->msme_number}}" placeholder="{{ translate('Ex: 5466766756') }}"
                                    maxlength="255">
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label" for="exampleFormControlInput1"> {{ translate('Title') }}
                                </label>
                                <input type="text" name="title" class="form-control" value="{{$stores->title}}"
                                    placeholder="{{ translate('Ex:  ') }}" maxlength="255">
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Store') }}
                                    {{ translate('Address') }} </label>
                                <textarea type="text" name="address" class="form-control"
                                    placeholder="{{ translate('Ex:  3 Station Road, Dhuri Arcade Near Vasai Bus Station, Bassein Road(vasai Road)') }}"
                                    maxlength="255">{{$stores->address}}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-capitalize" for="latitude">{{ translate('latitude') }}
                                    <i class="tio-info-outined" data-toggle="tooltip" data-placement="top"
                                        title="{{ translate('click_on_the_map_select_your_default_location') }}">
                                    </i>
                                </label>
                                <input type="text" id="latitude" name="latitude" class="form-control"
                                    placeholder="{{ translate('Ex:') }} 23.8118428" value="{{$stores->latitude}}"
                                    readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-capitalize" for="longitude">{{ translate('longitude') }}
                                    <i class="tio-info-outined" data-toggle="tooltip" data-placement="top"
                                        title="{{ translate('click_on_the_map_select_your_default_location') }}">
                                    </i>
                                </label>
                                <input type="text" step="0.1" name="longitude" class="form-control"
                                    placeholder="{{ translate('Ex:') }} 90.356331" id="longitude"
                                    value="{{$stores->longitude}}" readonly>
                            </div>
                            <div class="col-6">
                                <label class="input-label">
                                    {{translate('coverage (km)')}}
                                    <i class="tio-info-outined" data-toggle="tooltip" data-placement="top"
                                        title="{{ translate('This value is the radius from your branch location, and customer can order inside  the circle calculated by this radius. The coverage area value must be less or equal than 1000.') }}">
                                    </i>
                                </label>
                                <input type="number" name="coverage" min="1" max="1000" class="form-control"
                                    placeholder="{{ translate('Ex : 3') }}" value="{{$stores->coverage}}">
                            </div>
                            <div class="col-md-6" id="location_map_div">
                                <input id="pac-input" class="controls rounded" data-toggle="tooltip"
                                    data-placement="right" name="map_location"
                                    data-original-title="{{ translate('search_your_location_here') }}" type="text"
                                    placeholder="{{ translate('search_here') }}" />
                                <div id="location_map_canvas" class="overflow-hidden rounded" style="height: 100%">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div>
                                    <div class="text-center mb-3">
                                        <img id="viewer" class="img--105"
                                            onerror="this.src='{{asset('public/assets/admin/img/900x400/img1.jpg')}}'"
                                            src="{{asset('storage/app/public/store')}}/{{$stores['document']}}"
                                            alt="image" />
                                    </div>
                                </div>
                                <label class="form-label text-capitalize">{{ translate('Document  ') }}</label><small
                                    class="text-danger">*
                                    ( {{ translate('ratio') }}
                                    3:1 )</small>
                                <div class="custom-file">
                                    <input type="file" name="document" id="customFileEg1" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                        oninvalid="document.getElementById('en-link').click()">
                                    <label class="custom-file-label" for="customFileEg1">{{ translate('choose') }}
                                        {{ translate('file') }}</label>
                                </div>

                            </div>
                            <div class="col-12">
                                <div class="btn--container justify-content-end">
                                    <a type="button" href="{{route('admin.store.index')}}"
                                        class="btn btn--reset">{{translate('Back')}}</a>
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
$(document).ready(function() {
    $('#get_city_name_by_owner').on('change', function() {
        var owner_id = $(this).val();
        var selectedOption = $(this).find('option:selected');
        var city_id = selectedOption.attr('city-id');
        var city_name = selectedOption.attr('city-val');
        let stateId = selectedOption.attr('state-id');
        //console.log(stateId);
        getWarehouse(city_id);
        $('.get_city').empty();
        $('.get_city').append('<option value="' + city_id + '">' + city_name + '</option>');
    });
    // $('.get_city').html('<option value="">Select City</option>');

    $('#click_on_city').on('click', function() {
        let selectedOptionOwner = $('#get_city_name_by_owner').find('option:selected');
        var stateId = selectedOptionOwner.attr('state-id');
       
        $.ajax({
            url: '{{url('/')}}/admin/store/get-city-by-owner/'+stateId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // console.log(data);
                $.each(data.city, function(key, value) {
                    // $('.get_city').empty();
                    $('.get_city').append('<option value="' + value.id + '">' +
                        value.city + '</option>');

                });
            }
        });
    });
});
$('.get_city').on('change', function() {
    getWarehouse($(this).val());
});

function getWarehouse(cityId = null) {
    $('.get_warehouse').empty();
    $.ajax({
        url: '{{url('/')}}/admin/store/get-warehouse-by-city/' + cityId,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.message) {
                var message = data.message;
                Swal.fire({
                title: 'Alert',
                html: message,
                icon: 'info',
                confirmButtonText: 'OK'
            });
                // $('.get_warehouse').html('<option value="">'+ message +'</option>');
            } else {
                // Clear previous options
                $('#get_warehouse').empty();
                $.each(data.warehouse, function(key, value) {
                    $('.get_warehouse').append('<option value="' + value.id + '">' +
                        value.name + '</option>');

                });
            }

        }
    });
};
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
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ \App\Model\BusinessSetting::where('key', 'map_api_client_key')->first()?->value }}&libraries=places&v=3.45.8">
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
</script>

</body>
@endpush
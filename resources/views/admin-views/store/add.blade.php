@extends('layouts.admin.app')

@section('title', translate('Add New Store'))

@push('css_or_js')

@endpush

@section('content')
<?php

$warehouse_id = auth('admin')->user()->warehouse_id;


 $stores = \App\Model\Warehouse::where('id',$warehouse_id)->first();
?>
<div class="content container-fluid">

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{asset('public/assets/admin/img/category.png')}}" class="w--24" alt="">
            </span>
            <span>
                {{translate('Store')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->
    <form action="{{route('admin.store.store')}}" method="post" id="timeForm" enctype="multipart/form-data" class="needs-validation form_customer" novalidate>
        <div class="row g-2">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="tio-user"></i>
                            {{translate('Owner information')}}
                        </h5>
                    </div>
                    <div class="card-body pt-sm-0 pb-sm-4">
                        @csrf
                        @php($data = Helpers::get_business_settings('language'))
                        @php($default_lang = Helpers::get_default_language())
                        {{-- @php($default_lang = 'en') --}}

                        </ul>
                        <!-- <input type="text" id="prev_id" value="{{$prevId}}"> -->
                        <div class="row align-items-end g-4" style="margin-top: 40px;">
                            <div class="col-sm-6">
                                <label class="form-label" for=" "> {{ translate('City') }} </label>
                                @if(auth('admin')->user()->admin_role_id == 3)
                                <input value="{{auth('admin')->user()->city->city}}" class="form-control manually-border-color" readonly>
                                <input value="{{auth('admin')->user()->city_id}}" name="city_id" type="hidden" class="form-control" readonly>
                                @else
                                <select name="city_id" id="click_on_city" class="sget_city  form-control" required>
                                    <option value="" disabled selected>Select City</option>
                                    @foreach(\App\Model\City::where('status','1')->get() as $city)
                                    <option value="{{$city->id}}">{{$city->city}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please select city.
                                </div>
                                @endif
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('Warehouse') }}
                                </label>
                                @if(auth('admin')->user()->admin_role_id == 3)
                                <input value="{{auth('admin')->user()->warehouse->name}}" class="form-control" readonly>
                                <input value="{{auth('admin')->user()->warehouse_id}}" name="warehouse_id" type="hidden" class="form-control" readonly>
                                @else
                                <select name="warehouse_id" id="" class="select_wh_option get_warehouse form-control" required>
                                </select>
                                <div class="invalid-feedback">
                                    Please select warehouse.
                                </div>
                                @endif
                                <span class="warehoues_error"></span>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="tio-user"></i>
                            {{translate('Store information')}}
                        </h5>
                    </div>
                    <div class="card-body pt-sm-0 pb-sm-4">
                    <div class="row align-items-end g-4" style="margin-top: 40px;">

                        <div class="col-sm-4">
                            <label class="form-label" for="exampleFormControlInput1">{{ translate('Store') }}
                                {{ translate('Name') }} </label>
                            <input type="text" name="name" class="form-control"
                                placeholder="{{ translate('Ex: Store Name') }}"  maxlength="255" required>
                                <div class="invalid-feedback">
                                    Please enter store name.
                                </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="exampleFormControlInput1">{{ translate('Store') }}
                                {{ translate('Code') }} </label>
                            <input type="text" name="code" class="get_wh_code form-control"
                                style="text-transform: uppercase;"placeholder="{{ translate('Ex: QUINN1') }}" 
                                maxlength="255" required>
                                <div class="invalid-feedback">
                                    Please enter store code.
                                </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="exampleFormControlInput1">{{ translate('Store') }}
                                {{ translate('Rating') }} </label>
                            <input   min="1" max="5" name="admin_rating" class="form-control" oninput="validateRating(this)"
                            type="text"  pattern="^\d+(\.\d+)?$"   placeholder="{{ translate('Ex: 1 to 5') }}"  required>
                            
                                <div class="invalid-feedback">
                                Please enter a valid rating between 1 and 5.
                                </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="exampleFormControlInput1">{{ translate('Shop') }}
                                {{ translate('Licence') }} </label>
                            <input type="text" name="shop_licence" class="form-control manually-border-color"  
                                placeholder="{{ translate('Ex: Shop Licence') }}" maxlength="255">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="exampleFormControlInput1">
                                {{ translate('Area') }} </label>
                            <select name="area_id" id="" class="form-control" required>
                                <option value="">Select Area</option>
                                @foreach(\App\Model\CityArea::where('status','1')->get() as $area)
                                <option value="{{$area->id}}">{{$area->area}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                    Please enter store area.
                                </div>
                        </div>
                        <div class="col-sm-4">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('GST Number') }} </label>
                                        <input type="text" name="gst_number" class="form-control manually-border-color"
                                            id=" ">
                                            <div class="invalid-feedback">
                                            Please enter store GST number.
                                        </div>
                                    </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="exampleFormControlInput1">{{ translate('Store') }}
                                {{ translate('Address') }} </label>
                            <textarea type="text" name="address" class="form-control"
                                placeholder="{{ translate('Ex: Address)') }}" maxlength="255" required></textarea>
                                <div class="invalid-feedback">
                                    Please enter store address.
                                </div>
                        </div>
                       
                        <div class="col-sm-6">
                                <div>
                                    <div class="text-center mb-3">
                                        <img id="viewer" class="img--105"
                                            onerror="this.src='{{asset('public/assets/admin/img/900x400/img1.jpg')}}'"
                                            src="{{asset('public/assets/admin/img/900x400/img1.jpg')}}/"
                                            alt="image" />
                                    </div>
                                </div>
                                <label class="form-label text-capitalize">{{ translate('Document  ') }}</label> 
                                <div class="custom-file">
                                    <input type="file" name="document" id="customFileEg1" class="custom-file-input manually-border-color"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                        oninvalid="document.getElementById('en-link').click()">
                                    <label class="custom-file-label manually-border-color" for="customFileEg1">{{ translate('choose') }}
                                        {{ translate('file') }}</label>
                                </div>

                            </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="tio-user"></i>
                            {{translate('Business information')}}
                        </h5>
                    </div>
                    <div class="card-body pt-sm-0 pb-sm-4">
                        <div class="row align-items-end g-4" style="margin-top: 40px;">
                            <div class="col-sm-6">
                                <label class="form-label" for="exampleFormControlInput1">
                                    {{ translate('BRN No.') }}
                                </label>
                                <input type="text" name="brn_number" class="form-control manually-border-color"
                                    placeholder="{{ translate('Ex: BRN No.') }}" maxlength="255">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="exampleFormControlInput1">
                                    {{ translate('MSME No.') }}
                                </label>
                                <input type="text" name="msme_number" class="form-control manually-border-color"
                                    placeholder="{{ translate('Ex: MSME No.') }}" maxlength="255">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="tio-time"></i>
                            {{translate('Store Time')}}
                        </h5>
                    </div>
                    <div class="card-body pt-sm-0 pb-sm-4">
                        <div class="row align-items-end g-4" style="margin-top: 40px;">
                            <div class="col-sm-6">
                                <label class="form-label" for="exampleFormControlInput1">
                                    {{ translate('open_time') }}
                                </label>
                                <input type="time" name="open_time"
                                                    class="open_time form-control" value="" disabled required />
                               <div class="invalid-feedback">
                                    Please enter store open time.
                                </div> 
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="exampleFormControlInput1">
                                    {{ translate('close_time') }}
                                </label>
                                <input type="time" name="close_time"
                                                    class="close_time form-control"  value="" disabled required />
                                <div class="invalid-feedback">
                                    Please enter store close time.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="tio-user"></i>
                            {{translate('Location')}}
                        </h5>
                    </div>
                    <div class="card-body ">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <label class="form-label text-capitalize"
                                                for="latitude">{{ translate('latitude') }}
                                                <i class="tio-info-outined" data-toggle="tooltip" data-placement="top"
                                                    title="{{ translate('click_on_the_map_select_your_default_location') }}">
                                                </i>
                                            </label>
                                            <input type="text" id="latitude" name="latitude" class="form-control"
                                                placeholder="{{ translate('Ex:') }} 23.8118428"
                                                value="{{ old('latitude') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <label class="form-label text-capitalize"
                                                for="longitude">{{ translate('longitude') }}
                                                <i class="tio-info-outined" data-toggle="tooltip" data-placement="top"
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
                                                <i class="tio-info-outined" data-toggle="tooltip" data-placement="top"
                                                    title="{{ translate('This value is the radius from your branch location, and customer can order inside  the circle calculated by this radius. The coverage area value must be less or equal than 1000.') }}">
                                                </i>
                                            </label>
                                            <input type="number" name="coverage" min="1" max="1000" class="form-control"
                                                placeholder="{{ translate('Ex : 3') }}" value="{{ old('coverage') }}" required>
                                                <div class="invalid-feedback">
                                                    Please enter coverage(km).
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="location_map_div">
                                <input id="pac-input" class="controls rounded" data-toggle="tooltip"
                                    data-placement="right" name="map_location"
                                    data-original-title="{{ translate('search_your_location_here') }}" type="text"
                                    placeholder="{{ translate('search_here') }}" />
                                <div id="location_map_canvas" class="overflow-hidden rounded" style="height: 100%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="btn--container justify-content-end">
                    <a type="button" href="{{route('admin.store.list')}}"
                        class="btn btn--reset">{{translate('Back')}}</a>
                    <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                </div>
            </div>
        </div>

    </form>
</div>

@endsection

@push('script_2')
<script>
function validateRating(input) {
 
    if(input.value == ''){
        input.value = '';

    }
    if (input.value > 5) {
        input.setCustomValidity('Rating must be between 1 and 5');
        input.value = '';
    } else {
        input.setCustomValidity('');
    }
}
</script>
<script>
$(document).ready(function() {
    $('#click_on_city').on('change', function() {
        $('.open_time').val('');
    $('.close_time').val('');
        $('.get_wh_code').val('');
        var citySelected = $(this).find('option:selected');
        var cityId = citySelected.val();
        $.ajax({
            url: 'get-warehouse/' + cityId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log(data);
                if (data.message) {
                    var message = data.message;
                    Swal.fire({
                        title: 'Alert',
                        html: message,
                        icon: 'info',
                        confirmButtonText: 'OK'
                    }).then((result)=>{
                    location.reload();
                });
                    // $('.get_warehouse').html('<option value="">'+ message +'</option>');
                } else {
                    $('.get_warehouse').empty();

                    // Add the default option
                    $('.get_warehouse').html('<option value="">Select Warehouse</option>');

                    $.each(data.warehouse, function(key, value) {
                        $('.get_warehouse').append('<option code="' + value.code +
                            '" value="' +
                            value
                            .id + '">' +
                            value.name + '</option>');
                    });

                    console.log(data.prevId);
                    $('.get_warehouse').on('change', function() {
                        var code = $(this).find('option:selected');
                        var selectedCode = code.attr('code');
                        var prev_id = $('#prev_id').val();
                        console.log(345);

                        $('.get_wh_code').val(selectedCode + data.prevId);
                    });
                }

            }
        });
    });
});
// $('#click_on_city').on('click', function() {
//     $('.get_wh_code').empty();

// });
$('.get_warehouse').on('change', function() {
 
    var warehouse_id = $(this).val();
    $.ajax({
        url: 'get-warehouse-data/' + warehouse_id,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log(data.warehouse);
           $('.open_time').val(data.warehouse.open_time);
           $('.close_time').val(data.warehouse.close_time);
    }
    });
});
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

<script>
    $(document).ready(function() {
        $('#country-dropdown').on('change', function() {
            var country_id = this.value;
            $("#state-dropdown").html('');
            $.ajax({
                url: "{{url('get-states-by-country')}}",
                type: "POST",
                data: {
                    country_id: country_id,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    $.each(result.states, function(key, value) {
                        $("#state-dropdown").append('<option value="' + value.id +
                            '">' + value.name + '</option>');
                    });
                    $('#city-dropdown').html(
                        '<option value="">Select State First</option>');
                }
            });
        });
        $('#state-dropdown').on('change', function() {
            var state_id = this.value;
            $("#city-dropdown").html('');
            $.ajax({
                url: "{{url('get-cities-by-state')}}",
                type: "POST",
                data: {
                    state_id: state_id,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    $.each(result.cities, function(key, value) {
                        $("#city-dropdown").append('<option value="' + value.id +
                            '">' +
                            value.name + '</option>');
                    });
                }
            });
        });
    });
</script>
</body>
@endpush




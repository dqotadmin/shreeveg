@extends('layouts.admin.app')

@section('title', translate('Update Warehouse'))

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
                {{ translate('Update Warehouse') }}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->


    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
            <form action="{{route('admin.warehouse.update',[$warehouses['id']])}}" method="post" id="timeForm" enctype="multipart/form-data" class="needs-validation form_customer" novalidate>
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

                                <input type="hidden" id="prev_id" value=" ">
                                <div class="row align-items-end g-4" style="padding-top: 50px;">
                                    <div class="col-sm-4">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Select City') }}

                                        </label>
                                        <select id="city_code" name="city_id" class="city_code form-control" required>
                                            <option value="" disabled selected>Select City</option>
                                            @foreach(\App\Model\City::orderBy('id','DESC')->where(['state_id'=>19])->where('status','1')->get() as $city)
                                            <option value="{{$city['id']}}" id="city_alpha_code_{{$city['id']}} "
                                                data-val="<?php echo $city->city_code; ?>"
                                                {{$warehouses->city_id ==$city->id ? 'selected': ''}}>{{$city['city']}}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select city.
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Warehouse Code') }} </label>
                                        <input type="text" name="code" class="form-control city_by_code" value="{{$warehouses->code}}" style="color:green"
                                            id="city_by_code" required>
                                        <div class="invalid-feedback">
                                            Please enter warehouse code.
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Warehouse Name') }} </label>
                                        <input type="text" name="name" value="{{$warehouses->name}}"
                                            class="form-control" maxlength="255"required>
                                            <div class="invalid-feedback">
                                            Please enter warehouse name.
                                            </div>
                                    </div>
                                    {{-- <div class="col-sm-4">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Warehouse Address') }} </label>
                                        <textarea type="text" name="address" class="form-control" required
                                            maxlength="255">{{$warehouses->address}}</textarea>
                                            <div class="invalid-feedback">
                                            Please enter warehouse address.
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-4">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('GST Number') }} </label>
                                        <input type="text" name="gst_number" class="form-control manually-border-color" value="{{$warehouses->gst_number}}"
                                            id="city_by_code">
                                            <div class="invalid-feedback">
                                            Please enter warehouse GST number.
                                        </div>
                                    </div>
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

                                    <div class="col-sm-6">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('Warehouse Open Time') }}
                                        </label>
                                        <input type="time" name="open_time" class="form-control"
                                            value="{{$warehouses->open_time}}" required>
                                        <div class="invalid-feedback">
                                            Please enter warehouse open time.
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <label class="form-label" for=" ">{{ translate('Warehouse Close Time') }}
                                        </label>
                                        <input type="time" name="close_time" class="form-control"
                                            value="{{$warehouses->close_time}}" required>
                                        <div class="invalid-feedback">
                                            Please enter warehouse close time.
                                        </div>

                                    </div>
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
                                    <div class="col-sm-6">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('BRN Number') }} </label>
                                        <input type="text" name="brn_number" class="form-control manually-border-color"  
                                            value="{{$warehouses->brn_number}}">
                                    </div>

                                    <div class="col-sm-6">
                                        <label class="form-label"
                                            for="exampleFormControlInput1">{{ translate('MSME Number') }} </label>
                                        <input type="text" name="msme_number" class="form-control manually-border-color"  
                                            value="{{$warehouses->msme_number}}">
                                    </div>
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
                                            <div class="col-6">
                                                <div class="form-group mb-0">
                                                    <label class="form-label text-capitalize"
                                                        for="search_location">{{ translate('radius') }}
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ translate('radius in KM') }}">
                                                        </i>
                                                    </label>
                                                    <select name="coverage" class="form-control" id="radius" style="width: 270px;padding: 3px;">
                                                        @for($i=1; $i<=25; $i++)
                                                               <option value="{{$i}}" {{($i == $warehouses->coverage)?'selected':''}} >{{$i}} KM</option>
                                                       @endfor
                                                       </select>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group mb-0">
                                                    <label class="form-label text-capitalize"
                                                        for="address">{{ translate('address') }}
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ translate('google map address') }}">
                                                        </i>
                                                    </label>
                                                    <input type="text" id="pac-input" name="address"
                                                        class="form-control"
                                                        placeholder="{{ translate('Ex:') }} Nagaur, Rajasthan 341001, India"
                                                        value="{{$warehouses->address}}">
                                                </div>
                                            </div>
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
                                                        value="{{$warehouses->latitude}}" value="{{ old('latitude') }}"
                                                        readonly>
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
                                                        value="{{$warehouses->longitude}}"
                                                        value="{{ old('longitude') }}" readonly>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="location_map_div">
                                       
                                        <div id="location_map_canvas" class="overflow-hidden rounded"
                                            style="height: 100%"></div>
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
                                    {{translate('delivery order time slot')}}
                                </h5>
                            </div>
                            <div class="card-body pt-sm-0 pb-sm-4">
                                <div class="row align-items-end g-4  mt-3">
                                    <table class="table table-bordered" id="box-delivery-pair">
                                        <tr>
                                            <th>Start Time</th>
                                            <th> End Time</th>
                                            <th> Hide Option Before(In minutes)</th>
                                            <th> <button type="button" id="add-delivery-pair"
                                                    class="remove-delivery-pair btn btn-outline-success">Add
                                                    More</button> </th>

                                        </tr>
                                        @if($warehouses->delivery_time)
                                        @foreach(json_decode($warehouses->delivery_time,true) as $key => $warehouse)

                                        <tr class="row-delivery-pair">
                                            <td><input type="time" name="delivery_open_time[]"
                                                    class="form-control input-delivery-pair" required
                                                    value="{{$warehouse['open']}}">
                                            </td>
                                            <td><input type="time" name="delivery_close_time[]"
                                                    class="form-control input-delivery-pair" required
                                                    value="{{$warehouse['close']}}">
                                            </td>
                                            <td><input type="number" name="hide_option_before[]"
                                                    class="form-control input-delivery-pair" required value="{{$warehouse['hide_option_before']}}"/>
                                            </td>
                                            <td><button type="button"
                                                    class="remove-delivery-pair btn btn-outline-danger">Remove</button>
                                            </td>

                                        </tr>
                                        @endforeach
                                        @else

                                        <tr class="row-delivery-pair">
                                            <td><input type="time" name="delivery_open_time[]"
                                                    class="form-control delivery_open_time" required>
                                            </td>
                                            <td><input type="time" name="delivery_close_time[]"
                                                    class="form-control delivery_close_time" required>
                                            </td>
                                            <td><button type="button" class="remove-delivery-pair
                                                     btn btn-outline-danger">Remove</button>
                                            </td>

                                        </tr>
                                        @endif
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
                                    <table class="table table-bordered" id="box-pre-order-pair">
                                        <tr>
                                            <th>Start Time</th>
                                            <th> End Time</th>
                                            <!-- <th> <button type="button" id="add-pre-order-pair"
                                                    class="remove-pre-order-pair btn btn-outline-success">Add
                                                    More</button> </th> -->

                                        </tr>
                                        
                                        @if(isset($warehouses->pre_order_time) )
                                      
                                        @foreach(json_decode($warehouses->pre_order_time,true) as $key => $warehouse)
                                            <tr class="row-pre-order-pair">
                                                <td><input type="time" name="pre_order_open_time[]"
                                                        class="form-control input-pre-order-pair manually-border-color" required
                                                        value="{{@$warehouse['open']}}" />
                                                </td>
                                                <td><input type="time" name="pre_order_close_time[]"
                                                        class="form-control input-pre-order-pair manually-border-color" required
                                                        value="{{@$warehouse['close']}}" />
                                                </td>
                                                <!-- <td><button type="button"
                                                        class="remove-pre-order-pair btn btn-outline-danger">Remove</button>
                                                </td> -->

                                            </tr>
                                        @endforeach
                                        @else
                                        <tr class="row-pre-order-pair">
                                            <td><input type="time" name="pre_order_open_time[]"
                                                    class="form-control input-pre-order-pair manually-border-color" 
                                                    value="" />
                                            </td>
                                            <td><input type="time" name="pre_order_close_time[]"
                                                    class="form-control input-pre-order-pair manually-border-color" 
                                                    value="" />
                                            </td>
                                             

                                        </tr>
                                        @endif
                                    </table>

                                    <input name="position" value="0" hidden>


                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="btn--container justify-content-end">
                        <a type="button" href="{{route('admin.warehouse.list')}}"  class="btn btn--reset">{{translate('Back')}}</a>
                            <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script_2')<script
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
    
    $(document).ready(function () {
    var map;
    var marker;
    var circle;

    function initAutocomplete() {
        // Assuming you have existing latitude and longitude values
        var existingLatitude = parseFloat($('#latitude').val()) || 23.811842872190343;
        var existingLongitude = parseFloat($('#longitude').val()) || 90.356331;

        var myLatLng = {
            lat: existingLatitude,
            lng: existingLongitude
        };

        map = new google.maps.Map(document.getElementById("location_map_canvas"), {
            center: myLatLng,
            zoom: 13,
            mapTypeId: "roadmap",
        });

        marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
        });

        marker.setMap(map);
        var geocoder = new google.maps.Geocoder();

        // Draw the circle on page load
        drawCircle(myLatLng);

        google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
            var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
            var coordinates = JSON.parse(coordinates);
            var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
            marker.setPosition(latlng);
            map.panTo(latlng);

            document.getElementById('latitude').value = coordinates['lat'];
            document.getElementById('longitude').value = coordinates['lng'];

            geocoder.geocode({
                'latLng': latlng
            }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                        document.getElementById('address').innerHTML = results[1].formatted_address;
                    }
                }
            });

            // Draw the circle when a new location is selected
            drawCircle(latlng);
        });

        const input = document.getElementById("pac-input");
        const searchBox = new google.maps.places.SearchBox(input);
       // map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);

        map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
        });

        let markers = [];

        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();
            
            if (places.length == 0) {
                return;
            }

            markers.forEach((marker) => {
                marker.setMap(null);
            });
            markers = [];

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

                $('#latitude').val(mrkr.position.lat());
                $('#longitude').val(mrkr.position.lng());

                google.maps.event.addListener(mrkr, "click", function (event) {
                    document.getElementById('latitude').value = this.position.lat();
                    document.getElementById('longitude').value = this.position.lng();

                    // Draw the circle when a suggestion is selected
                    drawCircle(this.position);
                });

                markers.push(mrkr);

                if (place.geometry.viewport) {
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });

        // Function to draw circle
        function drawCircle(center) {
            var radius = parseFloat($('#radius').val());
            if (!isNaN(radius)) {
                // Check if a circle already exists
                if (circle) {
                    // Update the existing circle's radius
                    circle.setRadius(radius * 1000);
                } else {
                    // Create a new circle
                    circle = new google.maps.Circle({
                        map: map,
                        fillColor: "#ADD8E6",
                        fillOpacity: 0.3,
                        strokeColor: "#0000FF",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        center: center,
                        radius: radius * 1000
                    });
                }
            }
        }

        // Draw circle on radius input change
        $('#radius').on('change', function () {
            var radius = parseFloat($(this).val());
            if (!isNaN(radius)) {
                drawCircle(marker.getPosition());
            }
        });
    }

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
$('.city_code').on('change', function() {
    var city_code = $(this).val();
    var selectedOption = $(this).find("option:selected");

    // Get the data attribute value
    var dataAttributeValue = selectedOption.attr("data-val");
    var prev_id = $('#prev_id').val();
    console.log('previous id' + 'RJ' + dataAttributeValue + prev_id);


    $.ajax({
        url: '{{url('/')}}/admin/warehouse/get-code-by-city/' + city_code,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // $('.get_warehouse').html('<option value="">'+ message +'</option>');
            $('.city_by_code').empty();
            // Add the default option
            $('.city_by_code').val(data.prevId);
            // console.log(data.prevId);
            // Display the data attribute value in the span
            var warehouse_code = 'RJ' + dataAttributeValue + data.prevId;
            // console.log(warehouse_code);
            $(".city_by_code").val(warehouse_code);
            $(".city_by_code").css('color', 'green');
        }
    });
});
</script>
<!-- add multirows for revise time slot -->
<script>
// Add more functionality
$('#add-revise-pair').on('click', function() {
    if (checkReviseTime()) {
        var newPair = $('.row-revise-pair:first').clone();
        newPair.find('input').val('');
        newPair.appendTo('#box-revise-pair');
    }
});

// Remove functionality
$(document).on('click', '.remove-revise-pair', function() {
    $(this).closest('.row-revise-pair').remove();
});

//  validation
function checkReviseTime() {
    var valid = true;

    $('input[name^="revise_time_open"]').each(function(index) {

        var startTime = $(this).val(); //get start time
        var endTimeArray = document.getElementsByName('revise_time_close[]');
        var endTime = endTimeArray[index].value; // get end time

        //split hours and minutes
        var startTimeSplit = startTime.split(':'); //10:30 -> 10,30
        var endTimeSplit = endTime.split(':'); //11:30 -> 11,30

        // Convert hours and minutes to integers
        var startTimeHours = parseInt(startTimeSplit[0]); // get hourse ( 10,30)-> (10)
        var startTimeMinutes = parseInt(startTimeSplit[1]); // get minutes ( 10,30)-> (30)
        var endTimeHours = parseInt(endTimeSplit[0]);
        var endTimeMinutes = parseInt(endTimeSplit[1]);

        // Calculate the time difference in minutes
        var timeDifferenceMinutes = (endTimeHours * 60 + endTimeMinutes) - (startTimeHours * 60 +
            startTimeMinutes); // 60

        if (startTime == '' || endTime == '') {
            Swal.fire({
                title: 'Alert',
                html: 'Please fill start and end time of current row.',
                icon: 'info',
                confirmButtonText: 'OK'
            });
            valid = false;
        } else if (startTime && endTime) {
            var startMoment = moment(startTime, 'HH:mm');
            var endMoment = moment(endTime, 'HH:mm');
            if (!endMoment.isAfter(startMoment)) {
                Swal.fire({
                    title: 'Alert',
                    html: 'End time must be after start time.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
                valid = false;
            }
            if (timeDifferenceMinutes < 30) {
                Swal.fire({
                    title: 'Alert',
                    html: 'Start time and end time difference is <strong>' + timeDifferenceMinutes +
                        ' minutes</strong>. It should be 30 and greater than 30.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
                valid = false;
            }

            if (index > 0 && valid) {
                //get diff prev end time and new start time
                var preEndTime = moment(endTimeArray[index - 1].value, 'HH:mm'); // get pre end time

                //get diffrence
                var preEndTimeSlot = (endTimeArray[index - 1].value).split(':'); //11:30 -> 11,30
                var NewStartTime = startTime.split(':'); //12:30 -> 12,30

                // Convert hours and minutes to integers
                var preEndTimeHours = parseInt(preEndTimeSlot[0]); //11
                var preEndTimeMinutes = parseInt(preEndTimeSlot[1]); //30
                var startTimeHours = parseInt(NewStartTime[0]); //12
                var endTimeMinutes = parseInt(NewStartTime[1]); //30

                // Calculate the time difference in minutes
                var newRowTimeDifferenceMinutes = (startTimeHours * 60 + endTimeMinutes) - (preEndTimeHours *
                    60 + preEndTimeMinutes); //60

                if (newRowTimeDifferenceMinutes < 30) {
                    Swal.fire({
                        title: 'Alert',
                        html: 'Previous End Time And New start time difference is <strong>' +
                            newRowTimeDifferenceMinutes +
                            ' minutes</strong>. It should be 30 and greater than 30.',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                    valid = false;
                }
                if (!startMoment.isAfter(preEndTime)) {
                    Swal.fire({
                        title: 'Alert',
                        html: ' Next time slot must be greater than previous.',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                    valid = false;
                }
            }
        }
    });
    return valid;
};
</script>
<!-- add multirows for delivery time slot -->
<script>
// Add more functionality
$('#add-delivery-pair').on('click', function() {
    if (checkDeliveryTime()) {
        var newPair = $('.row-delivery-pair:first').clone();
        newPair.find('input').val('');
        newPair.appendTo('#box-delivery-pair');
    }
});

// Remove functionality
$(document).on('click', '.remove-delivery-pair', function() {
    $(this).closest('.row-delivery-pair').remove();
});

//  validation

function checkDeliveryTime() {
    var valid = true;

    $('input[name^="delivery_open_time"]').each(function(index) {

        var startTime = $(this).val(); //get start time
        var endTimeArray = document.getElementsByName('delivery_close_time[]');
        var endTime = endTimeArray[index].value; // get end time

        //split hours and minutes
        var startTimeSplit = startTime.split(':'); //10:30 -> 10,30
        var endTimeSplit = endTime.split(':'); //11:30 -> 11,30

        // Convert hours and minutes to integers
        var startTimeHours = parseInt(startTimeSplit[0]); // get hourse ( 10,30)-> (10)
        var startTimeMinutes = parseInt(startTimeSplit[1]); // get minutes ( 10,30)-> (30)
        var endTimeHours = parseInt(endTimeSplit[0]);
        var endTimeMinutes = parseInt(endTimeSplit[1]);

        // Calculate the time difference in minutes
        var timeDifferenceMinutes = (endTimeHours * 60 + endTimeMinutes) - (startTimeHours * 60 +
            startTimeMinutes); // 60

        if (startTime == '' || endTime == '') {
            Swal.fire({
                title: 'Alert',
                html: 'Please fill start and end time of current row.',
                icon: 'info',
                confirmButtonText: 'OK'
            });
            valid = false;
        } else if (startTime && endTime) {
            var startMoment = moment(startTime, 'HH:mm');
            var endMoment = moment(endTime, 'HH:mm');
            if (!endMoment.isAfter(startMoment)) {
                Swal.fire({
                    title: 'Alert',
                    html: 'End time must be after start time.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
                valid = false;
            }
            if (timeDifferenceMinutes < 30) {
                Swal.fire({
                    title: 'Alert',
                    html: 'Start time and end time difference is <strong>' + timeDifferenceMinutes +
                        ' minutes</strong>. It should be 30 and greater than 30.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
                valid = false;
            }
            if (index > 0 && valid) {
                //get diff prev end time and new start time
                var preEndTime = moment(endTimeArray[index - 1].value, 'HH:mm'); // get pre end time

                //get diffrence
                var preEndTimeSlot = (endTimeArray[index - 1].value).split(':'); //11:30 -> 11,30
                var NewStartTime = startTime.split(':'); //12:30 -> 12,30

                // Convert hours and minutes to integers
                var preEndTimeHours = parseInt(preEndTimeSlot[0]); //11
                var preEndTimeMinutes = parseInt(preEndTimeSlot[1]); //30
                var startTimeHours = parseInt(NewStartTime[0]); //12
                var endTimeMinutes = parseInt(NewStartTime[1]); //30

                // Calculate the time difference in minutes
                var newRowTimeDifferenceMinutes = (startTimeHours * 60 + endTimeMinutes) - (preEndTimeHours *
                    60 + preEndTimeMinutes); //60
                if (newRowTimeDifferenceMinutes < 30) {
                    Swal.fire({
                        title: 'Alert',
                        html: 'Previous end time And New start time difference is <strong>' +
                            newRowTimeDifferenceMinutes +
                            ' minutes</strong>. It should be 30 and greater than 30.',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                    valid = false;
                }
                if (!startMoment.isAfter(preEndTime)) {
                    Swal.fire({
                        title: 'Alert',
                        html: ' Next time slot must be greater than previous.',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                    valid = false;
                }
            }
        }
    });
    return valid;
};
</script>
<!-- add multirows for order cancel time slot -->
<script>
// Add more functionality
$('#add-order-cancel-pair').on('click', function() {
    if (checkOrderCancelTime()) {
        var newPair = $('.row-order-cancel-pair:first').clone();
        newPair.find('input').val('');
        newPair.appendTo('#box-order-cancel-pair');
    }
});

// Remove functionality
$(document).on('click', '.remove-order-cancel-pair', function() {
    $(this).closest('.row-order-cancel-pair').remove();
});

//  validation
function checkOrderCancelTime() {
    var valid = true;

    $('input[name^="order_cancel_open_time"]').each(function(index) {

        var startTime = $(this).val(); //get start time
        var endTimeArray = document.getElementsByName('order_cancel_close_time[]');
        var endTime = endTimeArray[index].value; // get end time

        //split hours and minutes
        var startTimeSplit = startTime.split(':'); //10:30 -> 10,30
        var endTimeSplit = endTime.split(':'); //11:30 -> 11,30

        // Convert hours and minutes to integers
        var startTimeHours = parseInt(startTimeSplit[0]); // get hourse ( 10,30)-> (10)
        var startTimeMinutes = parseInt(startTimeSplit[1]); // get minutes ( 10,30)-> (30)
        var endTimeHours = parseInt(endTimeSplit[0]);
        var endTimeMinutes = parseInt(endTimeSplit[1]);

        // Calculate the time difference in minutes
        var timeDifferenceMinutes = (endTimeHours * 60 + endTimeMinutes) - (startTimeHours * 60 +
            startTimeMinutes); // 60

        if (startTime == '' || endTime == '') {
            Swal.fire({
                title: 'Alert',
                html: 'Please fill start and end time of current row.',
                icon: 'info',
                confirmButtonText: 'OK'
            });
            valid = false;
        } else if (startTime && endTime) {
            var startMoment = moment(startTime, 'HH:mm');
            var endMoment = moment(endTime, 'HH:mm');
            if (!endMoment.isAfter(startMoment)) {
                Swal.fire({
                    title: 'Alert',
                    html: 'End time must be after start time.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
                valid = false;
            }
            if (timeDifferenceMinutes < 30) {
                Swal.fire({
                    title: 'Alert',
                    html: 'Start time and end time difference is <strong>' + timeDifferenceMinutes +
                        ' minutes</strong>. It should be 30 and greater than 30.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
                valid = false;
            }
            if (index > 0 && valid) {
                //get diff prev end time and new start time
                var preEndTime = moment(endTimeArray[index - 1].value, 'HH:mm'); // get pre end time

                //get diffrence
                var preEndTimeSlot = (endTimeArray[index - 1].value).split(':'); //11:30 -> 11,30
                var NewStartTime = startTime.split(':'); //12:30 -> 12,30

                // Convert hours and minutes to integers
                var preEndTimeHours = parseInt(preEndTimeSlot[0]); //11
                var preEndTimeMinutes = parseInt(preEndTimeSlot[1]); //30
                var startTimeHours = parseInt(NewStartTime[0]); //12
                var endTimeMinutes = parseInt(NewStartTime[1]); //30

                // Calculate the time difference in minutes
                var newRowTimeDifferenceMinutes = (startTimeHours * 60 + endTimeMinutes) - (preEndTimeHours *
                    60 + preEndTimeMinutes); //60
                if (newRowTimeDifferenceMinutes < 30) {
                    Swal.fire({
                        title: 'Alert',
                        html: 'Previous End Time And New start time difference is <strong>' +
                            newRowTimeDifferenceMinutes +
                            ' minutes</strong>. It should be 30 and greater than 30.',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                    valid = false;
                }
                if (!startMoment.isAfter(preEndTime)) {
                    Swal.fire({
                        title: 'Alert',
                        html: ' Next time slot must be greater than previous.',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                    valid = false;
                }
            }
        }
    });
    return valid;
};
</script>
<!-- add multirows for pre order time slot -->
<script>
// Add more functionality
$('#add-pre-order-pair').on('click', function() {
    if (checkPreOrderTime()) {
        var newPair = $('.row-pre-order-pair:first').clone();
        newPair.find('input').val('');
        newPair.appendTo('#box-pre-order-pair');
    }
});

// Remove functionality
$(document).on('click', '.remove-pre-order-pair', function() {
    $(this).closest('.row-pre-order-pair').remove();
});

//  validation
function checkPreOrderTime() {
    var valid = true;

    $('input[name^="pre_order_open_time"]').each(function(index) {

        var startTime = $(this).val(); //get start time
        var endTimeArray = document.getElementsByName('pre_order_close_time[]');
        var endTime = endTimeArray[index].value; // get end time

        //split hours and minutes
        var startTimeSplit = startTime.split(':'); //10:30 -> 10,30
        var endTimeSplit = endTime.split(':'); //11:30 -> 11,30

        // Convert hours and minutes to integers
        var startTimeHours = parseInt(startTimeSplit[0]); // get hourse ( 10,30)-> (10)
        var startTimeMinutes = parseInt(startTimeSplit[1]); // get minutes ( 10,30)-> (30)
        var endTimeHours = parseInt(endTimeSplit[0]);
        var endTimeMinutes = parseInt(endTimeSplit[1]);

        // Calculate the time difference in minutes
        var timeDifferenceMinutes = (endTimeHours * 60 + endTimeMinutes) - (startTimeHours * 60 +
            startTimeMinutes); // 60

        if (startTime == '' || endTime == '') {
            Swal.fire({
                title: 'Alert',
                html: 'Please fill start and end time of current row.',
                icon: 'info',
                confirmButtonText: 'OK'
            });


            valid = false;
        } else if (startTime && endTime) {
            var startMoment = moment(startTime, 'HH:mm');
            var endMoment = moment(endTime, 'HH:mm');
            if (!endMoment.isAfter(startMoment)) {
                Swal.fire({
                    title: 'Alert',
                    html: 'End time must be after start time.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });

                valid = false;
            }
            if (timeDifferenceMinutes < 30) {
                Swal.fire({
                    title: 'Alert',
                    html: 'Start time and end time difference is <strong>' + timeDifferenceMinutes +
                        ' minutes</strong>. It should be 30 and greater than 30.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });

                valid = false;
            }
            if (index > 0 && valid) {
                //get diff prev end time and new start time
                var preEndTime = moment(endTimeArray[index - 1].value, 'HH:mm'); // get pre end time

                //get diffrence
                var preEndTimeSlot = (endTimeArray[index - 1].value).split(':'); //11:30 -> 11,30
                var NewStartTime = startTime.split(':'); //12:30 -> 12,30

                // Convert hours and minutes to integers
                var preEndTimeHours = parseInt(preEndTimeSlot[0]); //11
                var preEndTimeMinutes = parseInt(preEndTimeSlot[1]); //30
                var startTimeHours = parseInt(NewStartTime[0]); //12
                var endTimeMinutes = parseInt(NewStartTime[1]); //30

                // Calculate the time difference in minutes
                var newRowTimeDifferenceMinutes = (startTimeHours * 60 + endTimeMinutes) - (preEndTimeHours *
                    60 + preEndTimeMinutes); //60
                if (newRowTimeDifferenceMinutes < 30) {
                    Swal.fire({
                        title: 'Alert',
                        html: 'Previous End Time And New start time difference is <strong>' +
                            newRowTimeDifferenceMinutes +
                            ' minutes</strong>. It should be 30 and greater than 30.',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                    valid = false;
                }
                if (!startMoment.isAfter(preEndTime)) {
                    Swal.fire({
                        title: 'Alert',
                        html: ' Next time slot must be greater than previous.',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                    valid = false;
                }
            }
        }
    });
    return valid;
};
</script>

</body>
@endpush
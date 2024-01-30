@extends('layouts.admin.app')

@section('title', translate('Add New Warehouse'))

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
                {{translate('warehouse')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->

    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
            <form action="{{route('admin.warehouse.store')}}" method="post" id="timeForm" enctype="multipart/form-data" class="needs-validation form_customer" novalidate>
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <select name="coverage" class="form-control" id="radius" style="width: 270px;padding: 3px;">
                            @for($i=1; $i<=25; $i++)
                                   <option value="{{$i}}" >{{$i}} KM</option>
                           @endfor
                           </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <input type="text" id="mapaddress" name="address"
                        class="form-control"
                        placeholder="{{ translate('Ex:') }} Nagaur, Rajasthan 341001, India"
                        value="{{ old('myaddress') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <input type="text" id="latitude" name="latitude"
                        class="form-control"
                        placeholder="22.548744"
                        value="" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <input type="text" id="longitude" name="longitude"
                        class="form-control"
                        placeholder="28.65987"
                        value="" required>
                    </div>
                </div>
                
                <div class="row g-2">
                    
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
                                    
                                    <div class="col-md-6" id="location_map_div">
                                        
                                        <div id="location_map_canvas" class="overflow-hidden rounded"
                                            style="height: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    
                    <div class="col-sm-12">
                        <div class="btn--container justify-content-end">
                            <a type="button" href="{{route('admin.warehouse.list')}}"
                                class="btn btn--reset">{{translate('Back')}}</a>
                            <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                        </div>
                    </div>
                </div>
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
$(document).ready(function() {
    function initAutocomplete() {
        var myLatLng = {
            lat: 23.811842872190343,
            lng: 90.356331
        };

        const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
            center: myLatLng,
            zoom: 13,
            mapTypeId: "roadmap",
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
        });

        marker.setMap(map);
        var geocoder = new google.maps.Geocoder();

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
                        document.getElementById('mapaddress').innerHtml = results[1].formatted_address;
                    }
                }
            });
        });

        const input = document.getElementById("mapaddress");
        const searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);

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

                google.maps.event.addListener(mrkr, "click", function(event) {
                    document.getElementById('latitude').value = this.position.lat();
                    document.getElementById('longitude').value = this.position.lng();

                    $('#address').val(place.formatted_address);
                   
                    // Draw circle when a suggestion is selected
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
                var circle = new google.maps.Circle({
                    map: map,
                    fillColor: "#ADD8E6",
                    fillOpacity: 0.3,
                    strokeColor: "#0000FF",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    center: center,
                    radius: radius*1000
                });
            }
        }
        
        // Draw circle on radius input change
        $('#radius').on('change', function() {
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



<!-- add multirows for revise time slot -->
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
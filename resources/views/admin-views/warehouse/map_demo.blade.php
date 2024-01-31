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

                
                
                    <div class="form-group">
                        <label for="address_address">Address</label>
                        <input type="text" id="address-input" name="address_address" class=" map-input" placeholder="search address">
                        <select name="coverage" class="form-control" id="radius" style="width: 270px;padding: 3px;">
                            @for($i=1; $i<=25; $i++)
                                   <option value="{{$i}}" >{{$i}} KM</option>
                           @endfor
                           </select>
                        <input type="text" name="latitude" id="latitude" placeholder="address_latitude" />
                        <input type="text" name="address_longitude" id="longitude" placeholder="address_longitude" />
                    </div>
                    <div id="location_map_canvas" style="width:100%;height:400px; ">
                        <div style="width: 100%; height: 100%" id="address-map"></div>
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

 {{-- <script src="/js/mapInput.js"></script> --}}
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bs-custom-file-input/1.3.4/bs-custom-file-input.min.js" integrity="sha512-91BoXI7UENvgjyH31ug0ga7o1Ov41tOzbMM3+RPqFVohn1UbVcjL/f5sl6YSOFfaJp+rF+/IEbOOEwtBONMz+w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



<!-- add multirows for revise time slot -->
<!-- add multirows for delivery time slot -->
<script>

$(document).ready(function () {
    var map;
    var marker;
    var circle;

    function initAutocomplete() {
        var myLatLng = {
            lat: 23.811842872190343,
            lng: 90.356331
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
        });

        const input = document.getElementById("address-input");
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

                google.maps.event.addListener(mrkr, "click", function (event) {
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
</script>
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ \App\Model\BusinessSetting::where('key', 'map_api_client_key')->first()?->value }}&libraries=places&callback=initialize">
</script>

</body>
@endpush
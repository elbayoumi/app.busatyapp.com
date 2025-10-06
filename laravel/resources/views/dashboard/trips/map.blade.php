@extends('dashboard.trips.layouts.app')

@section('trips')
    @php
        $defaultLat = $trip->latitude ?? 30.033333;
        $defaultLng = $trip->longitude ?? 31.233334;
    @endphp

    <div class="tab-content">
        <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

            <!-- Trip Routes Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Longitude</th>
                            <th>Latitude</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $trip->longitude }}</td>
                            <td>{{ $trip->latitude }}</td>
                        </tr>
                    </tbody>
                </table>
                <!-- Point Details -->
                <div id="pointDetails" class="mt-4 alert alert-info" style="display:none;">
                    <strong>Point Details:</strong>
                    <div id="pointData"></div>
                </div>

            </div>

            <!-- Trip Status -->
            <div class="mt-3">
                <div id="tripStatus" class="alert alert-secondary">
                    💤 Trip Status: Waiting for live data...
                </div>
            </div>

            <!-- Total Trip Time -->
            <div class="mt-3">
                <div id="totalTripTime" class="alert alert-primary">
                    ⏱ Total Trip Time: 0s
                </div>
            </div>

            <!-- Map Display -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <div id="googleMap" style="height:400px;"></div>
                </div>
            </div>


        </div>
    </div>
@endsection

@push('page_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.3/socket.io.js"></script>
    <script>
        var socket = io('https://node.busatyapp.com', {
            query: {
                token: '71e456b873f87f214f139799878b911a'
            }
        });

        var googleMap;
        var dbPath = [];
        var livePath = [];
        var dbPolyline;
        var livePolyline;
        var busMarker;
        var startMarker;

        var lastPointTimestamp = null;
        var tripStartTimestamp = {{ $trip->created_at ? strtotime($trip->created_at) * 1000 : 'null' }};
        var latestKnownTimestamp = tripStartTimestamp;

        var tripId = {{ $trip->id }}; // 🛤️ Current Trip ID
        var defaultLat = {{ $defaultLat }};
        var defaultLng = {{ $defaultLng }};

        function formatTime(seconds) {
            if (seconds >= 3600) {
                return (seconds / 3600).toFixed(1) + 'h';
            } else if (seconds >= 60) {
                return (seconds / 60).toFixed(1) + 'm';
            } else {
                return seconds + 's';
            }
        }

        function updateTotalTripTime() {
            if (tripStartTimestamp && latestKnownTimestamp) {
                let diffSeconds = ((latestKnownTimestamp - tripStartTimestamp) / 1000).toFixed(0);
                document.getElementById('totalTripTime').innerText = `⏱ Total Trip Time: ${formatTime(diffSeconds)}`;
            }
        }

        function myMap() {
            var mapProp = {
                center: new google.maps.LatLng(defaultLat, defaultLng),
                zoom: 17,
            };
            googleMap = new google.maps.Map(document.getElementById("googleMap"), mapProp);

            let routes = @json($trip->routes);

            var lastDbPointTimestamp = null;
            var firstDbPointTimestamp = routes.length > 0 && routes[0].created_at ? new Date(routes[0].created_at)
            .getTime() : null;

            routes.forEach(function(route) {
                let lat = parseFloat(route.latitude);
                let lng = parseFloat(route.longitude);
                let latLng = {
                    lat: lat,
                    lng: lng
                };
                dbPath.push(latLng);

                let thisPointTimestamp = route.created_at ? new Date(route.created_at).getTime() : null;
                if (thisPointTimestamp && thisPointTimestamp > latestKnownTimestamp) {
                    latestKnownTimestamp = thisPointTimestamp;
                }

                let timeSinceStart = firstDbPointTimestamp && thisPointTimestamp ? ((thisPointTimestamp -
                    firstDbPointTimestamp) / 1000) : 0;
                let timeSinceLast = lastDbPointTimestamp && thisPointTimestamp ? ((thisPointTimestamp -
                    lastDbPointTimestamp) / 1000) : 0;

                lastDbPointTimestamp = thisPointTimestamp;

                var marker = new google.maps.Marker({
                    position: latLng,
                    map: googleMap,
                    title: `🛤 From Start: ${formatTime(timeSinceStart)} | From Last: ${formatTime(timeSinceLast)}`,
                    icon: {
                        url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                    }
                });

                marker.addListener('click', function() {
                    document.getElementById('pointDetails').style.display = 'block';
                    document.getElementById('pointData').innerHTML = `
                    <p><strong>Latitude:</strong> ${lat}</p>
                    <p><strong>Longitude:</strong> ${lng}</p>
                    <p><strong>Time from Start:</strong> ${formatTime(timeSinceStart)}</p>
                    <p><strong>Time from Last Point:</strong> ${formatTime(timeSinceLast)}</p>
                `;
                });
            });

            if (dbPath.length > 0) {
                dbPolyline = new google.maps.Polyline({
                    path: dbPath,
                    geodesic: true,
                    strokeColor: "#007bff",
                    strokeOpacity: 1.0,
                    strokeWeight: 4,
                });
                dbPolyline.setMap(googleMap);

                startMarker = new google.maps.Marker({
                    position: dbPath[0],
                    map: googleMap,
                    title: "Start Point",
                    icon: {
                        url: "http://maps.google.com/mapfiles/ms/icons/green-dot.png"
                    }
                });

                var bounds = new google.maps.LatLngBounds();
                dbPath.forEach(function(p) {
                    bounds.extend(p);
                });
                googleMap.fitBounds(bounds);
            }

            let initialPosition = dbPath.length > 0 ? dbPath[0] : {
                lat: defaultLat,
                lng: defaultLng
            };

            busMarker = new google.maps.Marker({
                position: initialPosition,
                map: googleMap,
                title: "Bus Live Location",
                icon: {
                    url: "{{ asset('assets/dashboard/bus.png') }}",
                    scaledSize: new google.maps.Size(30, 30)
                }
            });

            updateTotalTripTime();

            socket.on("{{ $trip->bus_id }}", (message) => {
                message = JSON.parse(message);
                console.log('Live Update:', message);

                if (!message.trip_id || !message.latitude || !message.longitude) return;

                // Check if this message is for this trip
                if (message.trip_id != tripId) {
                    document.getElementById('tripStatus').className = 'alert alert-info';
                    document.getElementById('tripStatus').innerText = 'ℹ️ Another Trip Data Received';
                    return;
                }

                var lng = parseFloat(message.longitude);
                var lat = parseFloat(message.latitude);

                var nowTimestamp = message.created_at ? new Date(message.created_at).getTime() : Date.now();
                latestKnownTimestamp = nowTimestamp;

                updateLiveTracking(lat, lng, nowTimestamp);

                document.getElementById('tripStatus').className = 'alert alert-success';
                document.getElementById('tripStatus').innerText = '🟢 Trip Status: Receiving live data...';

                updateTotalTripTime();
            });
        }

        function updateLiveTracking(lat, lng, currentTimestamp) {
            var latLng = new google.maps.LatLng(lat, lng);

            busMarker.setPosition(latLng);
            googleMap.panTo(latLng);

            livePath.push({
                lat: lat,
                lng: lng
            });

            if (livePolyline) {
                livePolyline.setPath(livePath);
            } else {
                livePolyline = new google.maps.Polyline({
                    path: livePath,
                    geodesic: true,
                    strokeColor: "#28a745",
                    strokeOpacity: 1.0,
                    strokeWeight: 4,
                    map: googleMap
                });
            }

            if (!tripStartTimestamp) {
                tripStartTimestamp = currentTimestamp;
            }

            let timeSinceStart = ((currentTimestamp - tripStartTimestamp) / 1000).toFixed(0);
            let timeSinceLastPoint = lastPointTimestamp ? ((currentTimestamp - lastPointTimestamp) / 1000).toFixed(0) : 0;

            lastPointTimestamp = currentTimestamp;

            var marker = new google.maps.Marker({
                position: latLng,
                map: googleMap,
                title: `⏱ From Start: ${formatTime(timeSinceStart)} | From Last: ${formatTime(timeSinceLastPoint)}`,
                icon: {
                    url: "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png"
                }
            });

            marker.addListener('click', function() {
                document.getElementById('pointDetails').style.display = 'block';
                document.getElementById('pointData').innerHTML = `
                <p><strong>Latitude:</strong> ${lat}</p>
                <p><strong>Longitude:</strong> ${lng}</p>
                <p><strong>Time from Start:</strong> ${formatTime(timeSinceStart)}</p>
                <p><strong>Time from Last Point:</strong> ${formatTime(timeSinceLastPoint)}</p>
            `;
            });
        }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=myMap&language=ar&region=EG&async defer">
    </script>
@endpush

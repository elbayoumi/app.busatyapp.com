@extends('layouts.auth')

@section('content')
<div class="glass-box">
    <h2>Register as {{ ucfirst($type) }}</h2>
    <p class="subtitle">Create your {{ $type }} account to start managing your buses and routes.</p>

    <form method="POST" action="#">
        @csrf

        <!-- Full Name -->
        <div class="mb-3 text-start">
            <label for="name" class="form-label">👤 Full Name</label>
            <input id="name" type="text" name="name" class="form-control"
                placeholder="Enter your name" required>
        </div>

        <!-- Email -->
        <div class="mb-3 text-start">
            <label for="email" class="form-label">📧 Email</label>
            <input id="email" type="email" name="email" class="form-control"
                placeholder="Enter your email" required>
        </div>

        <!-- Password -->
        <div class="mb-3 text-start">
            <label for="password" class="form-label">🔑 Password</label>
            <input id="password" type="password" name="password" class="form-control"
                placeholder="Enter password" required>
        </div>

        <!-- Confirm Password -->
        <div class="mb-3 text-start">
            <label for="password_confirmation" class="form-label">🔑 Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                class="form-control" placeholder="Confirm password" required>
        </div>

        <!-- Extra field if type is school -->
        @if($type === 'school')
        <div class="mb-3 text-start">
            <label for="school_name" class="form-label">🏫 School Name</label>
            <input id="school_name" type="text" name="school_name" class="form-control"
                placeholder="Enter school name" required>
        </div>
        @endif

        <!-- Register Button -->
        <button type="submit" class="btn w-100 mt-3">
            ✏️ Register
        </button>

        <!-- Trust Info -->
        <div class="trust">🔒 Secured by SSL Encryption</div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // ✅ Leaflet map same as login page (optional)
    var map = L.map('map', {
        zoomControl: false,
        dragging: false,
        scrollWheelZoom: false,
        doubleClickZoom: false
    }).setView([30.0444, 31.2357], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var routeCoords = [
        [30.0444, 31.2357],
        [30.0460, 31.2400],
        [30.0480, 31.2425],
        [30.0500, 31.2450],
        [30.0520, 31.2480]
    ];

    var route = L.polyline(routeCoords, {
        color: '#00b3ff',
        weight: 6,
        opacity: 0.8
    }).addTo(map);

    map.fitBounds(route.getBounds());

    var busIcon = L.divIcon({
        html: '🚌',
        iconSize: [30, 30],
        className: ''
    });

    var busMarker = L.marker(routeCoords[0], {
        icon: busIcon
    }).addTo(map);

    let index = 0, step = 0;

    function moveBus() {
        let current = routeCoords[index];
        let next = routeCoords[(index + 1) % routeCoords.length];
        let lat = current[0] + (next[0] - current[0]) * step;
        let lng = current[1] + (next[1] - current[1]) * step;
        busMarker.setLatLng([lat, lng]);
        step += 0.01;
        if (step >= 1) {
            step = 0;
            index = (index + 1) % routeCoords.length;
        }
    }

    setInterval(moveBus, 100);
</script>
@endpush

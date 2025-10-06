@extends('layouts.auth')

@section('content')
    <div class="glass-box">
        <img src="{{ asset('assets/icons/bus-logo.png') }}" alt="Logo">

        <!-- Toggle Buttons -->
        <div class="toggle-buttons mb-4">
            <button type="button" class="btn btn-outline-primary active" data-type="parent">👨‍👩‍👧 Parent</button>
            <button type="button" class="btn btn-outline-primary" data-type="school">🏫 School</button>
        </div>

        <h2 id="login-title">Welcome Parent 👋</h2>
        <p id="login-subtitle" class="subtitle">
            Please login to manage your routes and track your buses securely.
        </p>

        <form method="POST" action="{{ route('dashboard.login') }}" class="mt-4 text-start">
            @csrf

            <div class="mb-3">
                <label for="login-email" class="form-label">📧 Email</label>
                <input id="login-email" type="email" name="email"
                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                    placeholder="Enter your email" required autofocus>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <label for="login-password" class="form-label">🔑 Password</label>
                    <small><a href="{{ route('password.request') }}">Forgot Password?</a></small>
                </div>
                <div class="input-group">
                    <input id="login-password" type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password"
                        required>
                    <button type="button" class="btn btn-outline-secondary toggle-password"
                        style="border-radius: 0 10px 10px 0;">
                        👁️
                    </button>
                </div>
                @error('password')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember"
                    {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Remember Me</label>
            </div>

            <!-- ✅ Sign In Button -->
            <button type="submit" class="btn w-100 mb-2">
                🚀 Sign In
            </button>

            <!-- ✅ Correct Register Links -->
            <a href="{{ route('parent.register.form') }}" class="btn btn-outline-primary w-100 mb-2" id="register-parent">
                ✏️ Register as Parent
            </a>

            <a href="{{ route('school.register.form') }}" class="btn btn-outline-primary w-100" id="register-school"
                style="display: none;">
                🏫 Register as School
            </a>

            <div class="trust">🔒 Secured by SSL Encryption</div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // ✅ Leaflet Map Init
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

        let index = 0,
            step = 0;

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

        // ✅ Toggle Password Visibility
        document.querySelector('.toggle-password').addEventListener('click', function() {
            var passwordInput = document.getElementById('login-password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                this.textContent = '👁️';
            }
        });

        // ✅ Toggle Register Buttons & Text
        const toggleButtons = document.querySelectorAll('.toggle-buttons button');
        const title = document.getElementById('login-title');
        const subtitle = document.getElementById('login-subtitle');
        const registerParent = document.getElementById('register-parent');
        const registerSchool = document.getElementById('register-school');

        toggleButtons.forEach(button => {
            button.addEventListener('click', () => {
                toggleButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                const type = button.getAttribute('data-type');
                if (type === 'parent') {
                    title.textContent = 'Welcome Parent 👋';
                    subtitle.textContent =
                        'Please login to manage your routes and track your buses securely.';
                    registerParent.style.display = 'block';
                    registerSchool.style.display = 'none';
                } else {
                    title.textContent = 'Welcome School 👋';
                    subtitle.textContent =
                        'Please login to manage all your bus routes and students with confidence.';
                    registerParent.style.display = 'none';
                    registerSchool.style.display = 'block';
                }
            });
        });
    </script>
@endpush

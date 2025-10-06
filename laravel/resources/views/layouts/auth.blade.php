<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>{{ $title ?? ($settings?->name ?? 'Bussaty') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO & OG Meta Tags (Add if needed) -->

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/icons/favicon-32x32.png') }}">
    <meta name="theme-color" content="#0d6efd">

    <!-- ✅ Shared Auth Styles -->
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: row;
            font-family: 'Cairo', sans-serif;
            overflow: hidden;
            background: linear-gradient(135deg, #0d6efd 0%, #00b3ff 100%);
        }

        .map-side {
            width: 40%;
            height: 50vh;
            align-self: center;
            border-radius: 30px;
            overflow: hidden;
            margin: auto 40px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15),
                0 15px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .map-side:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2),
                0 20px 40px rgba(0, 0, 0, 0.15);
        }

        #map {
            height: 100%;
            width: 100%;
            filter: grayscale(0.2) brightness(1.05);
        }

        .login-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px;
            position: relative;
        }

        .glass-box {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(25px);
            border-radius: 30px;
            padding: 50px 40px;
            max-width: 450px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            position: relative;
            animation: fadeIn 1s ease forwards;
        }

        .glass-box img {
            max-height: 60px;
            border-radius: 8px;
            margin-bottom: 10px;
            animation: logoEntrance 1.5s ease forwards;
            filter: drop-shadow(0 0 10px rgba(0, 179, 255, 0.4));
        }

        @keyframes logoEntrance {
            0% {
                opacity: 0;
                transform: scale(0.8) rotate(-10deg);
            }

            50% {
                opacity: 1;
                transform: scale(1.1) rotate(5deg);
            }

            100% {
                opacity: 1;
                transform: scale(1) rotate(0deg);
            }
        }

        .toggle-buttons button.active {
            background-color: #0d6efd;
            color: #fff;
        }

        .toggle-buttons button {
            margin-right: 10px;
        }

        .glass-box h2 {
            font-weight: 800;
            color: #0d6efd;
            margin-bottom: 5px;
        }

        .glass-box p.subtitle {
            font-size: 14px;
            color: #333;
            margin-bottom: 25px;
        }

        .glass-box .form-control {
            border-radius: 10px;
        }

        .glass-box .btn {
            border-radius: 50px;
            font-weight: 700;
            padding: 12px 30px;
            background: linear-gradient(270deg, #0d6efd, #00b3ff, #0d6efd);
            background-size: 600% 600%;
            animation: gradientShift 4s ease infinite;
            border: none;
            color: #fff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .glass-box .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .trust {
            margin-top: 20px;
            font-size: 12px;
            color: #555;
        }

        footer {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.8);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            color: #333;
            z-index: 999;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
                padding: 20px;
            }

            .map-side {
                display: none;
            }

            .login-side {
                padding: 20px;
            }

            .glass-box {
                padding: 40px 20px;
                border-radius: 20px;
            }

            .glass-box h2 {
                font-size: 22px;
            }

            .glass-box p.subtitle {
                font-size: 13px;
            }

            .toggle-buttons {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .toggle-buttons button {
                width: 100%;
                border-radius: 50px;
            }

            .glass-box .btn {
                font-size: 15px;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .leaflet-div-icon {
            font-size: 30px;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Map Side -->
    <div class="map-side">
        <div id="map"></div>
        <footer>
            &copy; {{ now()->year }} {{ $settings?->name ?? 'Bussaty' }} · All rights reserved.
        </footer>
    </div>

    <!-- Auth Content -->
    <div class="login-side">
        <!-- ✅ Flash & Validation Errors Styled Alert -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show text-start" role="alert">
                <strong>✅ Success:</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show text-start" role="alert">
                <strong>❌ Error:</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show text-start" role="alert">
                <strong>⚠️ Please fix the following:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Leaflet JS + Bootstrap -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>

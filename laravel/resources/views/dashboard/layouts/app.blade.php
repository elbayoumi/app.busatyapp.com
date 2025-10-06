<!DOCTYPE html>
<html lang="ar" dir="rtl" class="loading">
<x-layout.head />

<head>
    {{-- Meta & Title --}}
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? ($settings?->name ?? 'لوحة التحكم') }}</title>

    {{-- Google Fonts --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Inter:wght@400;600;700&display=swap"
        rel="stylesheet" />
    <link rel="icon" href="{{ asset('assets/icons/bus-logo.png') }}" type="image/png">

    {{-- Core Styles --}}
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/vendors/css/vendors-rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/bootstrap-extended.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/colors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/components.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/themes/dark-layout.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/themes/bordered-layout.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/themes/semi-dark-layout.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/custom-rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/assets/css/style-rtl.css') }}">

    {{-- Select2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" />

    {{-- Custom Style --}}
    <style>
        body {
            font-family: 'Cairo', 'Inter', sans-serif;
            background: linear-gradient(to bottom, #f7f7f7, #f0f2f5);
        }

        .content-wrapper {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .alert {
            border-radius: 10px;
            padding: 12px 20px;
            font-size: 0.95rem;
        }

        .nav-link-style:hover i {
            transform: rotate(15deg);
            transition: all 0.3s ease;
        }
    </style>

    @stack('page_vendor_css')
    @stack('page_styles')
</head>

<body class="vertical-layout vertical-menu-modern navbar-floating footer-static" data-open="click"
    data-menu="vertical-menu-modern" data-col="">

    {{-- Header --}}
    <x-layout.header />

    {{-- Sidebar --}}
    <x-layout.sidebar />

    {{-- Main Content --}}
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">

            {{-- Breadcrumb or Dynamic Title --}}
            @yield('route')

            {{-- Flash Messages --}}
            <div class="mt-2">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <x-alert type="danger" :message="$error" />
                    @endforeach
                @endif

                @if (session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif

                @if (session('error'))
                    <x-alert type="danger" :message="session('error')" />
                @endif
            </div>

            {{-- Main Page Content --}}
            @yield('content')

        </div>
    </div>

    {{-- Footer --}}
    <x-layout.footer />

    {{-- Scripts --}}
    @livewireScripts
    <script src="{{ asset('assets/dashboard/app-assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/app-assets/js/core/app-menu.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/app-assets/js/core/app.min.js') }}"></script>

    {{-- Select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.full.min.js"></script>

    {{-- Custom Scripts --}}
    <script>
        $(document).ready(function() {
            $('select').select2({
                dir: 'rtl'
            });
            $('.but_delete_action').on('click', function() {
                if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                    $('#' + $(this).data('delete')).submit();
                }
            });
            $('.but_update_action').on('click', function() {
                $('#' + $(this).data('update')).submit();
            });
        });

        @if (auth()->guard('web')->user()?->dark_mode)
            $('html').removeClass('loading').addClass('loaded dark-layout');
        @else
            $('html').removeClass('loading').addClass('loaded light-layout');
        @endif

        $('#dark_mode').on('click', function() {
            $.get("{{ route('dashboard.profile.dark-mode-update') }}");
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (feather) {
                feather.replace();
            }
        });
    </script>

    @stack('page_scripts_vendors')
    @stack('page_scripts')
</body>

</html>

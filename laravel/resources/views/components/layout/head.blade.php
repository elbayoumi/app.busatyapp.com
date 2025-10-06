<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- ضروري للريسبونسف -->
    <title>{{ $settings->name }} - Dashboard</title>
    <link rel="icon" href="{{ asset('assets/icons/bus-logo.png') }}" type="image/png">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- jQuery - قبل أي سكريبتات تعتمد عليه --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    {{-- Vendor CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/vendors/css/vendors.min.css') }}">

    {{-- Core CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/bootstrap-extended.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/components.css') }}">

    {{-- Theme CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/themes/dark-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/themes/bordered-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/themes/semi-dark-layout.css') }}">

    {{-- Page CSS --}}
    <link rel="stylesheet"
        href="{{ asset('assets/dashboard/app-assets/css-rtl/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/pages/dashboard-ecommerce.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/plugins/charts/chart-apex.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/dashboard/app-assets/css-rtl/plugins/extensions/ext-component-toastr.css') }}">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css-rtl/custom-rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/assets/css/style-rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/assets/css/style.css') }}">

    {{-- Font Family Override --}}
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }

        .dropdown-menu {
            z-index: 1050 !important;
        }

        .table-responsive {
            overflow: visible !important;
        }

        .dt-multilingual table td {
            vertical-align: middle;
        }
    </style>

    {{-- Dynamic CSS & Livewire --}}
    @stack('page_vendor_css')
    @stack('page_styles')
    @livewireStyles
</head>

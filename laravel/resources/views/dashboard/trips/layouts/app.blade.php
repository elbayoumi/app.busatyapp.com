@extends('dashboard.layouts.app')
@push('page_vendor_css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/vendors.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">

@endpush
@push('page_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/plugins/forms/pickers/form-flat-pickr.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/plugins/forms/form-validation.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/pages/app-user.min.css">
    <style>
        .nav-tabs .nav-link:after {
            width: 0;
            height: 0;
        }
        </style>
    @endpush
    <script src="https://cdn.socket.io/4.6.0/socket.io.min.js"
    integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous">
    </script>
@section('content')
  <div class="card">
    <div class="border-bottom">
        <ul class="nav nav-tabs" style="margin: 0;padding: 10px;">
            @if(auth()->user()->canany(['super', 'trips-show']))
            <li class="nav-item">
                <a class="nav-link  @if (itemIsActive('trips', 'show')) {{'active'}} @endif " href="{{ route('dashboard.trips.show', $trip->id) }}">عرض / عرض بيانات الرحلة</a>
            </li>
            @endif

            @if(auth()->user()->canany(['super', 'attendances-list']))
            <li class="nav-item">
                <a class="nav-link  @if (itemIsActive('attendances', 'index')) {{'active'}} @endif " href="{{ route('dashboard.attendances.index', $trip->id) }}">عرض / غياب الرحلة</a>
            </li>
            @endif

            @if ($trip->status == 0)
            @if(auth()->user()->canany(['super', 'trips-map']))
            <li class="nav-item">
                <a class="nav-link  @if (itemIsActive('trips', 'map')) {{'active'}} @endif " href="{{ route('dashboard.trips.map', $trip->id) }}">عرض /  الرحلة علي الخريطة </a>
            </li>
            @endif
            @endif

        </ul>
    </div>

            @yield('trips')

</div>



@endsection

@push('page_scripts_vendors')
<script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
<script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
@endpush

@push('page_scripts')
<script src="{{ asset('assets/dashboard') }}/app-assets/js/scripts/pages/app-user-edit.min.js"></script>
<script src="{{ asset('assets/dashboard') }}/app-assets/js/scripts/components/components-navs.min.js"></script>
<script>
    $(document).ready(function() {
       $('.basic-select2').select2();
   });
</script>

@endpush

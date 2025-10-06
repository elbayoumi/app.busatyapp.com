@extends('dashboard.layouts.app')
@push('page_vendor_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
@endpush
@push('page_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/plugins/forms/pickers/form-flat-pickr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/pages/app-user.min.css">
@endpush
@section('content')

<!-- users edit start -->
<section class="app-user-edit">
    <div class="card">
        <div class="card-header border-bottom">
            <h4 class="card-title">عرض /  غياب الطالب </h4>
            <div>
                <a href="{{ route('dashboard.attendances.index', $attendances->trip->id) }}" class="btn btn-gradient-primary">Back</a>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>اسم الطالب</th>
                                    <td>{{ $attendances->students->name  }}</td>
                                 </tr>
                                 <tr>
                                    <th>اسم المدرسة</th>
                                    <td>{{ $attendances->schools->name  }}</td>
                                 </tr>
                                 <tr>
                                    <th>المرحلة</th>
                                    <td>{{ $attendances->students->grade->name }}</td>
                                 </tr>
                                 <tr>
                                    <th>الصف</th>
                                    <td>{{ $attendances->students->classroom->name }}</td>
                                 </tr>
                                 <tr>
                                    <th>اسم الباص</th>
                                    <td>{{isset($attendances->trip->bus) != null ?  $attendances->trip->bus->name : 'Undefined'}}</td>
                                 </tr>
                                 <tr>
                                    <th>رقم الباص</th>
                                    <td>{{isset($attendances->trip->bus) != null ?  $attendances->trip->bus->car_number : 'Undefined'}}</td>
                                 </tr>

                                 <tr>
                                    <th>تاريخ  الغياب</th>
                                    <td>{{isset($attendances->attendence_date ) != null ?  $attendances->attendence_date  : 'Undefined'}}</td>
                                 </tr>

                                 <tr>
                                    <th>الحضور والغياب</th>
                                    <td>{{isset($attendances->attendence_status ) != 0 ?  'حضور'  : 'غياب'}}</td>
                                 </tr>

                            </tbody>

                        </table>
                    </div>

                </div>
                <!-- Account Tab ends -->


            </div>


        </div>
    </div>
</section>
<!-- users edit ends -->

@endsection

@push('page_scripts_vendors')
<script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
<script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
@endpush

@push('page_scripts')
    <script src="{{ asset('assets/dashboard') }}/app-assets/js/scripts/pages/app-user-edit.min.js"></script>
    <script src="{{ asset('assets/dashboard') }}/app-assets/js/scripts/components/components-navs.min.js"></script>
@endpush

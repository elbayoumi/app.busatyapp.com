@extends('dashboard.layouts.app')
@push('page_vendor_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
@endpush
@push('page_styles')

    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashboard') }}/app-assets/css/plugins/forms/pickers/form-flat-pickr.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashboard') }}/app-assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/pages/app-user.min.css">
@endpush
@section('content')
    <!-- users edit start -->
    <section class="app-user-edit">
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title">اضف باص</h4>

            </div>
            <div class="card-body mt-3">

                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                    <!-- users edit account form start -->
                    <form method="POST" action="{{ route('dashboard.buses.store') }}"
                        class="m-form m-form--fit m-form--label-align-right" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">الوصف</label>
                                    <input type="text" class="form-control" placeholder="Name"
                                        value="{{ old('name') }}" name="name" id="name" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">رقم الباص</label>
                                    <input type="text" class="form-control" placeholder="car number"
                                        value="{{ old('car_number') }}" name="car_number" id="car_number" required />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes"> ملحوظة</label>
                                    <textarea type="text" class="form-control"value="" name="notes" id="notes" required rows="10">{{ old('notes') }}</textarea>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_id">المدرسة</label>
                                    <select class="form-control basic-select2" name="school_id" id="school_id"
                                        onchange="console.log($(this).val())" required>
                                        <option value="" selected disabled> اختر المدرسة </option>
                                        @foreach ($school as $sch)
                                            <option value="{{ $sch->id }}">{{ $sch->name }}</option>
                                        @endforeach


                                    </select>
                                </div>
                            </div>

                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="attendant_driver_id">السائق </label>
                                    <select class="form-control basic-select2" name="attendant_driver_id" id="attendant_driver_id"
                                        >
                                        <option value="" disabled> اختر السائق</option>
                                        <option value="">ليس لدية سائق</option>


                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="attendant_admins_id">المشرف </label>
                                    <select class="form-control basic-select2" name="attendant_admins_id" id="attendant_admins_id"
                                        >
                                        <option value="" disabled> اختر المشرف</option>
                                        <option value="">ليس لدية مشرف</option>


                                    </select>
                                </div>
                            </div> --}}


                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">حفظ </button>
                            </div>
                        </div>
                    </form>
                    <!-- users edit account form ends -->
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

    <script>
        $(document).ready(function() {
            $('.basic-select2').select2();
        });
        // $(document).ready(function() {
        //     $('select[name="school_id"]').on('change', function() {
        //         var school_id = $(this).val();
        //         if (school_id) {
        //             $.ajax({
        //                 url: "{{ URL::to('dashboard/attendants/get-driver') }}/" + school_id,
        //                 type: "GET",
        //                 dataType: "json",
        //                 success: function(data) {
        //                     $('select[name="attendant_driver_id"]').empty();
        //                     $('select[name="attendant_driver_id"]').append(
        //                         '<option value="">ليس لدية سائق</option>');
        //                     $.each(data, function(key, value) {
        //                         $('select[name="attendant_driver_id"]').append(
        //                             '<option value="' + key + '">' + value +
        //                             '</option>');
        //                     });
        //                 },
        //             });
        //         } else {
        //             console.log('AJAX load did not work');
        //         }
        //     });
        // });

        // $(document).ready(function() {
        //     $('select[name="school_id"]').on('change', function() {
        //         var school_id = $(this).val();
        //         if (school_id) {
        //             $.ajax({
        //                 url: "{{ URL::to('dashboard/attendants/get-admins') }}/" + school_id,
        //                 type: "GET",
        //                 dataType: "json",
        //                 success: function(data) {
        //                     $('select[name="attendant_admins_id"]').empty();
        //                     $('select[name="attendant_admins_id"]').append(
        //                         '<option value="">ليس لدية مشرف</option>');
        //                     $.each(data, function(key, value) {
        //                         $('select[name="attendant_admins_id"]').append(
        //                             '<option value="' + key + '">' + value +
        //                             '</option>');
        //                     });
        //                 },
        //             });
        //         } else {
        //             console.log('AJAX load did not work');
        //         }
        //     });
        // });
    </script>
@endpush

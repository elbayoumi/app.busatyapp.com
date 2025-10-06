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
            <h4 class="card-title">اضف طلاب الي الباص</h4>

        </div>
        <div class="card-body mt-3">

            <!-- Account Tab starts -->
            <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                <!-- users edit account form start -->
                <form method="POST" action="{{ route('dashboard.students.store.to.bus') }}" class="m-form m-form--fit m-form--label-align-right" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="school_id"> المدرسة</label>
                                <select class="form-control basic-select2" name="school_id" id="school_id">
                                    <option value="" disabled>اختر المدرسة</option>

                                    @forelse ($schools as $school)
                                    <option value="{{ $school->id }}" >{{ $school->name }}</option>


                                    @empty
                                   @endforelse
                               </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="bus_id"> الباص</label>
                                <select class="form-control  basic-select2" name="bus_id" id="bus_id">
                                    <option value="" disabled> اختر الباص</option>

                               </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trip_type">نوع اشتراك الباص</label>
                                <select class="form-control " id="myDIV" name="trip_type" id="trip_type">
                                    <option value="" disabled> اختر النوع </option>
                                    <option value="full_day"> ذهاب وعودة </option>
                                    <option value="end_day"> ذهب فقط </option>
                                    <option value="start_day"> عودة فقط </option>

                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="student_id"> الطلاب</label>
                                <select class="form-control  basic-select2" name="student_id[]" id="student_id" multiple>
                                    <option value="" disabled> اختر الطلاب</option>

                               </select>
                            </div>
                        </div>





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
         $(document).ready(function () {
        $('select[name="school_id"]').on('change', function () {
            var school_id = $(this).val();
            if (school_id) {
                $.ajax({
                    url: "{{ URL::to('dashboard/buses/get-bus') }}/" + school_id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('select[name="bus_id"]').empty();
                        $.each(data, function (key, value) {
                            $('select[name="bus_id"]').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });

    $(document).ready(function () {
        $('select[name="school_id"]').on('change', function () {
            var school_id = $(this).val();
            if (school_id) {
                $.ajax({
                    url: "{{ URL::to('dashboard/students/get-students-school') }}/" + school_id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('select[name="student_id[]"]').empty();
                        $.each(data, function (key, value) {
                            $('select[name="student_id[]"]').append('<option value="' + key + '" >' + value + '</option>');
                        });
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });

    $(document).ready(function() {
            $('.basic-select2').select2();
        });
    </script>
@endpush

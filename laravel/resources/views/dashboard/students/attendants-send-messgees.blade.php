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
            <h4 class="card-title">
                <span>اضافة / </span>
                <span>رسالة الي </span>
                <span>اولياء امور الطالب : </span>
                <span>{{ $student->name }}</span>
            </h4>

        </div>
        <div class="card-body mt-1">

            <!-- Account Tab starts -->
            <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                <!-- users edit account form start -->
                <form method="POST" action="{{ route('dashboard.students.attendants.send.messgees.store', $student->id) }}" class="m-form m-form--fit m-form--label-align-right" enctype="multipart/form-data">
                    @csrf
                    <div class="row">


                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="attendant_id">المرافق</label>
                                <select class="form-control basic-select2" name="attendant_id" id="attendant_id" required>
                                    <option value="" disabled> اختر المرافق </option>
                                    @foreach ($student->bus->attendants as $attendant)
                                        <option value="{{ $attendant->id }}">{{ $attendant->name }}</option>
                                    @endforeach


                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="parents_id">اولياء الامور</label>
                                <select class="form-control basic-select2" name="parents_id[]" id="parents_id" required multiple aria-readonly="true">
                                    @foreach ($student->my_Parents as $parent)
                                        <option value="{{ $parent->id }}" selected >{{ $parent->name }}</option>
                                    @endforeach


                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="message_id">المرافق</label>
                                <select class="form-control basic-select2" name="message_id" id="message_id" required>
                                    @foreach ($messages as $message)
                                        <option value="{{ $message->id }}">{{ $message->message }}</option>
                                    @endforeach


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
            $('select[name="my__parent_id"]').on('change', function () {
                var my__parent_id = $(this).val();
                if (my__parent_id) {
                    $.ajax({
                        url: "{{ URL::to('dashboard/students/get-students') }}/" + my__parent_id,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('select[name="student_id"]').empty();
                            $.each(data, function (key, value) {
                                $('select[name="student_id"]').append('<option value="' + key + '">' + value + '</option>');
                            });
                        },
                    });
                } else {
                    console.log('AJAX load did not work');
                }
            });
        });
    </script>
@endpush

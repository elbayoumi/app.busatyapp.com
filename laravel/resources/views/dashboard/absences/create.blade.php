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
            <h4 class="card-title">اضافة / طلب غياب</h4>

        </div>
        <div class="card-body mt-3">

            <!-- Account Tab starts -->
            <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                <!-- users edit account form start -->
                <form method="POST" action="{{ route('dashboard.absences.store') }}" class="m-form m-form--fit m-form--label-align-right" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="my__parent_id"> ولي الامر</label>
                                <select class="form-control basic-select2" name="my__parent_id" id="my__parent_id">
                                    <option value="" disabled> اختر ولي الامر </option>

                                    @forelse ($parent as $parent)

                                    <option value="{{ $parent->id }}" >{{ $parent->name }}</option>

                                    @empty
                                   @endforelse
                               </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="student_id"> الابن</label>
                                <select class="form-control" name="student_id" id="student_id" required>
                                    <option value="" disabled selected> اختتر الابن</option>



                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="attendence_date">تاريخ الغياب</label>
                                <input id="attendence_date" type="text" class="form-control birthdate-picker" value="{{ old('attendence_date') }}" name="attendence_date" id="attendence_date" required  autocomplete="off" placeholder="YYYY-MM-DD" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="attendence_type">فترة الغياب</label>
                                <select class="form-control" name="attendence_type" id="attendence_type" required>
                                    <option value="" disabled > اختر فترة الغياب</option>
                                    <option value="start_day">:: بدية اليوم  ::</option>
                                    <option value="end_day">:: نهاية اليوم ::</option>
                                    <option value="full_day">:: اليوم كامل::</option>


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
        $(document).ready(function() {
            $('.basic-select2').select2();
        });
    </script>
@endpush

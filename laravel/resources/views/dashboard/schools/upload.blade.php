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
                <h4 class="card-title">اضافة مجموعة من الطلاب عن طريق ملف اكسل</h4>

                <a class="btn btn-primary" href="{{ route('dashboard.students.attachments.import') }}">تحميل ملف</a>
            </div>
            <div class="card-body mt-3">

                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                    <!-- users edit account form start -->
                    <form method="POST" action="{{ route('dashboard.students.import') }}"
                        class="m-form m-form--fit m-form--label-align-right" enctype="multipart/form-data">
                        @csrf
                        <div class="row">


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_id">المدرسة</label>
                                    <select class="form-control  basic-select2" name="school_id" id="school_id" required>
                                        <option value="" disabled selected> اختر المدرسة </option>
                                        @foreach ($school as $sch)
                                            <option value="{{ $sch->id }}">{{ $sch->name }}</option>
                                        @endforeach


                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="grade_id">المرحلة الدراسية</label>
                                    <select class="form-control" name="grade_id" id="grade_id" required>
                                        <option value="" disabled selected> اختر المرحلة </option>



                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="classroom_id">الصفوف الدراسية</label>
                                    <select class="form-control" name="classroom_id" id="classroom_id" required>
                                        <option value="" disabled selected> اختر الصف </option>



                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="attachment">ملف الاكسل</label>
                                    <input type="file" class="form-control" placeholder="attachment" value="" name="attachment" id="attachment"/>

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
    {{-- <script src="https://code.jquery.com/jquery-3.5.0.js"></script> --}}

    <script>
         $(document).ready(function() {
            $('.basic-select2').select2();
        });
        $(document).ready(function() {
            $('select[name="school_id"]').on('change', function() {
                var school_id = $(this).val();
                if (school_id) {
                    $.ajax({
                        url: "{{ URL::to('dashboard/grades/get-grades') }}/" + school_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="grade_id"]').empty();
                            $('select[name="classroom_id"]').empty();
                            $('select[name="grade_id"]').append('<option value="" disabled selected> اختر مرحلة دراسي</option>');
                            $('select[name="classroom_id"]').append('<option value="" disabled selected> اختر صف دراسي</option>');
                            $.each(data, function(key, value) {
                                $('select[name="grade_id"]').append('<option value="' +
                                    key + '">' + value + '</option>');
                            });
                        },
                    });
                } else {
                    console.log('AJAX load did not work');
                }
            });
        });

        $(document).ready(function() {
            $('select[name="grade_id"]').on('change', function() {
                var school_id = $('select[name="school_id"]').val();
                var grade_id = $(this).val();
                if (grade_id) {
                    $.ajax({
                        url: "{{ URL::to('dashboard/classrooms/get-classrooms') }}/" + grade_id,
                        type: "GET",
                        dataType: "json",
                        data:{school_id:school_id},
                        success: function(data) {
                            $('select[name="classroom_id"]').empty();
                            $('select[name="classroom_id"]').append('<option value="" disabled> اختر صف دراسي</option>');
                            $.each(data, function(key, value) {
                                $('select[name="classroom_id"]').append(
                                    '<option value="' + key + '">' + value +
                                    '</option>');
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

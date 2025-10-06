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
        <div class="card-body">
            <h4 class="card-title"> تعديل بيانات الصف الدراسي</h4>

            <div class="tab-content">
                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                    <!-- users edit account form start -->
                    <form action="{{ route('dashboard.classrooms.update', $class->id) }}" method="post" enctype="multipart/form-data" class="mt-2">
                        @csrf
                        @method('PUT')
                        <div class="row">


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">اسم</label>
                                    <input type="text" class="form-control" placeholder="Name" value="{{ $class->name }}" name="name" id="name" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_id">اسم المدرسة</label>
                                    <input type="text" class="form-control" placeholder="name" value="{{ $class->school->name }}" name="school_id" id="school_id" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="grade_id">اسم المرحلة الدرسية</label>
                                    <input type="text" class="form-control" placeholder="name" value="{{ $class->school->name }}" name="grade_id" id="grade_id" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">الحالة</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="1" @if ($class->status == 1) selected @endif>مفعل</option>
                                        <option value="0" @if ($class->status == 0) selected @endif>غير مفعل</option>
                                    </select>
                                </div>
                            </div>



                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                <button type="submit"
                                    class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">حفظ التغييرات
                                    </button>
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
@endpush

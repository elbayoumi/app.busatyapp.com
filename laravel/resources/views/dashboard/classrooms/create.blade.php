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
                <h4 class="card-title">  اضف صف دراسي  :{{$school->name}} </h4>

            </div>
            <div class="card-body mt-2">

                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                    <!-- users edit account form start -->
                    <form method="POST" action="{{ route('dashboard.classrooms.store')}}"
                        class="m-form m-form--fit m-form--label-align-right" enctype="multipart/form-data">
                        @csrf
                        @method('post')
                        <div class="row">

                            <input type="hidden" class="form-control" placeholder="Name" value="{{$school->id}}" name="school_id" id="{{$school->name}}" required />

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">اسم الصف الدراسي</label>
                                    <input type="text" class="form-control" placeholder="Name" value="{{ old('name') }}" name="name" id="name" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="grade_id">اختر المرحلة الدرسية</label>
                                    <select class="form-control" name="grade_id" id="grade_id">
                                        @forelse ($all_grades as $grade)

                                        <option value="{{ $grade->id }}" >{{ $grade->name }}</option>

                                    @empty
                                    @endforelse
                                   </select>
                                </div>
                            </div>



                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">الحالة</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="1">مفعل</option>
                                        <option value="0">غير مفعل</option>
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
@endpush

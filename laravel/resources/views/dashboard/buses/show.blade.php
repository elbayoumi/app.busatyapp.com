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
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item mr-1">
                    <a class="nav-link d-flex align-items-center active" id="account-tab"
                        data-toggle="tab" href="#account" aria-controls="account" role="tab" aria-selected="true">
                        <i class="fa fa-bus"></i><span class="d-none d-sm-block"> عرض بيانات الباص </span>
                    </a>
                </li>
                <li class="nav-item mr-1">
                    <a target="_blank" href="{{ route('dashboard.buses.print.data', $buses->id) }}" class="btn btn-gradient-secondary">
                        <span class="d-none d-sm-block"> طباعة </span>
                    </a>
                </li>

                <li class="nav-item mr-1">
                    <a target="_blank" href="{{ route('dashboard.buses.export.data', $buses->id) }}" class="btn btn-gradient-secondary">
                        <span class="d-none d-sm-block"> ملف اكسل </span>
                    </a>
                </li>
                <li class="nav-item mr-1">
                    <a target="_blank" href="{{ route('dashboard.buses.export.data.pdf', $buses->id) }}" class="btn btn-gradient-secondary">
                        <span class="d-none d-sm-block"> ملف pdf </span>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                    <!-- users edit account form start -->
                    <form class="mt-2">
                        <div class="row">


                                <!-- users edit media object start -->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">وصف الباص</label>
                                    <input type="text" class="form-control" placeholder="Name" value="{{ $buses->name }}" name="name" id="name" disabled/>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="car_number">رقام الباص</label>
                                    <input type="text" class="form-control" placeholder="car_number" value="{{ $buses->car_number }}" name="car_number" id="car_number" disabled/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="students"> عدد الطلاب</label>
                                    <input type="text" class="form-control" placeholder="students" value="{{ $buses->students->count()  }}" name="students" id="students" disabled/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_id">  اسم المدرسة</label>
                                    <input type="text" class="form-control" placeholder="school_id" value="{{ $buses->schools->name  }}" name="school_id" id="school_id" disabled/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="attendant_driver_id"> اسم السائق</label>
                                    <input type="text" class="form-control" placeholder="attendant_driver_id" value="{{  isset($buses->driver) != null ? $buses->driver->name : 'Undefined' }}" name="attendant_driver_id" id="attendant_driver_id" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="attendant_driver_id">  اسم المشرف</label>
                                    <input type="text" class="form-control" placeholder="attendant_driver_id" value="{{  isset($buses->admin) != null ? $buses->admin->name : 'Undefined' }}" name="attendant_driver_id" id="attendant_driver_id" disabled/>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">ملحوظة</label>

                                    <textarea type="text" class="form-control" placeholder="notes" value="" name="notes" id="notes" rows="5" disabled>{{ $buses->notes }}</textarea>
                                </div>
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

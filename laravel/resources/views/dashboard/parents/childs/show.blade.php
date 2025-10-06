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
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title"> بيانات الطالب {{ $Student->name }}</h4>

                </div>
        <div class="card-body">
            <ul class="nav nav-pills" role="tablist">

            <div class="tab-content">
                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                    <!-- users edit account form start -->
                    <form class="mt-2">
                        <div class="row">

                            <div class="col-md-12">
                                <!-- users edit media object start -->
                                <div class="media mb-2">
                                    <img width="60"
                                    src="{{ $school->logo_path }}"
                                   class="m--img-rounded m--marginless" alt="cover">
                                    <div class="media-body mt-50">
                                        {{-- <h4>Eleanor Aguilar</h4> --}}
                                        <div class="col-12 d-flex mt-1 px-0">
                                            {{-- <label class="btn btn-primary mr-75 mb-0" for="change-picture">
                                                <span class="d-none d-sm-block">Change</span>
                                                <input class="form-control" type="file" name="avatar" id="change-picture" hidden accept="image/png, image/jpeg, image/jpg" />
                                                <span class="d-block d-sm-none">
                                                    <i class="mr-0" data-feather="edit"></i>
                                                </span>
                                            </label> --}}
                                            @if ($Student->logo() != null)
                                                <a href="{{ route('dashboard.common.image-delete', $Student->logo()->id) }}" class="btn btn-outline-secondary d-none d-sm-block">Remove</a>
                                            @endif
                                            <button class="btn btn-outline-secondary d-block d-sm-none">
                                                <i class="mr-0" data-feather="trash-2"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- users edit media object ends -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">اسم الابن</label>
                                    <input type="text" class="form-control" placeholder="Name" value="{{ $Student->name }}" name="name" id="name" disabled/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone">رقم االهاتف</label>
                                    <input type="tel" class="form-control" placeholder="Phone" value="{{ $Student->phone }}" name="phone" id="phone" disabled/>
                                </div>
                            </div>

                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">المدرسة</label>
                                    <input type="text" class="form-control" placeholder="Name" value="{{ $Student->schools->name}}" name="name" id="name" disabled/>
                                </div>
                            </div> --}}

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">المرحلة الدراسية</label>
                                    <input type="text" class="form-control" placeholder="Name" value="{{ $Student->grade->name}}" name="name" id="name" disabled/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="classroom"> الصف الدراسي</label>
                                    <input type="text" class="form-control" placeholder="grade" value="{{ $Student->classroom->name}}" name="classroom" id="classroom" disabled/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="Date_Birth"> تاريخ الميلاد</label>
                                    <input type="text" class="form-control" placeholder="Date_Birth" value="{{ $Student->Date_Birth}}" name="Date_Birth" id="Date_Birth" disabled/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city_name"> اسم المدينة</label>
                                    <input type="text" class="form-control" placeholder="city_name" value="{{ $Student->city_name}}" name="city_name" id="city_name" disabled/>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">  العنوان </label>
                                    <input type="text" class="form-control" placeholder="address" value="{{ $Student->address}}" name="address" id="address" disabled/>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- users edit account form ends -->
                </div>
                <!-- Account Tab ends -->

                <!-- Information Tab starts -->

                <!-- Information Tab ends -->


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

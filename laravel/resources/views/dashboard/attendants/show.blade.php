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
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center active" id="account-tab"
                        data-toggle="tab" href="#account" aria-controls="account" role="tab" aria-selected="true">
                        <i data-feather="user"></i><span class="d-none d-sm-block"> عرض بيانات المرافق </span>
                    </a>
                </li>


            </ul>
            <div class="tab-content">
                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                    <!-- users edit account form start -->
                    <form class="mt-2">
                        <div class="row">

                            <div class="col-md-12">
                                <!-- users edit media object start -->
                                <div class="media mb-2">
                                    <img
                                    src="{{ $attendant->logo_path }}" alt="users avatar"
                                    class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                    height="90" width="90">

                                    <div class="media-body mt-50">
                                        {{-- <h4>Eleanor Aguilar</h4> --}}
                                        <div class="col-12 d-flex mt-1 px-0">

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
                                    <label for="name">اسم المرافق</label>
                                    <input type="text" class="form-control" placeholder="Name" value="{{ $attendant->name }}" name="name" id="name" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="username">اسم المستخدم</label>
                                    <input type="text" class="form-control" placeholder="username" value="{{ $attendant->username }}" name="username" id="username" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone">الهاتف</label>
                                    <input type="tel" class="form-control" placeholder="Phone" value="{{ $attendant->phone }}" name="phone" id="phone" disabled/>
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city_name"> اسم المدبنة </label>
                                    <input type="text" class="form-control" placeholder="city name" value="{{ isset($attendant->city_name) ? $attendant->city_name  : 'غير معرف'  }}" name="city_name" id="city_name" disabled/>
                                </div>
                            </div> --}}

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">العنوان</label>
                                    <input type="text" class="form-control" placeholder="address" value="{{ $attendant->address }}" name="address" id="address" disabled/>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">الوظيفة</label>
                                    <input type="text" class="form-control" placeholder="type" value="{{ isset($attendant->type) ? $attendant->type  : 'غير معرف'  }}" name="type" id="type" disabled/>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">تاريخ  الانضمام</label>
                                    <input type="text" class="form-control" placeholder="type" value="{{isset($attendant->created_at) ? $attendant->created_at  : 'غير معرف'  }}" name="Joining_Date" id="Joining_Date" disabled/>
                                </div>
                            </div>


                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">تاريخ الميلاد</label>
                                    <input type="text" class="form-control" placeholder="birth_date" value="{{ isset($attendant->birth_date) ? $attendant->birth_date  : 'غير معرف'  }}" name="birth_date" id="birth_date" disabled/>
                                </div>
                            </div> --}}



                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender_id">النوع</label>
                                    <input type="text" class="form-control" placeholder="gender_id" value="{{ $attendant->gender->name }}" name="gender_id" id="gender_id" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_id">المدرسة</label>
                                    <input type="text" class="form-control" placeholder="school_id" value="{{ $attendant->schools->name }}" name="school_id" id="school_id" disabled/>
                                </div>
                            </div>
{{--
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="typeBlood">الديانة</label>
                                    <input type="text" class="form-control" placeholder="religion" value="{{ isset($attendant->religion->name) ? $attendant->religion->name : 'غير معرف'   }}" name="religion" id="religion" disabled/>
                                </div>
                            </div> --}}

{{--
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="typeBlood"> فصيلة الدم</label>
                                    <input type="text" class="form-control" placeholder="type__blood_id" value="{{ isset($attendant->typeBlood->name) ? $attendant->typeBlood->name : 'غير معرف' }}" name="typeBlood" id="typeBlood" disabled/>
                                </div>
                            </div> --}}


                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="typeBlood"> الحالة</label>
                                    <input type="text" class="form-control" placeholder="type__blood_id" value="{{ $attendant->status == 0 ? 'غير مفعل' : 'مفعل'}}" name="typeBlood" id="typeBlood" disabled/>
                                </div>
                            </div> --}}



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

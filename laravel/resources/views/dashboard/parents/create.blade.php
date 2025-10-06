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

            <!-- Account Tab starts -->
            <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                <!-- users edit account form start -->
                <form method="POST" action="{{ route('dashboard.parents.store') }}" class="m-form m-form--fit m-form--label-align-right" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <!-- users edit media object start -->
                            <div class="media mb-2">
                                <img src="{{ image_or_placeholder('', 'profile') }}" alt="users avatar"
                                    class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                    height="90" width="90" />
                                <div class="media-body mt-50">
                                    {{-- <h4>Eleanor Aguilar</h4> --}}
                                    <div class="col-12 d-flex mt-1 px-0">
                                        <label class="btn btn-primary mr-75 mb-0" for="change-picture">
                                            <span class="d-none d-sm-block">اختر صورة</span>
                                            <input class="form-control" type="file" name="logo" id="change-picture" hidden accept="image/png, image/jpeg, image/jpg" />
                                            <span class="d-block d-sm-none">
                                                <i class="mr-0" data-feather="edit"></i>
                                            </span>
                                        </label>
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
                                <label for="name">اسم</label>
                                <input type="text" class="form-control" placeholder="Name" value="{{ old('name') }}" name="name" id="name" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">البريد الالكترونى</label>
                                <input type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" name="email" id="email" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="phone">رقم الهاتف</label>
                                <input type="tel" class="form-control" placeholder="Phone" value="{{ old('phone') }}" name="phone" id="phone" required />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="password">الرقم السرى</label>
                                <input type="password" class="form-control" placeholder="Password" value="{{ old('password') }}" name="password" id="password" required />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address">العنوان</label>
                                <input type="text" class="form-control" placeholder="address" value="{{ old('address') }}" name="address" id="address" required />
                            </div>
                        </div>


                        {{-- <div class="col-md-4">
                            <div class="form-group">
                                <label for="school_id">المدرسة</label>
                                <select class="form-control" name="school_id" id="school_id" required>
                                    <option value="" disabled> اختر المدرسة </option>
                                    @foreach($school as $sch)
                                    <option value="{{$sch->id}}">{{$sch->name}}</option>
                                    @endforeach


                                </select>
                            </div>
                        </div> --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status"> الحالة</label>
                                <select class="form-control" name="status" id="status" required>
                                    <option value="" disabled>اختر الحالة</option>
                                    <option value="1">:: مفعل  ::</option>
                                    <option value="0">:: غير مفعل ::</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email_verified_at"> حالة البريد الالكتروني</label>
                                <select class="form-control" name="email_verified_at" id="email_verified_at" required>
                                    <option value="" disabled>اختر الحالة</option>
                                    <option value="1">:: مفعل  ::</option>
                                    <option value="0">:: غير مفعل ::</option>
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

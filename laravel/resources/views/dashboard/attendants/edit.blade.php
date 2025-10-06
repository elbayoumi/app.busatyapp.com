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
            <h4 class="card-title"> تعديل بيانات المرافق</h4>

        </div>
        <div class="card-body mt-3">
            <ul class="nav nav-pills" role="tablist">

            <div class="tab-content">
                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                    <!-- users edit account form start -->
                    <form action="{{route('dashboard.attendants.update', $attendant->id) }}" method="post" enctype="multipart/form-data" class="mt-2">
                        @csrf
                        @method('PUT')
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
                                <!-- users edit media object ends -->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">اسم</label>
                                    <input type="text" class="form-control" placeholder="Name" value="{{ $attendant->name }}" name="name" id="name" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="username">اسم المستخدم</label>
                                    <input type="username" class="form-control" placeholder="username" value="{{ $attendant->username }}" name="username" id="username" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone">رقم الهاتف</label>
                                    <input type="tel" class="form-control" placeholder="Phone" value="{{ $attendant->phone }}" name="phone" id="phone" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password">الرقم السرى</label>
                                    <input type="password" class="form-control" placeholder="Password" name="password" id="password" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">العنوان</label>
                                    <input type="text" class="form-control" placeholder="address" value="{{ $attendant->address }}" name="address" id="address" />
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city_name">اسم المدينة</label>
                                    <input type="text" class="form-control" placeholder="city_name" value="{{ $attendant->city_name }}" name="city_name" id="city_name" />
                                </div>
                            </div> --}}

                            {{-- <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label for="birth_date">تاريخ الميلاد</label>
                                    <input id="birth_date" type="text" class="form-control birthdate-picker" value="{{ $attendant->birth_date }}" name="birth_date" id="birth_date"   autocomplete="off" placeholder="YYYY-MM-DD" />
                                </div>
                            </div> --}}

                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label for="created_at">تاريخ الانضمام</label>
                                    <input id="created_at" type="text" class="form-control birthdate-picker" value="{{ $attendant->created_at }}" name="created_at" id="created_at"   autocomplete="off" placeholder="YYYY-MM-DD" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender_id">النوع</label>
                                    <select class="form-control" name="gender_id" id="gender_id">
                                        <option value="" disabled> اختر النوع</option>
                                        @foreach($genders as $gender)
                                        <option value="{{$gender->id}}" @if ($attendant->gender_id == $gender->id) selected @endif>{{$gender->name}}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type__blood_id">فصيلة الدم</label>
                                    <select class="form-control" name="type__blood_id" id="type__blood_id">
                                        <option value="" disabled> اختر فصيلة الدم</option>
                                        <option value="">غير معرف</option>
                                        @foreach($typeBlood as $type)
                                        <option value="{{$type->id}}"  @if ($attendant->type__blood_id == $type->id) selected @endif>{{$type->name}}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bus_id">الباص</label>
                                    <select class="form-control  basic-select2" name="bus_id" id="bus_id" required>
                                        <option value="" disabled> اختر المدرسة </option>
                                        <option value="" disabled>لم يتم تحديد الباص</option>
                                        @foreach($buses as $bus)
                                        <option value="{{$bus->id}}" @if ($attendant->bus_id == $bus->id) selected @endif>{{$bus->name}}</option>
                                        @endforeach


                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="religion_id">الديانة</label>
                                    <select class="form-control" name="religion_id" id="religion_id">
                                        <option value="" disabled> اختر الديانة  </option>
                                        <option value="">غير معرف</option>
                                        @foreach($religion as $rel)
                                        <option value="{{$rel->id}}" @if ($attendant->religion_id == $rel->id) selected @endif>{{$rel->name}}</option>
                                        @endforeach


                                    </select>
                                </div>
                            </div> --}}

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type">نوع المرافق</label>
                                    <select class="form-control" name="type" id="type" required>
                                        <option value="" disabled>اختر المشرف</option>
                                        <option value="drivers"  @if ($attendant->type == 'drivers') selected @endif>:: سائق ::</option>
                                        <option value="admins"   @if ($attendant->type == 'admins') selected @endif>:: مشرف ::</option>


                                    </select>
                                </div>
                            </div>




                        </div>

                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                <button type="submit"
                                    class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">تعديل</button>
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
        $(document).ready(function() {
            $('.basic-select2').select2();
        });
    </script>
@endpush

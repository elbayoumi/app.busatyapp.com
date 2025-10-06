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
                <h4 class="card-title">اضف مرافق</h4>

            </div>
            <div class="card-body mt-3">

                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                    <!-- users edit account form start -->
                    <form method="POST" action="{{ route('dashboard.attendants.store') }}"
                        class="m-form m-form--fit m-form--label-align-right" enctype="multipart/form-data">
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
                                                <input class="form-control" type="file" name="logo"
                                                    id="change-picture" hidden accept="image/png, image/jpeg, image/jpg" />
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
                                    <label for="name">اسم المرافق</label>
                                    <input type="text" class="form-control" placeholder="Name"
                                        value="{{ old('name') }}" name="name" id="name" required
                                        autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="username">اسم المستخدم</label>
                                    <input type="text" class="form-control" placeholder="username"
                                        value="{{ old('username') }}" name="username" id="username" autocomplete="off"
                                         />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password">الرقم السري </label>
                                    <input type="password" class="form-control" placeholder="Password" value=""
                                        name="password" id="password" autocomplete="off" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone">الهاتف</label>
                                    <input type="tel" class="form-control" placeholder="Phone"
                                        value="{{ old('phone') }}" name="phone" id="phone" required />
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">العنوان</label>
                                    <input type="text" class="form-control" placeholder="address"
                                        value="{{ old('address') }}" name="address" id="address" required />
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city_name">اسم المدينة</label>
                                    <input type="text" class="form-control" placeholder="City name"
                                        value="{{ old('city_name') }}" name="city_name" id="address"  />
                                </div>
                            </div> --}}

                            {{-- <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label for="birth_date">تاريخ الميلاد</label>
                                    <input id="birth_date" type="text" class="form-control birthdate-picker"
                                        value="{{ old('birth_date') }}" name="birth_date" id="birth_date"
                                        autocomplete="off" placeholder="YYYY-MM-DD" />
                                </div>
                            </div> --}}
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label for="Joining_Date">تاريخ الانضمام</label>
                                    <input id="Joining_Date" type="text" class="form-control birthdate-picker"
                                        value="{{ old('Joining_Date') }}" name="Joining_Date" id="Joining_Date"
                                        autocomplete="off" placeholder="YYYY-MM-DD" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender_id">النوع</label>
                                    <select class="form-control" name="gender_id" id="gender_id" required>
                                        <option value="" disabled> اختر النوع</option>
                                        @foreach ($genders as $gender)
                                            <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                                        @endforeach


                                    </select>
                                </div>
                            </div>


                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type__blood_id">فصيلة الدم</label>
                                    <select class="form-control" name="type__blood_id" id="type__blood_id" >
                                        <option value="" disabled> اختر فصيلة الدم</option>
                                        <option value="">غير معرف</option>

                                        @foreach ($typeBlood as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach


                                    </select>
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="religion_id">الديانة</label>
                                    <select class="form-control" name="religion_id" id="religion_id" required>
                                        <option value="" disabled> اختر الديانة </option>
                                        <option value="">غير معرف</option>

                                        @foreach ($religion as $rel)
                                            <option value="{{ $rel->id }}">{{ $rel->name }}</option>
                                        @endforeach


                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_id">المدرسة</label>
                                    <select class="form-control basic-select2" name="school_id" id="school_id" required>
                                        <option value="" disabled> اختر المدرسة </option>
                                        @foreach ($school as $sch)
                                            <option value="{{ $sch->id }}">{{ $sch->name }}</option>
                                        @endforeach


                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bus_id ">الباصات</label>
                                    <select class="form-control  basic-select2" name="bus_id" id="bus_id">
                                        <option value="" disabled> اختر الباص </option>
                                        <option value="" disabled>لم يتم تحديد الباص</option>



                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type">نوع المرافق</label>
                                    <select class="form-control" name="type" id="type" required>
                                        <option value="" disabled>اختر المشرف</option>
                                        <option value="drivers">:: سائق ::</option>
                                        <option value="admins">:: مشرف ::</option>


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

$(document).ready(function() {
            $('select[name="school_id"]').on('change', function() {
                var school_id = $(this).val();
                if (school_id) {
                    $.ajax({
                        url: "{{ URL::to('dashboard/buses/get-bus') }}/" + school_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="bus_id"]').empty();
                            $('select[name="bus_id"]').append(
                                '<option value="" disabled selected> اختر الباص</option>');
                            $('select[name="bus_id"]').append(
                                '<option value="">لم يتم تحديد الباص</option>');
                            $.each(data, function(key, value) {
                                $('select[name="bus_id"]').append('<option value="' +
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
            $('.basic-select2').select2();
        });
    </script>
@endpush

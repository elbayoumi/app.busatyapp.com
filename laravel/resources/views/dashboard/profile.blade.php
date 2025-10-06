@extends('dashboard.layouts.app')
@push('page_vendor_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
@endpush
@push('page_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/plugins/forms/pickers/form-flat-pickr.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/pages/app-user.css">
@endpush
@section('content')

<!-- users edit start -->
<section class="app-user-edit">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center active" id="account-tab" data-toggle="tab" href="#account" aria-controls="account" role="tab" aria-selected="true">
                        <i data-feather="user"></i><span class="d-none d-sm-block">الحساب</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" id="change_password-tab" data-toggle="tab" href="#change_password" aria-controls="change_password" role="tab" aria-selected="false">
                        <i data-feather='key'></i></i><span class="d-none d-sm-block">تغير كلمة المرور</span>
                    </a>
                </li>


            </ul>
            <div class="tab-content">
                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                    <!-- users edit media object start -->

                    <!-- users edit media object ends -->
                    <!-- users edit account form start -->
                    <form action="{{ route('dashboard.profile.update') }}" method="post" enctype="multipart/form-data" class="form-validate">
                        @method('PUT')
                        @csrf
                        <div class="media mb-2">
                            <img src="{{ auth()->guard('web')->user()->logo_path}}" alt="users avatar" class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer w-25"  />
                            <div class="media-body mt-50">
                                <h4>{{ auth()->guard('web')->user()->name }}</h4>
                                <div class="col-12 d-flex mt-1 px-0">
                                    <label class="btn btn-primary mr-75 mb-0" for="change-picture">
                                        <span class="d-none d-sm-block">اختر صورة</span>
                                        <input type="file" name="logo" id="change-picture" hidden accept="image/png, image/jpeg, image/jpg" class="form-control" />
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">الاسم</label>
                                    <input type="text" name="name" placeholder="Name" value="{{ auth()->guard('web')->user()->name }}" class="form-control" id="name" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">البريد الالكتروني</label>
                                    <input type="email" name="email" placeholder="Email" value="{{ auth()->guard('web')->user()->email }}" class="form-control"  id="email" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company">كلمة السر الحالية</label>
                                    <input type="password" name="current_password" class="form-control" placeholder="Enter your current password to حفظ التغيرات" id="company" />
                                </div>
                            </div>
                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">حفظ التغيرات</button>
                            </div>
                        </div>
                    </form>
                    <!-- users edit account form ends -->
                </div>
                <!-- Account Tab ends -->

                <!-- change_password Tab starts -->
                <div class="tab-pane" id="change_password" aria-labelledby="change_password-tab" role="tabpanel">
                    <!-- users edit Info form start -->
                    <form class="form-validate">
                        <div class="row mt-1">
                            <div class="col-12">
                                <h4 class="mb-1">
                                    <i data-feather="user" class="font-medium-4 mr-25"></i>
                                    <span class="align-middle">تغير كلمة السر</span>
                                </h4>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company"> كلمة سر جديدة </label>
                                    <input type="password" name="new_password" class="form-control" placeholder="Enter your new password to حفظ التغيرات" id="company" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company">تأكيد كلمة السر الجديدة</label>
                                    <input type="password" name="new_password_confirmation" class="form-control" placeholder="Enter your new password confirmation to حفظ التغيرات" id="company" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company">كلمة المرور الحالية</label>
                                    <input type="password" name="current_password" class="form-control" placeholder="Enter your current password to حفظ التغيرات" id="company" />
                                </div>
                            </div>
                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">حفظ التغيرات</button>
                            </div>
                        </div>
                    </form>
                    <!-- users edit Info form ends -->
                </div>
                <!-- change_password Tab ends -->



            </div>
        </div>
    </div>
</section>
<!-- users edit ends -->


@endsection

@push('page_scripts_vendors')

@endpush

@push('page_scripts')
    <script src="{{ asset('assets/dashboard') }}/app-assets/js/scripts/pages/app-user-edit.js"></script>
    <script src="{{ asset('assets/dashboard') }}/app-assets/js/scripts/components/components-navs.js"></script>
@endpush

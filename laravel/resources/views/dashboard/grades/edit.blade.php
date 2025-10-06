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
                        <i data-feather="user"></i><span class="d-none d-sm-block">تعديل بيانات المرحلة الدراسية</span>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" id="information-tab" data-toggle="tab"
                        href="#information" aria-controls="information" role="tab" aria-selected="false">
                        <i data-feather="info"></i><span class="d-none d-sm-block">Information</span>
                    </a>
                </li> --}}

            </ul>
            <div class="tab-content">
                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                    <!-- users edit account form start -->
                    <form action="{{ route('dashboard.schools.update', $school->id) }}" method="post" enctype="multipart/form-data" class="mt-2">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" placeholder="Name" value="{{ $school->name }}" name="name" id="name" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input type="email" class="form-control" placeholder="Email" value="{{ $school->email }}" name="email" id="email" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="tel" class="form-control" placeholder="Phone" value="{{ $school->phone }}" name="phone" id="phone" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" placeholder="Password" name="password" id="password" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" placeholder="address" value="{{ $school->address }}" name="address" id="address" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city_name">City name</label>
                                    <input type="text" class="form-control" placeholder="city_name" value="{{ $school->city_name }}" name="city_name" id="city_name" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="1" @if ($school->status == 1) selected @endif>Active</option>
                                        <option value="0" @if ($school->status == 0) selected @endif>Deactivated</option>
                                    </select>
                                </div>
                            </div>



                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                <button type="submit"
                                    class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">Save
                                    Changes</button>
                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
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

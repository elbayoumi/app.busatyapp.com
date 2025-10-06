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
                        <i data-feather="user"></i><span class="d-none d-sm-block">المرحلة الدراسية:: {{ $grade->name}}  </span>
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

                                <!-- users edit media object ends -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">اسم</label>
                                    <input type="text" class="form-control" placeholder="Name" value="{{ $grade->name }}" name="name" id="name" disabled/>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">ملحوظة</label>
                                    <input type="text" class="form-control" placeholder="Name" value="{{ $grade->notes }}" name="Notes" id="Name" disabled/>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">الحالة</label>
                                    <select class="form-control" name="status" id="status" disabled>
                                        <option value="1" @if ($grade->status == 1) selected @endif>مفعل</option>
                                        <option value="0" @if ($grade->status == 0) selected @endif>غير مفعل</option>
                                    </select>
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

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
                <h4 class="card-title">اضف باص</h4>

            </div>
            <div class="card-body mt-3">

                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                    <!-- users edit account form start -->
                    <form method="POST" action="{{ route('dashboard.school_messages.store') }}"
                        class="m-form m-form--fit m-form--label-align-right">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_id">المدرسة</label>
                                    <select class="form-control basic-select2" name="school_id" id="school_id"
                                        onchange="console.log($(this).val())" required>
                                        <option value="" selected disabled> اختر المدرسة </option>
                                        @foreach ($school as $sch)
                                            <option value="{{ $sch->id }}">{{ $sch->name }}</option>
                                        @endforeach


                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <label for="event_date">تاريخ الحداث</label>
                                    <input id="event_date" type="text" class="form-control birthdate-picker" value="{{ old('event_date') }}" name="event_date" id="event_date" required  autocomplete="off" placeholder="YYYY-MM-DD" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">عنوان الحدث</label>
                                    <input type="text" class="form-control" placeholder="Name"
                                        value="{{ old('name') }}" name="name" id="name" required />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="message"> الرسالة</label>
                                    <textarea type="text" class="form-control"value="" name="message" id="message" required rows="5">{{ old('message') }}</textarea>
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

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
                <h4 class="card-title"> عرض بيانات الاعلان</h4>

            </div>
            <div class="card-body mt-3">
                <ul class="nav nav-pills" role="tablist">

                    <div class="tab-content">
                        <!-- Account Tab starts -->
                        <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                            <!-- users edit account form start -->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">title </label>
                                        <input disabled type="text" class="form-control" placeholder="title"
                                            value="{{ $ades?->title }}" name="title" id="title" />
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="body">body </label>
                                        <input disabled type="text" class="form-control" placeholder="body"
                                            name="body" value="{{ $ades?->body }}" id="body" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="link">link </label>
                                        <input disabled type="text" class="form-control" placeholder="link"
                                            name="link" value="{{ $ades?->link }}" id="link" />
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="alt">alt </label>
                                        <input disabled type="text" class="form-control" placeholder="alt" name="alt"
                                            value="{{ $ades?->alt }}" id="alt" />
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <img class="w-25 rounded-circle" src="{{ $ades?->image_path }}"
                                            alt="{{ $ades?->alt }}">
                                    </div>
                                </div>

                            </div>

                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                @if ($ades?->adesSchool?->count())
                                    <a href="{{ route('dashboard.adesSchool.showSchools', $ades?->id) }}"
                                        class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">عرض المدارس</a>
                                @else
                                    <a href="#" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">لايوجد مدارس اضف
                                        الان</a>
                                @endif

                            </div>
                        </div>
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
    <script>
        document.querySelectorAll('.form-group').forEach((parentElement) => {
            // ابحث عن العناصر الابنة داخل .form-group
            const inputElement = parentElement.querySelector('input, select, textarea');

            // تحقق من وجود العنصر input داخل .form-group
            if (inputElement) {
                // inputElement.setAttribute('disabled', 'disabled');

                parentElement.addEventListener('click', () => {
                    console.log('Double Clicked!');
                    inputElement.removeAttribute('disabled');
                });
            } else {
                console.error('No input element found inside .form-group');

                // يمكنك إلقاء نظرة على الأبناء في حالة عدم العثور على عنصر input
                console.log(parentElement.children);
            }
        });
    </script>
@endpush

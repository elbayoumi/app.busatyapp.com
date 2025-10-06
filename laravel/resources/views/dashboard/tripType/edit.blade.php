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
                <h4 class="card-title"> انشاء سؤال شائع</h4>

            </div>
            <div class="card-body mt-3">
                <ul class="nav nav-pills" role="tablist">

                    <div class="tab-content">
                        <!-- Account Tab starts -->
                        <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                            <!-- users edit account form start -->
                            <form action="{{ route('dashboard.trip-type.update',$question->id) }}" method="post"
                                enctype="multipart/form-data" class="mt-2">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="question">question </label>
                                            <input  type="text" class="form-control" placeholder="question" value="{{ $question->question }}"
                                                name="question" id="question" />
                                        </div>

                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="answer">answer </label>
                                            <textarea name="answer" class="form-control" id="answer" cols="40" rows="10">{{ $question->answer }}</textarea>

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            <select id="type" name="type" class="form-control">
                                                <option value="" disabled selected>Select a type</option>
                                                <option value="parents">Parents</option>
                                                <option value="schools">Schools</option>
                                                <option value="attendants">Attendants</option>
                                            </select>
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
        document.getElementById('start_date').addEventListener('input', function() {
            var startDate = new Date(this.value);
            startDate.setDate(startDate.getDate() + 1);

            var end_date = document.getElementById('end_date');
            end_date.min = startDate.toISOString().split('T')[0];
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

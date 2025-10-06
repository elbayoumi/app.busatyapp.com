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
                <h4 class="card-title"> انشاء كوبون {{ request()->model }}</h4>

            </div>
            <div class="card-body mt-3">
                <ul class="nav nav-pills" role="tablist">
                    <div class="tab-content">
                        <!-- Account Tab starts -->
                        <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                            <!-- users edit account form start -->
                            <form action="{{ route('dashboard.coupon.store') }}" method="post"
                                enctype="multipart/form-data" class="mt-2">
                                @csrf
                                {{-- @method('PUT') --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="allow_at">Allow At</label>
                                            <input type="date" class="form-control" placeholder="allow_at" value=""
                                                min="{{ date('Y-m-d') }}" name="allow_at" id="allow_at" required />
                                        </div>
                                    </div>
                                    <input type="hidden" name="school_id" value="{{ request()->school_id }}">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="expires_at">Expires At</label>
                                            <input type="date" class="form-control" placeholder="expires_at" required
                                                value="" min="{{ date('Y-m-d') }}" name="expires_at"
                                                id="expires_at" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="code">code</label>
                                            <input type="text" class="form-control" placeholder="code" name="code"
                                                id="code" />
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display: none;">
                                        <div class="form-group">
                                            <label for="model">model</label>
                                            <input type="text" class="form-control" placeholder="model" name="model"
                                                id="model" value="{{ $model }}" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="usage_limit">المده</label>
                                            <input type="number" min="1" step="1" class="form-control"
                                                required placeholder="المدة" value="dashboard" name="usage_limit"
                                                id="usage_limit" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="user_limit">عدد المستخدمين</label>
                                            <input type="number" min="1" step="1" class="form-control"
                                                required placeholder="عدد المستخدمين" value="dashboard" name="user_limit"
                                                id="user_limit" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                    <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">انشاء</button>
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

    <script>
        document.getElementById('allow_at').addEventListener('input', function() {
            var allowDate = new Date(this.value);
            allowDate.setDate(allowDate.getDate() + 1);

            var expiresDate = document.getElementById('expires_at');
            expiresDate.min = allowDate.toISOString().split('T')[0];
        });

        document.getElementById('expires_at').addEventListener('input', function() {
            var expiresDate = new Date(this.value);
            expiresDate.setDate(expiresDate.getDate() - 1);

            var allowDate = document.getElementById('allow_at');
            allowDate.max = expiresDate.toISOString().split('T')[0];
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

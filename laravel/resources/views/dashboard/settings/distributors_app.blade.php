@extends('dashboard.layouts.app')
@push('page_vendor_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/editors/quill/katex.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/editors/quill/monokai-sublime.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/editors/quill/quill.snow.css">
    <script src="{{ asset('ckeditor') }}/ckeditor.js"></script>

@endpush
@push('page_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/plugins/forms/form-quill-editor.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/pages/page-blog.css">

    <style>
        .demo-inline-spacing > * {
            margin-right: 1.5rem;
            margin-top: 0.5rem;
            margin-bottom: 1.5rem;
        }
    </style>
@endpush
@section('content')

<div class="blog-edit-wrapper">
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">اعدادات تطبيق الموزع</h4>
                </div>
                <div class="card-body">
                   
                    <!-- Form -->
                    <form method="POST" action="{{ route('dashboard.settings.distributors-app.update') }}" enctype="multipart/form-data" class="form form-vertical">
                        @csrf
                        @method('PUT')
                        <div class="row">


                            <div class="col-md-12 col-12">
                                <div class="form-group mb-2">
                                    <label for="distributors-latest-version">احدث نسخة</label>
                                    <input type="text" name="distributors_latest_version" class="form-control" value="{{ $settings->distributors_latest_version }}" />
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary mt-2 mr-1">حفظ التغيرات</button>
                                <button type="reset" class="btn btn-outline-secondary mt-2">الغاء</button>
                            </div>
                        </div>
                    </form>
                    <!--/ Form -->
                </div>
            </div>
        </div>

        

       
    </div>
</div>

@endsection

@push('page_scripts_vendors')
    <script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/editors/quill/quill.min.js"></script>

@endpush

@push('page_scripts')
    <script src="{{ asset('assets/dashboard') }}/app-assets/js/scripts/pages/page-general-settings.js"></script>
    <script src="{{ asset('assets/dashboard') }}/app-assets/js/scripts/pages/page-settings.min.js"></script>
@endpush


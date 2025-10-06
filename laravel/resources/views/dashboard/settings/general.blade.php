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
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">الاعدادات العامة</h4>
                </div>
                <div class="card-body">

                    <!-- Form -->
                    <form method="POST" action="{{ route('dashboard.settings.general.update') }}" enctype="multipart/form-data" class="form form-vertical">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <input type="hidden" name="form_type" value="general">

                            <div class="col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="name">{{ strtoupper('name') }}</label>
                                    <input type="text" class="form-control" name="name" value="{{ $settings->name }}" id="name" placeholder="Name" />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="slogan">{{ strtoupper('slogan') }}</label>
                                    <input type="text" class="form-control" name="slogan" value="{{ $settings->slogan }}" id="slogan" placeholder="Slogan" />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="telephone">{{ strtoupper('telephone') }}</label>
                                    <input type="tel" class="form-control" name="telephone" value="{{ $settings->telephone }}" id="telephone" placeholder="Telephone number" />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="mobile">{{ strtoupper('mobile') }}</label>
                                    <input type="tel" class="form-control" name="mobile" value="{{ $settings->mobile }}" id="mobile" placeholder="Mobile number" />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="email">{{ strtoupper('e-mail') }}</label>
                                    <input type="email" class="form-control" name="email" value="{{ $settings->email }}" id="email" placeholder="E-mail" />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="short-description">{{ strtoupper('short description') }}</label>
                                    <textarea class="form-control" name="short_description" id="short-description" rows="4" placeholder="Your short description here...">{{ $settings->short_description }}</textarea>
                                </div>
                            </div>

                            <div class="col-12 mb-2">
                                <div class="border rounded p-2">
                                    <h4 class="mb-1">{{ strtoupper('Light Logo') }}</h4>
                                    <div class="media flex-column flex-md-row">
                                        {{-- @dd(image_or_placeholder($settings->light_logo_full_path)) --}}
                                        <img src="{{ image_or_placeholder($settings->light_logo_full_path) }}" id="light-logo-feature-image" class="rounded mr-2 mb-1 mb-md-0" width="170" height="110" alt="light logo Featured Image" />
                                        <div class="media-body">
                                            <small class="text-muted">Required image resolution 800x400, image size 10mb.</small>
                                            <p class="my-50">
                                                <a href="javascript:void(0);" id="light-logo-image-text">{{ $settings->light_logo_full_path }}</a>
                                            </p>
                                            <div class="d-inline-block">
                                                <div class="form-group mb-0">
                                                    <div class="custom-file">
                                                        <input type="file" name="light_logo" class="custom-file-input" id="lightLogoCustomFile" accept="image/*" />
                                                        <label class="custom-file-label" for="lightLogoCustomFile">اختر ملف</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-2">
                                <div class="border rounded p-2">
                                    <h4 class="mb-1">{{ strtoupper('Dark Logo') }}</h4>
                                    <div class="media flex-column flex-md-row">
                                        <img src="{{ image_or_placeholder($settings->dark_logo_full_path) }}" id="dark-logo-feature-image" class="rounded mr-2 mb-1 mb-md-0" width="170" height="110" alt="dark logo Featured Image" />
                                        <div class="media-body">
                                            <small class="text-muted">Required image resolution 800x400, image size 10mb.</small>
                                            <p class="my-50">
                                                <a href="javascript:void(0);" id="dark-logo-image-text">{{ $settings->dark_logo_full_path }}</a>
                                            </p>
                                            <div class="d-inline-block">
                                                <div class="form-group mb-0">
                                                    <div class="custom-file">
                                                        <input type="file" name="dark_logo" class="custom-file-input" id="darkLogoCustomFile" accept="image/*" />
                                                        <label class="custom-file-label" for="darkLogoCustomFile">اختر ملف</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-2">
                                <div class="border rounded p-2">
                                    <h4 class="mb-1">{{ strtoupper('Favicon') }}</h4>
                                    <div class="media flex-column flex-md-row">
                                        <img src="{{ image_or_placeholder($settings->favicon_full_path) }}" id="favicon-feature-image" class="rounded mr-2 mb-1 mb-md-0" width="170" height="110" alt="light logo Featured Image" />
                                        <div class="media-body">
                                            <small class="text-muted">Required image resolution 800x400, image size 10mb.</small>
                                            <p class="my-50">
                                                <a href="javascript:void(0);" id="favicon-image-text">{{ $settings->favicon_full_path }}</a>
                                            </p>
                                            <div class="d-inline-block">
                                                <div class="form-group mb-0">
                                                    <div class="custom-file">
                                                        <input type="file" name="favicon" class="custom-file-input" id="faviconCustomFile" accept="image/*" />
                                                        <label class="custom-file-label" for="faviconCustomFile">اختر ملف</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-2">
                                <div class="border rounded p-2">
                                    <h4 class="mb-1">{{ strtoupper('Dashboard Logo') }}</h4>
                                    <div class="media flex-column flex-md-row">
                                        <img src="{{ image_or_placeholder($settings->dashboard_logo_full_path) }}" id="dashboard-logo-feature-image" class="rounded mr-2 mb-1 mb-md-0" width="170" height="110" alt="dashboard logo Featured Image" />
                                        <div class="media-body">
                                            <small class="text-muted">Required image resolution 800x400, image size 10mb.</small>
                                            <p class="my-50">
                                                <a href="javascript:void(0);" id="dashboard-logo-image-text">{{ $settings->dashboard_logo_full_path }}</a>
                                            </p>
                                            <div class="d-inline-block">
                                                <div class="form-group mb-0">
                                                    <div class="custom-file">
                                                        <input type="file" name="dashboard_logo" class="custom-file-input" id="dashboardLogoCustomFile" accept="image/*" />
                                                        <label class="custom-file-label" for="dashboardLogoCustomFile">اختر ملف</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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


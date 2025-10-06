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
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">SMTP اعدادات</h4>
                </div>
                <div class="card-body">
                   
                    <!-- Form -->
                    <form method="POST" action="{{ route('dashboard.settings.smtp.update') }}" enctype="multipart/form-data" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator">
                        @csrf
                        @method('PUT')
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="host">MAIL HOST</label>
                                    <input type="text" name="smtp_host" value="{{ $settings->smtp_host }}" id="host" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="port">MAIL PORT</label>
                                    <input type="text" name="smtp_port" value="{{ $settings->smtp_port }}" id="port" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="username">MAIL USERNAME</label>
                                    <input type="text" name="smtp_username" value="{{ $settings->smtp_username }}" id="username" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="password">MAIL PASSWORD</label>
                                    <input type="text" name="smtp_password" value="{{ $settings->smtp_password }}" id="password" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="encryption">MAIL ENCRYPTION</label>
                                    <input type="text" name="smtp_encryption" value="{{ $settings->smtp_encryption }}" id="encryption" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="from-address">MAIL FROM ADDRESS</label>
                                    <input type="text" name="smtp_from_address" value="{{ $settings->smtp_from_address }}" id="from-address" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="from-name">MAIL FROM NAME</label>
                                    <input type="text" name="smtp_from_name" value="{{ $settings->smtp_from_name }}" id="from-name" class="form-control">
                                </div>
                            </div>

                            
                            <div class="col-12 mt-50">
                                <button type="submit" class="btn btn-primary mr-1">حفظ التغيرات</button>
                                <button type="reset" class="btn btn-outline-secondary">الغاء</button>
                            </div>
                        </div>
                    </form>
                    <!--/ Form -->
                </div>
            </div>
        </div>
        

        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">ختبار اعدادات الـ SMTP </h4>
                </div>
                <div class="card-body">
                   
                    <!-- Form -->
                    <form method="POST" action="{{ route('dashboard.settings.smtp.test') }}" enctype="multipart/form-data" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator">
                        @csrf
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="test-email">ارسال الى</label>
                                    <input type="text" name="test_email" value="{{ $settings->email }}" required id="test-email" class="form-control">
                                </div>
                            </div>
                            

                            
                            <div class="col-6">
                                <button type="submit" class="btn btn-warning mr-1">ارسال ايميل اختبار</button>
                            </div>
                        </div>
                    </form>
                    <!--/ Form -->
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Instruction</h4>
                </div>
                <div class="card-body">
                   
                    <div class="card-body">
                        <p class="text-danger">Please be carefull when you are configuring SMTP. For incorrect configuration you will get error at the time of order place, new registration, sending newsletter.</p>
                        <h6 class="text-muted">For Non-SSL</h6>
                        <ul class="list-group">
                            <li class="list-group-item text-dark">Select sendmail for Mail Driver if you face any issue after configuring smtp as Mail Driver</li>
                            <li class="list-group-item text-dark">Set Mail Host according to your server Mail Client Manual Settings</li>
                            <li class="list-group-item text-dark">Set Mail port as 587</li>
                            <li class="list-group-item text-dark">Set Mail Encryption as ssl if you face issue with tls</li>
                        </ul>
                        <br>
                        <h6 class="text-muted">For SSL</h6>
                        <ul class="list-group mar-no">
                            <li class="list-group-item text-dark">Select sendmail for Mail Driver if you face any issue after configuring smtp as Mail Driver</li>
                            <li class="list-group-item text-dark">Set Mail Host according to your server Mail Client Manual Settings</li>
                            <li class="list-group-item text-dark">Set Mail port as 465</li>
                            <li class="list-group-item text-dark">Set Mail Encryption as ssl</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
</div>

@endsection

@push('page_scripts_vendors')
    <script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/editors/quill/katex.min.js"></script>
    <script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/editors/quill/highlight.min.js"></script>
    <script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/editors/quill/quill.min.js"></script>
@endpush

@push('page_scripts')
    <script src="{{ asset('assets/dashboard') }}/app-assets/js/scripts/pages/page-blog-edit.js"></script>
    <script>
        $(".checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
    <script>
        CKEDITOR.replace('editor1', {
            language: 'en'
        });

    </script>
@endpush

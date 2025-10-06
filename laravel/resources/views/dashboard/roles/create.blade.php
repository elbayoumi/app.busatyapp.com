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
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">Roles / Create New</h4>
                </div>
                <div class="card-body">
                   
                    <!-- Form -->
                    <form action="{{ route('dashboard.roles.store') }}" method="post" enctype="multipart/form-data" class="mt-2">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="form-group mb-2">
                                    <label for="blog-edit-name">Name</label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                                </div>
                            </div>

                            <div class="col-12 mb-2">
                                <h5>Permissions</h5>
                            </div>

                            <div class="col-12 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input checkAll" id="checkAll" />
                                    <label class="custom-control-label" for="checkAll">check all</label>
                                </div>
                            </div>

                            <div class="col-12 mb-2">
                                @foreach ($permissions_groups as $key => $permissions_group)
                                    <p class="card-text mb-0">
                                        <span>{{ $permissions_group[0]->group }}</span>
                                    </p>
                                    <div class="demo-inline-spacing">
                                        @foreach ($permissions_group as $index => $permission) 
                                            <div class="custom-control custom-checkbox">
                                                {{-- <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="custom-control-input @if ($permission->name == 'super') checkAll @endif" id="custom-check-{{$key}}-{{$index}}" /> --}}
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="custom-control-input" id="custom-check-{{$key}}-{{$index}}" />

                                                <label class="custom-control-label" for="custom-check-{{$key}}-{{$index}}">{{$permission->display_name}}</label>
                                            </div>
                                        
                                        @endforeach
                                    </div>
                                @endforeach
                                
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

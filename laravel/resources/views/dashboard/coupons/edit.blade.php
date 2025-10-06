@extends('dashboard.layouts.app')

@push('page_vendor_css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/app-assets/vendors/css/vendors.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@endpush

@push('page_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/app-assets/css/plugins/forms/pickers/form-flat-pickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/app-assets/css/plugins/forms/form-validation.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/app-assets/css/pages/app-user.min.css') }}">
@endpush

@section('content')
<section class="app-user-edit">
    <div class="card">
        <div class="card-header border-bottom">
            <h4 class="card-title">تعديل كوبون {{ $coupon->code }}</h4>
        </div>
        <div class="card-body mt-3">
            <form action="{{ route('dashboard.coupon.update', $coupon->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Allow At --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="allow_at">Allow At</label>
                            <input type="date" class="form-control" name="allow_at" id="allow_at"
                                value="{{ old('allow_at', optional($coupon->allow_at)->format('Y-m-d')) }}"
                                min="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    {{-- Expires At --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="expires_at">Expires At</label>
                            <input type="date" class="form-control" name="expires_at" id="expires_at"
                                value="{{ old('expires_at', optional($coupon->expires_at)->format('Y-m-d')) }}"
                                min="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    {{-- Code --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code">Code</label>
                            <input type="text" class="form-control" name="code" id="code"
                                value="{{ old('code', $coupon->code) }}" required>
                        </div>
                    </div>

                    {{-- Hidden model --}}
                    <div class="col-md-6" style="display: none;">
                        <div class="form-group">
                            <label for="model">Model</label>
                            <input type="text" class="form-control" name="model" id="model"
                                value="{{ old('model', $coupon->model) }}">
                        </div>
                    </div>

                    {{-- Usage Limit --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="usage_limit">Usage Limit</label>
                            <input type="number" class="form-control" name="usage_limit" id="usage_limit" min="1"
                                value="{{ old('usage_limit', $coupon->usage_limit) }}" required>
                        </div>
                    </div>

                    {{-- User Limit --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_limit">User Limit</label>
                            <input type="number" class="form-control" name="user_limit" id="user_limit" min="1"
                                value="{{ old('user_limit', $coupon->user_limit) }}" required>
                        </div>
                    </div>

                    {{-- School ID (if present) --}}
                    @if(request()->has('school_id'))
                        <input type="hidden" name="school_id" value="{{ request()->school_id }}">
                    @endif
                </div>

                {{-- Submit Button --}}
                <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                    <button type="submit" class="btn btn-primary mb-1 mb-sm-0">تحديث</button>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary ml-1">رجوع</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('page_scripts_vendors')
<script src="{{ asset('assets/dashboard/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/dashboard/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/dashboard/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
@endpush

@push('page_scripts')
<script>
    // Auto adjust min/max between allow_at and expires_at
    document.getElementById('allow_at').addEventListener('input', function() {
        var allowDate = new Date(this.value);
        allowDate.setDate(allowDate.getDate() + 1);
        document.getElementById('expires_at').min = allowDate.toISOString().split('T')[0];
    });

    document.getElementById('expires_at').addEventListener('input', function() {
        var expiresDate = new Date(this.value);
        expiresDate.setDate(expiresDate.getDate() - 1);
        document.getElementById('allow_at').max = expiresDate.toISOString().split('T')[0];
    });

    // Optional: Enable editing inputs on click if you're using a readonly template logic
    document.querySelectorAll('.form-group').forEach((parentElement) => {
        const inputElement = parentElement.querySelector('input, select, textarea');
        if (inputElement) {
            parentElement.addEventListener('click', () => {
                inputElement.removeAttribute('disabled');
            });
        }
    });
</script>
@endpush

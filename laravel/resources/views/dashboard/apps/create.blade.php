@extends('dashboard.layouts.app')

@push('page_vendor_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Select2 CSS (needed because we use .select2 on the name select) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endpush

@push('page_styles')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css/plugins/forms/pickers/form-flat-pickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css/plugins/forms/form-validation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/app-assets/css/pages/app-user.min.css') }}">
@endpush

@section('content')
    <style>
        body {
            background: #f8fafc;
        }
        .app-user-edit .card {
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            border: none;
        }
        .app-user-edit .card-header {
            background: linear-gradient(90deg,#2196f3 0,#21cbf3 100%);
            color: #fff;
            border-radius: 1rem 1rem 0 0;
        }
        .app-user-edit .card-body {
            background: #fff;
            border-radius: 0 0 1rem 1rem;
        }
        .form-label i {
            margin-left: 6px;
            color: #2196f3;
        }
        .form-control, .select2-container--default .select2-selection--single {
            border-radius: 0.7rem !important;
            min-height: 45px;
            font-size: 1.1rem;
        }
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1e3f3;
            background: #f4f8fb;
        }
        .btn-primary {
            border-radius: 0.7rem;
            font-size: 1.2rem;
            box-shadow: 0 2px 8px rgba(33,203,243,0.15);
        }
        .card-body .card {
            background: #f4f8fb;
            border-radius: 0.7rem;
            border: none;
        }
        /* Large and nice switch button */
        .form-switch.form-switch-lg .form-check-input.big-switch {
            width: 3.5em;
            height: 2em;
            background-color: #d1e3f3;
            border-radius: 2em;
            transition: background 0.3s;
        }
        .form-switch.form-switch-lg .form-check-input.big-switch:checked {
            background-color: #2196f3;
        }
        .form-switch.form-switch-lg .form-check-input.big-switch:focus {
            box-shadow: 0 0 0 0.25rem rgba(33,203,243,0.15);
        }
        .switch-label {
            min-width: 90px;
            text-align: center;
            transition: color 0.3s;
        }
    </style>

    <!-- Create New App -->
    <section class="app-user-edit">
        <div class="card shadow-sm">
            <div class="card-header border-bottom">
                <h4 class="card-title mb-0"><i data-feather="plus-circle"></i> إنشاء تطبيق جديد</h4>
            </div>

            <div class="card-body py-3">
                <form action="{{ route('dashboard.app.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        <!-- Application Name -->
                        <div class="col-md-6">
                            <div class="card shadow-sm border-info mb-2">
                                <div class="card-body">
                                    <label for="name" class="form-label fw-bold">
                                        <i data-feather="tag"></i> اسم التطبيق
                                    </label>
                                    <!-- Using Select2 for better UX -->
                                    <select name="name" id="name" class="form-control select2">
                                        @foreach ($newApps as $name)
                                            <option value="{{ $name }}" {{ old('name') === $name ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- App Status -->
                        <div class="col-md-6">
                            <div class="card shadow-sm border-success mb-2">
                                <div class="card-body">
                                    <label for="status" class="form-label fw-bold">
                                        <i data-feather="activity"></i> حالة التطبيق
                                    </label>
                                    <div class="d-flex align-items-center gap-2 mt-2">
                                        <!-- Initial label reflects default checked state -->
                                        <span id="status-label" class="switch-label fs-5 fw-bold text-success">مفعل</span>
                                        <div class="form-check form-switch form-switch-lg">
                                            <!-- Hidden input guarantees "0" when unchecked -->
                                            <input type="hidden" name="status" value="0">
                                            <input class="form-check-input big-switch"
                                                   type="checkbox"
                                                   name="status"
                                                   id="status"
                                                   value="1"
                                                   {{ old('status', 1) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Google Auth -->
                        <div class="col-md-6">
                            <div class="card shadow-sm border-warning mb-2">
                                <div class="card-body">
                                    <label for="google_auth" class="form-label fw-bold">
                                        <i data-feather="check"></i> التسجيل بجوجل
                                    </label>
                                    <div class="d-flex align-items-center gap-2 mt-2">
                                        <!-- Initial label is "off" -->
                                        <span id="google_auth-label" class="switch-label fs-5 fw-bold text-danger">غير مفعل</span>
                                        <div class="form-check form-switch form-switch-lg">
                                            <!-- Hidden input guarantees "0" when unchecked -->
                                            <input type="hidden" name="google_auth" value="0">
                                            <input class="form-check-input big-switch"
                                                   type="checkbox"
                                                   name="google_auth"
                                                   id="google_auth"
                                                   value="1"
                                                   {{ old('google_auth', 0) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    @error('google_auth') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- App Version -->
                        <div class="col-md-6">
                            <div class="card shadow-sm border-secondary mb-2">
                                <div class="card-body">
                                    <label for="version" class="form-label fw-bold">
                                        <i data-feather="smartphone"></i> إصدار التطبيق
                                    </label>
                                    <!-- maxlength matches validation rule: max:10 -->
                                    <input type="text"
                                           name="version"
                                           id="version"
                                           class="form-control"
                                           placeholder="مثال: 1.0.0"
                                           maxlength="10"
                                           value="{{ old('version') }}">
                                    @error('version') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Updating Mode -->
                        <div class="col-md-6">
                            <div class="card shadow-sm border-primary mb-2">
                                <div class="card-body">
                                    <label for="is_updating" class="form-label fw-bold">
                                        <i data-feather="refresh-cw"></i> وضع التحديث
                                    </label>
                                    <div class="d-flex align-items-center gap-2 mt-2">
                                        <!-- Initial label is "not updating" -->
                                        <span id="is_updating-label" class="switch-label fs-5 fw-bold text-secondary">ليس تحت التحديث</span>
                                        <div class="form-check form-switch form-switch-lg">
                                            <!-- Hidden input guarantees "0" when unchecked -->
                                            <input type="hidden" name="is_updating" value="0">
                                            <input class="form-check-input big-switch"
                                                   type="checkbox"
                                                   name="is_updating"
                                                   id="is_updating"
                                                   value="1"
                                                   {{ old('is_updating', 0) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    @error('is_updating') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex flex-sm-row flex-column mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i data-feather="check-circle"></i> إنشاء التطبيق
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        feather.replace();

        // Use 'input' event so label updates immediately while user toggles the switch
        document.getElementById('status').addEventListener('input', function () {
            const lbl = document.getElementById('status-label');
            const on = this.checked;
            lbl.textContent = on ? 'مفعل' : 'غير مفعل';
            lbl.classList.toggle('text-success', on);
            lbl.classList.toggle('text-danger', !on);
        });

        document.getElementById('google_auth').addEventListener('input', function () {
            const lbl = document.getElementById('google_auth-label');
            const on = this.checked;
            lbl.textContent = on ? 'مفعل' : 'غير مفعل';
            lbl.classList.toggle('text-success', on);
            lbl.classList.toggle('text-danger', !on);
        });

        document.getElementById('is_updating').addEventListener('input', function () {
            const lbl = document.getElementById('is_updating-label');
            const on = this.checked;
            lbl.textContent = on ? 'تحت التحديث' : 'ليس تحت التحديث';
            lbl.classList.toggle('text-primary', on);
            lbl.classList.toggle('text-secondary', !on);
        });
    </script>
@endsection

@push('page_scripts_vendors')
    <!-- Select2 JS (needed because we use .select2 on the name select) -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@push('page_scripts')
    <script>
        // Initialize Select2 on the name select for better UX
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'اختر من القائمة',
                dir: 'rtl',
                width: '100%' // ensures proper width inside Bootstrap cards
            });
        });
    </script>
@endpush

@extends('dashboard.layouts.app')

@push('page_vendor_css')
    <!-- Vendor CSS for Select2 and Flatpickr -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@endpush

@push('page_styles')
    <!-- Page-specific styles for form pickers and validation -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/app-assets/css/plugins/forms/pickers/form-flat-pickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/app-assets/css/plugins/forms/form-validation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/app-assets/css/pages/app-user.min.css') }}">
@endpush

@section('content')
    <section class="app-user-edit">
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title">إنشاء الاشتراك</h4>
            </div>

            <div class="card-body mt-3">
                <div class="tab-content">
                    <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                        <!-- Subscription creation form -->
                        <form action="{{ route('dashboard.subscription.store') }}" method="POST" enctype="multipart/form-data" class="mt-2">
                            @csrf
                            <div class="row">

                                <!-- Select parent subscriber (multi-select) -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="parent_id">المشترك من أولياء الأمور</label>
                                        <select id="select_page" class="form-control basic-select2" name="parent_id[]" multiple required>
                                            @foreach ($parent as $value)
                                                <option value="{{ $value->id }}">{{ $value->id . ' - ' . $value->email }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Start date (default: tomorrow) -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" class="form-control" name="start_date" id="start_date" required value="{{ \Carbon\Carbon::tomorrow()->toDateString() }}" min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}" />
                                    </div>
                                </div>

                                <!-- End date (default: tomorrow) -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">End Date</label>
                                        <input type="date" class="form-control" name="end_date" id="end_date" required value="{{ \Carbon\Carbon::tomorrow()->toDateString() }}" min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}" />
                                    </div>
                                </div>

                                <!-- Subscription status -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">الحالة</label>
                                        <select name="status" class="form-control" required>
                                            @foreach (\App\Enums\SubscriptionStatus::cases() as $status)
                                                <option value="{{ $status->value }}" {{ old('status') == $status->value ? 'selected' : '' }}>
                                                    {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Currency selection -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="currency">نوع العملة</label>
                                        <select class="form-control" name="currency" id="currency" required>
                                            <option value="EGP" selected>EGP</option>
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                            <option value="GBP">GBP</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Amount input -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input type="number" class="form-control" name="amount" id="amount" required value="0" min="0" step="0.01" placeholder="Amount" />
                                    </div>
                                </div>

                            </div>

                            <!-- Submit button -->
                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">إنشاء</button>
                            </div>
                        </form>
                    </div> <!-- End tab -->
                </div>
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
    <script src="{{ asset('assets/dashboard/app-assets/js/scripts/pages/app-user-edit.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/app-assets/js/scripts/components/components-navs.min.js') }}"></script>

    <!-- Auto-update end date min based on start date -->
    <script>
        document.getElementById('start_date').addEventListener('input', function() {
            var startDate = new Date(this.value);
            startDate.setDate(startDate.getDate() + 1);
            var end_date = document.getElementById('end_date');
            end_date.min = startDate.toISOString().split('T')[0];
        });
    </script>

    <!-- Initialize Select2 -->
    <script>
        $(document).ready(function () {
            $('.basic-select2').select2({
                placeholder: 'اختر من القائمة',
                width: '100%'
            });
        });
    </script>
@endpush

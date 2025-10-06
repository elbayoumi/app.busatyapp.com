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
                <h4 class="card-title"> تعديل بيانات الاشتراك</h4>

            </div>
            <div class="card-body mt-3">
                @if($subscription && $subscription->subscriptable)
    <div class="mt-1">
        <h6>
            <span class="text-primary">الاسم:</span>
            {{ $subscription->subscriptable->name ?? 'غير معروف' }}
        </h6>
        <h6>
            <span class="text-primary">البريد الإلكتروني:</span>
            {{ $subscription->subscriptable->email ?? 'لا يوجد' }}
        </h6>
    </div>
@endif

                <ul class="nav nav-pills" role="tablist">

                    <div class="tab-content">
                        <!-- Account Tab starts -->
                        <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                            <!-- users edit account form start -->
                            <form action="{{route('dashboard.subscription.update', $subscription->id) }}" method="post" enctype="multipart/form-data" class="mt-2">
                                @csrf
                                @method('PUT')
                                <div class="row">



                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date">Start Date</label>
                                            <input  type="date" class="form-control" placeholder="start_date"
                                                value="{{ $subscription ? date('Y-m-d', strtotime($subscription->start_date)) : '' }}"
                                                name="start_date" id="start_date" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_date">End Date</label>
                                            <input  type="date" class="form-control" placeholder="end_date"
                                                value="{{ $subscription ? date('Y-m-d', strtotime($subscription->end_date)) : '' }}"
                                                name="end_date" id="end_date" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">الحالة</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value="" > اختر الحالة</option>
                                                @foreach (statusAr() as $index => $status)
                                                    <option value="{{ $index }}"
                                                        @if ($subscription?->status == $index ) selected @endif>
                                                        {{ $status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_method">payment method</label>
                                            <input  type="text" class="form-control" placeholder="payment_method"
                                                value="{{ $subscription?->payment_method }}" name="payment_method"
                                                id="payment_method" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="plan_name">plan name payment method</label>
                                            <input  type="text" class="form-control" placeholder="plan_name"
                                                value="{{ $subscription?->plan_name }}" name="plan_name"
                                                id="plan_name" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="amount">amount {{  $subscription?->currency  }}</label>
                                            <input  type="number" min="0" step="0.01" class="form-control" placeholder="amount" value="{{$subscription?->amount  }}" name="amount" id="amount"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                    <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">تعديل</button>
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

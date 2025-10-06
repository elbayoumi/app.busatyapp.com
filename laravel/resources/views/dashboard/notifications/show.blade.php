@extends('dashboard.layouts.app')

@push('page_vendor_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/vendors.min.css">
@endpush

@section('content')

<!-- Notification Detail Start -->
<section class="app-notification-detail">
    <div class="card">
        <div class="card-body">
            <div class="card-header border-bottom p-2">
                <h4 class="card-title">تفاصيل الإشعار</h4>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="notification" aria-labelledby="notification-tab" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>العنوان</th>
                                    <td>{{ $notification->title }}</td>
                                </tr>
                                <tr>
                                    <th>المحتوى</th>
                                    <td>{{ $notification->content }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الإرسال</th>
                                    <td>{{ $notification->created_at }}</td>
                                </tr>
                                <tr>
                                    <th>حالة الإشعار</th>
                                    <td class="text-{{ $notification->status == 'مقروء' ? 'success' : 'warning' }}">{{ $notification->status }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Notification Detail End -->

@endsection

@push('page_scripts_vendors')
<script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
@endpush

@push('page_scripts')
    <script src="{{ asset('assets/dashboard') }}/app-assets/js/scripts/pages/app-notification-detail.min.js"></script>
@endpush

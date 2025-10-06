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
    <section id="language-selection">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom p-2">
                        <a class="btn btn-success m-2" href="{{ route('dashboard.notification.send') }}">اختبار</a>
                        <h4 class="card-title">اختيار اللغة</h4>
                        <select onchange="changeLang(this)" id="language" class="form-control">
                            <option value="ar">العربية</option>
                            <option value="en">English</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="notifications-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom p-2">
                        <h4 class="card-title">قائمة الإشعارات</h4>
                    </div>

                    <div class="table-responsive">
                        <table class="dt-notifications table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>عنوان الإشعار</th>
                                    <th>المحتوى</th>
                                    <th>المحتوى للابليكيشن</th>
                                    <th>نوع المستلم</th>
                                    <th>نوع المرسل</th>
                                    <th>مجموعة</th>
                                    <th>الإضافات</th>
                                    <th>الإعدادات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notifications as $index => $notification)
                                    <tr>
                                        <td>{{ $notification->id }}</td>
                                        <td id="notification-title-{{ $notification->id }}">
                                            {{ $notification->title['ar'] ?? 'لا يوجد عنوان' }}</td>
                                        <td id="notification-body-{{ $notification->id }}">
                                            {{ $notification->body['ar'] ?? 'لا يوجد محتوى' }}</td>
                                        <td id="notification-default-body-{{ $notification->id }}">
                                            {{ $notification->default_body['ar'] ?? 'لا يوجد محتوى' }}</td>
                                        <td>{{ removeAppModels($notification->to_model_type) }}</td>
                                        <td>{{ removeAppModels($notification->for_model_type) }}</td>
                                        @if (is_array($notification->model_additional) && count($notification->model_additional) > 0)
                                        <td>
                                            <span>
                                                {{ implode(', ', array_filter(array_map(function($item) {
                                                    if (is_array($item)) {
                                                        return implode(', ', $item);
                                                    }
                                                    return $item;
                                                }, $notification->model_additional))) }}
                                            </span>
                                        </td>
                                    @else
                                        <td>❌</td>
                                    @endif



                                        <td>{{ $notification->group ? '✅' : '❌' }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-success btn-sm dropdown-toggle" href="#"
                                                    role="button" id="dropdownMenuLink" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    العمليات
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right"
                                                    aria-labelledby="dropdownMenuLink">
                                                    @if (auth()->user()->canany(['super', 'notifications-edit']))
                                                        <a href="{{ route('dashboard.notifications.edit', $notification->id) }}"
                                                            class="dropdown-item" style="padding: 6px;" title="تعديل">
                                                            <i data-feather='edit'></i> تعديل الإشعار
                                                        </a>
                                                    @endif
                                                    @if (auth()->user()->canany(['super', 'notifications-destroy']))
                                                        <a onclick="event.preventDefault();"
                                                            data-delete="delete-form-{{ $index }}"
                                                            href="{{ route('dashboard.notifications.destroy', $notification->id) }}"
                                                            class="m-portlet__nav-link btn m-btn m-btn--hover-blog m-btn--icon m-btn--icon-only m-btn--pill but_delete_action"
                                                            style="padding: 6px;" title="Delete">
                                                            <i data-feather='trash'></i> حذف الإشعار
                                                        </a>
                                                        <form id="delete-form-{{ $index }}"
                                                            action="{{ route('dashboard.notifications.destroy', $notification->id) }}"
                                                            method="POST" style="display: none;">
                                                            @method('DELETE')
                                                            @csrf
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{ $notifications->links('vendor.pagination.bootstrap-4') }}
@endsection

@push('page_scripts_vendors')
    <script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
@endpush

@push('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var languageSelect = document.getElementById('language');
            var selectedLanguage = localStorage.getItem('selectedLanguage') || 'ar'; // الافتراضي هو العربية
            languageSelect.value = selectedLanguage;

            languageSelect.addEventListener('change', function() {
                changeLang(this.value);
            });

            // قم بتشغيل التحديث على الصفحة عند التحميل
            updateNotifications(selectedLanguage);
        });

        function changeLang(lang) {
            localStorage.setItem('selectedLanguage', lang.value);
            updateNotifications(lang.value);
        }

        function updateNotifications(language) {

            var notifications = @json($notifications->toArray()).data;
            notifications.forEach(
                (notification) => {
                    var titleElement = document.getElementById('notification-title-' + notification.id);
                    var bodyElement = document.getElementById('notification-body-' + notification.id);
                    var defaultBodyElement = document.getElementById('notification-default-body-' + notification.id);
                    if (titleElement && bodyElement && defaultBodyElement) {
                        titleElement.innerHTML = notification.title[language] ?? 'لا يوجد عنوان';
                        bodyElement.innerHTML = notification.body[language] ?? 'لا يوجد محتوى';
                        defaultBodyElement.innerHTML = notification.default_body[language] ?? 'لا يوجد محتوى';
                    }
                }
            );

        }
    </script>
@endpush

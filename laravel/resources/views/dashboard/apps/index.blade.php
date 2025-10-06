@extends('dashboard.layouts.app')

@push('page_vendor_css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">
@endpush

@section('content')
    <section id="multilingual-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom p-2">
                        <h4 class="card-title">قائمة التطبيقات</h4>
                    </div>
                    <div class="card-header border-bottom p-1">
                        <form action="{{ route('dashboard.app.index') }}" method="GET" style="min-width: 100%">
                            <div class="row">
                                <div class="col-md-3 my-50">
                                    <input type="text" class="form-control" placeholder="اسم التطبيق"
                                        value="{{ request()->name }}" name="name" id="name"/>
                                </div>
                                <div class="col-md-3 my-50">
                                    <button type="submit" class="btn btn-primary btn-block">بحث</button>
                                </div>
                                <div class="col-md-3 my-50">
                                    <a href="{{ route('dashboard.app.create') }}" class="btn btn-success btn-block">إضافة تطبيق</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive min-height-17rem">
                        <table class="dt-multilingual table table-striped table-hover align-middle">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th><i data-feather="smartphone"></i> اصدار التطبيق</th>
                                    <th><i data-feather="tag"></i> اسم التطبيق</th>
                                    <th><i data-feather="activity"></i> الحالة</th>
                                    <th><i data-feather="check"></i> تسجيل جوجل</th>
                                    <th><i data-feather="info"></i> بيانات إضافية</th>
                                    <th><i data-feather="calendar"></i> تاريخ الإنشاء</th>
                                    <th><i data-feather="settings"></i> العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($apps as $index => $app)
                                    <tr>
                                        <td><strong>{{ $index + 1 }}</strong></td>
                                        <td>
                                            <span class="badge badge-info">
                                                <i data-feather="code"></i> {{ $app?->version }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-primary">{{ $app?->name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $app->status ? 'badge-success' : 'badge-danger' }}">
                                                <i data-feather="{{ $app->status ? 'check-circle' : 'x-circle' }}"></i>
                                                {{ $app->status ? 'مفعل' : 'غير مفعل' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $app->google_registered ? 'badge-success' : 'badge-warning' }}">
                                                <i data-feather="{{ $app->google_registered ? 'check' : 'alert-circle' }}"></i>
                                                {{ $app->google_registered ? 'مسجل' : 'غير مسجل' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-info">
                                                <i data-feather="users"></i>
                                                {{ $app->users_count ?? 'غير محدد' }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                // Build human diff in Arabic + exact timestamp
                                                $createdAt = $app->created_at;
                                                $exact = $createdAt ? $createdAt->format('Y-m-d H:i') : null;
                                                $human = $createdAt ? $createdAt->locale('ar')->diffForHumans() : 'غير متاح';
                                            @endphp

                                            @if($createdAt)
                                                <!-- Show "time ago" + a small hint with exact date; tooltip also shows exact -->
                                                <span class="text-body"
                                                      data-toggle="tooltip"
                                                      data-bs-toggle="tooltip"  {{-- supports BS4/BS5 --}}
                                                      title="{{ $exact }}">
                                                    <i data-feather="clock"></i> {{ $human }}
                                                </span>
                                                <small class="text-muted d-block">
                                                    <i data-feather="calendar"></i> {{ $exact }}
                                                </small>
                                            @else
                                                <span class="text-muted">غير متاح</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button"
                                                    id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    العمليات
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                    <a href="{{ route('dashboard.app.edit', ['app' => $app->id]) }}" class="dropdown-item">
                                                        <i data-feather='edit'></i> تعديل
                                                    </a>

                                                    @if (auth()->user()->canany(['super', 'app-destroy']))
                                                        <a onclick="event.preventDefault();"
                                                            data-delete="delete-form-{{ $index }}"
                                                            href="{{ route('dashboard.app.destroy', ['app' => $app->id]) }}"
                                                            class="dropdown-item but_delete_action"
                                                            style="padding: 6px;" title="Delete">
                                                            <i data-feather='trash'></i> حذف
                                                        </a>
                                                        <form id="delete-form-{{ $index }}"
                                                            action="{{ route('dashboard.app.destroy', ['app' => $app->id]) }}"
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
                    </div> <!-- /table-responsive -->
                </div>
            </div>
        </div>
    </section>
@endsection

@push('page_scripts_vendors')
<script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
@endpush

@push('page_scripts')
<script>
    // Init Select2 if you use .basic-select2 somewhere on the page
    $(document).ready(function() {
        $('.basic-select2').select2();
    });

    // Feather icons render
    if (window.feather && typeof window.feather.replace === 'function') {
        feather.replace();
    }

    // Initialize Bootstrap tooltip (support BS4 and BS5 selectors)
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();     // BS4
        $('[data-bs-toggle="tooltip"]').tooltip();  // BS5
    });
</script>
@endpush

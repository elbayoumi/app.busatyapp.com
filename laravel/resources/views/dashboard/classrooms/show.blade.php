@extends('dashboard.layouts.app')
@push('page_vendor_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
@endpush
@push('page_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/plugins/forms/pickers/form-flat-pickr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/pages/app-user.min.css">
@endpush
@section('content')

<!-- users edit start -->
<section class="app-user-edit">
    <div class="card">
        <div class="card-header border-bottom">
            <h4 class="card-title">الصفوف الدرسية</h4>

        </div>
        <div class="table-responsive">
            <table class="dt-multilingual table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>اسم الصف</th>
                        <th>المدرسة</th>
                        <th>المرحلة الدرسية</th>
                        <th>عدد الطلاب</th>

                        <th>تاريخ الاضافة</th>
                        <th>الاعدادات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($school->classrooms as $index => $class)
                    <tr>
                        <td>{{ $class->id }}</td>
                        <td>{{ $class->name }}</td>
                        <td>{{ $class->school->name }}</td>

                        <td>{{ $class->grade->name }}</td>
                        <td style="min-width:50px">

                            <a class="dropdown-item" href="{{route('dashboard.students.classrooms-students',$class->id)}}"
                                style="padding: 6px;" title="show-grades">
                           <i class="fa fa-user"></i>  ( {{ $class->students->count() }} ) </a>
                        </td>
                        <td>{{ $class->created_at }}</td>
                        <td>
                        <div class="dropdown ">
                            <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                العمليات
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">


                                @if (auth()->user()->canany(['super', 'class-edit']))
                                    <a href="{{ route('dashboard.classrooms.edit', $class->id) }}"
                                        class="dropdown-item"
                                        style="padding: 6px;" title="Edit">
                                        <i data-feather='edit'></i>
                                        تعديل بيانات الصف

                                    </a>
                                @endif

                                @if (auth()->user()->canany(['super', 'classrooms-destroy']))
                                <a onclick="event.preventDefault();"
                                    data-delete="delete-form-{{ $index }}"
                                    href="{{ route('dashboard.classrooms.destroy', $class->id) }}"
                                    class="dropdown-item but_delete_action"
                                    style="padding: 6px;" title="Delete">
                                    <i data-feather='trash'></i>
                                    حذف بيانات الصف

                                </a>
                                <form id="delete-form-{{ $index }}"
                                    action="{{ route('dashboard.classrooms.destroy', $class->id) }}"
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
@endpush

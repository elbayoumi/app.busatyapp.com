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
        <div class="card-body">
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center active" id="account-tab"
                        data-toggle="tab" href="#account" aria-controls="account" role="tab" aria-selected="true">
                        <i class="fa fa-bus"></i><span class="d-none d-sm-block"> عرض بيانات الرسالة </span>
                    </a>
                </li>
                @if ($message->parents->count() > 0)
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" id="parents-tab" data-toggle="tab" href="#parents" aria-controls="parents" role="tab" aria-selected="false">
                        <i  class="fa fa-school"></i><span class="d-none d-sm-block">بيانات اولياء الامر الوصول اليهم</span>
                    </a>
                </li>
                @endif

                @if ($students->count() > 0)
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" id="students-tab" data-toggle="tab" href="#students" aria-controls="students" role="tab" aria-selected="false">
                        <i  class="fa fa-school"></i><span class="d-none d-sm-block"> طلاب تم الوصول اليهم</span>
                    </a>
                </li>
                @endif

                @if ($students_no->count() > 0)
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" id="students_no-tab" data-toggle="tab" href="#students_no" aria-controls="students_no" role="tab" aria-selected="false">
                        <i  class="fa fa-school"></i><span class="d-none d-sm-block"> طلاب  لم يتم الوصول اليهم</span>
                    </a>
                </li>
                @endif
            </ul>
            <div class="tab-content">
                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                    <!-- users edit account form start -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>عنوان الرسالة</th>
                                    <td>{{ $message->name }}</td>
                                </tr>
                                <tr>
                                    <th>اسم المدرسة</th>
                                    <td>{{ $message->schools->name }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الحدث</th>
                                    <td>{{ $message->event_date }}</td>
                                </tr>

                                <tr>
                                    <th>الرسالة</th>
                                    <td>{!! $message->message !!}</td>
                                </tr>

                            </tbody>

                        </table>
                    </div>

                    <!-- users edit account form ends -->
                </div>
                <!-- Account Tab ends -->

                @if ($message->parents->count() > 0)

                <div class="tab-pane" id="parents" aria-labelledby="parents-tab" role="tabpanel">
                    <div class="table-responsive">
                        <table class="dt-multilingual table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>صورة</th>
                                    <th> الاسم </th>
                                    <th> عدد الابناء </th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($message->parents as $index => $parent)
                                    <tr>
                                        <td>{{ $parent->id }}</td>
                                        <td><img width="60"
                                            src="{{ $parent->logo_path }}"
                                           class="m--img-rounded m--marginless" alt="cover">
                                        </td>
                                        <td>{{ $parent->name }}</td>

                                            <td style="min-width:50px">
                                                <a class="dropdown-item" href="{{route('dashboard.students.index', ['parent_id', $parent->id])}}"
                                                      style="padding: 6px;" title="show-parent">
                                                 <i class="fa fa-user"></i>  ( {{ $parent->students->count()  }} ) </a>
                                              </td>



                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
                @endif

                @if ($students->count() > 0)

                <div class="tab-pane" id="students" aria-labelledby="students-tab" role="tabpanel">
                    <div class="table-responsive">
                        <table class="dt-multilingual table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>صورة الطالب</th>
                                    <th>اسم الطالب</th>
                                    <th>اسم المدرسة</th>
                                    <th>الباص</th>
                                    <th>عدد اولياء المور</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $index => $Studen)
                                    <tr>
                                        <td>{{ $Studen->id }}</td>
                                        <td>
                                            <img width="60"
                                            src="{{ $Studen->logo_path }}"
                                           class="m--img-rounded m--marginless" alt="cover">
                                        </td>
                                        <td>{{ $Studen->name }}</td>
                                        <td>{{ $Studen->schools->name }}</td>
                                        @if ($Studen->bus != null)
                                        <td>
                                            <a href="{{route('dashboard.buses.show', $Studen->bus->id)}}"><i class="fa fa-bus"></i></a>
                                        </td>
                                        @else
                                        <td style="min-width: 120px;color: tomato"> لا يستخدم الباص</td>
                                        @endif


                                        @if ($Studen->my_Parents->count() > 0 )
                                        <td style="min-width:50px">

                                            <a class="dropdown-item" href="{{route('dashboard.parents.index', ['student_id', $Studen->id])}}"
                                                style="padding: 6px;" title="show-grades">
                                           <i class="fa fa-user"></i>  ( {{ $Studen->my_Parents->count() }} ) </a>
                                        </td>

                                        @else
                                        <td style="color: tomato;min-width:120px" > لا يوجد ولي امر</td>
                                        @endif


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
                @endif
                @if ($students_no->count() > 0)

                <div class="tab-pane" id="students_no" aria-labelledby="students_no-tab" role="tabpanel">
                    <div class="table-responsive">
                        <table class="dt-multilingual table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>صورة الطالب</th>
                                    <th>اسم الطالب</th>
                                    <th>اسم المدرسة</th>
                                    <th>الباص</th>
                                    <th>عدد اولياء المور</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students_no as $index => $Studen)
                                    <tr>
                                        <td>{{ $Studen->id }}</td>
                                        <td>
                                            <img width="60"
                                            src="{{ $Studen->logo_path }}"
                                           class="m--img-rounded m--marginless" alt="cover">
                                        </td>
                                        <td>{{ $Studen->name }}</td>
                                        <td>{{ $Studen->schools->name }}</td>
                                        @if ($Studen->bus != null)
                                        <td>
                                            <a href="{{route('dashboard.buses.show', $Studen->bus->id)}}"><i class="fa fa-bus"></i></a>
                                        </td>
                                        @else
                                        <td style="min-width: 120px;color: tomato"> لا يستخدم الباص</td>
                                        @endif


                                        @if ($Studen->my_Parents->count() > 0 )
                                        <td style="min-width:50px">

                                            <a class="dropdown-item" href="{{route('dashboard.parents.index', ['student_id', $Studen->id])}}"
                                                style="padding: 6px;" title="show-grades">
                                           <i class="fa fa-user"></i>  ( {{ $Studen->my_Parents->count() }} ) </a>
                                        </td>

                                        @else
                                        <td style="color: tomato;min-width:120px" > لا يوجد ولي امر</td>
                                        @endif


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
                @endif
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
@endpush

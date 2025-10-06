@extends('dashboard.layouts.app')
@push('page_vendor_css')
@endpush
@push('page_styles')
@endpush
@section('content')
    <section class="app-user-list">
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75">{{ $Student_count }}</h3>
                            <span><a href="{{ route('dashboard.students.index') }}">عدد الطلاب</a> </span>
                        </div>
                        <div class="avatar bg-light-primary p-50">
                            <span class="avatar-content">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-user font-medium-4">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75">{{ $My_Parent_count }}</h3>
                            <span><a href="{{ route('dashboard.parents.index') }}"> عدد اولياء الامور</a></span>
                        </div>
                        <div class="avatar bg-light-danger p-50">
                            <span class="avatar-content">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-user-plus font-medium-4">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <line x1="20" y1="8" x2="20" y2="14"></line>
                                    <line x1="23" y1="11" x2="17" y2="11"></line>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75">{{ $Attendant_count }}</h3>
                            <span><a href="{{ route('dashboard.attendants.index') }}"> عدد المرافقين</a></span>
                        </div>
                        <div class="avatar bg-light-success p-50">
                            <span class="avatar-content">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-user-check font-medium-4">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <polyline points="17 11 19 13 23 9"></polyline>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75">{{ $admins_count }}</h3>
                            <span><a href="{{ route('dashboard.staff.index') }}"> عدد المشرفين</a></span>
                        </div>
                        <div class="avatar bg-light-warning p-50">
                            <span class="avatar-content">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-user-check font-medium-4">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <polyline points="17 11 19 13 23 9"></polyline>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75">{{ $bus_count }}</h3>
                            <span><a href="{{ route('dashboard.buses.index') }}">عدد الباصات</a> </span>
                        </div>
                        <div class="avatar bg-light-info p-50">
                            <span class="avatar-content">
                                <i class="fa fa-bus fa-2x"></i>

                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75">{{ $School_count }}</h3>
                            <span><a href="{{ route('dashboard.schools.index') }}">عدد المدارس</a> </span>
                        </div>
                        <div class="avatar bg-light-success p-50">
                            <span class="avatar-content">
                                <i class="fa fa-school fa-2x"></i>

                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75">{{ $Grade_coun }}</h3>
                            <span><a href="{{ route('dashboard.grades.index') }}">عدد المراحل</a> </span>
                        </div>
                        <div class="avatar bg-light-success p-50">
                            <span class="avatar-content">
                                {{-- <i class="fa-duotone fa-layer-group"></i> --}}
                                <i class="fa-solid fa-layer-group fa-2x"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75">{{ $Classroom_count }}</h3>
                            <span><a href="{{ route('dashboard.classrooms.index') }}">عدد الصفوف</a> </span>
                        </div>
                        <div class="avatar bg-light-success p-50">
                            <span class="avatar-content">
                                <i class="fa-solid fa-layer-group fa-2x"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section class="app-user-edit">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center active" id="account-tab"
                            data-toggle="tab" href="#account" aria-controls="account" role="tab" aria-selected="true">
                            <i  class="fa fa-school"></i><span class="d-none d-sm-block">اخر المدارس</span>
                        </a>
                    </li>
                    @if ($students->count() > 0)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" id="students-tab" data-toggle="tab" href="#students" aria-controls="students" role="tab" aria-selected="false">
                            <i  data-feather="user"></i><span class="d-none d-sm-block">اخر الطلاب</span>
                        </a>
                    </li>
                    @endif

                    @if ($buses->count() > 0)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" id="buses-tab" data-toggle="tab" href="#buses" aria-controls="buses" role="tab" aria-selected="false">
                            <i class="fa fa-bus"></i><span class="d-none d-sm-block">اخر الباصات</span>
                        </a>
                    </li>
                    @endif

                    @if ($admins->count() > 0)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" id="admins-tab" data-toggle="tab" href="#admins" aria-controls="admins" role="tab" aria-selected="false">
                            <i  data-feather="user"></i><span class="d-none d-sm-block">اخر المشرفين</span>
                        </a>
                    </li>
                    @endif

                    @if ($drivers->count() > 0)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" id="drivers-tab" data-toggle="tab" href="#drivers" aria-controls="drivers" role="tab" aria-selected="false">
                            <i  data-feather="user"></i><span class="d-none d-sm-block">اخر السائقين</span>
                        </a>
                    </li>
                    @endif

                    @if ($parents->count() > 0)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" id="parents-tab" data-toggle="tab" href="#parents" aria-controls="parents" role="tab" aria-selected="false">
                            <i  data-feather="user"></i><span class="d-none d-sm-block">اولياء الامور</span>
                        </a>
                    </li>
                    @endif

                </ul>
                <div class="tab-content">
                    <!-- Account Tab starts -->
                    @if ($Schools->count() > 0)

                    <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                        <div class="table-responsive">
                            <table class="dt-multilingual table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>اسم المدرسة</th>
                                        <th>عدد المراحل الدرسية</th>
                                        <th>عدد الصفوف الدرسية</th>
                                        <th>عدد الطلاب</th>
                                        <th>عدد الباصات</th>
                                        <th>عدد السائقين</th>
                                        <th> عدد المشرفين </th>
                                        <th>الاعدادت</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Schools as $index => $school)
                                    <tr>
                                        <td>{{ $school->id }}</td>
                                        {{-- <td>

                                            <img width="60"
                                             src="{{ $school->logo_path }}"
                                            class="m--img-rounded m--marginless" alt="cover">

                                        </td> --}}
                                        <td>{{ $school->name }}</td>
                                        <td>
                                            @if (auth()->user()->canany(['super', 'grades-list']))

                                            <a class="dropdown-item" href="{{route('dashboard.grades.index',['school_id' => $school->id])}}"
                                                style="padding: 6px;" title="show-grades">
                                           <i class="fa fa-school"></i>  ( {{ $school->grades->count() }} ) </a>

                                           @endif

                                        </td>

                                        <td style="min-width:50px">
                                            @if (auth()->user()->canany(['super', 'grades-list']))

                                            <a class="dropdown-item" href="{{route('dashboard.classrooms.index',['school_id' => $school->id])}}"
                                                style="padding: 6px;" title="show-classrooms">
                                           <i class="fa fa-building"></i>  ( {{ $school->classrooms->count() }} ) </a>
                                           @endif


                                        </td>




                                        <td style="min-width:50px">
                                            @if (auth()->user()->canany(['super', 'students-list']))

                                            <a class="dropdown-item" href="{{route('dashboard.students.index',['school_id' => $school->id])}}"
                                                style="padding: 6px;" title="show-students">
                                           <i class="fa fa-user"></i>
                                           ( {{ $school->students->count() }} )
                                           </a>
                                            @endif

                                        </td>

                                        <td style="min-width:50px">
                                            @if (auth()->user()->canany(['super', 'buses-list']))

                                            <a class="dropdown-item" href="{{route('dashboard.buses.index',['school_id' => $school->id])}}"
                                                style="padding: 6px;" title="show-buses">
                                           <i class="fa fa-bus"></i>  ( {{ $school->buses->count() }} ) </a>
                                            @endif

                                        </td>
                                        <td style="min-width:50px">
                                            @if (auth()->user()->canany(['super', 'attendants-list']))

                                            <a class="dropdown-item" href="{{route('dashboard.attendants.index',['school_id' => $school->id, 'type' => 'drivers'])}}"
                                                style="padding: 6px;" title="show-drivers">
                                                <i class="fa-regular fa-id-card"></i>  ( {{ $school->attendants->where('type', 'drivers')->count() }} )
                                            </a>
                                            @endif

                                        </td>

                                        <td style="min-width:50px">
                                            @if (auth()->user()->canany(['super', 'attendants-list']))

                                            <a class="dropdown-item" href="{{route('dashboard.attendants.index',['school_id' => $school->id, 'type' => 'admins'])}}"
                                                style="padding: 6px;" title="show-admins">
                                                <i class="fa-solid fa-user-pen"></i> ( {{ $school->attendants->where('type', 'admins')->count() }} ) </a>
                                            @endif

                                        </td>


                                        <td>
                                        <div class="dropdown">
                                            <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                العمليات
                                            </a>
                                            @if (auth()->user()->canany(['super', 'grades-create']))

                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                <a class="dropdown-item" href="{{route('dashboard.grades.edit',$school->id)}}"
                                                    style="padding: 6px;" title="edit-grades">
                                               <i  class="fa fa-plus"></i>اضف المراحل الدراسية </a>
                                               @endif

                                        @if (auth()->user()->canany(['super', 'classrooms-create']))
                                           <a class="dropdown-item" href="{{route('dashboard.classrooms.create',$school->id)}}"
                                            style="padding: 6px;" title="create-grades">
                                            <i  class="fa fa-plus"></i> اضف صف دراسي</a>
                                        @endif



                                                @if (auth()->user()->canany(['super', 'schools-show']))
                                                <a href="{{ route('dashboard.schools.show', $school->id) }}"
                                                    class="dropdown-item"
                                                    style="padding: 6px;" title="Show">
                                                    <i data-feather='eye'></i>
                                                    عرض بيانات المدرسة
                                                </a>
                                                @endif


                                            @if (auth()->user()->canany(['super', 'schools-edit']))
                                                <a href="{{ route('dashboard.schools.edit', $school->id) }}"
                                                    class="dropdown-item"
                                                    style="padding: 6px;" title="Edit">
                                                    <i data-feather='edit'></i>
                                                    تعديل بيانات المدرسة

                                                </a>
                                            @endif

                                            @if (auth()->user()->canany(['super', 'schools-destroy']))
                                                <a onclick="event.preventDefault();"
                                                    data-delete="delete-form-{{ $index }}"
                                                    href="{{ route('dashboard.schools.destroy', $school->id) }}"
                                                    class="dropdown-item but_delete_action"
                                                    style="padding: 6px;" title="Delete">
                                                    <i data-feather='trash'></i>
                                                    حذف بيانات المدرسة

                                                </a>
                                                <form id="delete-form-{{ $index }}"
                                                    action="{{ route('dashboard.schools.destroy', $school->id) }}"
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
                    @endif
                    <!-- Account Tab ends -->



                    @if ($students->count() > 0)
                    <div class="tab-pane" id="students" aria-labelledby="students-tab" role="tabpanel">

                        <div class="table-responsive">
                            <table class="dt-multilingual table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>صورة الطالب</th>
                                        <th>KEY</th>
                                        <th>SECRET</th>

                                        <th>اسم الطالب</th>
                                        {{-- <th>اسم المدرسة</th> --}}
                                        <th>الباص</th>
                                        {{-- <th>اسم السائق</th>
                                        <th>اسم المشرف</th> --}}
                                        <th>عدد اولياء المور</th>
                                        <th> تاريخ الاضافة</th>
                                        <th>الاعدادت</th>

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
                                            <td>{{ $Studen->parent_key }}</td>
                                            <td>{{ $Studen->parent_secret }}</td>
                                            <td>{{ $Studen->name }}</td>
                                            {{-- <td>{{ $Studen->schools->name }}</td> --}}
                                            @if ($Studen->bus_id != null)
                                            <td>
                                                @if (auth()->user()->canany(['super', 'buses-list']))

                                                <a href="{{route('dashboard.buses.index', ['bus_number' => $Studen->bus_id])}}"><i class="fa fa-bus"></i></a>
                                                @endif

                                            </td>
                                            @else
                                            <td style="min-width: 120px;color: tomato"> لا يستخدم الباص</td>
                                            @endif

                                            {{-- <td>{{ $Studen->attendant_driver->name }}</td>
                                            <td>{{ $Studen->attendant_admins->name }}</td> --}}

                                            @if ($Studen->my_Parents->count() > 0 )
                                            <td style="min-width:50px">
                                                @if (auth()->user()->canany(['parents-list']))

                                                <a class="dropdown-item" href="{{route('dashboard.parents.index',['student_id' => $Studen->id])}}"
                                                    style="padding: 6px;" title="show-parents">
                                               <i class="fa fa-user"></i>  ( {{ $Studen->my_Parents->count() }} ) </a>

                                               @endif

                                            </td>

                                            @else
                                            <td style="color: tomato;min-width:120px" > لا يوجد ولي امر</td>
                                            @endif
                                            <td  style="min-width:120px">{{ $Studen->created_at->diffForHumans()  }}</td>

                                            <td>
                                            <div class="dropdown">
                                                <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    العمليات
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">

                                                    @if (auth()->user()->canany(['super', 'addresses-create']))
                                                    <a href="{{ route('dashboard.addresses.create',$Studen->id) }}"
                                                        class="dropdown-item"
                                                        style="padding: 6px;" title="create">
                                                        <i data-feather='eye'></i>
                                                        اضف طلب تغير عنوان
                                                    </a>
                                                @endif

                                                    @if (auth()->user()->canany(['super', 'students-show']))
                                                    <a href="{{ route('dashboard.students.show',$Studen->id) }}"
                                                        class="dropdown-item"
                                                        style="padding: 6px;" title="show">
                                                        <i data-feather='eye'></i>
                                                        عرض بيانات الطالب
                                                    </a>
                                                @endif

                                                @if (auth()->user()->canany(['super', 'students-edit']))
                                                    <a href="{{ route('dashboard.students.edit',$Studen->id) }}"
                                                        class="dropdown-item"
                                                        style="padding: 6px;" title="Edit">
                                                        <i data-feather='edit'></i>
                                                        تعديل بيانات الطالب
                                                    </a>
                                                @endif

                                                @if (auth()->user()->canany(['super', 'students-destroy']))
                                                    <a onclick="event.preventDefault();"
                                                        data-delete="delete-form-{{ $index }}"
                                                        href="{{ route('dashboard.students.destroy', $Studen->id) }}"
                                                        class="dropdown-item but_delete_action"
                                                        style="padding: 6px;" title="Delete">
                                                        <i data-feather='trash'></i>
                                                        حذف بيانات الطالب

                                                    </a>
                                                    <form id="delete-form-{{ $index }}"
                                                        action="{{ route('dashboard.students.destroy', $Studen->id) }}"
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
                    @endif

                    @if ($buses->count() > 0)
                    <div class="tab-pane" id="buses" aria-labelledby="buses-tab" role="tabpanel">

                        <div class="table-responsive">
                            <table class="dt-multilingual table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>الاسم</th>
                                        <th> رقم الباص </th>
                                        <th>   الطلاب </th>
                                        <th> تاريخ الاضافة</th>
                                        <th>الاعدادت</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($buses as $index => $bus)
                                    <tr>
                                        <td>{{ $bus->id }}</td>


                                        <td>{{ $bus->name }}</td>

                                        {{-- <td>{{ $buses->schools->name }}</td> --}}
                                        <td>{{ $bus->car_number }}</td>


                                        <td style="min-width:50px">
                                            @if (auth()->user()->canany(['super', 'students-show']))

                                            <a class="dropdown-item"
                                                href="{{ route('dashboard.students.index', ['bus_id' => $bus->id]) }}"
                                                style="padding: 6px;" title="show-students">
                                                <i class="fa fa-user"></i> ( {{ $bus->students->count() }} ) </a>
                                             @endif

                                            </td>



                                        <td>{{ $bus->created_at }}</td>




                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-success btn-sm dropdown-toggle" href="#"
                                                    role="button" id="dropdownMenuLink" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    العمليات
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right"
                                                    aria-labelledby="dropdownMenuLink">


                                                    @if (auth()->user()->canany(['super', 'buses-show']))
                                                        <a href="{{ route('dashboard.buses.show', $bus->id) }}"
                                                            class="dropdown-item" style="padding: 6px;" title="show">
                                                            <i data-feather='eye'></i>
                                                            عرض بيانات الباص
                                                        </a>
                                                    @endif

                                                    @if (auth()->user()->canany(['super', 'buses-edit']))
                                                        <a href="{{ route('dashboard.buses.edit', $bus->id) }}"
                                                            class="dropdown-item" style="padding: 6px;" title="Edit">
                                                            <i data-feather='edit'></i>
                                                            تعديل بيانات الباص
                                                        </a>
                                                    @endif

                                                    @if (auth()->user()->canany(['super', 'buses-destroy']))
                                                        <a onclick="event.preventDefault();"
                                                            data-delete="delete-form-{{ $index }}"
                                                            href="{{ route('dashboard.buses.destroy', $bus->id) }}"
                                                            class="dropdown-item but_delete_action" style="padding: 6px;"
                                                            title="Delete">
                                                            <i data-feather='trash'></i>
                                                            حذف بيانات الباص

                                                        </a>
                                                        <form id="delete-form-{{ $index }}"
                                                            action="{{ route('dashboard.buses.destroy', $bus->id) }}"
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
                    @endif

                    @if ($admins->count() > 0)
                    <div class="tab-pane" id="admins" aria-labelledby="admins-tab" role="tabpanel">

                        <div class="table-responsive">
                            <table class="dt-multilingual table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th> الصورة</th>
                                        <th> الاسم</th>
                                        {{-- <th> المدرسة</th> --}}
                                        <th> الوظيفة</th>
                                        <th> الباص</th>
                                        {{-- <th> الطلاب</th> --}}
                                        <th>تاريخ الاضافة</th>
                                        <th>الاعدادت</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admins as $index => $attendant)
                                        <tr>
                                            <td>{{ $attendant->id }}</td>
                                            <td>
                                                <img width="60"
                                                 src="{{ $attendant->logo_path }}"
                                                class="m--img-rounded m--marginless" alt="cover">

                                            </td>
                                            <td>{{ $attendant->name }}</td>
                                            {{-- <td>{{ $attendant->schools->name }}</td> --}}
                                            <td>{{ $attendant->type }}</td>



                                            <td>{{ isset($attendant->bus) != null ? $attendant->bus->name : '' }}</td>




                                            <td>{{ $attendant->created_at }}</td>



                                            <td>
                                            <div class="dropdown">
                                                <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    العمليات
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">



                                                    @if (auth()->user()->canany(['super', 'attendants-show']))
                                                    <a href="{{ route('dashboard.attendants.show',$attendant->id) }}"
                                                        class="dropdown-item"
                                                        style="padding: 6px;" title="show">
                                                        <i data-feather='eye'></i>
                                                        عرض بيانات المرفق
                                                   </a>
                                                @endif


                                                @if (auth()->user()->canany(['super', 'attendants-edit']))
                                                    <a href="{{ route('dashboard.attendants.edit',$attendant->id) }}"
                                                        class="dropdown-item"
                                                        style="padding: 6px;" title="Edit">
                                                        <i data-feather='edit'></i>
                                                        تعديل بيانات المرفق
                                                    </a>
                                                @endif

                                                @if (auth()->user()->canany(['super', 'attendant-destroy']))
                                                    <a onclick="event.preventDefault();"
                                                        data-delete="delete-form-{{ $index }}"
                                                        href="{{ route('dashboard.attendants.destroy', $attendant->id) }}"
                                                        class="dropdown-item but_delete_action"
                                                        style="padding: 6px;" title="Delete">
                                                        <i data-feather='trash'></i>
                                                        حذف بيانات المرافق

                                                    </a>
                                                    <form id="delete-form-{{ $index }}"
                                                        action="{{ route('dashboard.attendants.destroy', $attendant->id) }}"
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
                    @endif
                    @if ($drivers->count() > 0)
                    <div class="tab-pane" id="drivers" aria-labelledby="drivers-tab" role="tabpanel">

                        <div class="tab-pane" id="admins" aria-labelledby="admins-tab" role="tabpanel">

                            <div class="table-responsive">
                                <table class="dt-multilingual table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th> الصورة</th>
                                            <th> الاسم</th>
                                            {{-- <th> المدرسة</th> --}}
                                            <th> الوظيفة</th>
                                            <th> الباص</th>
                                            {{-- <th> الطلاب</th> --}}
                                            <th>تاريخ الاضافة</th>
                                            <th>الاعدادت</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($drivers as $index => $attendant)
                                            <tr>
                                                <td>{{ $attendant->id }}</td>
                                                <td>
                                                    <img width="60"
                                                     src="{{ $attendant->logo_path }}"
                                                    class="m--img-rounded m--marginless" alt="cover">

                                                </td>
                                                <td>{{ $attendant->name }}</td>
                                                {{-- <td>{{ $attendant->schools->name }}</td> --}}
                                                <td>{{ $attendant->type }}</td>

                                                <td>{{ isset($attendant->bus) != null ? $attendant->bus->name : '' }}</td>

                                                <td>{{ $attendant->created_at }}</td>



                                                <td>
                                                <div class="dropdown">
                                                    <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        العمليات
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">



                                                        @if (auth()->user()->canany(['super', 'attendants-show']))
                                                        <a href="{{ route('dashboard.attendants.show',$attendant->id) }}"
                                                            class="dropdown-item"
                                                            style="padding: 6px;" title="show">
                                                            <i data-feather='eye'></i>
                                                            عرض بيانات المرفق
                                                       </a>
                                                    @endif


                                                    @if (auth()->user()->canany(['super', 'attendants-edit']))
                                                        <a href="{{ route('dashboard.attendants.edit',$attendant->id) }}"
                                                            class="dropdown-item"
                                                            style="padding: 6px;" title="Edit">
                                                            <i data-feather='edit'></i>
                                                            تعديل بيانات المرفق
                                                        </a>
                                                    @endif

                                                    @if (auth()->user()->canany(['super', 'attendant-destroy']))
                                                        <a onclick="event.preventDefault();"
                                                            data-delete="delete-form-{{ $index }}"
                                                            href="{{ route('dashboard.attendants.destroy', $attendant->id) }}"
                                                            class="dropdown-item but_delete_action"
                                                            style="padding: 6px;" title="Delete">
                                                            <i data-feather='trash'></i>
                                                            حذف بيانات المرافق

                                                        </a>
                                                        <form id="delete-form-{{ $index }}"
                                                            action="{{ route('dashboard.attendants.destroy', $attendant->id) }}"
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
                    @endif

                    @if ($parents->count() > 0)
                    <div class="tab-pane" id="parents" aria-labelledby="parents-tab" role="tabpanel">

                        <div class="table-responsive">
                            <table class="dt-multilingual table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>صورة</th>
                                        <th> الاسم </th>
                                        {{-- <th> المدرسة </th> --}}
                                        <th> عدد الابناء </th>
                                        <th> تاريخ الاضافة</th>
                                        <th>الاعدادت</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($parents as $index => $parent)
                                        <tr>
                                            <td>{{ $parent->id }}</td>
                                            <td><img width="60"
                                                src="{{ $parent->logo_path }}"
                                               class="m--img-rounded m--marginless" alt="cover">
                                            </td>
                                            <td>{{ $parent->name }}</td>
                                            {{-- <td>{{ $parent->schools->name }}</td> --}}
                                                {{-- <a href="{{ route('dashboard.parents.child', $parent->id)}}"> <i class="fa fa-child"></i> ({{ $parent->students->count() }})</a> --}}
                                                <td style="min-width:50px">
                                                    @if (auth()->user()->canany(['super', 'students-show']))

                                                    <a class="dropdown-item" href="{{route('dashboard.students.index',['parent_id' => $parent->id])}}"
                                                          style="padding: 6px;" title="show-students">
                                                     <i class="fa fa-user"></i>  ( {{ $parent->students->count()  }} ) </a>
                                                     @endif

                                                    </td>

                                            <td>{{ $parent->created_at }}</td>

                                            <td>
                                            <div class="dropdown">
                                                <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    العمليات
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">


                                                    @if (auth()->user()->canany(['super', 'parents-show']))
                                                    <a href="{{ route('dashboard.parents.show',$parent->id) }}"
                                                        class="dropdown-item"
                                                        style="padding: 6px;" title="Show">
                                                        <i data-feather='show'></i>
                                                        عرض بيانات ولي الامر
                                                    </a>
                                                @endif
                                                @if (auth()->user()->canany(['super', 'parents-edit']))
                                                    <a href="{{ route('dashboard.parents.edit',$parent->id) }}"
                                                        class="dropdown-item"
                                                        style="padding: 6px;" title="Edit">
                                                        <i data-feather='edit'></i>
                                                        تعديل بيانات ولي الامر
                                                    </a>
                                                @endif
                                                @if (auth()->user()->canany(['super', 'parents-edit']))
                                                    <a href="{{ route('dashboard.parents.add-child',$parent->id) }}"
                                                        class="dropdown-item"
                                                        style="padding: 6px;" title="Edit">
                                                        <i data-feather='edit'></i>
                                                        اضف ابن
                                                    </a>
                                                @endif
                                                @if (auth()->user()->canany(['super', 'parents-destroy']))
                                                    <a onclick="event.preventDefault();"
                                                        data-delete="delete-form-{{ $index }}"
                                                        href="{{ route('dashboard.parents.destroy', $parent->id) }}"
                                                        class="dropdown-item but_delete_action"
                                                        style="padding: 6px;" title="Delete">
                                                        <i data-feather='trash'></i>
                                                        حذف بيانات ولي الامر

                                                    </a>
                                                    <form id="delete-form-{{ $index }}"
                                                        action="{{ route('dashboard.parents.destroy', $parent->id) }}"
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
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('page_scripts_vendors')
@endpush

@push('page_scripts')
@endpush

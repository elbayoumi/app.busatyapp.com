@extends('dashboard.layouts.app')
@push('page_vendor_css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">
<!-- BEGIN: Vendor CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/extensions/toastr.min.css">
<!-- END: Vendor CSS-->


<!-- BEGIN: Custom CSS-->

@endpush
@push('page_styles')
@endpush
@section('content')
{{-- @dd('sdfde') --}}
    <section id="multilingual-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom p-2">
                        <h4 class="card-title">قائمة / الطلاب</h4>

                    </div>
                    <div class="card-header border-bottom p-1">

                        <form action="{{ route('dashboard.students.index') }}" method="GET" style="min-width: 100%">
                            <div class="row">

                                <div class="col-md-3  my-50">

                                    <input type="text" class="form-control" placeholder="الاسم"
                                        value="{{ request()->text }}" name="text" id="text"/>
                                </div>

                                <div class="col-md-3 my-50">
                                    <select name="parent_key" class="form-control basic-select2">
                                        <option value="">الكود الاول (KEY)</option>
                                        @foreach ($all_Student as $Student)
                                            <option value="{{ $Student->id }}" {{ request()->parent_key == $Student->id ? 'selected' : '' }}>{{ $Student->parent_key }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- <div class="col-md-3 my-50">
                                    <select name="parent_secret" class="form-control basic-select2">
                                        <option value="">الكود الثاني (SECRET)</option>
                                        @foreach ($all_Student as $Student)
                                            <option value="{{ $Student->id }}" {{ request()->parent_secret == $Student->id ? 'selected' : '' }}>{{ $Student->parent_secret }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                 --}}

                                 <div class="col-md-3 my-50">
                                    <select name="school_id" class="form-control basic-select2">
                                        <option value="">المدرسة</option>
                                        @foreach ($schools as $school)
                                            <option value="{{ $school->id }}" {{ request()->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                               <div class="col-md-3 my-50">
                                    <select name="bus_id" class="form-control basic-select2">
                                        <option value=""> الباص</option>
                                        @foreach ($all_buses as $bus)
                                            <option value="{{ $bus->id }}" {{ request()->bus_id == $bus->id ? 'selected' : '' }}>{{ $bus->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 my-50">
                                    <select name="grade_id" class="form-control basic-select2">
                                        <option value=""> المرحل الدرسية</option>
                                        @foreach ($grades as $grade)
                                            <option value="{{ $grade->id }}" {{ request()->grade_id == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 my-50">
                                    <select name="classroom_id" class="form-control basic-select2">
                                        <option value=""> الصفوف الدرسية</option>
                                        @foreach ($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}" {{ request()->classroom_id == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 my-50">
                                    <select name="parent_id" class="form-control basic-select2">
                                        <option value="">اولياء الامور</option>
                                        @foreach ($parents as $parent)
                                            <option value="{{ $parent->id }}" {{ request()->parent_id == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                    <div class="col-md-3 my-50">
                                        <button type="submit" class="btn btn-primary btn-block">بحث</button>
                                    </div>


                            </div>

                        </form>

                    </div>
                    <div class="table-responsive">
                        <table class="dt-multilingual table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>صورة الطالب</th>
                                    <th>اسم الطالب</th>
                                    <th>اسم المدرسة</th>
                                    <th>الباص</th>
                                    {{-- <th>اسم السائق</th>
                                    <th>اسم المشرف</th> --}}
                                    <th>عدد اولياء المور</th>
                                    <th> تاريخ الاضافة</th>
                                    <th>الاعدادت</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_Student as $index => $Studen)
                                    <tr>
                                        <td>{{ $Studen->id }}</td>
                                        <td>
                                            <img width="60"
                                            src="{{ $Studen->logo_path }}"
                                           class="m--img-rounded m--marginless" alt="cover">
                                        </td>
                                        <td>{{ $Studen->name }}</td>
                                        <td> <a href="{{ route('dashboard.schools.show',$Studen?->schools?->id) }}">{{ $Studen?->schools?->name }}</a> </td>

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

                                                @if (auth()->user()->canany(['super', 'students-list']))
                                                <a href="{{$Studen->parent_key}}" onclick="copyURI(event)"
                                                    class="dropdown-item key"
                                                    style="padding: 6px;" title="copy" data-key="{{$Studen->parent_key}}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-copy" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M8 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z"></path>
                                                        <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"></path>
                                                     </svg>
                                                    الكود الاول (KEY)
                                                </a>
                                               @endif

                                               @if (auth()->user()->canany(['super', 'students-list']))
                                               <a href="{{$Studen->parent_secret}}" onclick="copyURI(event)"
                                                   class="dropdown-item key"
                                                   style="padding: 6px;" title="copy" data-key="{{$Studen->parent_secret}}">
                                                   <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-copy" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M8 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z"></path>
                                                    <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"></path>
                                                 </svg>
                                                   الكود الثاني (SECRET)
                                               </a>
                                              @endif
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
            </div>
        </div>
        {{ $all_Student->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}

    </section>
@endsection

@push('page_scripts_vendors')
<script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <!-- BEGIN: Vendor JS-->
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/extensions/toastr.min.js"></script>
    <!-- END: Page Vendor JS-->




@endpush

@push('page_scripts')

<script>

function copyURI(evt) {
    evt.preventDefault();
    navigator.clipboard.writeText(evt.target.getAttribute('href')).then(() => {
        toastr['success']('', 'تم نسخ النص با نجاح', {
                rtl: true
        });
    }, () => {
      return false;
    });
}


    $(document).ready(function() {
        $('.basic-select2').select2();
    });
    </script>
@endpush

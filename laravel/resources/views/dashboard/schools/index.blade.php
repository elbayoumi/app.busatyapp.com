@extends('dashboard.layouts.app')
@push('page_vendor_css')
@endpush
@push('page_styles')

@endpush

@section('content')
    <section id="multilingual-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom p-2">
                        <h4 class="card-title">قائمة / المدارس</h4>
                    </div>
                    <div class="card-header border-bottom p-1">

                        <form action="{{ route('dashboard.schools.index') }}" method="GET" style="min-width: 100%">
                            <div class="row">
                                <input type="hidden" value="{{ $adesId ?? '' }}" name="adesId" />
                                <div class="col-md-3  my-50">

                                    <input type="text" class="form-control" placeholder="للبحث"
                                        value="{{ request()->text }}" name="text" id="text" />
                                </div>

                                <div class="col-md-3 my-50">
                                    <select name="grade_id" class="form-control basic-select2">
                                        <option value=""> المرحل الدرسية</option>
                                        @foreach ($grades as $grade)
                                            <option value="{{ $grade->id }}"
                                                {{ request()->grade_id == $grade->id ? 'selected' : '' }}>
                                                {{ $grade->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 my-50">
                                    <select name="email_validate" class="form-control basic-select2">
                                        <option value="" selected>الجميع</option>
                                        <option value="1">مفعلة</option>
                                        <option value="0">غير مفعلة</option>
                                    </select>
                                </div>
                                <div class="col-md-3 my-50">
                                    <button type="submit" class="btn btn-primary btn-block">بحث</button>
                                </div>


                            </div>

                        </form>

                    </div>

                    <div class="table-responsive min-height-17rem">
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
                                    <th> عدد الكوبونات المستخدمه </th>
                                    @isset($adesId)
                                        <th> اضافة الاعلان </th>
                                    @endisset
                                    <th>الاعدادت</th>
                                    <th>عدد الاعلانات</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_schools as $index => $school)
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
                                                <a class="dropdown-item"
                                                    href="{{ route('dashboard.grades.index', ['school_id' => $school->id]) }}"
                                                    style="padding: 6px;" title="show-grades">
                                                    <i class="fa fa-school"></i> ( {{ $school->grades->count() }} ) </a>
                                            @endif

                                        </td>

                                        <td style="">
                                            @if (auth()->user()->canany(['super', 'grades-list']))
                                                <a class="dropdown-item"
                                                    href="{{ route('dashboard.classrooms.index', ['school_id' => $school->id]) }}"
                                                    style="padding: 6px;" title="show-classrooms">
                                                    <i class="fa fa-building"></i> ( {{ $school->classrooms->count() }} )
                                                </a>
                                            @endif


                                        </td>



                                        <td style="">
                                            @if (auth()->user()->canany(['super', 'students-list']))
                                                <a class="dropdown-item"
                                                    href="{{ route('dashboard.students.index', ['school_id' => $school->id]) }}"
                                                    style="padding: 6px;" title="show-students">
                                                    <i class="fa fa-user"></i>
                                                    ({{ $school->students->count() }})
                                                </a>
                                            @endif

                                        </td>

                                        <td style="">
                                            @if (auth()->user()->canany(['super', 'buses-list']))
                                                <a class="dropdown-item"
                                                    href="{{ route('dashboard.buses.index', ['school_id' => $school->id]) }}"
                                                    style="padding: 6px;" title="show-buses">
                                                    <i class="fa fa-bus"></i> ( {{ $school->buses->count() }} ) </a>
                                            @endif

                                        </td>
                                        <td style="">
                                            @if (auth()->user()->canany(['super', 'attendants-list']))
                                                <a class="dropdown-item"
                                                    href="{{ route('dashboard.attendants.index', ['school_id' => $school->id, 'type' => 'drivers']) }}"
                                                    style="padding: 6px;" title="show-drivers">
                                                    <i class="fa-regular fa-id-card"></i> (
                                                    {{ $school->attendants->where('type', 'drivers')->count() }} )
                                                </a>
                                            @endif

                                        </td>

                                        <td>
                                            @if (auth()->user()->canany(['super', 'attendants-list']))
                                                <a class="dropdown-item"
                                                    href="{{ route('dashboard.attendants.index', ['school_id' => $school->id, 'type' => 'admins']) }}"
                                                    style="padding: 6px;" title="show-admins">
                                                    <i class="fa-solid fa-user-pen"></i> (
                                                    {{ $school->attendants->where('type', 'admins')->count() }} ) </a>
                                            @endif

                                        </td>
                                        <td>
                                            @if (auth()->user()->canany(['super', 'coupon-list']))
                                                <a class="dropdown-item"
                                                    href="{{ route('dashboard.coupon.index', ['model' => 'schools', 'school_id' => $school->id]) }}"
                                                    style="padding: 6px;" title="show-coupons">
                                                    <i class="fa fa-ticket"></i> (
                                                    {{ $school->myCoupons->count() }} ) </a>
                                            @endif
                                        </td>
                                        @isset($adesId)
                                            <td>
                                                <form
                                                    action="{{ route('dashboard.adesSchool.storeSchooleToAdes', ['schoolId' => $school->id, 'adesId' => $adesId, 'ades_to' => $ades_to]) }}"
                                                    method="post">
                                                    @csrf
                                                    <!-- Include your form fields here -->
                                                    <button class="btn btn-primary btn-sm " role="button"
                                                        type="submit">اضف</button>
                                                </form>


                                            </td>
                                        @endisset

                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-success btn-sm dropdown-toggle" href="#"
                                                    role="button" id="dropdownMenuLink" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    العمليات
                                                </a>
                                                @if (auth()->user()->canany(['super', 'grades-create']))
                                                    <div class="dropdown-menu dropdown-menu-right"
                                                        aria-labelledby="dropdownMenuLink">
                                                        <a class="dropdown-item"
                                                            href="{{ route('dashboard.grades.edit', $school->id) }}"
                                                            style="padding: 6px;" title="edit-grades">
                                                            <i class="fa fa-plus"></i>اضف المراحل الدراسية </a>
                                                @endif

                                                @if (auth()->user()->canany(['super', 'classrooms-create']))
                                                    <a class="dropdown-item"
                                                        href="{{ route('dashboard.classrooms.create', $school->id) }}"
                                                        style="padding: 6px;" title="create-grades">
                                                        <i class="fa fa-plus"></i> اضف صف دراسي</a>
                                                @endif

                                                @if (auth()->user()->canany(['super', 'schools-show']))
                                                    <a href="{{ route('dashboard.coupon.create', ['model' => 'parents', 'school_id' => $school->id]) }}"
                                                        class="dropdown-item" style="padding: 6px;" title="Show">
                                                        <i data-feather='eye'></i>
                                                        انشاء كوبون المدرسة
                                                    </a>
                                                @endif
                                                @if (auth()->user()->canany(['super', 'schools-show']))
                                                <a href="{{ route('dashboard.coupon.index', ['model' => 'parents', 'school_id' => $school->id]) }}"
                                                    class="dropdown-item" style="padding: 6px;" title="Show">
                                                    <i data-feather='eye'></i>
                                                    عرض كوبونات المدرسة
                                                </a>
                                            @endif
                                                @if (auth()->user()->canany(['super', 'schools-show']))
                                                    <a href="{{ route('dashboard.schools.show', $school->id) }}"
                                                        class="dropdown-item" style="padding: 6px;" title="Show">
                                                        <i data-feather='eye'></i>
                                                        عرض بيانات المدرسة
                                                    </a>
                                                @endif


                                                @if (auth()->user()->canany(['super', 'schools-edit']))
                                                    <a href="{{ route('dashboard.schools.edit', $school->id) }}"
                                                        class="dropdown-item" style="padding: 6px;" title="Edit">
                                                        <i data-feather='edit'></i>
                                                        تعديل بيانات المدرسة

                                                    </a>
                                                @endif

                                                @if (auth()->user()->canany(['super', 'schools-destroy']))
                                                    <a onclick="event.preventDefault();"
                                                        data-delete="delete-form-{{ $index }}"
                                                        href="{{ route('dashboard.schools.destroy', $school->id) }}"
                                                        class="dropdown-item but_delete_action" style="padding: 6px;"
                                                        title="Delete">
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
                    <td>
                        {{ $school->adesSchool->count() }}

                    </td>
                    </tr>
                    @endforeach
                    </tbody>
                    </table>

                </div>
            </div>
        </div>
        </div>
        {{ $all_schools->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}

    </section>
@endsection

@push('page_scripts_vendors')
@endpush

@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.but_delete_action').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const formId = this.getAttribute('data-delete');
                const form = document.getElementById(formId);

                if (confirm('هل أنت متأكد من حذف هذه المدرسة؟')) {
                    form.submit();
                }
            });
        });

        // Feather icon refresh
        if (window.feather) {
            window.feather.replace();
        }
    });
</script>
@endpush


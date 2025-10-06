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
                        <h4 class="card-title">قائمة / المراحل الدرسية</h4>
                    </div>
                    <div class="card-header border-bottom p-1">

                        <form action="{{ route('dashboard.grades.index') }}" method="GET" style="min-width: 100%">
                            <div class="row">
                                <div class="col-md-3 my-50">
                                    <select name="school_id" class="form-control basic-select2">
                                        <option value="">المدرسة</option>
                                        @foreach ($schools as $school)
                                            <option value="{{ $school->id }}" {{ request()->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
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
                                    <th>الاسم</th>
                                    <th>المدرس</th>

                                    <th>الصفوف الدرسية</th>
                                    <th>الطلاب</th>
                                    <th>تاريخ الاضافة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_grade as $index => $grade)
                                    <tr>
                                        <td>{{ $grade->id }}</td>
                                        <td>{{ $grade->name }}</td>

                                        <td style="min-width:50px">
                                            <a class="dropdown-item" href="{{route('dashboard.schools.index',['grade_id' => $grade->id])}}"

                                                style="padding: 6px;" title="show-grades">
                                           <i class="fa fa-school"></i>  ( {{ $grade->schools->count() }} ) </a>
                                        </td>
                                        <td style="min-width:50px">
                                        
                                            <a class="dropdown-item" href="{{route('dashboard.classrooms.index',['grade_id' => $grade->id, 'school_id' => request()->school_id != '' ? request()->school_id : ''])}}"
                                                style="padding: 6px;" title="show-grades">
                                           <i class="fa fa-building"></i>  ( {{ $grade->classroomg->count() }} ) </a>
                                        </td>
                                        <td style="min-width:50px">
                                        
                                            <a class="dropdown-item" href="{{route('dashboard.students.index',['grade_id' => $grade->id, 'school_id' => request()->school_id != '' ? request()->school_id : ''])}}"
                                                style="padding: 6px;" title="show-grades">
                                           <i class="fa fa-user"></i>  ( {{ $grade->students->count() }} ) </a>
                                        </td>
                                        <td>{{ $grade->created_at }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        {{ $all_grade ->links('vendor.pagination.bootstrap-4') }}
    </section>
@endsection

@push('page_scripts_vendors')
@endpush

@push('page_scripts')
@endpush

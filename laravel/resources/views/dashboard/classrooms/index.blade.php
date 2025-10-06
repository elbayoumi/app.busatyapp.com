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
                        <h4 class="card-title">قائمة / الصفوف الدرسية</h4>
                    </div>
                    <div class="card-header border-bottom p-1">

                        <form action="{{ route('dashboard.classrooms.index') }}" method="GET" style="min-width: 100%">
                            <div class="row">

                                <div class="col-md-3  my-50">

                                    <input type="text" class="form-control" placeholder="الاسم"
                                        value="{{ request()->text }}" name="text" id="text"/>
                                </div>

                                <div class="col-md-3 my-50">
                                    <select name="school_id" class="form-control basic-select2">
                                        <option value="">المدرسة</option>
                                        @foreach ($schools as $school)
                                            <option value="{{ $school->id }}" {{ request()->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
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
                                    <th>اسم الصف</th>
                                    <th>المدرسة</th>
                                    <th>المرحلة الدرسية</th>
                                    <th>تاريخ الاضافة</th>
                                    <th>الاعدادت</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($all_classrooms))
                                @foreach ($all_classrooms as $index => $class)
                                <tr>
                                    <td>{{ $class->id }}</td>
                                    <td>{{ $class->name }}</td>
                                    <td>{{ $class->school->name }}</td>
                                    <td>{{ $class->grade->name }}</td>

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
                                @endif

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        {{ $all_classrooms ->links('vendor.pagination.bootstrap-4') }}
    </section>
@endsection

@push('page_scripts_vendors')
@endpush

@push('page_scripts')
@endpush

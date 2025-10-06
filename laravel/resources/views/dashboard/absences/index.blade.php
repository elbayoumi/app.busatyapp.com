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
    <section id="multilingual-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header border-bottom p-2">
                        <h4 class="card-title">قائمة / طلبات غياب الطلاب</h4>
                    </div>

                    <div class="card-header border-bottom p-1">


                        <form action="{{ route('dashboard.absences.index') }}" method="GET" style="min-width: 100%">
                            <div class="row">

                                <div class="col-md-3  my-50">

                                    <input type="text" class="form-control" placeholder="الاسم"
                                        value="{{ request()->text }}" name="text" id="text" />
                                </div>
                                <div class="col-md-3 my-50">
                                    <select name="school_id" class="form-control basic-select2">
                                        <option value="">المدرسة</option>
                                        @foreach ($schools as $school)
                                            <option value="{{ $school->id }}"
                                                {{ request()->school_id == $school->id ? 'selected' : '' }}>
                                                {{ $school->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 my-50">
                                    <select name="bus_id" class="form-control basic-select2">
                                        <option value="">الباص</option>
                                        @foreach ($buses as $bus)
                                            <option value="{{ $bus->id }}"
                                                {{ request()->bus_id == $bus->id ? 'selected' : '' }}>{{ $bus->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 my-50">
                                    <select name="my__parent_id" class="form-control basic-select2">
                                        <option value="">ولي الامر صاحب الطلب</option>
                                        @foreach ($parents as $parent)
                                            <option value="{{ $parent->id }}"
                                                {{ request()->my__parent_id == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 my-50">
                                    <select name="attendence_type" class="form-control basic-select2">
                                        <option value="">فترة الغياب</option>
                                        <option value="full_day"
                                            {{ request()->attendence_type == 'full_day' ? 'selected' : '' }}>اليوم كامل
                                        </option>
                                        <option value="end_day"
                                            {{ request()->attendence_type == 'end_day' ? 'selected' : '' }}>نهاية كامل
                                        </option>
                                        <option value="start_day"
                                            {{ request()->attendence_type == 'start_day' ? 'selected' : '' }}>بداية كامل
                                        </option>

                                    </select>
                                </div>
                                <div class="col-md-3 my-50">
                                    <input id="attendence_date" type="text" class="form-control birthdate-picker"
                                        value="{{ request()->attendence_date }}" name="attendence_date"
                                        id="attendence_date" autocomplete="off" placeholder="تاريخ الغياب" />
                                </div>
                                <div class="col-md-3 my-50">
                                    <input id="created_at" type="text" class="form-control birthdate-picker"
                                        value="{{ request()->created_at }}" name="created_at" id="created_at"
                                        autocomplete="off" placeholder="تاريخ ارسال الطلب" />
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
                                    <th> الاسم</th>
                                    <th>تاريخ الغياب</th>
                                    <th>فترة الغياب</th>
                                    <th>ولي الامر</th>
                                    <th>الاعدادت</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($absences as $index => $absenc)
                                    <tr>
                                        <td>{{ $absenc->id }}</td>
                                        <td>{{ $absenc->students->name }}</td>
                                        <td>{{ $absenc->attendence_date }}</td>
                                        <td> <span class="text-info">{{ $absenc->tr_trip_type() }}</span></td>
                                        <td>
                                            @if ($absenc->parent)
                                                <a href="{{ route('dashboard.parents.show', $absenc->parent->id) }}"
                                                    class="text-primary">
                                                    {{ $absenc->parent->name }}
                                                </a>
                                            @else
                                                <span class="text-danger">لا يوجد ولي امر</span>
                                            @endif
                                        </td>
                                        <td style="min-width: 150px">

                                            @if (auth()->user()->canany(['super', 'absences-edit']))
                                                <button type="button" class="btn m-btn m-btn--hover-blog m-btn--icon"
                                                    data-toggle="modal" data-target="#exampleModal-{{ $absenc->id }}">
                                                    <i data-feather='edit'></i>
                                                </button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModal-{{ $absenc->id }}"
                                                    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <form
                                                            action="{{ route('dashboard.absences.update', $absenc->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('put')
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        {{ $absenc->students->name }}</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="col">
                                                                        <div class="form-group">
                                                                            <label for="attendence_date">تاريخ
                                                                                الغياب</label>
                                                                            <input id="attendence_date" type="text"
                                                                                class="form-control birthdate-picker"
                                                                                value="{{ $absenc->attendence_date }}"
                                                                                name="attendence_date"
                                                                                id="attendence_date" required
                                                                                autocomplete="off"
                                                                                placeholder="YYYY-MM-DD" />
                                                                        </div>
                                                                    </div>

                                                                    <div class="col">
                                                                        <div class="form-group">
                                                                            <label for="attendence_type">فترة
                                                                                الغياب</label>
                                                                            <select class="form-control"
                                                                                name="attendence_type"
                                                                                id="attendence_type" required>
                                                                                <option value="" disabled> اختر فترة
                                                                                    الغياب</option>
                                                                                <option value="start_day"
                                                                                    @if ($absenc->attendence_type == 'start_day') selected @endif>
                                                                                    :: بداية اليوم ::</option>
                                                                                <option value="end_day"
                                                                                    @if ($absenc->attendence_type == 'end_day') selected @endif>
                                                                                    :: نهاية اليوم ::</option>
                                                                                <option value="full_day"
                                                                                    @if ($absenc->attendence_type == 'full_day') selected @endif>
                                                                                    :: اليوم كامل::</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                        class="btn btn-primary">حفظ</button>
                                                                </div>
                                                            </div>

                                                        </form>

                                                    </div>
                                                </div>
                                            @endif

                                            @if (auth()->user()->canany(['super', 'absences-show']))
                                                <a href="{{ route('dashboard.absences.show', $absenc->id) }}"
                                                    class="m-portlet__nav-link btn m-btn m-btn--hover-blog m-btn--icon m-btn--icon-only m-btn--pill"
                                                    style="padding: 6px;" title="Show">
                                                    <i data-feather='eye'></i>
                                                </a>
                                            @endif
                                            @if (auth()->user()->canany(['super', 'absences-destroy']))
                                                <a onclick="event.preventDefault();"
                                                    data-delete="delete-form-{{ $index }}"
                                                    href="{{ route('dashboard.absences.destroy', $absenc->id) }}"
                                                    class="m-portlet__nav-link btn m-btn m-btn--hover-blog m-btn--icon m-btn--icon-only m-btn--pill but_delete_action"
                                                    style="padding: 6px;" title="Delete">
                                                    <i data-feather='trash'></i>
                                                </a>
                                                <form id="delete-form-{{ $index }}"
                                                    action="{{ route('dashboard.absences.destroy', $absenc->id) }}"
                                                    method="POST" style="display: none;">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>
                                            @endif
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
    {{ $absences->links('vendor.pagination.bootstrap-4') }}
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

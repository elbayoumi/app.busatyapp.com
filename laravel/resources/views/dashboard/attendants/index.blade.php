@extends('dashboard.layouts.app')
@push('page_vendor_css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">

@endpush
@push('page_styles')
@endpush
@section('content')
    <section id="multilingual-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{-- <div class="card-header border-bottom">
                        <h4 class="card-title">{{$headPage}}</h4>
                        @if ($form == true)
                        <div class="col-md-4">
                            <form action="{{ route('dashboard.attendants.' . $routeName, $sclId) }}" method="GET">

                                <div class="row">
                                    <div class="form-group col">

                                        <input type="text" class="form-control" placeholder="بحث باستخدام اسم المدرسة<"
                                            value="{{ old('text') }}" name="text" id="text" required />

                                    </div>

                                    <div class="form-group col">

                                        <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">
                                            بحث</button>

                                    </div>
                                </div>


                            </form>

                        </div>
                        @else
                        <div class="col-md-4">
                            <form action="{{ route('dashboard.attendants.index') }}" method="GET">

                                <div class="row">
                                    <div class="form-group col">

                                        <input type="text" class="form-control" placeholder="بحث باستخدام اسم المدرسة<"
                                            value="{{ old('text') }}" name="text" id="text" required />

                                    </div>

                                    <div class="form-group col">

                                        <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">
                                            بحث</button>

                                    </div>
                                </div>


                            </form>

                        </div>
                        @endif

                    </div> --}}
                    <div class="card-header border-bottom p-2">
                        <h4 class="card-title">قائمة / المرافقين</h4>
                    </div>
                    <div class="card-header border-bottom p-1">



                        <form action="{{ route('dashboard.attendants.index') }}" method="GET" style="min-width: 100%">
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
                                    <select name="type" class="form-control basic-select2">
                                        <option value="">الوظيفة</option>
                                        <option value="drivers" {{ request()->type == 'drivers' ? 'selected' : '' }}>السائقين</option>
                                        <option value="admins" {{ request()->type ==  'admins' ? 'selected' : '' }}>المشرفين</option>

                                    </select>
                                </div>

                                <div class="col-md-3 my-50">
                                    <select name="status" class="form-control basic-select2">
                                        <option value="">الحالة</option>
                                        <option value="unactive" {{ request()->status == 'unactive' ? 'selected' : '' }}>غير مفعل</option>
                                        <option value="active" {{ request()->status ==  'active' ? 'selected' : '' }}>مفعل</option>

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
                                    <th> الصورة</th>
                                    <th> الاسم</th>
                                    <th> المدرسة</th>
                                    <th> الوظيفة</th>
                                    <th> الباص</th>
                                    {{-- <th> الطلاب</th> --}}
                                    <th>تاريخ الاضافة</th>
                                    <th>الاعدادت</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_Attendant as $index => $attendant)
                                    <tr>
                                        <td>{{ $attendant->id }}</td>
                                        <td>
                                            <img width="60"
                                             src="{{ $attendant?->logo_path }}"
                                            class="m--img-rounded m--marginless" alt="cover">

                                        </td>
                                        <td>{{ $attendant?->name }}</td>
                                        <td> <a href="{{ route('dashboard.schools.show',$attendant?->schools?->id) }}">{{ $attendant?->schools?->name }}</a> </td>
                                        <td>{{ $attendant->type }}</td>


                                        <td>{{  $attendant?->bus?->name }}</td>

                                        <td>{{ $attendant?->created_at }}</td>



                                        <td>
                                        <div class="dropdown">
                                            <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                العمليات
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">


                                                @if ($attendant->bus != null)
                                                @if (auth()->user()->canany(['super', 'trips-create']))
                                                <a href="{{ route('dashboard.trips.create',$attendant->id) }}"
                                                    class="dropdown-item"
                                                    style="padding: 6px;" title="create">
                                                    <i data-feather='plus'></i>
                                                    اضافة رحلة جديدة
                                               </a>
                                               @endif
                                                @endif

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
        </div>
        {{ $all_Attendant->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}

    </section>
@endsection

@push('page_scripts_vendors')
<script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>

@endpush

@push('page_scripts')
<script>
            $(document).ready(function() {
            $('.basic-select2').select2();
        });
</script>
@endpush

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

                <div class="card">
                    <div class="card-header border-bottom p-2">
                        <h4 class="card-title">قائمة / الرحلات</h4>

                    </div>
                    <div class="card-header border-bottom p-1">


                        <form action="{{ route('dashboard.trips.index') }}" method="GET" style="min-width: 100%">
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
                                    <select name="bus_id" class="form-control basic-select2">
                                        <option value="">الباص</option>
                                        @foreach ($buses as $bus)
                                            <option value="{{ $bus->id }}" {{ request()->bus_id == $bus->id ? 'selected' : '' }}>{{ $bus->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 my-50">
                                    <select name="trip_type" class="form-control">
                                        <option value="">نوع الرحلة</option>
                                            <option value="start_day" {{ request()->trip_type == 'start' ? 'selected' : '' }}>بداية اليوم</option>
                                            <option value="end_day" {{ request()->trip_type == 'end' ? 'selected' : '' }}>نهاية اليوم</option>

                                    </select>
                                </div>

                                <div class="col-md-3 my-50">
                                    <select name="status" class="form-control">
                                        <option value="">حالة الرحلة</option>
                                            <option value="completed" {{ request()->status == 'completed' ? 'selected' : '' }}>مكتملة</option>
                                            <option value="not_complete" {{ request()->status == 'completed' ? 'not_complete' : '' }}>غير مكتملة</option>

                                    </select>
                                </div>

                                <div class="col-md-3 my-50">
                                    <input id="trips_date" type="text" class="form-control birthdate-picker" value="{{ request()->trips_date  }}" name="trips_date" id="trips_date"   autocomplete="off" placeholder="تاريخ الرحلة" />
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
                                        <th>نوع الرحلة</th>
                                        <th>حالة الرحلة</th>
                                        <th>عرض علي الخريطة</th>
                                        <th>تاريخ الرحلة</th>
                                        <th>الاعدادت</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trips as $index => $trip)
                                        <tr>
                                            <td>{{ $trip->id }}</td>
                                            <td>{{ $trip->tr_trip_type() }}</td>
                                            <td class="text-{{ $trip->tr_status()['color'] }}">{{ $trip->tr_status()['text']}}</td>
                                            <td>
                                                <a target="_blank"
                                                   href="{{ route('dashboard.trips.map', $trip->id) }}"
                                                   class="{{ $trip->status->value == 0 ? 'text-success' : 'text-secondary' }}"
                                                   title="{{ $trip->status->value == 0 ? 'الرحلة جارية - اضغط لعرض الخريطة' : 'الرحلة منتهية - عرض الخريطة فقط' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="icon icon-tabler icon-tabler-map-pin{{ $trip->status->value != 0 ? '-off' : '' }}"
                                                         width="24" height="24" viewBox="0 0 24 24"
                                                         stroke-width="2" stroke="currentColor" fill="none"
                                                         stroke-linecap="round" stroke-linejoin="round">
                                                        @if($trip->status->value == 0)
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path d="M12 11m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                                                            <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"></path>
                                                        @else
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path d="M3 3l18 18"></path>
                                                            <path d="M9.44 9.435a3 3 0 0 0 4.126 4.124m1.434 -2.559a3 3 0 0 0 -3 -3"></path>
                                                            <path d="M8.048 4.042a8 8 0 0 1 10.912 10.908m-1.8 2.206l-3.745 3.744a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 0 1 -.48 -10.79"></path>
                                                        @endif
                                                    </svg>
                                                </a>
                                            </td>

                                            <td style="min-width: 200px">{{ \Carbon\Carbon::parse($trip->trips_date)->format('Y-m-d') }}</td>
                                            <td style="min-width: 200px">
                                                <div class="dropdown">
                                                    <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        العمليات
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                    @if($trip->status->value == 0)

                                                    @if (auth()->user()->canany(['super', 'trips-create']))
                                                        <a href="{{ route('dashboard.attendances.create',$trip->id) }}"
                                                            class="dropdown-item"
                                                            style="padding: 6px;" title="create">
                                                            <i data-feather='plus'></i>
                                                            اضف غياب الرحلة
                                                        </a>
                                                    @endif

                                                    @if (auth()->user()->canany(['super', 'trips-create']))
                                                    <a href="{{ route('dashboard.trips.end',$trip->id) }}"
                                                        class="dropdown-item"
                                                        style="padding: 6px;" title="create">
                                                        <i data-feather='send'></i>
                                                        انهاء الرحلة
                                                    </a>
                                                @endif

                                                    @endif
                                                    @if (auth()->user()->canany(['super', 'trips-show']))
                                                        <a href="{{ route('dashboard.trips.show',$trip->id) }}"
                                                            class="dropdown-item"
                                                            style="padding: 6px;" title="show">
                                                            <i data-feather='eye'></i>
                                                            عرض بيانات الرحلة
                                                        </a>
                                                    @endif
                                                    @if (auth()->user()->canany(['super', 'trips-edit']))
                                                        <a href="{{ route('dashboard.trips.edit',$trip->id) }}"
                                                            class="dropdown-item"
                                                            style="padding: 6px;" title="edit">
                                                            <i data-feather='edit'></i>
                                                            تعديل بيانات الرحلة
                                                        </a>
                                                    @endif
                                                    @if(auth()->user()->canany(['super', 'trips-destroy']))
                                                    <a onclick="event.preventDefault();" data-delete="delete-form-{{$index}}" href="{{ route('dashboard.addresses.destroy', $trip->id) }}" class="m-portlet__nav-link btn m-btn m-btn--hover-blog m-btn--icon m-btn--icon-only m-btn--pill but_delete_action" style="padding: 6px;" title="Delete">
                                                        <i data-feather='trash'></i>
                                                        حذف بيانات الرحلة
                                                    </a>
                                                    <form id="delete-form-{{$index}}" action="{{ route('dashboard.trips.destroy', $trip->id) }}" method="POST" style="display: none;">
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
    {{ $trips->links('vendor.pagination.bootstrap-4') }}
@endsection

@push('page_scripts_vendors')
    <script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
@endpush

@push('page_scripts')
    <script src="{{ asset('assets/dashboard') }}/app-assets/js/scripts/pages/app-user-edit.min.js"></script>
    <script src="{{ asset('assets/dashboard') }}/app-assets/js/scripts/components/components-navs.min.js"></script>
    <script>
// document.querySelectorAll("td").style.minWidth = "400px";
// const nodeList = document.querySelectorAll("td");
// nodeList.style.backgroundColor = "red";
    </script>
@endpush

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
    <!-- users edit start -->
    <section class="app-user-edit">
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title"> <span>اضافة / غياب باص :</span> <span>{{ $trip->bus->name }}</span></h4>
                <h5 style="font-family: 'Cairo', sans-serif;color: red"> تاريخ اليوم : {{ date('d - m - Y') }}</h5>

            </div>
            <div class="card-body">

                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                    <!-- users edit account form start -->
                    <form method="post" action="{{ route('dashboard.attendances.store', $trip->id) }}">
                        @csrf

                        <div class="row">
                            <div class="table-responsive">

                                <table class="dt-multilingual table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>اسم الطالب</th>
                                            <th>العنوان</th>
                                            <th>الحضور والغياب</th>
                                            <th>الاعدادات</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach (\App\Models\Student::where('bus_id', $trip->bus_id)->whereIn('trip_type', attendence_absence_type($trip->trip_type))->get() as $index => $student)
                                            <tr class="@if (in_array($student->id, $absence_ids)) bg-light-warning @endif">
                                                <td>{{ $student->id }}</td>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->address }}</td>

                                                <td style="min-width: 200px">
                                                    <div class="row">

                                                        @if (isset(
                                                                $student->attendance()->where('trip_id', $trip->id)->first()->student_id))
                                                            <label
                                                                class="col  text-gray-500 font-semibold sm:border-r sm:pr-4">
                                                                <input name="attendences[{{ $student->id }}]" disabled
                                                                    {{ $student->attendance()->where('trip_id', $trip->id)->first()->attendence_status != 0? 'checked': '' }}
                                                                    class="leading-tight" type="radio" value="presence">
                                                                <span class="text-success">حضور</span>
                                                            </label>

                                                            <label class="col  text-gray-500 font-semibold">
                                                                <input name="attendences[{{ $student->id }}]" disabled
                                                                    {{ $student->attendance()->where('trip_id', $trip->id)->first()->attendence_status == 0? 'checked': '' }}
                                                                    class="leading-tight" type="radio" value="absent">
                                                                <span class="text-danger">غياب</span>
                                                            </label>
                                                        @else
                                                            <label
                                                                class="col  text-gray-500 font-semibold sm:border-r sm:pr-4">
                                                                <input name="attendences[{{ $student->id }}]"
                                                                    class="leading-tight" type="radio" value="presence">
                                                                <span class="text-success">حضور</span>
                                                            </label>

                                                            <label class="col  text-gray-500 font-semibold">
                                                                <input name="attendences[{{ $student->id }}]"
                                                                    class="leading-tight" type="radio" value="absent">
                                                                <span class="text-danger">غياب</span>
                                                            </label>
                                                        @endif
                                                        <input type="hidden" name="student_id[]"
                                                            value="{{ $student->id }}">
                                                    </div>


                                                </td>

                                                <td>
                                                    @if (auth()->user()->canany(['super', 'trips-create']))
                                                    <a href="{{ route('dashboard.students.attendants.send.messgees',$student->id) }}"
                                                        class="dropdown-item"
                                                        style="padding: 6px;" title="create">
                                                        <i data-feather='send'></i>
                                                         ارسال الي ولي الامر
                                                    </a>
                                                @endif
                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>







                                <div class="col mt-1">
                                    <button class="btn btn-success" type="submit">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-device-floppy" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path
                                                    d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2">
                                                </path>
                                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                                <path d="M14 4l0 4l-6 0l0 -4"></path>
                                            </svg>
                                        </span>
                                        <span>حفظ</span>
                                    </button>
                                    <a class="btn btn-primary sm" href="{{ route('dashboard.trips.show', $trip->id) }}">
                                        <span>
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-arrow-back" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1"></path>
                                                </svg>
                                            </span>
                                        </span>
                                        <span>
                                            العودة الي الرحلة
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- users edit account form ends -->
                </div>
                <!-- Account Tab ends -->

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

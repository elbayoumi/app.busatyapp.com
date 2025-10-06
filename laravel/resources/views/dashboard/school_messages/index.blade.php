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
    <section id="multilingual-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom p-2">
                        <h4 class="card-title">قائمة / رسائل المدرسة الي اولياء الامور</h4>
                    </div>

                         <div class="card-header border-bottom p-1">

                    
                        <form action="{{ route('dashboard.school_messages.index') }}" method="GET" style="min-width: 100%">
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
                                        <input id="event_date" type="text" class="form-control birthdate-picker" value="{{ request()->event_date  }}" name="event_date" id="event_date"   autocomplete="off" placeholder="تاريخ الحدث" />
                                </div>
                                <div class="col-md-3 my-50">
                                    <input id="created_at" type="text" class="form-control birthdate-picker" value="{{ request()->created_at  }}" name="created_at" id="created_at"   autocomplete="off" placeholder="تاريخ الرسال " />
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
                                    <th> اسم المدرسة </th>
                                    <th>عنوان الحدث</th>
                                    <th> تاريخ الحداث </th>
                                    <th> تاريخ الاضافة</th>
                                    <th>الاعدادت</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($messages as $index => $message)
                                    <tr>
                                        <td>{{ $message->id }}</td>
                                        <td>{{ $message->schools->name }}</td>
                                        <td>{{ $message->name }}</td>
                                        <td>{{ $message->event_date }}</td>
                                        <td>{{ $message->created_at->diffForHumans()}}</td>
                                        <td>
                                            @if(auth()->user()->canany(['super', 'school_messages-show']))
                                            <a href="{{ route('dashboard.school_messages.show', $message->id) }}" class="m-portlet__nav-link btn m-btn m-btn--hover-blog m-btn--icon m-btn--icon-only m-btn--pill" style="padding: 6px;" title="Show">
                                                <i data-feather='eye'></i>
                                            </a>
                                        @endif
                                        @if(auth()->user()->canany(['super', 'school_messages-destroy']))
                                            <a onclick="event.preventDefault();" data-delete="delete-form-{{$index}}" href="{{ route('dashboard.school_messages.destroy', $message->id) }}" class="m-portlet__nav-link btn m-btn m-btn--hover-blog m-btn--icon m-btn--icon-only m-btn--pill but_delete_action" style="padding: 6px;" title="Delete">
                                                <i data-feather='trash'></i>
                                            </a>
                                            <form id="delete-form-{{$index}}" action="{{ route('dashboard.school_messages.destroy', $message->id) }}" method="POST" style="display: none;">
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
        {{ $messages ->links('vendor.pagination.bootstrap-4') }}
    </section>
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


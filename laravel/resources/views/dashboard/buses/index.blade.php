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
                        <h4 class="card-title"> قائمة /  الباصات </h4>
                    </div>
                    <div class="card-header border-bottom p-1">

                        <form action="{{ route('dashboard.buses.index') }}" method="GET" style="min-width: 100%">
                            <div class="row">

                                <div class="col-md-3  my-50">

                                    <input type="text" class="form-control" placeholder="الاسم"
                                        value="{{ request()->text }}" name="text" id="text"/>
                                </div>

                                <div class="col-md-3 my-50">
                                    <select name="bus_number" class="form-control basic-select2">
                                        <option value="">رقم الباص</option>
                                        @foreach ($all_buses as $bus)
                                            <option value="{{ $bus->id }}" {{ request()->bus_number == $bus->id ? 'selected' : '' }}>{{ $bus->car_number }}</option>
                                        @endforeach
                                    </select>
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
                                    <th> وصف الباص </th>
                                    <th> اسم المدرسة </th>
                                    <th>رقم الباص </th>
                                    <th> الطلاب </th>
                                    <th> تاريخ الاضافة</th>
                                    <th>الاعدادت</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_buses as $index => $buses)
                                    <tr>
                                        <td>{{ $buses->id }}</td>


                                        <td>{{ $buses->name }}</td>
                                        <td> <a href="{{ route('dashboard.schools.show',$buses?->schools?->id) }}">{{ $buses?->schools?->name }}</a> </td>

                                        <td>{{ $buses->car_number }}</td>


                                        <td style="min-width:50px">
                                            @if (auth()->user()->canany(['super', 'students-show']))

                                            <a class="dropdown-item"
                                                href="{{ route('dashboard.students.index', ['bus_id' => $buses->id]) }}"
                                                style="padding: 6px;" title="show-students">
                                                <i class="fa fa-user"></i> ( {{ $buses->students->count() }} ) </a>
                                             @endif

                                            </td>



                                        <td>{{ $buses->created_at->diffForHumans() }}</td>


                                        <div class="modal fade" id="exampleModal-{{$buses->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ route('dashboard.attendances.index', $buses->id) }}" method="post">
                                                  @csrf
                                                  @method('post')
                                                    <div class="modal-content">
                                                <div class="modal-header">
                                                  <h5 class="modal-title" id="exampleModalLabel">  بيانات رحلة الصباح</h5>
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                  </button>
                                                </div>
                                                <div class="modal-body">

                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="latitude">latitude</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="latitude" name="latitude"
                                                                    id="latitude"  />
                                                            </div>
                                                        </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="longitude">longitude</label>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="longitude" name="longitude"
                                                                        id="longitude" />
                                                                </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                  <button type="submit" class="btn btn-primary">حفظ</button>
                                                </div>
                                                </div>

                                                </form>

                                            </div>
                                          </div>

                                           {{-- end model --}}
                                          <div class="modal fade" id="endModal-{{$buses->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ route('dashboard.attendances.end', $buses->id) }}" method="post">
                                                  @csrf
                                                  @method('post')
                                                    <div class="modal-content">
                                                <div class="modal-header">
                                                  <h5 class="modal-title" id="exampleModalLabel">بيانات رحلة المساء</h5>
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                  </button>
                                                </div>
                                                <div class="modal-body">

                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="latitude">latitude</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="latitude" name="latitude"
                                                                    id="latitude"  />
                                                            </div>
                                                        </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="longitude">longitude</label>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="latitude" name="longitude"
                                                                        id="longitude"  />
                                                                </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                  <button type="submit" class="btn btn-primary">حفظ</button>
                                                </div>
                                                </div>

                                                </form>

                                            </div>
                                          </div>

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
                                                        <a href="{{ route('dashboard.buses.show', $buses->id) }}"
                                                            class="dropdown-item" style="padding: 6px;" title="show">
                                                            <i data-feather='eye'></i>
                                                            عرض بيانات الباص
                                                        </a>
                                                    @endif

                                                    @if (auth()->user()->canany(['super', 'buses-edit']))
                                                        <a href="{{ route('dashboard.buses.edit', $buses->id) }}"
                                                            class="dropdown-item" style="padding: 6px;" title="Edit">
                                                            <i data-feather='edit'></i>
                                                            تعديل بيانات الباص
                                                        </a>
                                                    @endif

                                                    @if (auth()->user()->canany(['super', 'buses-destroy']))
                                                        <a onclick="event.preventDefault();"
                                                            data-delete="delete-form-{{ $index }}"
                                                            href="{{ route('dashboard.buses.destroy', $buses->id) }}"
                                                            class="dropdown-item but_delete_action" style="padding: 6px;"
                                                            title="Delete">
                                                            <i data-feather='trash'></i>
                                                            حذف بيانات الباص

                                                        </a>
                                                        <form id="delete-form-{{ $index }}"
                                                            action="{{ route('dashboard.buses.destroy', $buses->id) }}"
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
        {{ $all_buses->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}

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
    <script>
    $(document).ready(function() {
        $('.basic-select2').select2();
    });
    </script>

@endpush

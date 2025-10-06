@extends('dashboard.layouts.app')
@push('page_vendor_css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">
@endpush
@push('page_styles')
@endpush
@section('content')
    <section id="multilingual-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header border-bottom p-2">
                        <h4 class="card-title">قائمة / الاعلانات</h4>
                    </div>
                    <div class="card-header border-bottom p-1">


                        <form action="{{ route('dashboard.adesSchool.index') }}" method="GET" style="min-width: 100%">
                            <div class="row">

                                <div class="col-md-3  my-50">


                                    <select id="select_page" class="form-control operator" name="parant_id">
                                        <option value="" selected> المدارس</option>
                                        @foreach ($allSchool as $value)
                                            <option value="{{ $value->id }}">
                                                {{ $value->id . ' - ' . $value->email }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3  my-50">

                                    <input type="text" class="form-control" placeholder="بحث"
                                        value="{{ request()->text }}" name="text" id="text" />
                                </div>
                                <div class="col-md-3  my-50">

                                    <input type="date" class="form-control" placeholder="بحث"
                                        value="" name="date" id="date" />
                                </div>

                                {{-- <div class="col-md-3 my-50">
                                    <select name="status" class="form-control basic-select2">
                                        <option value="">الحالة</option>
                                        <option value="unactive" {{ request()->status == 'unactive' ? 'selected' : '' }}>غير مفعل</option>
                                        <option value="active" {{ request()->status ==  'active' ? 'selected' : '' }}>مفعل</option>

                                    </select>
                                </div> --}}
                                <div class="col-md-3 my-50">
                                    <button type="submit" class="btn btn-primary btn-block">بحث</button>
                                </div>
                                <div class="col-md-3 my-50">
                                    <a href="{{ route('dashboard.adesSchool.create') }}"
                                        class="btn btn-success btn-block">اضافة</a>
                                </div>


                            </div>



                        </form>

                    </div>

                    <div class="table-responsive min-height-17rem">
                        <table class="dt-multilingual table text-center">
                            <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <th> العنوان</th>
                                    {{-- <th> المدرسة</th> --}}
                                    <th> صورة</th>

                                    <th>تاريخ الانشاء</th>
                                    <th>تاريخ التعديل</th>
                                    <th>عدد المدارس</th>
                                    <th>اضافة المدارس</th>
                                    <th>الاعدادت</th>

                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($ades as $index => $ades)
                                    <tr>
                                        <td> @isset($ades->id)
                                                {{ $ades->id }}
                                            @else
                                                undefined
                                            @endisset
                                        </td>

                                        <td><a href="{{ $ades?->link }}" class="btn btn-success btn-sm "
                                                target="_blank">{{ $ades?->title }}</a> </td>
                                        {{-- <td>{{ $subscrip->schools->name }}</td> --}}.

                                        <td><img class="w-25 rounded-circle" src="{{ $ades?->image_path }}"
                                                alt="{{ $ades?->alt }}"></td>


                                        <td>{{ $ades?->created_at }}</td>
                                        <td>{{ $ades?->updated_at }}</td>
                                        <td><a href="{{ $ades?->adesSchool?->count() ? route('dashboard.adesSchool.show', $ades?->id) : '#' }}"
                                                class="btn btn-success btn-sm ">{{ $ades?->adesSchool?->count() }}</a></td>

                                        <td><!-- Button trigger modal -->
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#exampleModal{{ $ades?->id }}">
                                                اضف
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal{{ $ades?->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form action="{{ route('dashboard.schools.index') }}" method="GET" style="min-width: 100%">

                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">توجيه الاعلان</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <input type="hidden" name="adesId"
                                                                value="{{ $ades->id }}">
                                                                <select id="ades_to" class="form-control operator" name="ades_to">
                                                                    <option value="all" selected> الكل</option>
                                                                    <option value="school" > المدارس</option>
                                                                    <option value="parent" > اولياء الامور</option>
                                                                    <option value="attendance" > المشرفين والسائقين</option>
                                                                </select>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary"><i data-feather='users'></i>
                                                                اذهب لاضافة المدارس</button>
                                                        </div>
                                                    </div>
                                                </form>

                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-success btn-sm dropdown-toggle" href="#"
                                                    role="button" id="dropdownMenuLink" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    العمليات
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right"
                                                    aria-labelledby="dropdownMenuLink">




                                                    @if (auth()->user()->canany(['super', 'attendants-show']))
                                                        <a href="{{ route('dashboard.adesSchool.show', $ades->id) }}"
                                                            class="dropdown-item" style="padding: 6px;" title="show">
                                                            <i data-feather='eye'></i>
                                                            عرض
                                                        </a>
                                                    @endif


                                                    @if (auth()->user()->canany(['super', 'attendants-edit']))
                                                        <a href="{{ route('dashboard.adesSchool.edit', $ades->id) }}"
                                                            class="dropdown-item" style="padding: 6px;" title="Edit">
                                                            <i data-feather='edit'></i>
                                                            تعديل
                                                        </a>
                                                    @endif

                                                    @if (auth()->user()->canany(['super', 'attendant-destroy']))
                                                        <a onclick="event.preventDefault();"
                                                            data-delete="delete-form-{{ $index }}"
                                                            href="{{ route('dashboard.adesSchool.destroy', $ades->id) }}"
                                                            class="dropdown-item but_delete_action" style="padding: 6px;"
                                                            title="Delete">
                                                            <i data-feather='trash'></i>
                                                            حذف

                                                        </a>
                                                        <form id="delete-form-{{ $index }}"
                                                            action="{{ route('dashboard.adesSchool.destroy', $ades->id) }}"
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
        {{-- {{ $subscription->appends(request()->query())->links('vendor.pagination.bootstrap-4') }} --}}

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

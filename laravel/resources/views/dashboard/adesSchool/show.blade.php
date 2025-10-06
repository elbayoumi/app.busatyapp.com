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
                        <h4 class="card-title">قائمة / اعلان {{ $mainAdes?->title }} </h4>
                        {{-- <p class="card-title">{{ $mainAdes?->title }}</p> --}}
                        {{-- <a href="{{ route('dashboard.adesSchool.show',$mainAdes->id) }}">

                            <img class="card-title rounded-circle" src="{{ $mainAdes?->image_path }} " alt="">

                        </a> --}}
                    </div>
                    <div class="row p-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">title </label>
                                <input disabled type="text" class="form-control" placeholder="title"
                                    value="{{ $mainAdes?->title }}" name="title" id="title" />
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="body">body </label>
                                <input disabled type="text" class="form-control" placeholder="body" name="body"
                                    value="{{ $mainAdes?->body }}" id="body" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="link">link </label>
                                <input disabled type="text" class="form-control" placeholder="link" name="link"
                                    value="{{ $mainAdes?->link }}" id="link" />
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="alt">alt </label>
                                <input disabled type="text" class="form-control" placeholder="alt" name="alt"
                                    value="{{ $mainAdes?->alt }}" id="alt" />
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <img class="w-25 rounded-circle" src="{{ $mainAdes?->image_path }}"
                                    alt="{{ $mainAdes?->alt }}">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-header border-bottom p-1">
                    <div class="row">


                        <form action="{{ route('dashboard.adesSchool.index') }}" method="GET" class="col-md-9"
                            style="min-width: 100%">
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

                                    <input type="text" class="form-control" placeholder="email"
                                        value="{{ request()->email }}" name="email" id="email" />
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
                                {{-- <div class="col-md-3 my-50">
                                        <a href="{{ route('dashboard.adesSchool.create') }}" class="btn btn-success btn-block"> اضافة الكل</a>
                                        <a href="{{ route('dashboard.adesSchool.create') }}" class="btn btn-danger btn-block"> حذف الكل</a>
                                    </div> --}}


                            </div>



                        </form>
                    </div>

                    {{-- <div class="col-md-3 my-50"> --}}
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#exampleModal{{ $mainAdes?->id }}">
                        اضف مدرسة
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal{{ $mainAdes?->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="{{ route('dashboard.schools.index') }}" method="GET" style="min-width: 100%">

                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">توجيه الاعلان</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <input type="hidden" name="adesId" value="{{ $mainAdes->id }}">
                                        <select id="ades_to" class="form-control operator" name="ades_to">
                                            <option value="all" selected> الكل</option>
                                            <option value="school"> المدارس</option>
                                            <option value="parent"> اولياء الامور</option>
                                            <option value="attendance"> المشرفين والسائقين</option>
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


                    {{-- <form class="col-md-3" action="{{ route('dashboard.schools.index') }}" method="GET"
                            style="min-width: 100%">
                            <input type="hidden" name="adesId" value="{{ $mainAdes->id }}">
                            <button type="submit" class="btn btn-success btn-block" title="adesSchool">
                                <i data-feather='users'></i>
                                اضافة مدارس
                            </button>
                        </form> --}}
                    {{-- <a href="{{ route('dashboard.adesSchool.create') }}" class="btn btn-success btn-block"> اضافة </a> --}}
                    {{-- </div> --}}
                </div>

                <div class="table-responsive min-height-17rem">
                    <table class="dt-multilingual table text-center">
                        <thead>
                            <tr>
                                <th>المعرف</th>
                                <th>موجه</th>
                                <th> اسم المدرسة</th>
                                {{-- <th> المدرسة</th> --}}
                                <th>تاريخ الانشاء</th>
                                <th>تاريخ التعديل</th>
                                <th>الاعدادت</th>

                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($adesSchool as $index => $adesSchool)
                                <tr>

                                    <td> @isset($adesSchool->id)
                                            {{ $adesSchool->id }}
                                        @else
                                            undefined
                                        @endisset
                                    </td>
                                    <td> @isset($adesSchool->to)
                                            {{ $adesSchool->to }}
                                        @else
                                            undefined
                                        @endisset
                                    </td>
                                    {{-- @dd($adesSchool?->schools->name) --}}
                                    <td><a href="{{ route('dashboard.schools.show', $adesSchool?->schools?->id) }}"
                                            class="btn btn-success btn-sm ">{{ $adesSchool?->schools?->name }}</a> </td>
                                    {{-- <td>{{ $subscrip->schools->name }}</td> --}}.



                                    <td>{{ $adesSchool?->created_at }}</td>
                                    <td>{{ $adesSchool?->updated_at }}</td>



                                    <td>
                                        <div class="dropdown">
                                            {{-- <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button"
                                                id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                العمليات
                                            </a> --}}

                                            @if (auth()->user()->canany(['super', 'attendant-destroy']))
                                                <a onclick="event.preventDefault();"
                                                    data-delete="delete-form-{{ $index }}" {{-- @dd($ades?->schools->id) --}}
                                                    href="{{ route('dashboard.adesSchool.removeSchooleToAdes', $adesSchool->id) }}"
                                                    class="dropdown-item but_delete_action btn btn-success btn-sm "
                                                    style="padding: 6px;" title="Delete">
                                                    <i data-feather='trash'></i>
                                                    حذف

                                                </a>
                                                <form id="delete-form-{{ $index }}"
                                                    action="{{ route('dashboard.adesSchool.removeSchooleToAdes', $adesSchool->id) }}"
                                                    method="POST" style="display: none;">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>
                                            @endif
                                            <div class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="dropdownMenuLink">




                                                {{-- @if (auth()->user()->canany(['super', 'attendants-show']))
                                                    <a href="{{ route('dashboard.adesSchool.show', $adesSchool->id) }}"
                                                        class="dropdown-item" style="padding: 6px;" title="show">
                                                        <i data-feather='eye'></i>
                                                        عرض
                                                    </a>
                                                @endif --}}

                                                {{--
                                                @if (auth()->user()->canany(['super', 'attendants-edit']))
                                                    <a href="{{ route('dashboard.adesSchool.edit', $adesSchool->id) }}"
                                                        class="dropdown-item" style="padding: 6px;" title="Edit">
                                                        <i data-feather='edit'></i>
                                                        تعديل
                                                    </a>
                                                @endif --}}

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

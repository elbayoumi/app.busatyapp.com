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
                            <h4 class="card-title">قائمة / طلبات تغير عناوين الطلاب</h4>
                        </div>

                             <div class="card-header border-bottom p-1">


                            <form action="{{ route('dashboard.addresses.index') }}" method="GET" style="min-width: 100%">
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
                                        <select name="my__parent_id" class="form-control basic-select2">
                                            <option value="">ولي الامر صاحب الطلب</option>
                                            @foreach ($parents as $parent)
                                                <option value="{{ $parent->id }}" {{ request()->my__parent_id == $parent->id ? 'selected' : '' }}>{{$parent->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 my-50">
                                        <input id="created_at" type="text" class="form-control birthdate-picker" value="{{ request()->created_at  }}" name="created_at" id="created_at"   autocomplete="off" placeholder="تاريخ ارسال الطلب" />
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
                                        <th> العنوان الجدديد</th>
                                        <th>الحالة</th>
                                        <th>الاعدادت</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($addresses as $index => $address)
                                        <tr>
                                            <td>{{ $address->id }}</td>
                                            <td style="min-width: 200px">{{ $address?->students?->name }}</td>
                                            <td style="min-width: 200px">{{ $address->New_address }}</td>
                                            <td style="min-width: 200px " class="text-{{ $address->status_text['color'] }}">{{ $address->status_text['text']}}</td>
                                            <td style="min-width: 200px">
                                                <div class="dropdown ">
                                                    <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        العمليات
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">

                                                        @if ($address->status == 0 or $address->status == 3)

                                                        @if (auth()->user()->canany(['super', 'addresses-edit']))
                                                        <a href="{{ route('dashboard.addresses.accepted', $address->id) }}"
                                                            class="dropdown-item text-success"
                                                            style="padding: 6px;"  title="edit">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <path d="M5 12l5 5l10 -10"></path>
                                                             </svg>
                                                             قبول الطلب
                                                        </a>
                                                       @endif

                                                       @if (auth()->user()->canany(['super', 'addresses-edit']))
                                                       <a href="{{ route('dashboard.addresses.unaccepted', $address->id) }}"
                                                           class="dropdown-item text-danger"
                                                           style="padding: 6px;"  title="edit">
                                                           <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path d="M18 6l-12 12"></path>
                                                            <path d="M6 6l12 12"></path>
                                                         </svg>
                                                            رفض الطلب
                                                       </a>
                                                      @endif
                                                       @endif

                                                        @if (auth()->user()->canany(['super', 'addresses-show']))
                                                        <a href="{{ route('dashboard.addresses.show', $address->id) }}"
                                                            class="dropdown-item"
                                                            style="padding: 6px;" title="show">
                                                            <i data-feather='eye'></i>

                                                            عرض الطلب
                                                        </a>
                                                       @endif
                                                       @if (auth()->user()->canany(['super', 'addresses-edit']))
                                                       <a href="{{ route('dashboard.addresses.edit', $address->id) }}"
                                                           class="dropdown-item"
                                                           style="padding: 6px;" title="edit">
                                                           <i data-feather='edit'></i>

                                                           تعديل الطلب
                                                       </a>
                                                      @endif
                                                      @if(auth()->user()->canany(['super', 'addresses-destroy']))
                                                      <a onclick="event.preventDefault();" data-delete="delete-form-{{$index}}" href="{{ route('dashboard.addresses.destroy', $address->id) }}" class="m-portlet__nav-link btn m-btn m-btn--hover-blog m-btn--icon m-btn--icon-only m-btn--pill but_delete_action" style="padding: 6px;" title="Delete">
                                                          <i data-feather='trash'></i>
                                                          حذف الطلب
                                                      </a>
                                                      <form id="delete-form-{{$index}}" action="{{ route('dashboard.addresses.destroy', $address->id) }}" method="POST" style="display: none;">
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
    </section>
    {{ $addresses->links('vendor.pagination.bootstrap-4') }}
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

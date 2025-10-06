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
                        <h4 class="card-title">قائمة / اولياء الامور</h4>
                    </div>
                    <div class="card-header border-bottom p-1">

                        <form action="{{ route('dashboard.parents.index') }}" method="GET" style="min-width: 100%">
                            <div class="row">

                                <div class="col-md-3  my-50">

                                    <input type="text" class="form-control" placeholder="بحث"
                                        value="{{ request()->text }}" name="text" id="text"/>
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
                                    <th>صورة</th>
                                    <th> الاسم </th>
                                    {{-- <th> المدرسة </th> --}}
                                    <th> عدد الابناء </th>
                                    <th>عدد الكوبنات</th>
                                    <th> تاريخ الاضافة</th>
                                    <th>الاعدادت</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_Parent as $index => $parent)
                                    <tr>
                                        <td>{{ $parent->id }}</td>
                                        <td><img width="60"
                                            src="{{ $parent->logo_path }}"
                                           class="m--img-rounded m--marginless" alt="cover">
                                        </td>
                                        <td>{{ $parent->name }}</td>
                                        {{-- <td>{{ $parent?->schools?->name }}</td> --}}
                                            {{-- <a href="{{ route('dashboard.parents.child', $parent->id)}}"> <i class="fa fa-child"></i> ({{ $parent->students->count() }})</a> --}}
                                            <td style="min-width:50px">
                                                @if (auth()->user()->canany(['super', 'students-show']))

                                                <a class="dropdown-item" href="{{route('dashboard.students.index',['parent_id' => $parent->id])}}"
                                                      style="padding: 6px;" title="show-students">
                                                 <i class="fa fa-user"></i>  ( {{ $parent->students->count()  }} ) </a>
                                                 @endif

                                                </td>
                                                <td>
                                                    @if (auth()->user()->canany(['super', 'coupon-list']))
                                                        <a class="dropdown-item"
                                                            href="{{ route('dashboard.coupon.index', ['model' => 'parents', 'parent_id' => $parent->id]) }}"
                                                            style="padding: 6px;" title="show-coupons">
                                                            <i class="fa fa-ticket"></i> (
                                                            {{ $parent->myCoupons->count() }} ) </a>
                                                    @endif
                                                </td>

                                        <td>{{ $parent->created_at }}</td>

                                        <td>
                                        <div class="dropdown">
                                            <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                العمليات
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">


                                                @if (auth()->user()->canany(['super', 'parents-show']))
                                                <a href="{{ route('dashboard.parents.show',$parent->id) }}"
                                                    class="dropdown-item"
                                                    style="padding: 6px;" title="Show">
                                                    <i data-feather='show'></i>
                                                    عرض بيانات ولي الامر
                                                </a>
                                            @endif
                                            @if (auth()->user()->canany(['super', 'parents-edit']))
                                                <a href="{{ route('dashboard.parents.edit',$parent->id) }}"
                                                    class="dropdown-item"
                                                    style="padding: 6px;" title="Edit">
                                                    <i data-feather='edit'></i>
                                                    تعديل بيانات ولي الامر
                                                </a>
                                            @endif
                                            @if (auth()->user()->canany(['super', 'parents-edit']))
                                                <a href="{{ route('dashboard.parents.add-child',$parent->id) }}"
                                                    class="dropdown-item"
                                                    style="padding: 6px;" title="Edit">
                                                    <i data-feather='edit'></i>
                                                    اضف ابن
                                                </a>
                                            @endif
                                            @if (auth()->user()->canany(['super', 'parents-destroy']))
                                                <a onclick="event.preventDefault();"
                                                    data-delete="delete-form-{{ $index }}"
                                                    href="{{ route('dashboard.parents.destroy', $parent->id) }}"
                                                    class="dropdown-item but_delete_action"
                                                    style="padding: 6px;" title="Delete">
                                                    <i data-feather='trash'></i>
                                                    حذف بيانات ولي الامر

                                                </a>
                                                <form id="delete-form-{{ $index }}"
                                                    action="{{ route('dashboard.parents.destroy', $parent->id) }}"
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
        {{ $all_Parent->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}

    </section>
@endsection

@push('page_scripts_vendors')
@endpush

@push('page_scripts')
@endpush

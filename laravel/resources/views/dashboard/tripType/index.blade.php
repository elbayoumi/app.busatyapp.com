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
                        <h4 class="card-title">قائمة / انواع الرحاله الافتراضيه</h4>
                    </div>
                    <div class="card-header border-bottom p-1">

                        <form action="{{ route('dashboard.trip-type.index') }}" method="GET" style="min-width: 100%">
                            <div class="row my-50 mx-2">
                                <div class="row col-md-3 ">
                                    <input type="text" class="form-control" placeholder="للبحث"
                                        value="{{ request()->text }}" name="text" id="text" />
                                </div>

                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary btn-block">بحث</button>
                                </div>
                            </div>

                    </div>

                    </form>

                </div>
                <div class="col-md-3">
                    <a href="{{ route('dashboard.trip-type.create') }}" class="btn btn-success mb-2 btn-block">اضافة</a>
                </div>
                <div class="table-responsive min-height-17rem">
                    <table class="dt-multilingual table">
                        <thead>
                            <tr>
                                <th>المعرف</th>
                                <th>النوع</th>
                                <th>التفاصيل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_tripType as $index => $tripType)
                                <tr>
                                    <td>{{ $tripType->id }}</td>

                                    <td>{{ $tripType->name }}</td>
                                    <td>{{ $tripType->description }}</td>
                                    <td>
                                                                          <div class="dropdown">
                                        <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            العمليات
                                        </a>
                                        @if (auth()->user()->canany(['super', 'grades-create']))
                                            <div class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="dropdownMenuLink">
                                        @endif
                                        @if (auth()->user()->canany(['super', 'trip-type-show']))
                                            <a href="{{ route('dashboard.trip-type.show', $tripType->id) }}"
                                                class="dropdown-item" style="padding: 6px;" title="Show">
                                                <i data-feather='eye'></i>
                                                عرض بيانات السؤال الشائع
                                            </a>
                                        @endif
                                        @if (auth()->user()->canany(['super', 'trip-type-edit']))
                                            <a href="{{ route('dashboard.trip-type.edit', $tripType->id) }}"
                                                class="dropdown-item" style="padding: 6px;" title="Edit">
                                                <i data-feather='edit'></i>
                                                تعديل بيانات السؤال الشائع
                                            </a>
                                        @endif

                                        @if (auth()->user()->canany(['super', 'trip-type-destroy']))
                                            <a onclick="event.preventDefault();"
                                                data-delete="delete-form-{{ $index }}"
                                                href="{{ route('dashboard.trip-type.destroy', $tripType->id) }}"
                                                class="dropdown-item but_delete_action" style="padding: 6px;"
                                                title="Delete">
                                                <i data-feather='trash'></i>
                                                حذف بيانات السؤال الشائع

                                            </a>
                                            <form id="delete-form-{{ $index }}"
                                                action="{{ route('dashboard.trip-type.destroy', $tripType->id) }}"
                                                method="POST" style="display: none;">
                                                @method('DELETE')
                                                @csrf
                                            </form>
                                        @endif
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
        {{ $all_tripType->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}

    </section>
@endsection

@push('page_scripts_vendors')
@endpush

@push('page_scripts')
@endpush

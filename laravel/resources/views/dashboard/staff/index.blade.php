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
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Staff</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="dt-multilingual table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                   <th>صورة</th>
                                    <th>اسم</th>
                                    <th>تاريخ الاضافة</th>
                                    <th>الاعدادات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_staff as $index => $staff)
                                    <tr>
                                        <td>{{ $staff->id }}</td>
                                       <td>
                                        <img width="60"
                                        src="{{ $staff->logo_path }}"
                                       class="m--img-rounded m--marginless" alt="cover">
                                       </td>
                                        <td>{{ $staff->name }}</td>
                                        <td>{{ $staff->created_at }}</td>
                                        <td>
                                            @if(auth()->user()->canany(['super', 'staff-show']))
                                                <a href="{{ route('dashboard.staff.show', $staff->id) }}" class="m-portlet__nav-link btn m-btn m-btn--hover-blog m-btn--icon m-btn--icon-only m-btn--pill" style="padding: 6px;" title="Show">
                                                    <i data-feather='eye'></i>
                                                </a>
                                            @endif

                                            @if(auth()->user()->canany(['super', 'staff-edit']))
                                                <a href="{{ route('dashboard.staff.edit', $staff->id) }}" class="m-portlet__nav-link btn m-btn m-btn--hover-blog m-btn--icon m-btn--icon-only m-btn--pill" style="padding: 6px;" title="Edit">
                                                    <i data-feather='edit'></i>
                                                </a>
                                            @endif

                                            @if(auth()->user()->canany(['super', 'staff-destroy']))
                                                <a onclick="event.preventDefault();" data-delete="delete-form-{{$index}}" href="{{ route('dashboard.staff.destroy', $staff->id) }}" class="m-portlet__nav-link btn m-btn m-btn--hover-blog m-btn--icon m-btn--icon-only m-btn--pill but_delete_action" style="padding: 6px;" title="Delete">
                                                    <i data-feather='trash'></i>
                                                </a>
                                                <form id="delete-form-{{$index}}" action="{{ route('dashboard.staff.destroy', $staff->id) }}" method="POST" style="display: none;">
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
        {{ $all_staff->links('vendor.pagination.bootstrap-4') }}
    </section>


@endsection

@push('page_scripts_vendors')

@endpush

@push('page_scripts')

@endpush

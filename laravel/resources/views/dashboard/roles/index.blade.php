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
                        <h4 class="card-title">roles</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="dt-multilingual table">
                            <thead>
                                <tr>
                                    <th>ID</th>
{{--                                    <th>Image</th>--}}
                                    <th>Name</th>
                                    <th>Created at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $index => $role)
                                    <tr>
                                        <td>{{ $role->id }}</td>
{{--                                        <td>--}}
{{--                                            <img width="60" src="{{asset('storage/roles/images')}}/{{ $role->main_image }}" class="m--img-rounded m--marginless" alt="cover">--}}
{{--                                        </td>--}}
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->created_at }}</td>
                                        <td>
                                            @if(auth()->user()->canany(['super', 'roles-show']))
                                                <a href="{{ route('dashboard.roles.show', $role->id) }}" class="m-portlet__nav-link btn m-btn m-btn--hover-blog m-btn--icon m-btn--icon-only m-btn--pill" style="padding: 6px;" title="Show">
                                                    <i data-feather='eye'></i>
                                                </a>
                                            @endif

                                            @if(auth()->user()->canany(['super', 'roles-edit']))
                                                <a href="{{ route('dashboard.roles.edit', $role->id) }}" class="m-portlet__nav-link btn m-btn m-btn--hover-blog m-btn--icon m-btn--icon-only m-btn--pill" style="padding: 6px;" title="Edit">
                                                    <i data-feather='edit'></i>
                                                </a>
                                            @endif

                                            @if(auth()->user()->canany(['super', 'roles-destroy']))
                                                <a onclick="event.preventDefault();" data-delete="delete-form-{{$index}}" href="{{ route('dashboard.roles.destroy', $role->id) }}" class="m-portlet__nav-link btn m-btn m-btn--hover-blog m-btn--icon m-btn--icon-only m-btn--pill but_delete_action" style="padding: 6px;" title="Delete">
                                                    <i data-feather='trash'></i>
                                                </a>
                                                <form id="delete-form-{{$index}}" action="{{ route('dashboard.roles.destroy', $role->id) }}" method="POST" style="display: none;">
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
        {{ $roles->links('vendor.pagination.bootstrap-4') }}
    </section>


@endsection

@push('page_scripts_vendors')

@endpush

@push('page_scripts')

@endpush

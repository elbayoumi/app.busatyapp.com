@extends('dashboard.layouts.app')

@push('page_vendor_css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashboard/app-assets/vendors/css/forms/select/select2.min.css') }}">
@endpush

@section('content')
    <section id="multilingual-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    {{-- Page Header --}}
                    <div class="card-header border-bottom p-2">
                        <h4 class="card-title">Coupon List / {{ request()->model }}</h4>
                    </div>

                    {{-- Filter Form --}}
                    <div class="card-header border-bottom p-1">
                        <form action="{{ route('dashboard.coupon.index') }}" method="GET" style="min-width: 100%">
                            <div class="row">
                                <div class="col-md-3 my-50">
                                    <input type="text" class="form-control" name="code" id="code"
                                        placeholder="Code" value="{{ request()->code }}" />
                                </div>
                                <div class="col-md-3 my-50">
                                    <button type="submit" class="btn btn-primary btn-block">Search</button>
                                </div>
                                <div class="col-md-3 my-50">
                                    <a href="{{ route('dashboard.coupon.create', ['model' => request()->model]) }}"
                                        class="btn btn-success btn-block">Add</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Coupon Table --}}
                    <div class="table-responsive min-height-17rem">
                        <table class="dt-multilingual table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @if (request()->school_id)
                                        <th>School</th>
                                    @endif
                                    <th>Code</th>
                                    <th>Discount</th>
                                    <th>Limit</th>
                                    <th>Status</th>
                                    <th>User Limit</th>
                                    <th>Valid From</th>
                                    <th>Valid To</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coupon as $index => $subscrip)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        @if (request()->school_id)
                                            <td>{{ $subscrip?->school?->name }}</td>
                                        @endif
                                        <td>{{ $subscrip->code }}</td>
                                        <td>{{ $subscrip->discount }}</td>
                                        <td>{{ $subscrip->usage_limit }}</td>
                                        <td>
                                            @if (is_null($subscrip->school_id))
                                                General
                                            @else
                                                <a href="{{ route('dashboard.schools.show', $subscrip->school_id) }}">
                                                    {{ $subscrip->school->name }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ $subscrip->user_limit ?? 'Unlimited' }}</td>
                                        <td>{{ $subscrip->allow_at }}</td>
                                        <td>{{ $subscrip->expires_at }}</td>

                                        {{-- Action Dropdown --}}
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-success btn-sm dropdown-toggle" href="#"
                                                    role="button" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    Actions
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">

                                                    {{-- View Users --}}
                                                    <a href="{{ route('dashboard.coupon.users', $subscrip->id) }}"
                                                        class="dropdown-item">
                                                        <i data-feather="users"></i> View Users
                                                    </a>

                                                    {{-- Edit --}}
                                                    <a href="{{ route('dashboard.coupon.edit', $subscrip->id) }}"
                                                        class="dropdown-item text-primary">
                                                        <i data-feather='edit'></i> Edit
                                                    </a>

                                                    {{-- Delete --}}
                                                    @if (auth()->user()->canany(['super', 'attendant-destroy']))
                                                        <a href="#"
                                                            onclick="event.preventDefault(); document.getElementById('delete-form-{{ $index }}').submit();"
                                                            class="dropdown-item text-danger">
                                                            <i data-feather='trash'></i> Delete
                                                        </a>
                                                        <form id="delete-form-{{ $index }}"
                                                            action="{{ route('dashboard.coupon.destroy', $subscrip->id) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
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
        {{ $coupon->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}

    </section>
@endsection

@push('page_scripts_vendors')
    <script src="{{ asset('assets/dashboard/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
@endpush

@push('page_scripts')
    <script>
        // Initialize Select2
        $(document).ready(function() {
            $('.basic-select2').select2();
        });
    </script>
@endpush

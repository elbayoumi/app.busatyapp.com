@extends('dashboard.layouts.app')

@section('title', 'Coupon Users')

@section('content')
<div class="card">
    {{-- Header --}}
    <div class="card-header">
        <h4 class="mb-1">Coupon Usage: <strong>{{ $coupon->code }}</strong></h4>
        <p class="mb-0">
            <span>Discount: <strong>{{ $coupon->discount }}%</strong></span> |
            <span>Valid: <strong>{{ $coupon->allow_at }} → {{ $coupon->expires_at }}</strong></span>
        </p>
    </div>

    {{-- Table --}}
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered mb-0">
            <thead class="thead-dark">
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 10%;">Type</th>
                    <th style="width: 35%;">Name / Email</th>
                    <th style="width: 20%;">Phone</th>
                    <th style="width: 20%;">Used At</th>
                    <th style="width: 20%;">View</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($uses as $index => $use)
                    @php
                        $user = $use->usedBy;
                    @endphp
                    <tr>
                        <td>{{ $uses->firstItem() + $index }}</td>
                        <td><span class="badge badge-info">{{ class_basename($use->used_by_type) }}</span></td>
                        <td>
                            {{ $user?->name ?? '-' }}
                            <br>
                            <small class="text-muted">{{ $user?->email ?? 'No email' }}</small>
                        </td>
                        <td>{{ $user?->phone ?? '-' }}</td>
                        <td>{{ $use->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            @if ($user && class_basename($use->used_by_type) === 'My_Parent')
                                <a href="{{ route('dashboard.parents.show', $user->id) }}" class="btn btn-sm btn-primary" target="_blank">
                                    View Parent
                                </a>
                            @elseif ($user && class_basename($use->used_by_type) === 'School')
                                <a href="{{ route('dashboard.schools.show', $user->id) }}" class="btn btn-sm btn-info" target="_blank">
                                    View School
                                </a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No users found for this coupon.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="card-footer d-flex justify-content-center">
        {{ $uses->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
@endsection

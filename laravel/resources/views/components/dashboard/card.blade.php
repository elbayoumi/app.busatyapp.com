@props([
    'title',
    'icon' => 'bar-chart',
    'value' => '',
    'color' => 'primary',
    'footer' => null,
])

<div class="col-lg-3 col-md-6 col-12 mb-2">
    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bolder mb-1">{{ $value }}</h2>
                <p class="card-text">{{ $title }}</p>
            </div>
            <div class="avatar bg-light-{{ $color }} p-50">
                <span class="avatar-content">
                    <i data-feather="{{ $icon }}" class="font-medium-3 text-{{ $color }}"></i>
                </span>
            </div>
        </div>
        @if($footer)
            <div class="card-footer text-muted small">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>

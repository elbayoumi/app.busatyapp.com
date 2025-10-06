@props(['type' => 'success', 'message'])

<div class="alert alert-{{ $type }}" role="alert">
    <div class="alert-body">
        {{ $message }}
    </div>
</div>

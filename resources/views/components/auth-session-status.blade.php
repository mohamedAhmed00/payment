@props(['status'])

@if ($status)
    <div class="alert alert-success" role="alert">
        <i class="mdi mdi-check-all me-2"></i>
        {{ $status }}
    </div>
@endif

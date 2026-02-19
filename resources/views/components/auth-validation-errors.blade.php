@props(['errors'])

@if ($errors->any())

    <div class="alert alert-danger" role="alert">
        <i class="mdi mdi-block-helper me-2"></i>
        {{ __('Whoops! Something went wrong.') }} <br>
        @foreach ($errors->all() as $error)
            - {{ $error }} <br>
        @endforeach

    </div>
@endif

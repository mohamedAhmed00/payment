<x-guest-layout>
<div class="col-md-8 col-lg-6 col-xl-4">
    <div class="card bg-pattern">
        <div class="card-body p-4">
            <div class="text-center w-75 m-auto">
                <div class="auth-logo">
                    <a class="logo logo-dark text-center">
                        <span class="logo-lg">
                            <img src="{{ asset('assets/images/logo.jpeg') }}">
                        </span>
                    </a>
                </div>
            </div>
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="{{ __('Email') }}">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password" placeholder="{{ __('Password') }}">
                        <div class="input-group-text" data-password="false">
                            <span class="password-eye"></span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember_me" checked>
                        <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
                    </div>
                </div>
                <div class="text-center d-grid">
                    <button class="btn btn-primary" type="submit">{{ __('Log in') }} </button>
                </div>
            </form>
        </div>
    </div>
    <!-- end card -->

    <div class="row mt-3">
        <div class="col-12 text-center">
            <p> <a href="{{ route('password.request') }}" class="text-white-50 ms-1">{{ __('Forgot your password?') }}</a></p>
        </div> <!-- end col -->
    </div>
    <!-- end row -->

</div> <!-- end col -->
</x-guest-layout>

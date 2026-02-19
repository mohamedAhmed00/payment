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
                    <p class="text-muted my-1">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </p>
                </div>
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="{{ __('Email') }}">
                    </div>

                    <div class="text-center d-grid">
                        <button class="btn btn-primary" type="submit"> {{ __('Email Password Reset Link') }} </button>
                    </div>
                </form>
            </div>
        </div>

    </div> <!-- end col -->

</x-guest-layout>


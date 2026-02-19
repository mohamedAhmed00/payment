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
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
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
                        <label for="password_confirmation" class="form-label">{{ __('Password') }}</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="current-password" placeholder="{{ __('Confirm Password') }}">
                            <div class="input-group-text" data-password="false">
                                <span class="password-eye"></span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center d-grid">
                        <button class="btn btn-primary" type="submit">{{ __('Reset Password') }} </button>
                    </div>
                </form>
            </div>
        </div>


    </div> <!-- end col -->
</x-guest-layout>

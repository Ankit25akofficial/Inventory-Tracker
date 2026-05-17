<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" style="color:#10b981;font-size:13px;margin-bottom:16px;text-align:center;" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label class="form-label" for="email">{{ __('Email Address') }}</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" style="color:#ef4444;font-size:12px;margin-top:6px;" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <label class="form-label" for="password">{{ __('Password') }}</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" style="color:#ef4444;font-size:12px;margin-top:6px;" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
            <label for="remember_me" class="checkbox-group">
                <input id="remember_me" type="checkbox" name="remember">
                <span style="font-size:13px;color:#cbd5e1;">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="forgot-password" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div>
            <button type="submit" class="btn-primary">
                {{ __('Secure Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>

<section>
    <header style="margin-bottom:24px;">
        <h2 style="font-size:18px;font-weight:700;color:#f1f5f9;">
            {{ __('Update Password') }}
        </h2>

        <p style="font-size:13px;color:#94a3b8;margin-top:4px;">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" style="display:flex;flex-direction:column;gap:20px;">
        @csrf
        @method('put')

        <div>
            <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
            <input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" style="color:#ef4444;font-size:12px;margin-top:4px;" />
        </div>

        <div>
            <label for="password" class="form-label">{{ __('New Password') }}</label>
            <input id="password" name="password" type="password" class="form-control" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" style="color:#ef4444;font-size:12px;margin-top:4px;" />
        </div>

        <div>
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" style="color:#ef4444;font-size:12px;margin-top:4px;" />
        </div>

        <div style="display:flex;align-items:center;gap:16px;margin-top:8px;">
            <button type="submit" class="btn btn-primary">{{ __('Save Password') }}</button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    style="font-size:13px;color:#10b981;font-weight:600;"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

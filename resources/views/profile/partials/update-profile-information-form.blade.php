<section>
    <header style="margin-bottom:24px;">
        <h2 style="font-size:18px;font-weight:700;color:#f1f5f9;">
            {{ __('Profile Information') }}
        </h2>

        <p style="font-size:13px;color:#94a3b8;margin-top:4px;">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" style="display:flex;flex-direction:column;gap:20px;">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" style="color:#ef4444;font-size:12px;margin-top:4px;" />
        </div>

        <div>
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" style="color:#ef4444;font-size:12px;margin-top:4px;" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div style="margin-top:12px;">
                    <p style="font-size:13px;color:#cbd5e1;">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" style="background:none;border:none;color:#6366f1;text-decoration:underline;cursor:pointer;padding:0;font-size:13px;">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p style="margin-top:8px;font-weight:600;font-size:13px;color:#10b981;">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div style="display:flex;align-items:center;gap:16px;margin-top:8px;">
            <button type="submit" class="btn btn-primary">{{ __('Save Profile') }}</button>

            @if (session('status') === 'profile-updated')
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

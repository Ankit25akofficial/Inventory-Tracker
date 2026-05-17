<section>
    <header style="margin-bottom:24px;">
        <h2 style="font-size:18px;font-weight:700;color:#ef4444;">
            {{ __('Delete Account') }}
        </h2>

        <p style="font-size:13px;color:#94a3b8;margin-top:4px;">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button
        class="btn" style="background:rgba(239,68,68,0.1);color:#ef4444;border:1px solid rgba(239,68,68,0.2);"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" style="padding:30px;background:#161b27;border-radius:12px;border:1px solid #1e2536;">
            @csrf
            @method('delete')

            <h2 style="font-size:18px;font-weight:700;color:#f1f5f9;">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p style="font-size:13px;color:#94a3b8;margin-top:8px;">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div style="margin-top:24px;">
                <label for="password" class="form-label sr-only">{{ __('Password') }}</label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-control"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" style="color:#ef4444;font-size:12px;margin-top:4px;" />
            </div>

            <div style="margin-top:30px;display:flex;justify-content:flex-end;gap:12px;">
                <button type="button" class="btn btn-secondary" x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="btn" style="background:#ef4444;color:#fff;border:none;">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>

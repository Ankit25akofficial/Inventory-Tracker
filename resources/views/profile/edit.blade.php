<x-app-layout>
    <x-slot name="title">Profile</x-slot>

    <div class="page-header">
        <div>
            <h1 class="page-title">Profile Settings</h1>
            <div class="page-subtitle">Manage your account information and security</div>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:24px;max-width:800px;">
        <div class="card">
            <div class="card-body" style="padding:30px;">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card">
            <div class="card-body" style="padding:30px;">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="card">
            <div class="card-body" style="padding:30px;">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>

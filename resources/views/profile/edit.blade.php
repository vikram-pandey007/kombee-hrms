<x-app-layout>
    <x-slot name="header">
        {{ __('Profile Settings') }}
    </x-slot>

    <div class="space-y-6">
        <!-- Update Profile Information -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg" data-testid="profile-info-section">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Profile Information</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Update your account's profile information and email address.
                </p>
            </div>
            <div class="p-6">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Update Password -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg" data-testid="password-section">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Update Password</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Ensure your account is using a long, random password to stay secure.
                </p>
            </div>
            <div class="p-6">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg" data-testid="delete-account-section">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Delete Account</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Once your account is deleted, all of its resources and data will be permanently deleted.
                </p>
            </div>
            <div class="p-6">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

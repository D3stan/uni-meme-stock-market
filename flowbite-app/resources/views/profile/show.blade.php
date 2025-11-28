@extends('app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Profile</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your account settings</p>
    </div>

    <!-- Profile Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center space-x-4 mb-6">
            <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-bold" id="avatar">
                U
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white" id="profile-name">Loading...</h2>
                <p class="text-gray-600 dark:text-gray-400" id="profile-email">Loading...</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Role</p>
                <p class="text-lg font-medium text-gray-900 dark:text-white capitalize" id="profile-role">-</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">CFU Balance</p>
                <p class="text-lg font-medium text-green-600 dark:text-green-400" id="profile-balance">0.00 CFU</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Member Since</p>
                <p class="text-lg font-medium text-gray-900 dark:text-white" id="profile-member-since">-</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                <p class="text-lg font-medium text-gray-900 dark:text-white" id="profile-status">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        Active
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Update Profile Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Update Profile</h3>
        
        <form id="update-profile-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
                <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Your name">
            </div>

            <div id="success-message" class="hidden p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <span class="font-medium">Success!</span> Profile updated successfully.
            </div>

            <div id="error-message" class="hidden p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <span class="font-medium">Error!</span> <span id="error-text"></span>
            </div>

            <button type="submit" class="w-full md:w-auto px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                Save Changes
            </button>
        </form>
    </div>
</div>

<script>
    async function loadProfile() {
        try {
            const response = await fetch('/api/user/profile', {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                const data = await response.json();
                const user = data.user;
                
                // Update profile display
                document.getElementById('profile-name').textContent = user.name;
                document.getElementById('profile-email').textContent = user.email;
                document.getElementById('profile-role').textContent = user.role;
                document.getElementById('profile-balance').textContent = `${parseFloat(user.cfu_balance).toFixed(2)} CFU`;
                
                // Update avatar with first letter of name
                document.getElementById('avatar').textContent = user.name.charAt(0).toUpperCase();
                
                // Update member since
                const memberSince = new Date(user.created_at);
                document.getElementById('profile-member-since').textContent = memberSince.toLocaleDateString();
                
                // Update status
                const statusElement = document.getElementById('profile-status');
                if (user.is_suspended) {
                    statusElement.innerHTML = '<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Suspended</span>';
                }
                
                // Set form value
                document.getElementById('name').value = user.name;
            }
        } catch (error) {
            console.error('Failed to load profile:', error);
        }
    }

    document.getElementById('update-profile-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Hide messages
        document.getElementById('success-message').classList.add('hidden');
        document.getElementById('error-message').classList.add('hidden');
        
        const formData = {
            name: document.getElementById('name').value
        };

        try {
            const response = await fetch('/api/user/profile', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                document.getElementById('success-message').classList.remove('hidden');
                // Reload profile to show updated data
                setTimeout(() => {
                    loadProfile();
                }, 500);
            } else {
                document.getElementById('error-text').textContent = data.message || 'Failed to update profile';
                document.getElementById('error-message').classList.remove('hidden');
            }
        } catch (error) {
            console.error('Update error:', error);
            document.getElementById('error-text').textContent = 'An error occurred. Please try again.';
            document.getElementById('error-message').classList.remove('hidden');
        }
    });

    // Load profile on page load
    loadProfile();
</script>
@endsection

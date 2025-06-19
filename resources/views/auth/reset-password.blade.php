@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <!-- Success Message Overlay (Hidden by default) -->
    <div id="successOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-2xl p-6 mx-4 w-full max-w-sm transform transition-all">
            <div class="text-center">
                <!-- Success Icon -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h3 class="text-lg font-medium text-gray-900 mb-2">Password Reset Successful!</h3>
                <p class="text-sm text-gray-500 mb-4">Your password has been changed successfully.</p>

                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                    <div id="progressBar" class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>

                <p class="text-xs text-gray-400">Redirecting to login page...</p>
            </div>
        </div>
    </div>

    <div class="max-w-md w-full">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">
                Reset Password
            </h2>
            <p class="text-sm text-gray-600 mb-8">
                Enter your new password below.
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form class="space-y-6" method="POST" action="{{ route('password.update') }}" onsubmit="return handleSubmit(event)">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="relative">
                    <input id="email" name="email" type="email" required readonly
                        value="{{ request('email') }}"
                        class="peer w-full border-b-2 border-gray-300 text-gray-900 placeholder-transparent focus:outline-none focus:border-indigo-600 px-0 py-2 transition-colors bg-gray-50"
                        placeholder="Email address">
                    <label for="email"
                        class="absolute left-0 -top-3.5 text-gray-600 text-sm transition-all
                        peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2
                        peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm">
                        Email address
                    </label>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative">
                    <input id="password" name="password" type="password" required
                        class="peer w-full border-b-2 border-gray-300 text-gray-900 placeholder-transparent focus:outline-none focus:border-indigo-600 px-0 py-2 transition-colors"
                        placeholder="New Password">
                    <label for="password"
                        class="absolute left-0 -top-3.5 text-gray-600 text-sm transition-all
                        peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2
                        peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm">
                        New Password
                    </label>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative">
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="peer w-full border-b-2 border-gray-300 text-gray-900 placeholder-transparent focus:outline-none focus:border-indigo-600 px-0 py-2 transition-colors"
                        placeholder="Confirm Password">
                    <label for="password_confirmation"
                        class="absolute left-0 -top-3.5 text-gray-600 text-sm transition-all
                        peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2
                        peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm">
                        Confirm Password
                    </label>
                </div>

                <div>
                    <button type="submit"
                        id="submitBtn"
                        class="w-full flex justify-center py-3 px-4 rounded-full text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all hover:scale-105">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let isSubmitting = false;

function handleSubmit(event) {
    event.preventDefault();

    if (isSubmitting) {
        return false;
    }

    const form = event.target;
    const formData = new FormData(form);

    // Show loader
    isSubmitting = true;
    showLoader();

    // Submit form via AJAX
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage();
        } else {
            throw new Error(data.message || 'Failed to reset password');
        }
    })
    .catch(error => {
        isSubmitting = false;
        const button = document.getElementById('submitBtn');
        button.disabled = false;
        button.innerHTML = '<span>Reset Password</span>';
        showError(error.message);
    });

    return false;
}

function showSuccessMessage() {
    const overlay = document.getElementById('successOverlay');
    const progressBar = document.getElementById('progressBar');

    // Show overlay with animation
    overlay.classList.remove('hidden');
    overlay.classList.add('animate-fade-in');

    // Animate progress bar
    let progress = 0;
    const duration = 3000; // 3 seconds
    const interval = 30; // Update every 30ms
    const steps = duration / interval;
    const increment = 100 / steps;

    const progressInterval = setInterval(() => {
        progress += increment;
        progressBar.style.width = `${Math.min(progress, 100)}%`;

        if (progress >= 100) {
            clearInterval(progressInterval);
            window.location.href = '{{ route("login") }}';
        }
    }, interval);
}

function showLoader() {
    const button = document.getElementById('submitBtn');
    button.disabled = true;
    button.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Resetting Password...</span>
    `;
}

function showError(message) {
    const errorElement = document.createElement('p');
    errorElement.className = 'mt-1 text-sm text-red-600';
    errorElement.textContent = message;

    // Remove any existing error messages
    const existingError = document.querySelector('.text-red-600');
    if (existingError) {
        existingError.remove();
    }

    const passwordInput = document.getElementById('password');
    passwordInput.parentNode.appendChild(errorElement);
}
</script>

<style>
.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>
@endsection

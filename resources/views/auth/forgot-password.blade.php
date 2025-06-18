@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">
                Forgot Password
            </h2>
            <p class="text-sm text-gray-600 mb-8">
                Enter your email address and we'll send you a link to reset your password.
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if (session('status'))
                <div class="mb-4 text-sm font-medium text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form class="space-y-6" method="POST" action="{{ route('password.email') }}" onsubmit="return handleSubmit(event)">
                @csrf

                <div class="relative">
                    <input id="email" name="email" type="email" required
                        class="peer w-full border-b-2 border-gray-300 text-gray-900 placeholder-transparent focus:outline-none focus:border-indigo-600 px-0 py-2 transition-colors"
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

                <div>
                    <button type="submit" id="submitBtn"
                        class="w-full flex justify-center items-center py-3 px-4 rounded-full text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span>Send Reset Link</span>
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let isSubmitting = false;

function handleSubmit(event) {
    event.preventDefault();

    // Check if already submitting
    if (isSubmitting) {
        return false;
    }

    // Get the email input and validate
    const emailInput = document.getElementById('email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(emailInput.value)) {
        showError('Please enter a valid email address');
        return false;
    }

    // Show loader and disable form
    isSubmitting = true;
    showLoader();

    // Submit the form
    event.target.submit();
    return true;
}

function showLoader() {
    const button = document.getElementById('submitBtn');
    button.disabled = true;
    button.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Sending Reset Link...</span>
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

    // Add new error message
    const emailInput = document.getElementById('email');
    emailInput.parentNode.appendChild(errorElement);
}

// Reset submission state if the page is shown from back/forward cache
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        isSubmitting = false;
        const button = document.getElementById('submitBtn');
        button.disabled = false;
        button.innerHTML = '<span>Send Reset Link</span>';
    }
});
</script>
@endsection

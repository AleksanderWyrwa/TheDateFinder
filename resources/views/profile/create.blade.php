@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <h2 class="text-2xl font-semibold text-gray-900">Create Your Profile</h2>

    @if(session('status'))
        <div class="bg-green-500 text-white p-4 rounded-lg mt-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('profile.store') }}" method="POST" id="profile-form" aria-labelledby="form-title">
        @csrf

        <!-- First Name -->
        <div class="mt-4">
            <label for="first_name" class="block">First Name</label>
            <input 
                type="text" 
                name="first_name" 
                id="first_name"
                class="border p-2 rounded mt-2 @error('first_name') border-red-500 @enderror" 
                value="{{ old('first_name') }}" 
                aria-describedby="first_name_error"
                required
            />
            @error('first_name')
                <p id="first_name_error" class="text-red-500 text-sm mt-2" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <!-- Birth Date -->
        <div class="mt-4">
            <label for="birth_date" class="block">Birth Date</label>
            <input 
                type="date" 
                name="birth_date" 
                id="birth_date"
                class="border p-2 rounded mt-2 @error('birth_date') border-red-500 @enderror" 
                value="{{ old('birth_date') }}" 
                required
                aria-describedby="birth_date_error"
            />
            @error('birth_date')
                <p id="birth_date_error" class="text-red-500 text-sm mt-2" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div class="mt-4">
            <label for="description" class="block">Description</label>
            <textarea 
                name="description" 
                id="description"
                class="border p-2 rounded mt-2 @error('description') border-red-500 @enderror"
                aria-describedby="description_error">{{ old('description') }}</textarea>
            @error('description')
                <p id="description_error" class="text-red-500 text-sm mt-2" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <!-- Height -->
        <div class="mt-4">
            <label for="height" class="block">Height (in cm)</label>
            <input 
                type="number" 
                name="height" 
                id="height"
                class="border p-2 rounded mt-2 @error('height') border-red-500 @enderror" 
                value="{{ old('height') }}" 
                min="50" 
                max="300"
                aria-describedby="height_error"
                required
            />
            @error('height')
                <p id="height_error" class="text-red-500 text-sm mt-2" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <!-- Weight -->
        <div class="mt-4">
            <label for="weight" class="block">Weight (in kg)</label>
            <input 
                type="number" 
                name="weight" 
                id="weight"
                class="border p-2 rounded mt-2 @error('weight') border-red-500 @enderror" 
                value="{{ old('weight') }}" 
                min="30" 
                max="300"
                aria-describedby="weight_error"
                required
            />
            @error('weight')
                <p id="weight_error" class="text-red-500 text-sm mt-2" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <button 
            type="submit" 
            id="submit-button" 
            class="mt-4 bg-blue-500 text-white p-2 rounded"
            aria-live="polite"
            aria-disabled="false"
            aria-describedby="submit_button_status"
        >
            Create Profile
        </button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const birthDateInput = document.getElementById('birth_date');
        const submitButton = document.getElementById('submit-button');
        const form = document.getElementById('profile-form');
        const submitButtonStatus = document.getElementById('submit_button_status');
        
        // Function to check if the user is at least 18 years old
        function checkAge() {
            const birthDate = new Date(birthDateInput.value);
            const today = new Date();
            const age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();

            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            if (age < 18) {
                submitButton.disabled = true;
                submitButton.classList.add('bg-gray-500');
                submitButton.classList.remove('bg-blue-500');
                submitButtonStatus.innerText = "You must be at least 18 years old to submit.";
                submitButton.setAttribute('aria-disabled', 'true');
            } else {
                submitButton.disabled = false;
                submitButton.classList.add('bg-blue-500');
                submitButton.classList.remove('bg-gray-500');
                submitButtonStatus.innerText = "";
                submitButton.setAttribute('aria-disabled', 'false');
            }
        }

        // Check age initially and every time the user changes the birth date
        birthDateInput.addEventListener('input', checkAge);

        // Check age on form submission (just in case)
        form.addEventListener('submit', function(e) {
            if (submitButton.disabled) {
                e.preventDefault(); // Prevent form submission if underage
                alert("You must be at least 18 years old to create a profile.");
            }
        });

        // Initial check
        checkAge();
    });
</script>

@endsection

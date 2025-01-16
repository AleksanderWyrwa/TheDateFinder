@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-2">
    <h2 class="text-3xl font-semibold text-gray-900 ">Edit Profile</h2>

    @if(session('status') === 'profile-updated')
        <div class="bg-green-100 text-green-800 p-4 rounded-md mb-6" role="alert" aria-live="assertive">
            Profile updated successfully.
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" id="edit-profile-form">
        @csrf

        <div class="space-y-6 bg-white shadow-md rounded-lg p-6">

            <!-- Personal Profile Fields -->
            
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" id="first_name" name="first_name" 
                    value="{{ old('first_name', $personalProfile->first_name) }}" 
                    class="mt-2 block w-full p-4 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                    aria-describedby="first-name-error">
                @error('first_name')
                    <div id="first-name-error" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="birth_date" class="block text-sm font-medium text-gray-700">Birth Date</label>
                <input type="date" id="birth_date" name="birth_date" 
                    value="{{ old('birth_date', $personalProfile->birth_date) }}" 
                    class="mt-2 block w-full p-4 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                    required
                    aria-describedby="birth-date-error">
                @error('birth_date')
                    <div id="birth-date-error" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" 
                    class="mt-2 block w-full p-4 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                    aria-describedby="description-error">{{ old('description', $personalProfile->description) }}</textarea>
                @error('description')
                    <div id="description-error" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="height" class="block text-sm font-medium text-gray-700">Height (cm)</label>
                <input type="number" id="height" name="height" 
                    value="{{ old('height', $personalProfile->height) }}" 
                    class="mt-2 block w-full p-4 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                    min="50" max="300"
                    aria-describedby="height-error">
                @error('height')
                    <div id="height-error" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                <input type="number" id="weight" name="weight" 
                    value="{{ old('weight', $personalProfile->weight) }}" 
                    class="mt-2 block w-full p-4 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                    min="30" max="300"
                    aria-describedby="weight-error">
                @error('weight')
                    <div id="weight-error" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Submit Button -->
            <div class="mt-6 flex justify-end">
                <button type="submit" id="submit-button" class="px-8 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                    aria-live="assertive" aria-disabled="false">
                    Save Changes
                </button>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript for Age Validation -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const birthDateInput = document.getElementById('birth_date');
        const submitButton = document.getElementById('submit-button');
        const form = document.getElementById('edit-profile-form');

        // Function to check if the user is at least 18 years old but not older than 100
        function checkAge() {
            const birthDate = new Date(birthDateInput.value);
            const today = new Date();
            const age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();

            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            // Disable the submit button if the user is under 18 or over 100 years old
            if (age < 18 || age > 100) {
                submitButton.disabled = true;
                submitButton.classList.add('bg-gray-400', 'hover:bg-gray-400');
                submitButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                submitButton.setAttribute('aria-disabled', 'true');
            } else {
                submitButton.disabled = false;
                submitButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
                submitButton.classList.remove('bg-gray-400', 'hover:bg-gray-400');
                submitButton.setAttribute('aria-disabled', 'false');
            }
        }

        // Check age when the birth date is changed
        birthDateInput.addEventListener('input', checkAge);

        // Check the age initially
        checkAge();
    });
</script>
@endsection

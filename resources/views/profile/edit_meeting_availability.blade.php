@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Edit Meeting Availability
            </h3>
        </div>

        <div class="border-t border-gray-200">
            <form action="{{ route('profile.updateMeetingAvailability', $availability->id) }}" method="POST" aria-labelledby="edit-availability-form">
                @csrf
                @method('PUT')

                <div class="px-4 py-5 sm:px-6">
                    <label for="date" class="block">Date</label>
                    <input 
                        type="date" 
                        name="date" 
                        value="{{ old('date', \Carbon\Carbon::parse($availability->date)->format('Y-m-d')) }}" 
                        class="border p-2 rounded mt-2" 
                        required 
                        id="date-input"
                        aria-describedby="date-error"
                    />
                    @error('date')
                        <p id="date-error" class="text-red-500 text-sm mt-2" role="alert">{{ $message }}</p>
                    @enderror

                    <label for="type" class="block mt-4">Type</label>
                    <input 
                        type="text" 
                        name="type" 
                        value="{{ old('type', $availability->type) }}" 
                        class="border p-2 rounded mt-2" 
                        required 
                        aria-describedby="type-error"
                    />
                    @error('type')
                        <p id="type-error" class="text-red-500 text-sm mt-2" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded" aria-live="assertive">
                    Update Availability
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Set max date to 1 year ahead and disable manual input for date picker
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('date-input');
        
        const maxDate = new Date();
        maxDate.setFullYear(maxDate.getFullYear() + 1);
        dateInput.max = maxDate.toISOString().split('T')[0];

        // Disable manual input (focus event handler to ensure date picker is used)
        dateInput.addEventListener('focus', function() {
            dateInput.blur(); // Remove focus to prevent keyboard entry
        });
    });
</script>
@endsection

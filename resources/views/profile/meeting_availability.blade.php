@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Manage Meeting Availability
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                View and manage your meeting availability here.
            </p>
        </div>

        <div class="border-t border-gray-200">
            <!-- Meeting Availability List -->
            <div class="px-4 py-5 sm:px-6">
                <h4 class="text-md font-medium text-gray-700">Your Availability</h4>
                @forelse($user->meetingAvailabilities as $availability)
                    <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 mt-4">
                        <div>
                            <strong>Date:</strong> {{ \Carbon\Carbon::parse($availability->date)->format('M d, Y') }} <br>
                            <strong>Type:</strong> {{ $availability->type }}
                        </div>
                        
                        <!-- Edit and Delete Buttons -->
                        <div class="text-right">
                            <a href="{{ route('profile.editMeetingAvailability', $availability->id) }}" class="bg-yellow-500 text-white p-2 rounded mt-2" aria-label="Edit Availability">
                                Edit
                            </a>
                            <form action="{{ route('profile.deleteMeetingAvailability', $availability->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this availability?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white p-2 rounded mt-2" aria-label="Delete Availability">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p>No meeting availability added yet.</p>
                @endforelse
            </div>

            <!-- Add New Meeting Availability Form -->
            <div class="px-4 py-5 sm:px-6">
                <h4 class="text-md font-medium text-gray-700">Add New Availability</h4>
                <form action="{{ route('profile.storeMeetingAvailability') }}" method="POST">
                    @csrf
                    <div class="mt-4">
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input 
                            type="date" 
                            name="date" 
                            class="border p-2 rounded mt-2 w-full" 
                            required 
                            id="new-date-input"
                            aria-describedby="date-help"
                        />
                        <small id="date-help" class="text-xs text-gray-500">Select a date within the next year.</small>

                        <label for="type" class="block text-sm font-medium text-gray-700 mt-4">Type</label>
                        <input 
                            type="text" 
                            name="type" 
                            class="border p-2 rounded mt-2 w-full" 
                            required 
                            aria-describedby="type-help"
                        />
                        <small id="type-help" class="text-xs text-gray-500">Enter the type of meeting (e.g., "Video Call").</small>
                    </div>

                    <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded w-full hover:bg-blue-600">
                        Add Availability
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Set max date to 1 year ahead and disable keyboard input for date picker
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('new-date-input');
        
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

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="profile-info-heading">
                Profile Information
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500" aria-labelledby="profile-info-heading">
                View and manage your personal profile details.
            </p>
        </div>

        <!-- Profile Info Section -->
        <div class="border-t border-gray-200">
            <dl>
                <!-- Full Name -->
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500" id="full-name">Full Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0" aria-labelledby="full-name">
                        {{ $user->personalProfile->first_name ?? 'N/A' }}
                    </dd>
                </div>

                <!-- Hobbies -->
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500" id="hobbies-heading">Hobbies</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0" aria-labelledby="hobbies-heading">
                        <div id="hobby-list" class="flex flex-wrap gap-2" aria-live="polite">
                            @forelse($user->hobbies as $hobby)
                                <span class="inline-block bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-sm" role="status">
                                    {{ $hobby->name }}
                                </span>
                            @empty
                                <span class="text-gray-500">No hobbies added.</span>
                            @endforelse
                        </div>

                        <!-- Add Hobby Input and Button -->
                        <div class="mt-4 flex items-center" role="form">
                            <label for="new-hobby" class="sr-only">Enter a hobby</label>
                            <input
                                type="text"
                                id="new-hobby"
                                placeholder="Enter a hobby"
                                class="border p-2 rounded mr-2"
                                aria-label="Hobby input field"
                            />
                            <button
                                id="add-hobby"
                                type="button"
                                class="bg-blue-500 text-white px-4 py-2 rounded"
                                aria-label="Add hobby"
                            >
                                +
                            </button>
                        </div>

                        <!-- Hidden Form for Hobby Submission -->
                        <form
                            id="hobby-form"
                            action="{{ route('profile.addHobby') }}"
                            method="POST"
                            class="hidden"
                            aria-live="assertive"
                        >
                            @csrf
                            <input type="hidden" id="hidden-hobby" name="hobby" />
                        </form>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<script>
    document.getElementById('add-hobby').addEventListener('click', function () {
        const hobbyInput = document.getElementById('new-hobby');
        const hobbyList = document.getElementById('hobby-list');
        const hobbyValue = hobbyInput.value.trim();

        if (hobbyValue) {
            // Create a new hobby tag
            const hobbyTag = document.createElement('span');
            hobbyTag.className = 'inline-block bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-sm';
            hobbyTag.innerText = hobbyValue;
            hobbyList.appendChild(hobbyTag);

            // Submit the form
            const hiddenHobby = document.getElementById('hidden-hobby');
            const hobbyForm = document.getElementById('hobby-form');
            hiddenHobby.value = hobbyValue;
            hobbyForm.submit();
        }

        // Clear input
        hobbyInput.value = '';
    });
</script>
@endsection

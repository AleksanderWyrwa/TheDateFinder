@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Profile Information
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                View and manage your personal profile details.
            </p>
        </div>

        <!-- Profile Info Section -->
        <div class="border-t border-gray-200">
            <dl>
<!-- Full Name -->
<div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        {{ $user->personalProfile->first_name ?? 'N/A' }}
                    </dd>
                </div>

                <!-- Birth Date -->
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Birth Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        {{ $user->personalProfile ? \Carbon\Carbon::parse($user->personalProfile->birth_date)->format('M d, Y') : 'N/A' }}
                    </dd>
                </div>

                <!-- Height -->
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Height</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        {{ $user->personalProfile->height ?? 'N/A' }} cm
                    </dd>
                </div>

                <!-- Weight -->
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Weight</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        {{ $user->personalProfile->weight ?? 'N/A' }} kg
                    </dd>
                </div>

                <!-- Description -->
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        {{ $user->personalProfile->description ?? 'N/A' }}
                    </dd>
                </div>

                <!-- Hobbies -->
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Hobbies</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <div id="hobby-list" class="flex flex-wrap gap-2">
                            @forelse($user->hobbies as $hobby)
                                <span 
                                    class="inline-block bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-sm cursor-pointer hover:bg-red-200 hover:text-red-800 transition-colors duration-200 ease-in-out delete-hobby"
                                    data-hobby-id="{{ $hobby->id }}"
                                >
                                    {{ $hobby->name }}
                                </span>
                            @empty
                                <span class="text-gray-500">No hobbies added.</span>
                            @endforelse
                        </div>

                        <!-- Add Hobby Input and Button -->
                        <div class="mt-4 flex items-center">
                            <form action="{{ route('profile.addHobby') }}" method="POST" id="hobby-form">
                                @csrf
                                <input
                                    type="text"
                                    name="hobby"
                                    id="new-hobby"
                                    placeholder="Enter a hobby"
                                    class="border p-2 rounded mr-2"
                                />
                                <button
                                    type="submit"
                                    class="bg-blue-500 text-white px-4 py-2 rounded"
                                >
                                    +
                                </button>
                            </form>
                        </div>
                    </dd>
                </div>
            </dl>

            <div class="mt-4 px-4 py-3 text-right sm:px-6">
                <a href="{{ route('profile.edit') }}" class="bg-blue-500 text-white p-2 rounded">Edit Profile</a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
        // Zbieramy wszystkie elementy hobby, które mają klasę 'delete-hobby'
        const hobbyElements = document.querySelectorAll('.delete-hobby');

        hobbyElements.forEach(hobbyEl => {
            hobbyEl.addEventListener('click', function () {
                const hobbyId = this.dataset.hobbyId; // Pobieramy ID hobby z atrybutu data-hobby-id
                const confirmed = confirm('Are you sure you want to delete this hobby?'); // Potwierdzenie usunięcia

                if (confirmed) {
                    // Wysyłamy zapytanie POST do serwera, aby usunąć hobby
                    fetch(`{{ route('profile.removeHobby', ['hobbyId' => '__HOBBY_ID__']) }}`.replace('__HOBBY_ID__', hobbyId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}', // Dodajemy token CSRF w nagłówku
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ hobby_id: hobbyId }),  // Przekazujemy hobby_id w ciele zapytania
                    })
                    .then(response => response.json())  // Parsowanie odpowiedzi serwera jako JSON
                    .then(data => {
                        if (data.success) {
                            this.remove();  // Jeśli operacja się powiodła, usuwamy element z DOM
                        } else {
                            alert('Failed to delete hobby: ' + data.message);  // Jeśli coś poszło nie tak, wyświetlamy komunikat
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);  // Logujemy szczegóły błędu w konsoli
                        alert('An error occurred. Please try again later.');  // Informujemy użytkownika o błędzie
                    });
                }
            });
        });
    });
</script>
@endsection

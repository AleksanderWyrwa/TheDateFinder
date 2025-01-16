@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4">
    <div class="max-w-2xl mx-auto">
        @if($unmatchedUser && $unmatchedUser->personalProfile)
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="bg-blue-500 text-white p-6">
                    <h3 class="text-2xl font-semibold text-center">{{ $unmatchedUser->personalProfile->first_name }}'s Profile</h3>
                </div>

                <div class="p-6">
                    <!-- User Name -->
                    <section class="mb-4">
                        <h5 class="font-bold text-lg">Name:</h5>
                        <p>{{ $unmatchedUser->personalProfile->first_name }} {{ $unmatchedUser->personalProfile->last_name }}</p>
                    </section>

                    <!-- Date of Birth -->
                    <section class="mb-4">
                        <h5 class="font-bold text-lg">Date of Birth:</h5>
                        <p class="text-gray-500">{{ \Carbon\Carbon::parse($unmatchedUser->personalProfile->birth_date)->format('F j, Y') }}</p>
                    </section>

                    <!-- Description -->
                    <section class="mb-4">
                        <h5 class="font-bold text-lg">Description:</h5>
                        <p>{{ $unmatchedUser->personalProfile->description }}</p>
                    </section>

                    <!-- Hobbies -->
                    <section class="mb-4">
                        <h5 class="font-bold text-lg">Hobbies:</h5>
                        <div class="mt-2">
                            @forelse($unmatchedUser->personalProfile->hobbies ?? [] as $hobby)
                                <span class="bg-blue-200 text-blue-800 text-sm px-3 py-1 rounded-full mr-2">{{ $hobby->name }}</span>
                            @empty
                                <span class="bg-gray-200 text-gray-600 text-sm px-3 py-1 rounded-full">No hobbies listed</span>
                            @endforelse
                        </div>
                    </section>

                    <!-- Meeting Availabilities -->
                    <section class="mb-4">
                        <h5 class="font-bold text-lg">Meeting Availabilities:</h5>
                        <div class="mt-2">
                            @if($unmatchedUser->meetingAvailabilities && $unmatchedUser->meetingAvailabilities->isNotEmpty())
                                <ul class="list-none pl-0">
                                    @foreach($unmatchedUser->meetingAvailabilities as $availability)
                                        <li class="mb-2">
                                            <span class="bg-green-200 text-green-800 text-sm px-3 py-1 rounded-full">
                                                {{ \Carbon\Carbon::parse($availability->date)->format('l, F j, Y') }} - {{ $availability->type }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="bg-gray-200 text-gray-600 text-sm px-3 py-1 rounded-full">No availabilities listed</span>
                            @endif
                        </div>
                    </section>

                    <!-- Action Buttons (like/dislike) -->
                    <section class="flex space-x-4 mt-6">
                        <form action="{{ route('matches.like', $unmatchedUser->id) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-green-500 text-white font-bold py-3 rounded-md hover:bg-green-600 transition duration-300" aria-label="Like this profile">
                                Like
                            </button>
                        </form>
                        <form action="{{ route('matches.dislike', $unmatchedUser->id) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-red-500 text-white font-bold py-3 rounded-md hover:bg-red-600 transition duration-300" aria-label="Dislike this profile">
                                Dislike
                            </button>
                        </form>
                    </section>
                </div>
            </div>
        @else
            <div class="mt-6 p-4 bg-yellow-100 text-yellow-800 rounded-lg">
                <p class="text-lg font-semibold">No unmatched users found. Please check back later.</p>
            </div>
        @endif
    </div>
</div>
@endsection

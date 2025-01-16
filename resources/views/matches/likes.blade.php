@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4">
    <div class="max-w-2xl mx-auto">
        @if($likedUsers->isNotEmpty())
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="bg-blue-500 text-white p-6">
                    <h3 class="text-2xl font-semibold text-center">Users who liked you</h3>
                </div>

                <div class="p-6">
                    @foreach($likedUsers as $likedUser)
                        @if($likedUser->personalProfile) <!-- Sprawdzamy, czy personalProfile istnieje -->
                            <div class="mb-6">
                                <h5 class="font-bold text-lg">{{ $likedUser->personalProfile->first_name }} {{ $likedUser->personalProfile->last_name }}</h5>
                                <p>{{ $likedUser->personalProfile->description }}</p>

                                <!-- Opcje: Akceptuj/Odrzuć -->
                                <div class="flex space-x-4">
                                    <form action="{{ route('matches.accept', $likedUser->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-500 text-white font-bold py-3 rounded-md hover:bg-green-600 transition duration-300">Accept</button>
                                    </form>
                                    <form action="{{ route('matches.reject', $likedUser->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-red-500 text-white font-bold py-3 rounded-md hover:bg-red-600 transition duration-300">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="mb-6">
                                <p>No profile information available for this user.</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @else
            <div class="mt-6 p-4 bg-yellow-100 text-yellow-800 rounded-lg">
                <p class="text-lg font-semibold">No one has liked you yet. Check back later!</p>
            </div>
        @endif
    </div>
</div>
@endsection

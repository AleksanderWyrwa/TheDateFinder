@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-gray-900">Find Matches</h2>

        @if(session('status'))
            <div class="bg-green-100 text-green-800 p-4 rounded-md mb-4">
                {{ session('status') }}
            </div>
        @endif

        @if($unmatchedUsers->isEmpty())
            <div class="text-center text-red-500">
                No new users available for matching.
            </div>
        @else
            <div class="space-y-4">
                @foreach ($unmatchedUsers as $user)
                    <div class="border p-4 rounded-lg mb-4">
                        <div>
                            <strong>Name:</strong> {{ $user->name }} <br>
                            <strong>Email:</strong> {{ $user->email }} <br>
                            <strong>Description:</strong> {{ $user->personalProfile->description ?? 'No description' }} <br>
                        </div>

                        <form action="{{ route('matches.like', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Like</button>
                        </form>

                        <form action="{{ route('matches.dislike', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md">Dislike</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

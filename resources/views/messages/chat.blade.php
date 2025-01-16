<!-- resources/views/messages/chat.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-semibold mb-4">Czat</h1>

            <div class="space-y-4">
                @foreach ($messages as $message)
                    <div class="p-4 bg-gray-100 rounded-md">
                        <p><strong>{{ $message->user->name }}</strong>: {{ $message->message }}</p>
                        <p class="text-sm text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                <!-- Formularz wysyłania wiadomości -->
                <form method="POST" action="{{ route('messages.send') }}">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $receiverId }}">
                    <textarea name="message" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Napisz wiadomość..."></textarea>
                    <button type="submit" class="mt-2 bg-blue-500 text-white p-2 rounded-md">Wyślij</button>
                </form>
            </div>
        </div>
    </div>
@endsection

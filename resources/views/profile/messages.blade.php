@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-2xl font-semibold mb-6 text-center text-gray-800">Chat</h1>

            <!-- Lista zmatchowanych użytkowników -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-3">Matched users:</h2>
                <ul class="space-y-3">
                    @foreach ($matchedUsers as $matchedUser)
                        @if ($matchedUser->id !== $user->id)
                            <li class="hover:bg-gray-100 rounded-lg transition duration-200">
                                <a href="{{ route('profile.messages', ['receiverId' => $matchedUser->id]) }}" class="block px-4 py-2 text-blue-600 hover:underline">
                                    {{ $matchedUser->name ?? 'Anonim' }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <!-- Wyświetlanie wiadomości tylko wtedy, gdy receiverId jest różne od 0 -->
            @if ($receiverId != 0)
                <div class="messages-container mb-8">
                    <h3 class="text-lg font-semibold mb-3 text-gray-800">Messages:</h3>

                    <!-- Wyświetlanie wiadomości -->
                    <div id="messages" class="space-y-4 max-h-96 overflow-y-auto bg-gray-50 p-4 rounded-lg shadow-inner">
                        @foreach ($messages as $message)
                            <div class="flex {{ $message->user_id == $user->id ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs md:max-w-md px-4 py-2 rounded-lg shadow-md text-sm
                                    {{ $message->user_id == $user->id ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
                                    <p>{{ $message->message }}</p>
                                </div>
                            </div>
                            <div class="flex {{ $message->user_id == $user->id ? 'justify-end' : 'justify-start' }}">
                                <span class="text-xs text-gray-500 mt-1">{{ $message->created_at->format('H:i') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Formularz do wysyłania wiadomości -->
                    <form action="{{ route('message.send') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $receiverId }}">
                        <textarea name="message" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none" rows="4" placeholder="Write a message, they're waiting..."></textarea>
                        <button type="submit" class="w-full mt-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200" aria-label="Send Message">
                            Send Message
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Skrypt do automatycznego przewijania -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const messagesContainer = document.getElementById('messages');
            if (messagesContainer) {
                // Scroll to the bottom if new messages exist
                messagesContainer.scrollTop = messagesContainer.scrollHeight;

                // Optional: If you want to keep it active after new messages come in
                const observer = new MutationObserver(() => {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                });
                observer.observe(messagesContainer, { childList: true });
            }
        });
    </script>
@endsection

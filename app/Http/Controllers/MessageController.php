<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;

class MessageController extends Controller
{
    public function getMessages($receiverId = null)
    {
        // Jeśli nie podano ID odbiorcy, wyświetl wszystkie rozmowy
        $user = auth()->user();
        
        if ($receiverId) {
            // Pobierz wiadomości z konkretnym odbiorcą
            $messages = Message::where(function($query) use ($user, $receiverId) {
                $query->where('user_id', $user->id)
                      ->where('receiver_id', $receiverId);
            })->orWhere(function($query) use ($user, $receiverId) {
                $query->where('user_id', $receiverId)
                      ->where('receiver_id', $user->id);
            })->get();
        } else {
            // Pobierz wszystkie rozmowy z użytkownikiem
            $messages = Message::where('user_id', $user->id)
                               ->orWhere('receiver_id', $user->id)
                               ->get();
        }

        // Zwróć widok z wiadomościami
        return view('messages.chat', compact('messages', 'receiverId'));
    }
}

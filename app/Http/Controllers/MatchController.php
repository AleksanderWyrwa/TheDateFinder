<?php

// app/Http/Controllers/MatchController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchController extends Controller
{
    // Like a user
    public function like(Request $request, $userId)
{
    $user = $request->user();
    $matchedUser = User::findOrFail($userId);

    // Ensure users are not liking themselves
    if ($user->id === $matchedUser->id) {
        return back()->with('status', 'You cannot like yourself.');
    }

    // Check if match already exists (either "liked" or "pending")
    $existingMatch = UserMatch::where(function($query) use ($user, $matchedUser) {
        $query->where('user_id', $user->id)
              ->where('matched_user_id', $matchedUser->id);
    })->orWhere(function($query) use ($user, $matchedUser) {
        $query->where('user_id', $matchedUser->id)
              ->where('matched_user_id', $user->id);
    })->first();

    if ($existingMatch) {
        return back()->with('status', 'You have already matched with this user.');
    }

    // Create a match record with status "pending" (for the "like" action)
    UserMatch::create([
        'user_id' => $user->id,
        'matched_user_id' => $matchedUser->id,
        'status' => 'pending', // Set status to "pending" for a like
    ]);

    return back()->with('status', 'You liked this user! The match is pending.');
}


public function dislike(Request $request, $userId)
    {
        $user = $request->user();
        $matchedUser = User::findOrFail($userId);

        // Ensure users are not disliking themselves
        if ($user->id === $matchedUser->id) {
            return back()->with('status', 'You cannot dislike yourself.');
        }

        // Check if match already exists
        $existingMatch = UserMatch::where(function($query) use ($user, $matchedUser) {
            $query->where('user_id', $user->id)
                ->where('matched_user_id', $matchedUser->id);
        })->orWhere(function($query) use ($user, $matchedUser) {
            $query->where('user_id', $matchedUser->id)
                ->where('matched_user_id', $user->id);
        })->first();

        if ($existingMatch) {
            return back()->with('status', 'You have already rejected or matched with this user.');
        }

        // Create a match record with status "rejected" (for the "dislike" action)
        UserMatch::create([
            'user_id' => $user->id,
            'matched_user_id' => $matchedUser->id,
            'status' => 'rejected', // Set status to "rejected" for a dislike
        ]);

        return back()->with('status', 'You disliked this user!');
    }

    public function showMatchedUsers(Request $request)
    {
        $user = auth()->user();

        // Pobierz użytkowników, którzy mają status "accepted" w tabeli "user_matches"
        $matchedUsers = User::whereHas('matches', function($query) use ($user) {
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('matched_user_id', $user->id);
            })
            ->where('status', 'accepted');
        })->get();

        return view('chat.index', compact('matchedUsers'));
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserMatch;
use App\Models\PersonalProfile;
use App\Models\Preference;
use App\Models\MeetingAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Hobby;
use App\Models\Message;

class ProfileController extends Controller
{
    public function acceptMatch(Request $request, $userId)
    {
        $user = $request->user();
        $matchedUser = User::findOrFail($userId);

        $match = UserMatch::where('user_id', $matchedUser->id)
                        ->where('matched_user_id', $user->id)
                        ->where('status', 'pending')
                        ->first();

        if ($match) {

            $match->update(['status' => 'accepted']);
            return back()->with('status', 'You have accepted this match!');
        }

        return back()->with('status', 'No match found to accept.');
    }

    public function rejectMatch(Request $request, $userId)
    {
        $user = $request->user();
        $matchedUser = User::findOrFail($userId);

        // Sprawdzamy, czy istnieje match z tym użytkownikiem
        $match = UserMatch::where('user_id', $matchedUser->id)
                        ->where('matched_user_id', $user->id)
                        ->where('status', 'pending')
                        ->first();

        if ($match) {
            $match->update(['status' => 'rejected']);
        }

        return back()->with('status', 'No match found to reject.');
    }


    // Wyświetlanie profili użytkowników, którzy nas polubili
    public function showLikes()
    {
        $user = auth()->user();

        // Get all users who are in an accepted match status
        $likedUsers = User::with('personalProfile') // Load profiles
            ->whereHas('matches', function($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                    ->orWhere('matched_user_id', $user->id);
                })
                ->where('status', 'pending');
            })
            ->get();

        return view('matches.likes', compact('likedUsers'));
    }
    public function showPendingMatches()
    {
        $user = auth()->user();

        // Pobierz wszystkich użytkowników, którzy mają status 'pending' i czekają na akceptację
        $pendingMatches = User::with('personalProfile', 'meetingAvailabilities', 'hobbies') // Ładuj profile, dostępność spotkań i hobby
            ->whereHas('matches', function($query) use ($user) {
                $query->where('matched_user_id', $user->id)
                    ->where('status', 'pending');
            })
            ->get();

        return view('matches.pending', compact('pendingMatches'));
    }

    public function viewProfile(Request $request)
    {
        $user = $request->user();
        $user->load('personalProfile', 'preferences', 'meetingAvailabilities', 'hobbies');
        return view('profile.view', compact('user'));
    }

    public function edit(Request $request)
    {
        $user = $request->user();
        $personalProfile = $user->personalProfile ?? new PersonalProfile();
        $preferences = $user->preferences ?? new Preference();
        $meetingAvailabilities = $user->meetingAvailabilities;
        $hobbies = $user->hobbies;

        return view('profile.edit', [
            'user' => $user,
            'personalProfile' => $personalProfile,
            'preferences' => $preferences,
            'meetingAvailabilities' => $meetingAvailabilities,
            'hobbies' => $hobbies,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'description' => 'required|string',
            'height' => 'required|numeric|min:50|max:300',
            'weight' => 'required|numeric|min:30|max:300',
        ]);

        $user = $request->user();

        $user->personalProfile()->updateOrCreate([], [
            'first_name' => $validated['first_name'],
            'birth_date' => $validated['birth_date'],
            'description' => $validated['description'],
            'height' => $validated['height'],
            'weight' => $validated['weight'],
        ]);

        return Redirect::route('profile.view')->with('status', 'Profile updated successfully!');
    }

    public function create()
    {
        if (auth()->user()->personalProfile) {
            return redirect()->route('profile.edit')->with('status', 'You already have a personal profile.');
        }
        return view('profile.create');
    }

    public function addHobby(Request $request)
    {
        $request->validate([
            'hobby' => 'required|string|max:255',
        ]);

        $hobbyName = $request->input('hobby');

        $hobby = Hobby::firstOrCreate(['name' => $hobbyName]);

        $user = auth()->user();
        $user->hobbies()->attach($hobby->id);

        return back()->with('status', 'Hobby added successfully!');
    }

    // ProfileController.php

    public function removeHobby(Request $request, $hobbyId)
    {
        // Sprawdź, czy użytkownik ma taki hobby
        $request->validate([
            'hobby_id' => 'required|exists:hobbies,id',
        ]);

        $user = auth()->user();

        // Detach hobby z użytkownika
        $user->hobbies()->detach($hobbyId);

        // Zwróć odpowiedź w formacie JSON
        return response()->json([
            'success' => true,
            'message' => 'Hobby deleted successfully!'
        ]);
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'description' => 'required|string',
            'height' => 'required|numeric|min:50|max:300',
            'weight' => 'required|numeric|min:30|max:300',
        ]);

        $user = $request->user();

        $user->personalProfile()->create([
            'first_name' => $validated['first_name'],
            'birth_date' => $validated['birth_date'],
            'description' => $validated['description'],
            'height' => $validated['height'],
            'weight' => $validated['weight'],
        ]);

        return Redirect::route('profile.edit')->with('status', 'Personal profile created successfully!');
    }

    public function showMeetingAvailability()
    {
        $user = auth()->user(); // Get the authenticated user
        return view('profile.meeting_availability', compact('user'));
    }

    // Store new meeting availability
    public function storeMeetingAvailability(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|string|max:255',
        ]);

        $availability = new MeetingAvailability();
        $availability->user_id = auth()->id();
        $availability->date = $validated['date'];
        $availability->type = $validated['type'];
        $availability->save();

        return redirect()->route('profile.meetingAvailability')->with('status', 'Meeting Availability Added!');
    }

    // Edit meeting availability
    public function editMeetingAvailability($id)
    {
        $availability = MeetingAvailability::findOrFail($id);
        return view('profile.edit_meeting_availability', compact('availability'));
    }

    // Update meeting availability
    public function updateMeetingAvailability(Request $request, $id)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|string|max:255',
        ]);

        $availability = MeetingAvailability::findOrFail($id);
        $availability->date = $validated['date'];
        $availability->type = $validated['type'];
        $availability->save();

        return redirect()->route('profile.meetingAvailability')->with('status', 'Meeting Availability Updated!');
    }

    // Delete meeting availability
    public function deleteMeetingAvailability($id)
    {
        $availability = MeetingAvailability::findOrFail($id);
        $availability->delete();

        return redirect()->route('profile.meetingAvailability')->with('status', 'Meeting Availability Deleted!');
    }

    // Show users who haven't been matched with the authenticated user
    public function showUnmatchedUser(Request $request)
    {
        $user = $request->user();
    
        // Fetch the first unmatched user (you can customize this logic to show a random user or the next user)
        $unmatchedUser = User::whereNotIn('id', function ($query) use ($user) {
            $query->select('matched_user_id')
                ->from('user_matches')
                ->where('user_id', $user->id);
        })
        ->where('id', '!=', $user->id)  // Dodano warunek, aby pominąć aktualnego użytkownika
        ->first();  // Tylko pierwszy użytkownik
    
        return view('profile.matching', compact('unmatchedUser'));
    }


    // Like a user
    public function likeUser(Request $request, $userId)
    {
        $user = $request->user();
        $matchedUser = User::findOrFail($userId);

        // Ensure users are not liking themselves
        if ($user->id === $matchedUser->id) {
            return back()->with('status', 'You cannot like yourself.');
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
            return back()->with('status', 'You have already matched with this user.');
        }

        // Create a match record with "liked" status
        UserMatch::create([
            'user_id' => $user->id,
            'matched_user_id' => $matchedUser->id,
            'status' => 'liked', // Mark as liked
        ]);

        return back()->with('status', 'You liked this user!');
    }

    // Dislike a user
    public function dislikeUser(Request $request, $userId)
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
            return back()->with('status', 'You have already matched with this user.');
        }

        // Create a match record with "disliked" status
        UserMatch::create([
            'user_id' => $user->id,
            'matched_user_id' => $matchedUser->id,
            'status' => 'disliked', // Mark as disliked
        ]);

        return back()->with('status', 'You disliked this user!');
    }

    // Match request
    public function matchUser(Request $request, $userId)
    {
        $user = auth()->user();
        $matchedUser = User::findOrFail($userId);

        if ($user->id === $matchedUser->id) {
            return back()->with('status', 'You cannot match with yourself.');
        }

        // Check if the match already exists
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

        // Create a match request
        UserMatch::create([
            'user_id' => $user->id,
            'matched_user_id' => $matchedUser->id,
            'status' => 'pending', // The match is pending
        ]);

        return back()->with('status', 'Match request sent!');
    }

    // Show messages between the authenticated user and the selected receiver
    public function messages($receiverId = 0)
    {
        // Pobierz aktualnie zalogowanego użytkownika
        $user = auth()->user();
    
        if (!$user) {
            return redirect()->route('login');
        }
    
        // Pobierz użytkowników, z którymi masz wzajemne dopasowanie
        $matchedUsers = User::whereHas('matches', function ($query) use ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('matched_user_id', '!=', $user->id)
                  ->where('status', 'accepted');
            })
            ->orWhere(function ($q) use ($user) {
                $q->where('matched_user_id', $user->id)
                  ->where('user_id', '!=', $user->id)
                  ->where('status', 'accepted');
            });
        })->get();
    
        // Jeśli receiverId jest różne od 0, pobierz wiadomości
        $messages = collect();
        if ($receiverId != 0) {
            $messages = Message::where(function ($query) use ($user, $receiverId) {
                $query->where('user_id', $user->id)
                      ->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($query) use ($user, $receiverId) {
                $query->where('user_id', $receiverId)
                      ->where('receiver_id', $user->id);
            })
            ->orderBy('created_at')
            ->get();
        }
    
        return view('profile.messages', [
            'user' => $user,
            'matchedUsers' => $matchedUsers,
            'receiverId' => $receiverId,
            'messages' => $messages,
        ]);
    }


    // Send a new message
    public function sendMessage(Request $request)
    {
        // Validation of the message
        $request->validate([
            'message' => 'required|string|max:255',
            'receiver_id' => 'required|exists:users,id'
        ]);
        
        // Get the authenticated user and the receiver
        $user = auth()->user();
        $receiver = User::find($request->receiver_id);
        
        // Create the message
        $message = new Message();
        $message->user_id = $user->id;
        $message->receiver_id = $receiver->id;
        $message->message = $request->message;
        $message->save();
        
        // Redirect back to the chat page with the receiver
        return redirect()->route('profile.messages', ['receiverId' => $receiver->id]);
    }

    public function showAllUsers(Request $request)
    {
        $this->authorize('viewAny', User::class); // Sprawdzamy, czy użytkownik ma uprawnienia do przeglądania

        $users = User::with('personalProfile')->get(); // Pobierz wszystkich użytkowników wraz z ich profilami

        return view('admin.users.index', compact('users'));
    }

    // Zmiana roli użytkownika na admina
    public function assignAdminRole($userId)
    {
        $user = User::findOrFail($userId);

        // Ustawiamy rolę na 'admin', można zmienić to na zgodne z systemem ról w aplikacji
        $user->update(['role' => 'admin']);

        return back()->with('status', 'User role updated to admin.');
    }

    // Usuwanie konta użytkownika
    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        // Przed usunięciem użytkownika, możemy chcieć usunąć powiązane dane, np. profil, dostępności, hobby itp.
        $user->delete();

        return back()->with('status', 'User account deleted successfully.');
    }
}

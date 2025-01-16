<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated user routes
Route::middleware('auth')->group(function () {
    // Show profile of a user by ID (public profile)
    Route::get('/user/{id}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/match', [ProfileController::class, 'showUnmatchedUser'])->name('profile.match');
    Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
    
    // Handle the "Like" and "Dislike" actions
    Route::post('/matches/{matchedUserId}/like', [MatchController::class, 'like'])->name('matches.like');
    Route::post('/matches/{matchedUserId}/dislike', [MatchController::class, 'dislike'])->name('matches.dislike');

    // Show the logged-in user's profile
    Route::get('/profile', [ProfileController::class, 'viewProfile'])->name('profile.view');
    
    // Edit the logged-in user's profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    
    // Update the logged-in user's profile
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    
    // Add a new personal profile
    Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile/store', [ProfileController::class, 'store'])->name('profile.store');
    
    // Remove a hobby from the logged-in user's profile
    Route::post('/profile/hobby/{hobbyId}/remove', [ProfileController::class, 'removeHobby'])->name('profile.removeHobby');
    
    // Delete the logged-in user's account
    Route::post('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.delete');

    Route::get('/profile/meeting-availability', [ProfileController::class, 'showMeetingAvailability'])->name('profile.meetingAvailability');
    
    // Store meeting availability
    Route::post('/profile/meeting-availability', [ProfileController::class, 'storeMeetingAvailability'])->name('profile.storeMeetingAvailability');

    // Edit meeting availability
    Route::get('/profile/meeting-availability/{id}/edit', [ProfileController::class, 'editMeetingAvailability'])->name('profile.editMeetingAvailability');
    
    // Update meeting availability
    Route::put('/profile/meeting-availability/{id}', [ProfileController::class, 'updateMeetingAvailability'])->name('profile.updateMeetingAvailability');
    
    // Delete meeting availability
    Route::delete('/profile/meeting-availability/{id}', [ProfileController::class, 'deleteMeetingAvailability'])->name('profile.deleteMeetingAvailability');
    Route::post('/profile/hobby/add', [ProfileController::class, 'addHobby'])->name('profile.addHobby');
    Route::post('/matches/{userId}/accept', [ProfileController::class, 'acceptMatch'])->name('matches.accept');
    Route::post('/matches/{userId}/reject', [ProfileController::class, 'rejectMatch'])->name('matches.reject');

    Route::get('/matches/pending', [ProfileController::class, 'showPendingMatches'])->name('matches.pending');

    Route::get('/matches/likes', [ProfileController::class, 'showLikes'])->name('matches.likes');

    // Messaging system
    // Show messages for a specific receiver
    Route::get('/profile/messages/{receiverId?}', [ProfileController::class, 'messages'])->name('profile.messages');

    // Send a message to a user
    Route::post('/messages/send', [ProfileController::class, 'sendMessage'])->name('message.send');
    
    // Admin Routes (without middleware)
    Route::get('/admin', function () {
        // Sprawdzamy, czy użytkownik jest administratorem
        if (auth()->user()->role === 'admin') {
            return view('admin.dashboard', [
                'users' => \App\Models\User::paginate(3) // Paginacja użytkowników
            ]);
        }
        return redirect('/');
    })->name('admin.dashboard');

    Route::get('/admin/edit-profile/{id}', function ($id) {
        // Sprawdzamy, czy użytkownik jest administratorem
        if (auth()->user()->role === 'admin') {
            $user = \App\Models\User::findOrFail($id);
            return view('admin.edit-profile', compact('user'));
        }
        return redirect('/');
    })->name('admin.editProfile');

    Route::get('/admin/make-admin/{id}', function ($id) {
        // Sprawdzamy, czy użytkownik jest administratorem
        if (auth()->user()->role === 'admin') {
            $user = \App\Models\User::find($id);
            if ($user) {
                $user->role = 'admin'; // Nadanie roli admina
                $user->save();
            }
            return redirect()->route('admin.dashboard');
        }
        return redirect('/');
    })->name('admin.makeAdmin');

    Route::get('/admin/delete-user/{id}', function ($id) {
        // Sprawdzamy, czy użytkownik jest administratorem
        if (auth()->user()->role === 'admin') {
            $user = \App\Models\User::find($id);
            if ($user) {
                $user->delete(); // Usunięcie użytkownika
            }
            return redirect()->route('admin.dashboard');
        }
        return redirect('/');
    })->name('admin.deleteUser');
});

require __DIR__.'/auth.php';

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function personalProfile()
    {
        return $this->hasOne(PersonalProfile::class);
    }

    public function preferences()
    {
        return $this->hasOne(Preference::class);
    }

    public function hobbies()
    {
        return $this->belongsToMany(Hobby::class, 'user_hobbies');
    }

    public function meetingAvailabilities()
    {
        return $this->hasMany(MeetingAvailability::class);
    }

    // User's matches (initiated by this user)
    public function matches()
    {
        return $this->hasMany(UserMatch::class, 'user_id');  // Using 'UserMatch' model instead of 'Match'
    }

    // Users who have matched with this user
    public function matchedWith()
    {
        return $this->hasMany(UserMatch::class, 'matched_user_id');  // Using 'UserMatch' model instead of 'Match'
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'user_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
    
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}

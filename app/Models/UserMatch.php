<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matched_user_id',
        'status',
    ];

    // User relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Matched user relationship
    public function matchedUser()
    {
        return $this->belongsTo(User::class, 'matched_user_id');
    }
}

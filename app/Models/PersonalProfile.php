<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'birth_date',
        'description',
        'height',
        'weight',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

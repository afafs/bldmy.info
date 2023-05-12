<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'day',
        'start',
        'end',
        'max_booking'
    ];
}

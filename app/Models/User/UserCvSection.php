<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCvSection extends Model
{
    use HasFactory;

    public function user_cv() {
        return $this->belongsTo('App\Models\User\UserCv');
    }
}

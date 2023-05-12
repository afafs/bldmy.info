<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVcard extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function user_vcard_services() {
        return $this->hasMany('App\Models\User\UserVcardService');
    }

    public function user_vcard_projects() {
        return $this->hasMany('App\Models\User\UserVcardProject');
    }

    public function user_vcard_testimonials() {
        return $this->hasMany('App\Models\User\UserVcardTestimonial');
    }

  
}

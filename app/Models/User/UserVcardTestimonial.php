<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVcardTestimonial extends Model
{
    use HasFactory;
    protected $fillable= [
        'user_vcard_id',
        'image',
        'name',
        'rating',
        'comment',
        'serial_number',
    ];

    public function user_vcard() {
        return $this->belongsTo('App\Models\User\UserVcard');
    }
}

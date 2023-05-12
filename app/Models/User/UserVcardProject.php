<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVcardProject extends Model
{
    use HasFactory;
    protected $fillable= [
        'user_vcard_id',
        'image',
        'title',
        'external_link_status',
        'external_link',
        'short_details',
        'serial_number',
    ];

    public function user_vcard() {
        return $this->belongsTo('App\Models\User\UserVcard');
    }
}

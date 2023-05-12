<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Controllers\Controller;
use App\Models\User\AppointmentBooking;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'image',
        'username',
        'email',
        'email_verified_at',
        'password',
        'contact_number',
        'address',
        'city',
        'state',
        'country',
        'status',
        'verification_token',
        'remember_token',
        'verification_link',
        'user_id',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPaymentGateway extends Model
{

    use HasFactory;
    protected $fillable = ['title', 'user_id', 'details', 'subtitle', 'name', 'type', 'information', 'keyword', 'status'];

    public function convertAutoData()
    {
        return json_decode($this->information, true);
    }
}

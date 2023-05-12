<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOfflinePaymentGateway extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'user_id', 'name', 'short_description', 'instructions', 'serial_number', 'status', 'is_receipt', 'receipt'];
}

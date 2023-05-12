<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'type', 'value', 'start_date', 'end_date', 'packages', 'maximum_uses_limit', 'total_uses'];
}

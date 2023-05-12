<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormInput extends Model
{
    use HasFactory;

    public function form_input_options()
    {
        return $this->hasMany(FormInputOption::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}

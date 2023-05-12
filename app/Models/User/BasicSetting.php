<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class BasicSetting extends Model
{
    public $table = "user_basic_settings";

    protected $fillable = [
        'user_id',
        'favicon',
        'logo',
        'cv',
        'base_color',
        'theme',
        'website_title',
        'email',
        'from_name'
    ];

    public function language()
    {
        return $this->hasMany('App\Models\User\Language', 'user_id');
    }
}

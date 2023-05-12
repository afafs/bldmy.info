<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEO extends Model
{
    use HasFactory;
    protected $table = 'user_seos';
    public $timestamps = false;

    protected $fillable = [
     'user_id',
      'language_id',
      'home_meta_keywords',
      'home_meta_description',
      'services_meta_keywords',
      'services_meta_description',
      'blogs_meta_keywords',
      'blogs_meta_description',
      'portfolios_meta_keywords',
      'portfolios_meta_description',
      'about_meta_keywords',
      'about_meta_description',
      'experience_meta_keywords',
      'experience_meta_description',
      'testimonial_meta_keywords',
      'testimonial_meta_description'
    ];
}

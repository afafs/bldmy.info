<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraMetaInformationColsInUserSeos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_seos', function (Blueprint $table) {
            $table->text('about_meta_keywords')->nullable();
            $table->text('about_meta_description')->nullable();
            $table->text('experience_meta_keywords')->nullable();
            $table->text('experience_meta_description')->nullable();
            $table->text('testimonial_meta_keywords')->nullable();
            $table->text('testimonial_meta_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_seos', function (Blueprint $table) {
            $table->dropColumn(['about_meta_keywords','about_meta_description','experience_meta_keywords','experience_meta_description','testimonial_meta_keywords','testimonial_meta_description']);
        });
    }
}

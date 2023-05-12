<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCvOriginalToUserBasicSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_basic_settings', function (Blueprint $table) {
            $table->string('cv_original', 100)->nullable()->after('cv');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_basic_settings', function (Blueprint $table) {
            $table->dropColumn('cv_original');
        });
    }
}

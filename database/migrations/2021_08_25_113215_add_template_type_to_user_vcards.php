<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemplateTypeToUserVcards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_vcards', function (Blueprint $table) {
            $table->tinyInteger('template')->after('slug_id')->default(1)->comment('number represents the template number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_vcards', function (Blueprint $table) {
            $table->dropColumn('template');
        });
    }
}

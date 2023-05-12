<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModifiedToMemberships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->tinyInteger('modified')->default(0)->comment('1 - modified by Admin, 0 - not modified by Admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn('modified');
        });
    }
}

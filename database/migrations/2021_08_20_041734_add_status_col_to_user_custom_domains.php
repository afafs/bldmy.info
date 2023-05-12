<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColToUserCustomDomains extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_custom_domains', function (Blueprint $table) {
            $table->tinyInteger('status')->comment('0 - Pending, 1 - Connected, 2 - Rejected')->default(0)->after('current_domain');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_custom_domains', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}

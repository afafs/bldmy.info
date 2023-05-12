<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomDomainColsInUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('custom_domain')->nullable()->after('email_verified');
            $table->tinyInteger('custom_domain_status')->default(0)->comment('0 - pending, 1 - approved, 2 - rejeced')->after('custom_domain');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['custom_domain', 'custom_domain_status']);
        });
    }
}

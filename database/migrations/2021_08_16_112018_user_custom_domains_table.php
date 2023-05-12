<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserCustomDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_custom_domains', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('requested_domain')->nullable();
            $table->string('current_domain')->nullable();
            $table->tinyInteger('status')->comment('0 - Requested, 1 - Connected, 2 - Rejected')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_custom_domains');
    }
}

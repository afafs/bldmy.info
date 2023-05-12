<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPaymentGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('title')->nullable();
            $table->text('details')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->mediumText('information')->nullable();
            $table->mediumText('keyword')->nullable();
            $table->tinyInteger('status')->nullable();
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
        Schema::dropIfExists('user_payment_gateways');
    }
}

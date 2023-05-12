<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOfflinePaymentGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_offline_payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('name')->nullable();
            $table->text('short_description')->nullable();
            $table->text('instructions')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->integer('serial_number')->nullable();
            $table->tinyInteger('is_receipt')->nullable();
            $table->string('receipt')->nullable();
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
        Schema::dropIfExists('user_offline_payment_gateways');
    }
}

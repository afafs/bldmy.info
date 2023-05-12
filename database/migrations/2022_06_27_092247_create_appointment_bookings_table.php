<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->decimal('amount')->nullable();
            $table->decimal('due_amount')->nullable();
            $table->string('transaction_id')->nullable();
            $table->text('transaction_details')->nullable();
            $table->string('category_id')->nullable();
            $table->longText('details')->nullable();
            $table->string('currency')->nullable();
            $table->string('receipt')->nullable();
            $table->string('payment_method')->nullable();
            $table->tinyInteger('status')->nullable()->comment('0=pending, 1=completed, 2=advance paid, 3=rejected');
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
        Schema::dropIfExists('appointment_bookings');
    }
}

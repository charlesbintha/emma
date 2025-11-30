<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tontine_subscription_id')->constrained()->onDelete('cascade');
            $table->integer('payment_number');
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->timestamp('payment_date')->nullable();
            $table->enum('status', ['pending', 'paid', 'late', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('reference')->nullable();
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
        Schema::dropIfExists('payments');
    }
}

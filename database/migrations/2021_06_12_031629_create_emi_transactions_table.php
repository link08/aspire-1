<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmiTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emi_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('loan_id');
            $table->float('emi');
            $table->string('transaction_id')->unique()->nullable();
            $table->string('payment_mode')->nullable();
            $table->enum('status', array(
                'GENERATED',
                'TRANSACTION_PENDING',
                'TRANSACTION_SUCCESS',
                'TRANSACTION_FAILED'
            ))->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emi_transactions');
    }
}

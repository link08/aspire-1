<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('amount');
            $table->bigInteger('amount_paid');
            $table->bigInteger('loan_term');
            $table->float('interest_rate');
            $table->enum('repayment_type', array(
                'WEEKLY',
                'MONTHLY',
                'ANNUALLY'
            ))->nullable();
            $table->enum('status', array(
                'PENDING',
                'APPROVED',
                'REJECTED',
                'CLOSED'
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
        Schema::dropIfExists('loans');
    }
}

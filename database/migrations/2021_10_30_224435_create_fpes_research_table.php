<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFpesResearchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fpes_research', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('involvement_id');
            $table->unsignedBigInteger('evaluation_period_id');
            $table->text('description');
            $table->date('from');
            $table->date('to');
            $table->integer('status');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('owner_id');
            $table->timestamps();

            $table->foreign('involvement_id')->references('id')->on('involvements')->onDelete('cascade');
            $table->foreign('evaluation_period_id')->references('id')->on('evaluation_periods')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fpes_research');
    }
}

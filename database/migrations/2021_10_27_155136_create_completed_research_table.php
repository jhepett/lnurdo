<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompletedResearchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('completed_research', function (Blueprint $table) {
            $table->id();
            $table->text('author');
            $table->string('paper_title');
            $table->text('keyword');
            $table->string('semester');
            $table->unsignedBigInteger('owner_id');
            $table->date('date_publication');
            $table->string('file_path');
            $table->longtext('file_names');
            $table->unsignedBigInteger('status');
            $table->text('remarks')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('completed_research');
    }
}

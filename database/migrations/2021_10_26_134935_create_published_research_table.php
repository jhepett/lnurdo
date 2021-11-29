<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublishedResearchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('published_research', function (Blueprint $table) {
            $table->id();
            $table->text('author');
            $table->string('publication_title');
            $table->string('journal_title');
            $table->string('vol_no');
            $table->string('issn');
            $table->string('site');
            $table->string('pages');
            $table->string('indexing');
            $table->text('keyword');
            $table->string('school_year');
            $table->string('semester');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('owner_id');
            $table->date('date_publication');
            $table->string('file_path');
            $table->longtext('file_names');
            $table->unsignedBigInteger('status');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
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
        Schema::dropIfExists('published_research');
    }
}

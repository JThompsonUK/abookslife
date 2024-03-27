<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->uuid('reference')->unique();
            $table->string('book_title')->nullable();
            $table->string('book_author')->nullable();
            $table->string('book_genre')->nullable();
            $table->text('description')->nullable();
            $table->string('published')->nullable();
            $table->integer('user_id_owner')->index();
            $table->string('book_cover')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}

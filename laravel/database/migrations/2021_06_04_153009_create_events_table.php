<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreign('event_author_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('event_calendar_id')->references('id')->on('calendar')->onUpdate('cascade')->onDelete('cascade');
            $table->string('event_content')->default('');
            $table->string('event_title')->default('');
            $table->dateTime('event_date');
            $table->enum('event_category', ['arrangement', 'reminder', 'task'])->default('reminder');
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
        Schema::dropIfExists('comments');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('login')->unique()->default('');
            $table->string('real_name')->default('');
            $table->string('email')->unique();
            //$table->timestamp('email_verified_at')->nullable();
            $table->string('token', 600)->nullable();
            $table->string('password');
            $table->integer('rating')->default(0);
            $table->string('password_reset_token')->nullable();
            $table->string('image_path')->default("avatars/standart.png");
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

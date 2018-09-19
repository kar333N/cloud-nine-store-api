<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_files', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description')->nullable();
            $table->string('email');
            $table->string('hash_user', 32);
            $table->string('hash_file', 32);
            $table->string('file_name');
            $table->tinyInteger('file_presence', false, true)->default('0');
            $table->tinyInteger('number_parts', false, true)->default('0');
            $table->timestamps();
            $table->index('email');
            $table->index('hash_user');
            $table->index('hash_file');
            $table->index('file_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user-files');
    }
}

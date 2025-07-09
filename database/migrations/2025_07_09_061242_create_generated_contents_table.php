<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('generated_contents', function (Blueprint $table) {
        $table->id();
        $table->string('tema');
        $table->string('gaya');
        $table->string('judul');
        $table->text('konten');
        $table->text('deskripsi')->nullable();
        $table->text('hashtag')->nullable();
        $table->timestamps();
    });
}


};
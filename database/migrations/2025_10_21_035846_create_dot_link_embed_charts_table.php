<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dot_link_embed_charts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bagan_id');
            $table->foreign('bagan_id')->references('id')->on('bagan_lists')->onDelete('cascade');
            $table->integer('from');
            $table->integer('to');
            $table->integer('rootId')->nullable();
            $table->enum('template',['blue','yellow','orange'])->default('orange');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dot_link_embed_charts');
    }
};

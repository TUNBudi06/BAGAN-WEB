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
        Schema::create('slink_chart_embeds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bagan_id');
            $table->foreign('bagan_id')->references('id')->on('bagan_lists')->onDelete('cascade');
            $table->string('label')->nullable();
            $table->integer('from');
            $table->integer('to');
            $table->enum('template',['blue','yellow','orange'])->default('orange');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slink_chart_embeds');
    }
};

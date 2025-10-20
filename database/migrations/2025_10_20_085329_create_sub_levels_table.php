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
        Schema::create('sub_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bagan_list_id');
            $table->foreign('bagan_list_id')->references('id')->on('bagan_lists')->onDelete('cascade');
            $table->string('name');
            $table->integer('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_levels');
    }
};

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
        Schema::create('link_chart_embeds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bagan_list_id');
            $table->foreign('bagan_list_id')->references('id')->on('bagan_lists')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('chart_id');
            $table->integer('chart_pid')->nullable();
            $table->integer('chart_ppid')->nullable();
            $table->integer('chart_stpid')->nullable();
            $table->unsignedBigInteger('template_bagan_id')->nullable();
            $table->foreign('template_bagan_id')->references('id')->on('template_bagans')->onUpdate('cascade')->onDelete('set null');
            $table->unsignedBigInteger('sub_level_id')->nullable();
            $table->foreign('sub_level_id')->references('id')->on('sub_levels')->onUpdate('cascade')->onDelete('set null');
            $table->unsignedBigInteger('node_type')->nullable();
            $table->foreign('node_type')->references('id')->on('template_bagans')->onUpdate('cascade')->onDelete('set null');
            $table->enum('type',['user','text'])->default('text');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('user_bagan_lists')->onUpdate('cascade')->onDelete('set null');
            $table->string('label')->nullable();
            $table->string('name')->nullable();
            $table->string('nik')->nullable();
            $table->string('team')->nullable();
            $table->string('img')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_chart_embeds');
    }
};

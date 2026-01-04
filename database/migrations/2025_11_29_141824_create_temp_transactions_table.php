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
        Schema::create('temp_transactions', function (Blueprint $table) {
            $table->id();
            $table->datetime('date_time')->index();
            $table->string('description')->nullable();
            $table->integer('debit')->default(0)->index();
            $table->integer('credit')->default(0)->index();
            $table->string('tag')->default('others')->index();
            $table->string('status')->index();
            $table->string('channel')->default('others');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_transactions');
    }
};

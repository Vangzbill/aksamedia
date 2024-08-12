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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id()->primary();
            $table->uuid('uuid');
            $table->string('name');
            $table->string('phone');
            $table->unsignedBigInteger('division_id');
            $table->string('position');
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('division_id')->references('id')->on('divisi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};

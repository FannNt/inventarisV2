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
        Schema::create('car_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->references('id')->on('cars')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->integer('total');
            $table->foreignId('kategori_id')->references('id')->on('car_services_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('bengkel')->nullable();
            $table->json('image')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamp('service_at') ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_services');
    }
};

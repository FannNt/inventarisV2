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
        Schema::create('report_service_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_item_id')->references('id')->on('service_items')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('car_service_id')->references('id')->on('car_services')->onDelete('cascade')->onUpdate('cascade');
            $table->double('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_service_items');
    }
};

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
        Schema::create('configures', function (Blueprint $table) {
            $table->string('id',10)->primary();
            $table->foreignId('item_id')->references('id')->on('item_inventaris')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamp('expired_at');
            $table->string('lab_name');
            $table->timestamp('calibrate_at')->default(now());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configures');
    }
};

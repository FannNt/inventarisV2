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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('uuid',10)->unique();
            $table->string('no_seri')->nullable();
            $table->string('name');
            $table->foreignId('id_ruangan')->references('id')->on('ruangans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_item_status')->references('id')->on('item_statuses')->onDelete('cascade')->onUpdate('cascade');
            $table->string('merk');
            $table->string('type');
            $table->year('tahun_pengadaan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

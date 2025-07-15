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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('atas_nama');
            $table->string('type');
            $table->string('nopol');
            $table->year('tahun_perakitan');
            $table->year('tahun_pembelian');
            $table->string('bahan_bakar');
            $table->string('warna');
            $table->date('tanggal_pajak');
            $table->enum('fungsi',['ambulance', 'pribadi']);
            $table->integer('odometer');
            $table->uuid('nomor_rangka')->unique();
            $table->uuid('nomor_mesin')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};

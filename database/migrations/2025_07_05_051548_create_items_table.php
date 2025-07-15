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
            $table->string('id',10)->primary();
            $table->string('no_seri')->nullable();
            $table->string('name');
            $table->integer('jumlah')->default(1);
            $table->string('merk_id');
            $table->foreign('merk_id')->references('id')->on('merks')->onDelete('cascade');
            $table->string('type')->nullable();
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

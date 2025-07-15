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
        Schema::create('item_inventaris', function (Blueprint $table) {
            $table->string('id',10)->primary();
            $table->string('ruangan_id');
            $table->foreign('ruangan_id')->references('id')->on('ruangans')->onDelete('cascade')->onUpdate('cascade');
            $table->string('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('items_status_id')->references('id')->on('item_statuses')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('asal_barang',['Beli','Bantuan','Hibah']);
            $table->date('tgl_pengadaan');
            $table->double('harga');
            $table->integer('no_rak');
            $table->integer('no_box');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_inventaris');
    }
};

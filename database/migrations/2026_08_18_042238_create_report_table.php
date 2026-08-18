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
        Schema::create('report', function (Blueprint $table) {
            $table->id('id_report');
            $table->foreignId('reporter_id')->constrained('users', 'id_user');
            $table->foreignId('reported_id')->constrained('users', 'id_user');
            $table->string('category')->nullable();
            $table->string('deskripsi');
            $table->string('bukti_gambar')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report');
    }
};

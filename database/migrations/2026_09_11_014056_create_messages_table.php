<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
      Schema::create('messages', function (Blueprint $table) {
    $table->id();

    // Tuliskan 'id_user' sebagai argumen kedua pada constrained()
    $table->foreignId('sender_id')->constrained('users', 'id_user')->onDelete('cascade');
    $table->foreignId('receiver_id')->constrained('users', 'id_user')->onDelete('cascade');

    $table->text('message');
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
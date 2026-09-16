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
       Schema::table('users', function (Blueprint $table) {
    if (!Schema::hasColumn('users', 'display_name')) {
        $table->string('display_name')->nullable();
    }
    if (!Schema::hasColumn('users', 'avatar')) {
        $table->string('avatar')->nullable();
    }
    if (!Schema::hasColumn('users', 'bio')) {
        $table->text('bio')->nullable();
    }
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};

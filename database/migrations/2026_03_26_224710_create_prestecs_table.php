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
        Schema::create('prestecs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuari_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('llibre_id')->constrained('llibres')->cascadeOnDelete();
            $table->boolean('actiu');
            $table->datetime('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestecs');
    }
};

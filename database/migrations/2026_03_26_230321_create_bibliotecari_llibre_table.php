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
        Schema::create('bibliotecari_llibre', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bibliotecari_id')->constrained('bibliotecaris')->cascadeOnDelete();
            $table->foreignId('llibre_id')->constrained('llibres')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['bibliotecari_id', 'llibre_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bibliotecari_llibre');
    }
};

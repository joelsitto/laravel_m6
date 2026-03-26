<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registre_temps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('data');
            $table->decimal('hores', 5, 2);
            $table->text('descripcio')->nullable();
            $table->timestamps();

            $table->index(['ticket_id', 'user_id', 'data']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registre_temps');
    }
};


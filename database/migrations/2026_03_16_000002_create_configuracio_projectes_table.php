<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracio_projectes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projecte_id')->unique()->constrained('projectes')->cascadeOnDelete();
            $table->enum('plantilla_correus', ['FORMAL', 'INFORMAL', 'TECNICA'])->default('FORMAL');
            $table->boolean('notificacions_actives')->default(true);
            $table->text('workflow_personalitzat')->nullable();
            $table->boolean('requereix_aprovacio_client')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracio_projectes');
    }
};

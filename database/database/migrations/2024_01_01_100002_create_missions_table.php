<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description');
            $table->string('adresse');
            $table->string('type_mission');
            $table->decimal('prix_deplacement', 10, 2)->nullable();
            $table->date('date_mission');
            $table->enum('statut', ['en_attente', 'en_cours', 'en_pause', 'suspendue', 'terminee'])->default('en_attente');
            $table->boolean('is_groupe')->default(false);
            $table->foreignId('chef_equipe_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};

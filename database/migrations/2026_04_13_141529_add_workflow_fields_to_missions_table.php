<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('missions', function (Blueprint $table) {
            // Ajout des horodatages
            $table->timestamp('started_at')->nullable()->after('date_mission');
            $table->timestamp('work_finished_at')->nullable()->after('started_at');
            $table->timestamp('submitted_at')->nullable()->after('work_finished_at');
            $table->timestamp('validated_at')->nullable()->after('submitted_at');

            // Extension du statut (MySQL raw pour la compatibilité InfinityFree)
            DB::statement("ALTER TABLE missions MODIFY COLUMN statut ENUM('en_attente', 'en_cours', 'en_pause', 'suspendue', 'soumis', 'a_modifier', 'terminee') DEFAULT 'en_attente'");
        });
    }

    public function down(): void
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->dropColumn(['started_at', 'work_finished_at', 'submitted_at', 'validated_at']);
            DB::statement("ALTER TABLE missions MODIFY COLUMN statut ENUM('en_attente', 'en_cours', 'en_pause', 'suspendue', 'terminee') DEFAULT 'en_attente'");
        });
    }
};

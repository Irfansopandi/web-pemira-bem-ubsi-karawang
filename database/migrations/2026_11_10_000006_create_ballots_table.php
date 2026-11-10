<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Strictly Anonymous Ballots Table
     * LUBER JURDIL: NO student_nim, student_id, or user foreign keys!
     */
    public function up(): void
    {
        Schema::create('ballots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_period_id')->constrained('election_periods')->onDelete('restrict');
            $table->foreignId('candidate_pair_id')->constrained('candidate_pairs')->onDelete('restrict');
            $table->string('ballot_hash', 64)->unique();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['election_period_id', 'candidate_pair_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ballots');
    }
};


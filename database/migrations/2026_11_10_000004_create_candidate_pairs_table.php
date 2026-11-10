<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_pairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_period_id')->constrained('election_periods')->onDelete('restrict');
            $table->unsignedTinyInteger('candidate_number');
            $table->string('chairman_name');
            $table->string('chairman_nim', 20);
            $table->string('chairman_study_program', 100);
            $table->string('vice_chairman_name');
            $table->string('vice_chairman_nim', 20);
            $table->string('vice_chairman_study_program', 100);
            $table->string('photo_url')->nullable();
            $table->text('vision');
            $table->json('mission');
            $table->json('work_programs')->nullable();
            $table->timestamps();

            $table->unique(['election_period_id', 'candidate_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_pairs');
    }
};


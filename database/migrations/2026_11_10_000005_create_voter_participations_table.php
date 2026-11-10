<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voter_participations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_period_id')->constrained('election_periods')->onDelete('restrict');
            $table->string('student_nim', 20);
            $table->foreign('student_nim')->references('nim')->on('students')->onDelete('restrict');
            $table->boolean('has_voted')->default(false)->index();
            $table->dateTime('voted_at')->nullable();
            $table->text('encrypted_vote_receipt')->nullable();
            $table->foreignId('verified_by_admin_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->dateTime('verified_at')->nullable();
            $table->string('tps_location')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->unique(['election_period_id', 'student_nim']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voter_participations');
    }
};


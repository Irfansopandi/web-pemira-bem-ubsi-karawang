<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->string('nim', 20)->primary();
            $table->string('name');
            $table->string('study_program', 100);
            $table->string('class_group', 20)->nullable();
            $table->string('academic_year', 20)->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();

            $table->index(['study_program', 'class_group']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};


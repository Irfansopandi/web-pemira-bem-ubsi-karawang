<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('asal_kampus', 100)->nullable()->after('semester');
            $table->string('email_kampus', 150)->nullable()->after('asal_kampus');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['asal_kampus', 'email_kampus']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('election_periods', function (Blueprint $table) {
            if (!Schema::hasColumn('election_periods', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('election_periods', 'is_active')) {
                $table->boolean('is_active')->default(false)->after('status')->index();
            }
            if (!Schema::hasColumn('election_periods', 'voting_status')) {
                $table->enum('voting_status', ['opened', 'closed'])->default('closed')->after('is_active')->index();
            }
            $table->dateTime('start_time')->nullable()->change();
            $table->dateTime('end_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('election_periods', function (Blueprint $table) {
            if (Schema::hasColumn('election_periods', 'voting_status')) {
                $table->dropColumn('voting_status');
            }
            if (Schema::hasColumn('election_periods', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('election_periods', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};

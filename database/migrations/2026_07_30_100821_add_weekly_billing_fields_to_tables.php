<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->integer('duration_weeks')->default(40);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->date('start_date')->nullable();
            $table->integer('duration_weeks')->default(40);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('duration_weeks');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'duration_weeks']);
        });
    }
};

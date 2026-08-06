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
        // 1. Create pivot table package_user (drop first if exists due to half-failed migration)
        Schema::dropIfExists('package_user');
        Schema::create('package_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->date('start_date');
            $table->integer('duration_weeks')->default(40);
            $table->timestamps();
        });

        // 2. Migrate existing data from users to package_user
        $users = DB::table('users')->whereNotNull('package_id')->get();
        foreach ($users as $user) {
            DB::table('package_user')->insert([
                'user_id' => $user->id,
                'package_id' => $user->package_id,
                'start_date' => $user->start_date ?? now()->format('Y-m-d'),
                'duration_weeks' => $user->duration_weeks ?? 40,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. We keep the old columns on the users table to avoid SQLite schema constraint violations.
        // They are no longer fillable and are ignored by the application code.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop pivot table
        Schema::dropIfExists('package_user');
    }
};

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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('author')->after('email');
            $table->json('permissions')->nullable()->after('role');
        });

        // Upgrade existing users to super-admin to avoid locking out the site owner
        \Illuminate\Support\Facades\DB::table('users')->update(['role' => 'super-admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'permissions']);
        });
    }
};

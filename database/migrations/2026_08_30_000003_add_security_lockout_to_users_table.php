<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'login_attempts')) {
                $table->unsignedTinyInteger('login_attempts')->default(0)->after('password');
            }
            if (!Schema::hasColumn('users', 'locked_until')) {
                $table->timestamp('locked_until')->nullable()->after('login_attempts');
            }
            if (!Schema::hasColumn('users', 'lockout_count')) {
                $table->unsignedTinyInteger('lockout_count')->default(0)->after('locked_until');
            }
            if (!Schema::hasColumn('users', 'otp_code')) {
                $table->string('otp_code', 10)->nullable()->after('lockout_count');
            }
            if (!Schema::hasColumn('users', 'otp_expires_at')) {
                $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['login_attempts', 'locked_until', 'lockout_count', 'otp_code', 'otp_expires_at']);
        });
    }
};

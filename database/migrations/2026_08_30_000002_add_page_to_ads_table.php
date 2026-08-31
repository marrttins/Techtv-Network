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
        if (!Schema::hasColumn('ads', 'page')) {
            Schema::table('ads', function (Blueprint $table) {
                $table->string('page')->default('home')->after('link');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('ads', 'page')) {
            Schema::table('ads', function (Blueprint $table) {
                $table->dropColumn('page');
            });
        }
    }
};

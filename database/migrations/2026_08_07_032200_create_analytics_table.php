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
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('impressions')->default(0);
            $table->timestamps();
        });

        // Seed mock historical data for the last 30 days
        $data = [];
        for ($i = 30; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $views = rand(300, 1500);
            $impressions = (int) ($views * (2 + (rand(0, 150) / 100)));

            $data[] = [
                'date' => $date,
                'views' => $views,
                'impressions' => $impressions,
                'created_at' => now()->subDays($i),
                'updated_at' => now()->subDays($i),
            ];
        }

        DB::table('analytics')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
};

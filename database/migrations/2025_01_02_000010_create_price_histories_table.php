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
        Schema::create('price_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meme_id')->constrained('memes')->onDelete('cascade');
            $table->decimal('price', 15, 5);
            $table->unsignedBigInteger('circulating_supply_snapshot');
            $table->enum('trigger_type', ['buy', 'sell', 'ipo']);
            $table->timestamp('recorded_at');
            $table->decimal('volume_24h', 15, 5)->default(0);
            $table->decimal('pct_change_24h', 10, 5)->default(0);
            
            // Composite index as specified in project
            $table->index(['meme_id', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_histories');
    }
};

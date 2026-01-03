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
        Schema::create('dividend_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meme_id')->constrained('memes')->onDelete('cascade');
            $table->decimal('amount_per_share', 15, 5);
            $table->decimal('total_distributed', 15, 5);
            $table->timestamp('distributed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dividend_histories');
    }
};

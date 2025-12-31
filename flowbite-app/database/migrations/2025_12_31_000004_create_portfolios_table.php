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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('meme_id')->constrained('memes')->onDelete('cascade');
            $table->unsignedBigInteger('quantity')->default(0);
            $table->decimal('avg_buy_price', 15, 5)->default(0.00000);
            $table->timestamps();

            $table->unique(['user_id', 'meme_id']);
            $table->index('user_id');
            $table->index('meme_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};

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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('meme_id')->nullable()->constrained('memes')->onDelete('set null');
            $table->enum('type', ['buy', 'sell', 'listing_fee', 'bonus', 'dividend']);
            $table->unsignedBigInteger('quantity')->nullable(); // Null per bonus/listing_fee
            $table->decimal('price_per_share', 16, 5)->nullable();
            $table->decimal('fee_amount', 15, 5)->default(0.00000);
            $table->decimal('total_amount', 15, 5); // Totale CFU della transazione
            $table->decimal('cfu_balance_after', 15, 5); // Saldo dopo operazione
            $table->timestamp('executed_at');

            $table->index('user_id');
            $table->index('meme_id');
            $table->index('executed_at');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

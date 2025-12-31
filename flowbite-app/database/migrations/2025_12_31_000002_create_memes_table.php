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
        Schema::create('memes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('title');
            $table->string('ticker', 10)->unique(); // Es. $DOGE, $PEPE
            $table->string('image_path'); // Relativo: {user_id}/{filename}
            $table->decimal('base_price', 15, 5)->default(1.00000);
            $table->decimal('slope', 15, 5)->default(0.10000); // Coefficiente volatilità M
            $table->decimal('current_price', 15, 5)->default(1.00000); // Cache prezzo corrente
            $table->unsignedBigInteger('circulating_supply')->default(0); // Azioni in circolazione
            $table->enum('status', ['pending', 'approved', 'suspended', 'delisted'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('trading_starts_at')->nullable(); // Quando inizia il trading
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('current_price');
            $table->index('circulating_supply');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memes');
    }
};

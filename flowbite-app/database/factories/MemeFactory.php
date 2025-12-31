<?php

namespace Database\Factories;

use App\Models\Meme;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meme>
 */
class MemeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $basePrice = fake()->randomFloat(5, 0.5, 5.0);
        
        return [
            'creator_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => fake()->sentence(3),
            'ticker' => '$' . strtoupper(fake()->unique()->lexify('????')),
            'image_path' => '', // Sarà impostato nel seeder
            'base_price' => $basePrice,
            'slope' => fake()->randomFloat(5, 0.01, 0.5),
            'current_price' => $basePrice,
            'circulating_supply' => 0,
            'status' => 'pending',
            'approved_at' => null,
            'approved_by' => null,
            'trading_starts_at' => null,
        ];
    }

    /**
     * Meme approvato e pronto per il trading
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_at' => now()->subDays(rand(1, 30)),
            'trading_starts_at' => now()->subDays(rand(0, 29)),
        ]);
    }

    /**
     * Meme con trading attivo (già approvato e con data passata)
     */
    public function tradeable(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_at' => now()->subDays(rand(2, 30)),
            'trading_starts_at' => now()->subDays(rand(1, 29)),
        ]);
    }

    /**
     * Meme sospeso
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
            'approved_at' => now()->subDays(rand(5, 30)),
            'trading_starts_at' => now()->subDays(rand(4, 29)),
        ]);
    }

    /**
     * Meme con azioni in circolazione
     */
    public function withSupply(int $supply): static
    {
        return $this->state(function (array $attributes) use ($supply) {
            $basePrice = $attributes['base_price'];
            $slope = $attributes['slope'];
            $currentPrice = $basePrice + ($slope * $supply);
            
            return [
                'circulating_supply' => $supply,
                'current_price' => $currentPrice,
            ];
        });
    }

    /**
     * Meme a bassa volatilità (blue chip)
     */
    public function lowVolatility(): static
    {
        return $this->state(fn (array $attributes) => [
            'slope' => fake()->randomFloat(5, 0.01, 0.05),
        ]);
    }

    /**
     * Meme ad alta volatilità (speculativo)
     */
    public function highVolatility(): static
    {
        return $this->state(fn (array $attributes) => [
            'slope' => fake()->randomFloat(5, 0.3, 1.0),
        ]);
    }
}

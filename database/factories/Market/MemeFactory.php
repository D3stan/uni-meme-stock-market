<?php

namespace Database\Factories\Market;

use App\Models\Market\Category;
use App\Models\Market\Meme;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Market\Meme>
 */
class MemeFactory extends Factory
{
    protected $model = Meme::class;

    public function definition(): array
    {
        return [
            'creator_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => fake()->words(3, true),
            'ticker' => strtoupper(fake()->unique()->lexify('????')),
            'image_path' => 'memes/default.jpg',
            'base_price' => 1.00,
            'slope' => 0.10,
            'current_price' => 1.00,
            'circulating_supply' => 0,
            'status' => 'approved', // Default to approved for testing
            'approved_at' => null, // Null by default to skip trading delay in tests
            'approved_by' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'approved_at' => null,
            'approved_by' => null,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_at' => null, // Null to skip trading delay in tests
            'approved_by' => null,
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }

    public function withSupply(int $supply): static
    {
        return $this->state(function (array $attributes) use ($supply) {
            $basePrice = $attributes['base_price'] ?? 1.00;
            $slope = $attributes['slope'] ?? 0.10;

            return [
                'circulating_supply' => $supply,
                'current_price' => $basePrice + ($slope * $supply),
            ];
        });
    }
}

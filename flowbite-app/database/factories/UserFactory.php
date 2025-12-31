<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'nickname' => fake()->optional(0.7)->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'trader',
            'cfu_balance' => 100.00000, // Bonus iniziale
            'is_suspended' => false,
            'profile_picture' => null,
            'status' => fake()->optional(0.5)->sentence(3),
            'last_daily_bonus_at' => null,
            'cached_net_worth' => 100.00000,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create an admin user.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'cfu_balance' => 10000.00000,
            'cached_net_worth' => 10000.00000,
        ]);
    }

    /**
     * Create a trader with specific balance.
     */
    public function withBalance(float $balance): static
    {
        return $this->state(fn (array $attributes) => [
            'cfu_balance' => $balance,
            'cached_net_worth' => $balance,
        ]);
    }

    /**
     * Create a suspended user.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_suspended' => true,
        ]);
    }
}

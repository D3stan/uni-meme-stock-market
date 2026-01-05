<?php

namespace App\Services;

use App\Models\Market\Category;
use App\Models\Market\Meme;
use App\Models\Financial\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateService
{
    /**
     * Get all categories.
     *
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return Category::all();
    }

    /**
     * Get user's current balance.
     *
     * @param User $user
     * @return float
     */
    public function getUserBalance(User $user): float
    {
        return $user->cfu_balance;
    }

    /**
     * Check if ticker already exists.
     *
     * @param string $ticker
     * @return bool
     */
    public function tickerExists(string $ticker): bool
    {
        return Meme::where('ticker', strtoupper($ticker))->exists();
    }

    /**
     * Create a new meme and store the image.
     *
     * @param array $data
     * @param UploadedFile $image
     * @param User $user
     * @return Meme
     * @throws \Exception
     */
    public function createMeme(array $data, UploadedFile $image, User $user): Meme
    {
        return DB::transaction(function () use ($data, $image, $user) {
            // Unique filename
            $extension = $image->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            
            // Store image in storage/app/public/data/{user_id}/
            $path = "data/{$user->id}";
            $image->storeAs($path, $filename, 'public');

            // Create meme record
            $meme = Meme::create([
                'creator_id' => $user->id,
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'ticker' => strtoupper($data['ticker']),
                'image_path' => $filename,
                'text_alt' => $data['text_alt'] ?? null,
                'base_price' => 10.00,
                'slope' => 0.01,
                'current_price' => 10.00,
                'circulating_supply' => 0,
                'status' => 'pending', 
            ]);

            // Deduct listing fee
            $user->decrement('cfu_balance', 20.00);

            // Create transaction record
            Transaction::create([
                'user_id' => $user->id,
                'meme_id' => $meme->id,
                'type' => 'listing_fee',
                'quantity' => null,
                'price_per_share' => null,
                'fee_amount' => 20.00,
                'total_amount' => -20.00,
                'cfu_balance_after' => $user->fresh()->cfu_balance,
                'executed_at' => now(),
            ]);

            return $meme;
        });
    }
}

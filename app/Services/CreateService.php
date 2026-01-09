<?php

namespace App\Services;

use App\Jobs\ProcessMemeWithAI;
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
    public const LISTING_FEE = 21.00;

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
     * Get the listing fee amount.
     *
     * @return float
     */
    public function getListingFee(): float
    {
        return self::LISTING_FEE;
    }

    /**
     * Check if user has sufficient funds to pay the listing fee.
     *
     * @param User $user
     * @return bool
     */
    public function hasSufficientFundsForListing(User $user): bool
    {
        return $user->cfu_balance >= self::LISTING_FEE;
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
        $meme = DB::transaction(function () use ($data, $image, $user) {
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

            // Create transaction for fee
            $user->decrement('cfu_balance', self::LISTING_FEE);
            Transaction::create([
                'user_id' => $user->id,
                'meme_id' => $meme->id,
                'type' => 'listing_fee',
                'quantity' => null,
                'price_per_share' => null,
                'fee_amount' => self::LISTING_FEE,
                'total_amount' => -self::LISTING_FEE,
                'cfu_balance_after' => $user->fresh()->cfu_balance,
                'executed_at' => now(),
            ]);

            return $meme;
        });

        ProcessMemeWithAI::dispatch($meme);

        return $meme;
    }
}

<?php

namespace App\Services;

use App\Jobs\ProcessMemeWithAI;
use App\Models\Financial\Transaction;
use App\Models\Market\Category;
use App\Models\Market\Meme;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateService
{
    /**
     * Get all categories.
     */
    public function getCategories(): Collection
    {
        return Category::all();
    }

    /**
     * Get user's current balance.
     */
    public function getUserBalance(User $user): float
    {
        return $user->cfu_balance;
    }

    /**
     * Check if ticker already exists.
     */
    public function tickerExists(string $ticker): bool
    {
        return Meme::where('ticker', strtoupper($ticker))->exists();
    }

    /**
     * Create a new meme, store the image, and process the listing fee transaction.
     *
     * @throws \Exception
     */
    public function createMeme(array $data, UploadedFile $image, User $user): Meme
    {
        $meme = DB::transaction(function () use ($data, $image, $user) {
            $extension = $image->getClientOriginalExtension();
            $filename = Str::uuid().'.'.$extension;

            $path = "data/{$user->id}";
            $image->storeAs($path, $filename, 'public');

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

            $user->decrement('cfu_balance', 20.00);
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

        ProcessMemeWithAI::dispatch($meme);

        return $meme;
    }
}

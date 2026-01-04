<?php

namespace Database\Seeders;

use App\Models\Market\Meme;
use App\Models\User;
use App\Models\Market\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class MemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $creators = User::where('role', 'trader')->get();
        $categories = Category::all();

        // Ensure storage directory exists
        if (!Storage::disk('public')->exists('memes')) {
            Storage::disk('public')->makeDirectory('memes');
        }

        $memes = [
            [
                'title' => 'Doge to the Moon',
                'ticker' => 'DOGE',
                'base_price' => 1.50,
                'slope' => 0.002,
                'current_price' => 2.45,
                'circulating_supply' => 500,
                'status' => 'approved',
                'approved_at' => now()->subDays(10),
            ],
            [
                'title' => 'Stonks Only Go Up',
                'ticker' => 'STONK',
                'base_price' => 5.00,
                'slope' => 0.005,
                'current_price' => 8.20,
                'circulating_supply' => 350,
                'status' => 'approved',
                'approved_at' => now()->subDays(15),
            ],
            [
                'title' => 'Pepe Vibes',
                'ticker' => 'PEPE',
                'base_price' => 0.50,
                'slope' => 0.001,
                'current_price' => 1.85,
                'circulating_supply' => 1000,
                'status' => 'approved',
                'approved_at' => now()->subDays(20),
            ],
            [
                'title' => 'Harold Pain',
                'ticker' => 'PAIN',
                'base_price' => 2.00,
                'slope' => 0.003,
                'current_price' => 1.20,
                'circulating_supply' => 200,
                'status' => 'approved',
                'approved_at' => now()->subDays(8),
            ],
            [
                'title' => 'Woman Yelling at Cat',
                'ticker' => 'YELL',
                'base_price' => 3.50,
                'slope' => 0.004,
                'current_price' => 5.80,
                'circulating_supply' => 450,
                'status' => 'approved',
                'approved_at' => now()->subDays(5),
            ],
            [
                'title' => 'Distracted Boyfriend',
                'ticker' => 'DIST',
                'base_price' => 4.00,
                'slope' => 0.006,
                'current_price' => 7.50,
                'circulating_supply' => 300,
                'status' => 'approved',
                'approved_at' => now()->subDays(3),
            ],
            [
                'title' => 'This is Fine',
                'ticker' => 'FINE',
                'base_price' => 2.50,
                'slope' => 0.002,
                'current_price' => 2.30,
                'circulating_supply' => 600,
                'status' => 'approved',
                'approved_at' => now()->subDays(12),
            ],
            [
                'title' => 'Surprised Pikachu',
                'ticker' => 'PIKA',
                'base_price' => 1.00,
                'slope' => 0.001,
                'current_price' => 2.10,
                'circulating_supply' => 800,
                'status' => 'approved',
                'approved_at' => now()->subDays(2),
            ],
            [
                'title' => 'Drake Hotline Bling',
                'ticker' => 'DRAKE',
                'base_price' => 6.00,
                'slope' => 0.008,
                'current_price' => 12.50,
                'circulating_supply' => 150,
                'status' => 'approved',
                'approved_at' => now()->subDays(1),
            ],
            [
                'title' => 'Two Buttons',
                'ticker' => 'BTN',
                'base_price' => 3.00,
                'slope' => 0.003,
                'current_price' => 4.20,
                'circulating_supply' => 400,
                'status' => 'approved',
                'approved_at' => now()->subDays(7),
            ],
            [
                'title' => 'Roll Safe',
                'ticker' => 'SAFE',
                'base_price' => 1.80,
                'slope' => 0.015,
                'current_price' => 2.90,
                'circulating_supply' => 80,
                'status' => 'approved',
                'approved_at' => now()->subDays(4),
            ],
            [
                'title' => 'Wojak Crying',
                'ticker' => 'WOJAK',
                'base_price' => 2.20,
                'slope' => 0.002,
                'current_price' => 1.90,
                'circulating_supply' => 550,
                'status' => 'approved',
                'approved_at' => now()->subDays(6),
            ],
            // Pending memes
            [
                'title' => 'Galaxy Brain',
                'ticker' => 'BRAIN',
                'base_price' => 2.80,
                'slope' => 0.004,
                'current_price' => 2.80,
                'circulating_supply' => 0,
                'status' => 'pending',
                'approved_at' => null,
            ],
            [
                'title' => 'Change My Mind',
                'ticker' => 'MIND',
                'base_price' => 3.20,
                'slope' => 0.003,
                'current_price' => 3.20,
                'circulating_supply' => 0,
                'status' => 'pending',
                'approved_at' => null,
            ],
            [
                'title' => 'Expanding Brain',
                'ticker' => 'XBRAIN',
                'base_price' => 1.90,
                'slope' => 0.002,
                'current_price' => 1.90,
                'circulating_supply' => 0,
                'status' => 'pending',
                'approved_at' => null,
            ],
            // Suspended memes
            [
                'title' => 'Inappropriate Content',
                'ticker' => 'INAP',
                'base_price' => 5.00,
                'slope' => 0.005,
                'current_price' => 5.00,
                'circulating_supply' => 0,
                'status' => 'suspended',
                'approved_at' => null,
            ],
            [
                'title' => 'Offensive Meme',
                'ticker' => 'OFF',
                'base_price' => 4.50,
                'slope' => 0.004,
                'current_price' => 4.50,
                'circulating_supply' => 0,
                'status' => 'suspended',
                'approved_at' => null,
            ],
        ];

        $textAlt = 'Frodo Baggins dal Signore degli Anelli sorride in modo complice e ironico. Sottotitolo: Va bene, tieniti pure i tuoi segreti.';

        foreach ($memes as $index => $memeData) {
            Meme::create([
                'creator_id' => $creators->random()->id,
                'category_id' => $categories->random()->id,
                'title' => $memeData['title'],
                'ticker' => $memeData['ticker'],
                'image_path' => 'meme.jpeg',
                'text_alt' => $textAlt,
                'base_price' => $memeData['base_price'],
                'slope' => $memeData['slope'],
                'current_price' => $memeData['current_price'],
                'circulating_supply' => $memeData['circulating_supply'],
                'status' => $memeData['status'],
                'approved_at' => $memeData['approved_at'],
                'approved_by' => $memeData['status'] === 'approved' ? $admin->id : null,
            ]);
        }
    }
}

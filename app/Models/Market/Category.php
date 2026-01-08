<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Retrieve all memes categorized under this category.
     *
     * @return HasMany<Meme>
     */
    public function memes(): HasMany
    {
        return $this->hasMany(Meme::class);
    }
}

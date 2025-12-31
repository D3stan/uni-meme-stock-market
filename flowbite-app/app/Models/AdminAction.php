<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AdminAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'action_type',
        'target_id',
        'target_type',
        'reason',
    ];

    /**
     * Admin who performed this action
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Target of this action (polymorphic)
     */
    public function target(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the target model instance
     */
    public function getTargetModelAttribute()
    {
        if (!$this->target_type || !$this->target_id) return null;
        return $this->target_type::find($this->target_id);
    }
}

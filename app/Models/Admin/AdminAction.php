<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AdminAction extends Model
{
    use HasFactory;

    // Only created_at, no updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'admin_id',
        'action_type',
        'target_id',
        'target_type',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /**
     * Retrieve the administrator who performed this action.
     *
     * @return BelongsTo<User, AdminAction>
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Retrieve the polymorphic entity that this action was performed on.
     *
     * @return MorphTo<Model, AdminAction>
     */
    public function target(): MorphTo
    {
        return $this->morphTo();
    }
}

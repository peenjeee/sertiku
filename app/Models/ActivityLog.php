<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject of the activity
     */
    public function subject(): MorphTo
    {
        return $this->morphTo('model');
    }

    /**
     * Log an activity
     */
    public static function log(
        string $action,
        ?string $description = null,
        $subject = null,
        array $properties = []
    ): self {
        $user = auth()->user();

        return self::create([
            'user_id'     => $user?->id,
            'action'      => $action,
            'model_type'  => $subject ? get_class($subject) : null,
            'model_id'    => $subject?->id ?? null,
            'description' => $description,
            'properties'  => $properties,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }

    /**
     * Get action icon
     */
    public function getIconAttribute(): string
    {
        return match ($this->action) {
            'login'              => '🔑',
            'logout'             => '🚪',
            'create_certificate' => '📜',
            'promote_admin'      => '⬆️',
            'demote_admin'       => '⬇️',
            'create_user'        => '👤',
            'update_settings'    => '⚙️',
            'create_ticket'      => '🎫',
            'blockchain_tx'      => '🔗',
            default              => '📝',
        };
    }

    /**
     * Get action color
     */
    public function getColorAttribute(): string
    {
        return match ($this->action) {
            'login'              => 'green',
            'logout'             => 'gray',
            'create_certificate' => 'blue',
            'promote_admin'      => 'purple',
            'demote_admin'       => 'red',
            'create_user'        => 'teal',
            'update_settings'    => 'yellow',
            default              => 'indigo',
        };
    }
}

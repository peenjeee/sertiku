<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportMessage extends Model
{
    protected $fillable = [
        'ticket_id',
        'sender_id',
        'message',
        'is_from_admin',
        'read_at',
    ];

    protected $casts = [
        'is_from_admin' => 'boolean',
        'read_at'       => 'datetime',
    ];

    /**
     * Get the ticket this message belongs to
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    /**
     * Get the sender of this message
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Mark this message as read
     */
    public function markAsRead(): void
    {
        if (! $this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }
}

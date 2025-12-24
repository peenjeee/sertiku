<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'file_path',
        'thumbnail_path',
        'orientation',
        'width',
        'height',
        'placeholders',
        'is_active',
        'usage_count',
        'sha256',
        'md5',
    ];

    protected $casts = [
        'placeholders' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns this template.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get certificates using this template.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Increment usage count.
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Get the full URL to the template file.
     */
    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Get the full URL to the thumbnail.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path ? asset('storage/' . $this->thumbnail_path) : null;
    }

    /**
     * Get SHA256 hash, generating it if missing.
     */
    public function getSha256Attribute($value): ?string
    {
        if ($value) {
            return $value;
        }

        if ($this->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->file_path)) {
            $path = \Illuminate\Support\Facades\Storage::disk('public')->path($this->file_path);
            $hash = hash_file('sha256', $path);

            // Save to DB to avoid re-calculating
            $this->sha256 = $hash;
            $this->saveQuietly();

            return $hash;
        }

        return null;
    }

    /**
     * Get MD5 hash, generating it if missing.
     */
    public function getMd5Attribute($value): ?string
    {
        if ($value) {
            return $value;
        }

        if ($this->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->file_path)) {
            $path = \Illuminate\Support\Facades\Storage::disk('public')->path($this->file_path);
            $hash = md5_file($path);

            // Save to DB to avoid re-calculating
            $this->md5 = $hash;
            $this->saveQuietly();

            return $hash;
        }

        return null;
    }
}

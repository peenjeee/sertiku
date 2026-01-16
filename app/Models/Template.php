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
        // Position fields
        'name_position_x',
        'name_position_y',
        'name_font_size',
        'name_font_color',
        'is_name_visible',
        'is_qr_visible',
        'name_font_family',
        'qr_position_x',
        'qr_position_y',
        'qr_size',
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
        if (\Illuminate\Support\Facades\Route::has('lembaga.template.image')) {
            return route('lembaga.template.image', $this->id);
        }
        return '';
    }

    /**
     * Get the full URL to the thumbnail.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail_path) {
            if (\Illuminate\Support\Facades\Route::has('lembaga.template.thumbnail')) {
                return route('lembaga.template.thumbnail', $this->id);
            }
        }
        return null;
    }

    /**
     * Get SHA256 hash, generating it if missing.
     */
    public function getSha256Attribute($value): ?string
    {
        if ($value) {
            return $value;
        }

        if ($this->file_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($this->file_path)) {
            $path = \Illuminate\Support\Facades\Storage::disk('local')->path($this->file_path);
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

        if ($this->file_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($this->file_path)) {
            $path = \Illuminate\Support\Facades\Storage::disk('local')->path($this->file_path);
            $hash = md5_file($path);

            // Save to DB to avoid re-calculating
            $this->md5 = $hash;
            $this->saveQuietly();

            return $hash;
        }

        return null;
    }
}

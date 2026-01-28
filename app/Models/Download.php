<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'file_path',
        'mime_type',
        'is_active',
        'downloads_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'downloads_count' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active downloads.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the public URL for the download.
     *
     * @return string
     */
    public function getPublicUrlAttribute()
    {
        return route('downloads.public', $this->slug);
    }
}

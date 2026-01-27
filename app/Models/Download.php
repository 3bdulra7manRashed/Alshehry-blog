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
        'downloads_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'downloads_count' => 'integer',
    ];

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

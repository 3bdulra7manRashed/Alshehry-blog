<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Subscriber extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'unsubscribe_token',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active subscribers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Generate unsubscribe token if not exists.
     */
    public function ensureUnsubscribeToken(): string
    {
        if (empty($this->unsubscribe_token)) {
            $this->unsubscribe_token = Str::random(64);
            $this->save();
        }

        return $this->unsubscribe_token;
    }

    /**
     * Generate the unsubscribe link for this subscriber.
     *
     * @return string
     */
    public function generateUnsubscribeLink(): string
    {
        // Ensure we have a token
        $token = $this->ensureUnsubscribeToken();

        return route('newsletter.unsubscribe', [
            'token' => $token,
        ]);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate unsubscribe token on creation
        static::creating(function ($subscriber) {
            if (empty($subscriber->unsubscribe_token)) {
                $subscriber->unsubscribe_token = Str::random(64);
            }
        });
    }
}

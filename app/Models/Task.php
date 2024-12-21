<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'description',
        'priority',
        'status',
        'due_date',
        'access_token',
        'token_expires_at'
    ]; 
    protected $casts = [
        'due_date' => 'date',
        'token_expires_at' => 'datetime'
    ];
    public function generateAccessToken($expiresInHours = 24): void
    {
        $this->access_token = bin2hex(random_bytes(16));
        $this->token_expires_at = now()->addHours($expiresInHours);
        $this->save();
    }
    public function isTokenValid($token): bool
    {
        return $this->access_token === $token && $this->token_expires_at && $this->token_expires_at->isFuture();
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }
    public function histories(): HasMany
    {
        return $this->hasMany(related: TaskHistory::class);
    }
    public function getDueDateAttribute($value): string
    {
        return \Carbon\Carbon::parse(time: $value)->format(format: 'Y-m-d');
    }
    
}
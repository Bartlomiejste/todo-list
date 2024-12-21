<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'priority', 'status', 'due_date', 'access_token', 'token_expires_at'
    ];
    
    // Method generate token
    public function generateAccessToken($expiresInHours = 24)
    {
        $this->access_token = bin2hex(random_bytes(16));
        $this->token_expires_at = now()->addHours($expiresInHours);
        $this->save();
    }
    
    // Checked token
    public function isTokenValid($token)
    {
        return $this->access_token === $token && $this->token_expires_at && $this->token_expires_at->isFuture();
    }
    

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'due_date' => 'date',
        'token_expires_at' => 'datetime'
    ];

    /**
     * Get the user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function histories()
    {
    return $this->hasMany(TaskHistory::class);
    }

    public function getDueDateAttribute($value)
{
    return \Carbon\Carbon::parse($value)->format('Y-m-d');
}


}
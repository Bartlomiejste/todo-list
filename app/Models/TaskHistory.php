<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'field',
        'old_value',
        'new_value',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
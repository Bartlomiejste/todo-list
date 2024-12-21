<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskReminder;
use Illuminate\Console\Command;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';
    protected $description = 'Send email reminders for tasks due tomorrow';
        
    public function handle(): bool
    {
        $tasks = Task::whereDate('due_date', '=', now()->addDay()->toDateString())->get();
    
        foreach ($tasks as $task) {
            $task->user->notify((new TaskReminder($task))->onQueue('emails'));  
        }
    
        $this->info('Task reminders queued successfully!');
        
        return self::SUCCESS;
    }
    
}
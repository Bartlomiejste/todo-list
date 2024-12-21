<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskReminder extends Notification
{
    use Queueable;

    private $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject('Task Reminder')
        ->line('Hello! This is a reminder for your task:')
        ->line("Task: {$this->task->name}")
        ->line("Due date: {$this->task->due_date}")
        ->action('View Task', route('tasks.show', ['task' => $this->task->id]))
        ->line('Thank you for using our application!')
        ->salutation('Best regards, Your Todo-list');
    }
}
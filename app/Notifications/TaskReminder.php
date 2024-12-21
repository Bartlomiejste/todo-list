<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskReminder extends Notification implements ShouldQueue
{
    use Queueable;
    private $task;
    public function __construct($task)
    {
        $this->task = $task;
    }
    public function via($notifiable): array
    {
        return ['mail'];
    }
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject(subject: 'Task Reminder')
        ->line(line: 'Hello! This is a reminder for your task:')
        ->line(line: "Task: {$this->task->name}")
        ->line(line: "Due date: {$this->task->due_date}")
        ->action(text: 'View Task', url: route(name: 'tasks.show', parameters: ['task' => $this->task->id]))
        ->line(line: 'Thank you for using our application!')
        ->salutation(salutation: 'Best regards, Your Todo-list');
    }
}
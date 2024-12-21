<?php

namespace Tests\Feature\Console\Commands;

use App\Console\Commands\SendTaskReminders;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskReminder;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendTaskRemindersTest extends TestCase
{
    use RefreshDatabase;

    /** #[Test] */
    public function it_queues_reminders_for_tasks_due_tomorrow(): void
    {

        Notification::fake();

        $user = User::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $user->id,
            'due_date' => now()->addDay()->toDateString(),
        ]);

        $this->artisan(SendTaskReminders::class)
            ->expectsOutput('Task reminders queued successfully!')
            ->assertExitCode(Command::SUCCESS);

        Notification::assertSentTo(
            $user,
            TaskReminder::class,
            function ($notification) use ($task) {
                return $notification->task->id === $task->id;
            }
        );
    }

    /** #[Test] */
    public function it_does_not_send_reminders_for_tasks_due_on_other_days(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        Task::factory()->create([
            'user_id' => $user->id,
            'due_date' => now()->addDays(2)->toDateString(),
        ]);

        $this->artisan(SendTaskReminders::class)
            ->expectsOutput('Task reminders queued successfully!')
            ->assertExitCode(Command::SUCCESS);

        Notification::assertNothingSent();
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(302);
        
    }

}
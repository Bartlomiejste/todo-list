<?php

namespace App\Http\Controllers;


use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $query = auth()->user()->tasks();
    
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
    
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }
    
        $tasks = $query->paginate(10);
    
        return view('tasks.index', compact('tasks'));
    }

    public function create(): View
    {
        return view('tasks.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:to-do,in progress,done',
            'due_date' => 'required|date',
        ]);
    
        auth()->user()->tasks()->create($validated);
    
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task): View
    {
        $this->authorize('view', $task);
    
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task): View
    {
        $this->authorize('update', $task);
    
        return view('tasks.edit', compact('task'));
    }
    
    public function update(TaskUpdateRequest $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);
    
        $changes = [];
        foreach ($request->validated() as $field => $newValue) {
            $oldValue = $task->{$field};
            if ($oldValue !== $newValue) {
                $changes[] = [
                    'task_id' => $task->id,
                    'field' => $field,
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
    
        if (!empty($changes)) {
            \App\Models\TaskHistory::insert($changes);
        }
    
        $task->update($request->validated());
    
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }
    
    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);
    
        if ($task->google_event_id) {
        try {
            $event = Event::find($task->google_event_id);
            if ($event) {
                $event->delete();
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error removing event from Google Calendar: ' . $e->getMessage());
        }
    }

        $task->delete();
    
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function history(Task $task): View
    {
    $this->authorize('view', $task);

    $history = $task->histories()->orderBy('created_at', 'desc')->get();

    return view('tasks.history', compact('task', 'history'));
    }

    public function generateLink(Task $task): RedirectResponse
    {
    $this->authorize('update', $task);

    $task->generateAccessToken();

    return back()->with('success', 'Public link generated successfully.');
    }

    public function viewPublicTask(Task $task, Request $request): View
    {
    $token = $request->query('token');

    if (!$task->isTokenValid($token)) {
        abort(403, 'Invalid or expired token.');
    }

    return view('tasks.public', compact('task'));
    } 

    public function syncWithGoogleCalendar(Task $task): RedirectResponse
    {
        $this->authorize('update', $task);
    
        try {

            $startDateTime = \Carbon\Carbon::parse($task->due_date);
            $endDateTime = $startDateTime->copy()->addHour();
    
            $event = Event::create([
                'name' => $task->name,
                'startDateTime' => $startDateTime,
                'endDateTime' => $endDateTime,
                'description' => $task->description,
            ]);
    

            $task->google_event_id = $event->id;
            $task->save();
    
            $eventUrl = "https://calendar.google.com/calendar/";
    
            return back()->with('success', "Task added to Google Calendar successfully. <a href='{$eventUrl}' target='_blank'>View in Google Calendar</a>");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to add task to Google Calendar: ' . $e->getMessage());
        }
    }
}
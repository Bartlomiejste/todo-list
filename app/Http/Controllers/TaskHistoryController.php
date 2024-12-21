<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskHistoryController extends Controller
{
    public function index(Task $task)
    {
        $this->authorize('view', $task);


        $history = $task->histories()->orderBy('created_at', 'desc')->get();

        return view('tasks.history', compact('task', 'history'));
    }
}
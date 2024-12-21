@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ isset($task) ? 'Edit Task' : 'Create Task' }}</h1>

        <form method="POST" action="{{ isset($task) ? route('tasks.update', $task) : route('tasks.store') }}">
            @csrf
            @if (isset($task))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Task Name</label>
                <input type="text" name="name" id="name" class="form-control"
                    value="{{ old('name', $task->name ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control">{{ old('description', $task->description ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label">Priority</label>
                <select name="priority" id="priority" class="form-select" required>
                    <option value="low" {{ old('priority', $task->priority ?? '') == 'low' ? 'selected' : '' }}>Low
                    </option>
                    <option value="medium" {{ old('priority', $task->priority ?? '') == 'medium' ? 'selected' : '' }}>Medium
                    </option>
                    <option value="high" {{ old('priority', $task->priority ?? '') == 'high' ? 'selected' : '' }}>High
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="to-do" {{ old('status', $task->status ?? '') == 'to-do' ? 'selected' : '' }}>To-Do
                    </option>
                    <option value="in progress" {{ old('status', $task->status ?? '') == 'in progress' ? 'selected' : '' }}>
                        In Progress</option>
                    <option value="done" {{ old('status', $task->status ?? '') == 'done' ? 'selected' : '' }}>Done
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" name="due_date" id="due_date" class="form-control"
                    value="{{ old('due_date', $task->due_date ?? '') }}" required>
            </div>

            <div class="d-flex justify-content-between mb-3">
                <button type="submit" class="btn btn-success">{{ isset($task) ? 'Update Task' : 'Create Task' }}</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        @if (isset($task))
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Public Link</h5>
                    <form method="POST" action="{{ route('tasks.generateLink', $task) }}" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-primary">Generate Public Link</button>
                    </form>

                    @if ($task->access_token)
                        <p>
                            <strong>Public Link:</strong><br>
                            <a href="{{ url('/tasks/public/' . $task->id . '?token=' . $task->access_token) }}"
                                target="_blank">
                                {{ url('/tasks/public/' . $task->id . '?token=' . $task->access_token) }}
                            </a>
                        </p>
                        <p>
                            <strong>Expires At:</strong> {{ $task->token_expires_at->format('Y-m-d H:i:s') }}
                        </p>
                    @else
                        <p>No public link has been generated yet.</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection

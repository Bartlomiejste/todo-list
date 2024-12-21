@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Task Details</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $task->name }}</h5>
                <p class="card-text"><strong>Description:</strong> {{ $task->description ?? 'No description provided' }}</p>
                <p class="card-text"><strong>Priority:</strong> {{ ucfirst($task->priority) }}</p>
                <p class="card-text"><strong>Status:</strong> {{ ucfirst($task->status) }}</p>
                <p class="card-text"><strong>Due Date:</strong> {{ $task->due_date }}</p>
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back to Tasks</a>
            </div>
        </div>
    </div>
@endsection

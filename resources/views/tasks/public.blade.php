@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Public Task: {{ $task->name }}</h1>
        <p>Description: {{ $task->description }}</p>
        <p>Priority: {{ ucfirst($task->priority) }}</p>
        <p>Status: {{ ucfirst($task->status) }}</p>
        <p>Due Date: {{ $task->due_date }}</p>
    </div>
@endsection

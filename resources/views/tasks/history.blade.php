@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>History for Task: {{ $task->name }}</h1>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary mb-3">Back to Tasks</a>

        @forelse ($history->groupBy('created_at') as $editTime => $changes)
            <div class="mb-5">
                <h5 class="text-muted">Edited on: {{ \Carbon\Carbon::parse($editTime)->format('Y-m-d H:i:s') }}</h5>
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Field</th>
                            <th>Previous Value</th>
                            <th>Current Value</th>
                            <th>Changed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($changes as $change)
                            <tr>
                                <td>{{ ucfirst($change->field) }}</td>
                                <td>{{ $change->old_value ?? 'N/A' }}</td>
                                <td>{{ $change->new_value }}</td>
                                <td>{{ $change->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <p>No changes recorded for this task.</p>
        @endforelse
    </div>
@endsection

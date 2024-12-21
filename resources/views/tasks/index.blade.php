@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="GET" action="{{ route('tasks.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="priority" class="form-select">
                        <option value="">All Priorities</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="to-do" {{ request('status') == 'to-do' ? 'selected' : '' }}>To-Do</option>
                        <option value="in progress" {{ request('status') == 'in progress' ? 'selected' : '' }}>In Progress
                        </option>
                        <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="due_date" class="form-control" value="{{ request('due_date') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
        <h1>Tasks</h1>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Add New Task</a>
        @if ($tasks->count())
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr>
                            <td>{{ $task->name }}</td>
                            <td>{{ ucfirst($task->priority) }}</td>
                            <td>{{ ucfirst($task->status) }}</td>
                            <td>{{ $task->due_date }}</td>
                            <td>
                                <a href="{{ route('tasks.show', $task) }}"
                                    class="btn btn-info btn-sm d-inline-block">View</a>
                                <a href="{{ route('tasks.edit', $task) }}"
                                    class="btn btn-warning btn-sm d-inline-block">Edit</a>
                                <a href="{{ route('tasks.history', $task) }}"
                                    class="btn btn-secondary btn-sm d-inline-block">History</a>
                                <form method="POST" action="{{ route('tasks.syncGoogleCalendar', $task) }}"
                                    class="d-inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Add Task to Google
                                        Calendar</button>
                                </form>
                                <button class="btn btn-danger btn-sm d-inline-block"
                                    onclick="showDeleteModal('{{ route('tasks.destroy', $task) }}')">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $tasks->links() }}
        @else
            <p>No tasks found. Start by adding a new task.</p>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {!! session('success') !!}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif




    <!-- Modal for delete confirmation -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this task? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDeleteModal(action) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = action;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
    </script>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Your Pending Tasks</h1>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create New +</a>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <select id="priorityFilter" class="form-control">
                    <option value="">All Priorities</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="statusFilter" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>

        @push('scripts')
            <script>
                $(document).ready(function() {
                    $('#priorityFilter, #statusFilter').on('change', function() {
                        let priority = $('#priorityFilter').val();
                        let status = $('#statusFilter').val();

                        axios.get("{{ route('tasks.index') }}", {
                                params: {
                                    priority: priority,
                                    status: status
                                }
                            })
                            .then(function(response) {
                                // Replace the table body with the new filtered tasks
                                let tbody = $(response.data).find('tbody').html();
                                $('table tbody').html(tbody);
                            })
                            .catch(function(error) {
                                alert('Error fetching filtered tasks.');
                            });
                    });
                });
            </script>
        @endpush

        <!-- Tasks Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Due Date</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if (session('message'))
                        <tr>
                            <td colspan="6" class="text-center text-success font-weight-bold">
                                {{ session('message') }}
                            </td>
                        </tr>
                    @endif
                    @forelse($tasks as $task)
                        @if (
                            (request('search') === null ||
                                stripos($task->name, request('search')) !== false ||
                                stripos($task->description, request('search')) !== false) &&
                                (request('priority') === null || request('priority') === '' || $task->priority === request('priority')) &&
                                (request('status') === null || request('status') === '' || $task->status === request('status')) &&
                                (request('due') === null ||
                                    request('due') === '' ||
                                    (request('due') === 'today' && $task->due_date->isToday()) ||
                                    (request('due') === 'this_week' && $task->due_date->isCurrentWeek()) ||
                                    (request('due') === 'next_week' && $task->due_date->isNextWeek())))
                            <tr>
                                <td>{{ $task->name }}</td>
                                <td>{{ $task->description }}</td>
                                <td>{{ $task->due_date->format('Y-m-d') }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $task->priority == 'high' ? 'danger' : ($task->priority == 'medium' ? 'warning' : 'success') }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="badge badge-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'info' : 'secondary') }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('tasks.show', $task->id) }}" class="text-info mr-2" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="text-warning mr-2"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0" title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this task?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No tasks found.</td>
                        </tr>
                    @endforelse
                </tbody>

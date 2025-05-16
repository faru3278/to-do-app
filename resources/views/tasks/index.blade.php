@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Your Pending Tasks</h1>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create New +</a>
    </div>
{{--
    <!-- Filter Bar -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('tasks.filter') }}">
                <div class="form-row">
                    <h5 class="col-md-12 mb-12">Filter Tasks</h5>
                    <div class="col-md-4 mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Search tasks...">
                    </div>
                    <div class="col-md-3 mb-4">
                        <select name="priority" class="form-control">
                            <option value="" hidden selected>Priority</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-4">
                        <select name="status" class="form-control">
                            <option value="" hidden selected>Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <select name="due" class="form-control">
                            <option value="" hidden selected>Due</option>
                            <option value="today">Today</option>
                            <option value="this_week">This Week</option>
                            <option value="next_week">Next Week</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <button type="submit" class="btn btn-secondary btn-block">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div> --}}

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
                @if(session('message'))
                    <tr>
                        <td colspan="6" class="text-center text-success font-weight-bold">
                            {{ session('message') }}
                        </td>
                    </tr>
                @endif
                @forelse($tasks as $task)
                    @if(
                        (request('search') === null || stripos($task->name, request('search')) !== false || stripos($task->description, request('search')) !== false) &&
                        (request('priority') === null || request('priority') === '' || $task->priority === request('priority')) &&
                        (request('status') === null || request('status') === '' || $task->status === request('status')) &&
                        (request('due') === null || request('due') === '' ||
                            (request('due') === 'today' && $task->due_date->isToday()) ||
                            (request('due') === 'this_week' && $task->due_date->isCurrentWeek()) ||
                            (request('due') === 'next_week' && $task->due_date->isNextWeek()))
                    )
                        <tr>
                            <td>{{ $task->name }}</td>
                            <td>{{ $task->description }}</td>
                            <td>{{ $task->due_date->format('Y-m-d') }}</td>
                            <td>
                                <span class="badge badge-{{ $task->priority == 'high' ? 'danger' : ($task->priority == 'medium' ? 'warning' : 'success') }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'info' : 'secondary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('tasks.show', $task->id) }}" class="text-info mr-2" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('tasks.edit', $task->id) }}" class="text-warning mr-2" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0" title="Delete" onclick="return confirm('Are you sure you want to delete this task?')">
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

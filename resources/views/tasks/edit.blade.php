@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <a href="{{ route('tasks.index') }}" class="btn btn-link position-absolute d-flex align-items-center" style="top: 10px; left: 10px;">
        <i class="bi bi-arrow-left me-2"></i> Back to List
    </a>
    <div class="card" style="width: 70%;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Edit Task</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Task Title</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $task->name }}" required>
                </div>
                <div class="form-group">
                    <label for="description">Task Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4" required>{{ $task->description }}</textarea>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="due_date">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="form-control" value="{{ \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="priority">Priority</label>
                        <select name="priority" id="priority" class="form-control" required>
                            <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Task</button>
            </form>
        </div>
    </div>
</div>
@endsection

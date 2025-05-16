@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <a href="{{ route('tasks.index') }}" class="btn btn-link position-absolute d-flex align-items-center" style="top: 10px; left: 10px;">
        <i class="bi bi-arrow-left me-2"></i> Back to List
    </a>
    <div class="card" style="width: 70%;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Create New Task</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Task Title</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter task title" required>
                </div>
                <div class="form-group">
                    <label for="description">Task Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter task description" required></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="due_date">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="priority">Priority</label>
                        <select name="priority" id="priority" class="form-control" required>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Create Task</button>
            </form>
        </div>
    </div>
</div>
@endsection

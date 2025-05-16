@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <a href="{{ route('tasks.index') }}" class="btn btn-link position-absolute d-flex align-items-center" style="top: 10px; left: 10px;">
        <i class="bi bi-arrow-left me-2"></i> Back to List
    </a>

    <div class="card" style="width: 70%;">
        <div class="card-header">
            <h4 class="mb-0">Task Details</h4>
        </div>
        <div class="card-body">
            <h5 class="card-title">{{ $task->name }}</h5>
            <p class="card-text"><strong>Description:</strong> {{ $task->description }}</p>
            <p class="card-text"><strong>Status:</strong> {{ $task->status }}</p>
            <p class="card-text"><strong>Priority:</strong> {{ $task->priority }}</p>
            <p class="card-text"><strong>Created At:</strong> {{ $task->created_at->format('d M Y, h:i A') }}</p>
            <p class="card-text"><strong>Updated At:</strong> {{ $task->updated_at->format('d M Y, h:i A') }}</p>
        </div>
    </div>
</div>
@endsection

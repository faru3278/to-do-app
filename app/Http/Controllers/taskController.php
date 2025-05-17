<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class taskController extends Controller
{
    public function index()
    {
        // Apply filters from the request if present
        $query = Task::query();

        if (request()->has('status') && request()->status) {
            $query->where('status', request()->status);
        }
        if (request()->has('priority') && request()->priority) {
            $query->where('priority', request()->priority);
        }
        if (request()->has('search') && request()->search) {
            $search = request()->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tasks = $query->get();
        return view('tasks.index')->with('tasks', $tasks);
        // Fetch all tasks from the database
        $tasks = Task::pending()->get();
        return view('tasks.index')->with('tasks', $tasks);
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date|after_or_equal:today',
        ]);

        // Create a new task using the validated data
        $task = Task::create($validatedData);

        // Redirect to the task list with a success message
        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.show')->with('task', $task);
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.edit')->with('task', $task);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date|after_or_equal:today',
            'status' => 'required|in:pending,in_progress,completed',
        ]);
        // Find the task by ID and update it with the validated data
        $task = Task::findOrFail($id);
        $task->update($validatedData);
        // Redirect to the task list with a success message
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy($id)
    {
        // Find the task by ID and delete it
        $task = Task::findOrFail($id);
        $task->delete();
        // Redirect to the task list with a success message
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
    public function filter(Request $request)
    {
        return view('tasks.index', [
            'tasks' => Task::query()
                ->when($request->search, fn($query) => $query->where('name', 'like', "%{$request->search}%"))
                ->when($request->priority, fn($query) => $query->where('priority', $request->priority))
                ->when($request->status, fn($query) => $query->where('status', $request->status))
                ->get(),
        ]);
    }
    public function search(Request $request)
    {
        // Search tasks by name or description
        // Return the search results
    }
    public function export(Request $request)
    {
        // Export tasks to CSV or Excel
        // Return the exported file
    }
    public function import(Request $request)
    {
        // Import tasks from CSV or Excel
        // Redirect to the task list with success or error message
    }
    public function markAsCompleted($id)
    {
        // Mark a task as completed
        // Redirect to the task list
    }
    public function markAsInProgress($id)
    {
        // Mark a task as in progress
        // Redirect to the task list
    }
    public function markAsPending($id)
    {
        // Mark a task as pending
        // Redirect to the task list
    }
    public function setPriority($id, $priority)
    {
        // Set the priority of a task
        // Redirect to the task list
    }
    public function setDueDate($id, $due_date)
    {
        // Set the due date of a task
        // Redirect to the task list
    }
    public function clearCompleted()
    {
        // Clear all completed tasks
        // Redirect to the task list
    }
    public function clearOverdue()
    {
        // Clear all overdue tasks
        // Redirect to the task list
    }
    public function clearAll()
    {
        // Clear all tasks
        // Redirect to the task list
    }
    public function showCompleted()
    {
        // Show only completed tasks
        // Return the task list
    }
    public function showPending()
    {
        // Show only pending tasks
        // Return the task list
    }
    public function showInProgress()
    {
        // Show only in-progress tasks
        // Return the task list
    }
    public function showHighPriority()
    {
        // Show only high-priority tasks
        // Return the task list
    }
    public function showMediumPriority()
    {
        // Show only medium-priority tasks
        // Return the task list
    }
    public function showLowPriority()
    {
        // Show only low-priority tasks
        // Return the task list
    }
    public function showDueToday()
    {
        // Show only tasks due today
        // Return the task list
    }
    public function showDueTomorrow()
    {
        // Show only tasks due tomorrow
        // Return the task list
    }
    public function showDueThisWeek()
    {
        // Show only tasks due this week
        // Return the task list
    }
    public function showDueNextWeek()
    {
        // Show only tasks due next week
        // Return the task list
    }
    public function showOverdue()
    {
        // Show only overdue tasks
        // Return the task list
    }
    public function showAll()
    {
        // Show all tasks
        // Return the task list
    }
    public function showByDate($date)
    {
        // Show tasks by specific date
        // Return the task list
    }
    public function showByMonth($month)
    {
        // Show tasks by specific month
        // Return the task list
    }
    public function showByYear($year)
    {
        // Show tasks by specific year
        // Return the task list
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class taskController extends Controller
{
    // Display a listing of the tasks with filters and only for the authenticated user
    public function index(Request $request)
    {
        $userId = auth()->id();

        $query = Task::where('user_id', $userId);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tasks = $query->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date|after_or_equal:today',
            'user_id' => 'nullable|exists:users,id', // Optional, if you want to set a specific user
        ]);

        $validatedData['user_id'] = auth()->id();

        Task::create($validatedData);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function show($id)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        return view('tasks.show', compact('task'));
    }

    public function edit($id)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        return view('tasks.edit', compact('task'));
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

        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        $task->update($validatedData);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy($id)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    public function filter(Request $request)
    {
        $userId = auth()->id();
        $tasks = Task::where('user_id', $userId)
            ->when($request->search, fn($query) => $query->where('name', 'like', "%{$request->search}%"))
            ->when($request->priority, fn($query) => $query->where('priority', $request->priority))
            ->when($request->status, fn($query) => $query->where('status', $request->status))
            ->get();

        return view('tasks.index', compact('tasks'));
    }

    public function search(Request $request)
    {
        $userId = auth()->id();
        $search = $request->input('search');
        $tasks = Task::where('user_id', $userId)
            ->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            })
            ->get();

        return view('tasks.index', compact('tasks'));
    }

    // The following methods are updated to filter by authenticated user

    public function markAsCompleted($id)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        $task->update(['status' => 'completed']);
        return redirect()->route('tasks.index')->with('success', 'Task marked as completed!');
    }

    public function markAsInProgress($id)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        $task->update(['status' => 'in_progress']);
        return redirect()->route('tasks.index')->with('success', 'Task marked as in progress!');
    }

    public function markAsPending($id)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        $task->update(['status' => 'pending']);
        return redirect()->route('tasks.index')->with('success', 'Task marked as pending!');
    }

    public function setPriority($id, $priority)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        $task->update(['priority' => $priority]);
        return redirect()->route('tasks.index')->with('success', 'Task priority updated!');
    }

    public function setDueDate($id, $due_date)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        $task->update(['due_date' => $due_date]);
        return redirect()->route('tasks.index')->with('success', 'Task due date updated!');
    }

    public function clearCompleted()
    {
        Task::where('user_id', auth()->id())->where('status', 'completed')->delete();
        return redirect()->route('tasks.index')->with('success', 'All completed tasks cleared!');
    }

    public function clearOverdue()
    {
        Task::where('user_id', auth()->id())->where('due_date', '<', now())->delete();
        return redirect()->route('tasks.index')->with('success', 'All overdue tasks cleared!');
    }

    public function clearAll()
    {
        Task::where('user_id', auth()->id())->delete();
        return redirect()->route('tasks.index')->with('success', 'All tasks cleared!');
    }

    public function showCompleted()
    {
        $tasks = Task::where('user_id', auth()->id())->where('status', 'completed')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function showPending()
    {
        $tasks = Task::where('user_id', auth()->id())->where('status', 'pending')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function showInProgress()
    {
        $tasks = Task::where('user_id', auth()->id())->where('status', 'in_progress')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function showHighPriority()
    {
        $tasks = Task::where('user_id', auth()->id())->where('priority', 'high')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function showMediumPriority()
    {
        $tasks = Task::where('user_id', auth()->id())->where('priority', 'medium')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function showLowPriority()
    {
        $tasks = Task::where('user_id', auth()->id())->where('priority', 'low')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function showDueToday()
    {
        $tasks = Task::where('user_id', auth()->id())
            ->whereDate('due_date', now()->toDateString())
            ->get();
        return view('tasks.index', compact('tasks'));
    }

    public function showDueTomorrow()
    {
        $tasks = Task::where('user_id', auth()->id())
            ->whereDate('due_date', now()->addDay()->toDateString())
            ->get();
        return view('tasks.index', compact('tasks'));
    }

    public function showDueThisWeek()
    {
        $tasks = Task::where('user_id', auth()->id())
            ->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->get();
        return view('tasks.index', compact('tasks'));
    }

    public function showDueNextWeek()
    {
        $start = now()->addWeek()->startOfWeek();
        $end = now()->addWeek()->endOfWeek();
        $tasks = Task::where('user_id', auth()->id())
            ->whereBetween('due_date', [$start, $end])
            ->get();
        return view('tasks.index', compact('tasks'));
    }

    public function showOverdue()
    {
        $tasks = Task::where('user_id', auth()->id())
            ->where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->get();
        return view('tasks.index', compact('tasks'));
    }

    public function showAll()
    {
        $tasks = Task::where('user_id', auth()->id())->get();
        return view('tasks.index', compact('tasks'));
    }

    public function showByDate($date)
    {
        $tasks = Task::where('user_id', auth()->id())
            ->whereDate('due_date', $date)
            ->get();
        return view('tasks.index', compact('tasks'));
    }

    public function showByMonth($month)
    {
        $tasks = Task::where('user_id', auth()->id())
            ->whereMonth('due_date', $month)
            ->get();
        return view('tasks.index', compact('tasks'));
    }

    public function showByYear($year)
    {
        $tasks = Task::where('user_id', auth()->id())
            ->whereYear('due_date', $year)
            ->get();
        return view('tasks.index', compact('tasks'));
    }

    // You may implement export/import as needed, but always filter by user_id
    public function export(Request $request)
    {
        // Export tasks to CSV or Excel for the authenticated user
        // Implement as needed
    }

    public function import(Request $request)
    {
        // Import tasks from CSV or Excel for the authenticated user
        // Implement as needed
    }
}

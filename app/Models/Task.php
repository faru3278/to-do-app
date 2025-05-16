<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'due_date',
        'status',
        'priority',
    ];
    protected $casts = [
        'due_date' => 'date',
    ];
    protected $attributes = [
        'status' => 'pending',
        'priority' => 'medium',
    ];
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }
    public function scopeMediumPriority($query)
    {
        return $query->where('priority', 'medium');
    }
    public function scopeLowPriority($query)
    {
        return $query->where('priority', 'low');
    }
    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today());
    }
    public function scopeDueTomorrow($query)
    {
        return $query->whereDate('due_date', today()->addDay());
    }
    public function scopeDueThisWeek($query)
    {
        return $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }
    public function scopeDueNextWeek($query)
    {
        return $query->whereBetween('due_date', [now()->addWeek()->startOfWeek(), now()->addWeek()->endOfWeek()]);
    }
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', today())->where('status', '!=', 'completed');
    }
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }
    public function scopeSortBy($query, $sortBy)
    {
        return $query->orderBy($sortBy, 'asc');
    }
    public function scopeSortByDesc($query, $sortBy)
    {
        return $query->orderBy($sortBy, 'desc');
    }
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    public function scopeWithPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
    public function scopeWithDueDate($query, $dueDate)
    {
        return $query->where('due_date', $dueDate);
    }
    public function scopeWithName($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }
    public function scopeWithDescription($query, $description)
    {
        return $query->where('description', 'like', "%{$description}%");
    }
    public function scopeWithCreatedAt($query, $createdAt)
    {
        return $query->whereDate('created_at', $createdAt);
    }
    public function scopeWithUpdatedAt($query, $updatedAt)
    {
        return $query->whereDate('updated_at', $updatedAt);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'project_id',
        'weightage',  // Added weightage here
    ];

    // Cast attributes to specific types
    protected $casts = [
        'weightage' => 'integer',
    ];

    /**
     * Get the project that owns the task.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the users assigned to the task.
     * The `withPivot('role_id')` indicates that there's a 'role_id' column on the
     * pivot table (task_user) which could be used to define a user's specific role
     * on this task (e.g., assignee, reviewer).
     * The `withTimestamps()` ensures `created_at` and `updated_at` are maintained on the pivot table.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role_id')->withTimestamps();
    }

    /**
     * Get the comments for the task.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // Add project_manager_id to fillable so it can be mass-assigned
protected $fillable = [
    'name',
    'description',
    'deadline',
    'priority',
    'project_manager_id',
    'space_id',
];

    // Relationship: Project belongs to a Space
    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    // Relationship: Project belongs to a User as manager
    public function manager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    // Relationship: Project has many employees (many-to-many)
    public function employees()
    {
        return $this->belongsToMany(User::class, 'project_user');
    }
}

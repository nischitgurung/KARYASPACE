<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{ 
    use HasFactory;

    protected $fillable = ['name', 'description', 'space_id', 'user_id', 'completion_percentage'];

public function space() {
    return $this->belongsTo(Space::class);
}


    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user')->withPivot('role');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}


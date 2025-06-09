<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'completion_percentage'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

public function task()
{
    return $this->belongsTo(Task::class);
}

}




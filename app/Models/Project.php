<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'description', 'space_id'];

    // Relationship: Project belongs to a Space
    public function space()
    {
        return $this->belongsTo(Space::class);
    }
}

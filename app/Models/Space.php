<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class, 'space_user')->withPivot('role');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Project;

class Space extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'user_id'];

    // A space belongs to an owner (creator)
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function members()
{
    return $this->belongsToMany(User::class)->withPivot('role_id')->withTimestamps();
}


    // A space can have many projects
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // A space can have many users (members) with roles
    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role_id')
                    ->withTimestamps();
    }
}

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

    // A space belongs to a creator/owner
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A space can have many projects
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // ✅ A space can have many users (members)
    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role_id')
                    ->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'user_id'];

    // Relationship: A space belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // (Optional) Relationship: A space can have many projects
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    use HasFactory;
    protected $table = 'spaces'; // Ensure correct table name


    protected $fillable = ['name', 'user_id'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'space_user')->withPivot('role');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}

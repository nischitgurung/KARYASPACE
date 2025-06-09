<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceUser extends Model
{
    use HasFactory;
    protected $fillable = ['space_id', 'user_id', 'role'];

    protected $table = 'space_user';

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


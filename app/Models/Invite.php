<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $fillable = [
        'token',
        'space_id',
        'inviter_id',
        'role_id',
        'expires_at',
    ];

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}

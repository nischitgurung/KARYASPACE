<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;
use App\Models\Space;
use App\Models\Project;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Spaces the user belongs to
     */
    public function spaces()
    {
        return $this->belongsToMany(Space::class)
                    ->select(['spaces.id', 'spaces.name']) // optional: avoids overfetching
                    ->withPivot('role_id')
                    ->withTimestamps();
    }

    /**
     * Get role ID of user in a space
     */
    public function spaceRole($spaceId)
    {
        return $this->spaces()
                    ->where('spaces.id', $spaceId)
                    ->first()?->pivot->role_id;
    }

    /**
     * Check user has one of the roles in a space
     */
    public function hasSpaceRole(Space $space, string|array $roleNames): bool
    {
        $roleNames = is_string($roleNames) ? [$roleNames] : $roleNames;

        $roleIds = Role::whereIn('name', $roleNames)->pluck('id');

        if ($roleIds->isEmpty()) return false;

        return $this->spaces()
                    ->where('spaces.id', $space->id)
                    ->wherePivotIn('role_id', $roleIds)
                    ->exists();
    }

    public function isSpaceAdmin(Space $space): bool
    {
        return $this->hasSpaceRole($space, 'Admin');
    }

    public function isSpaceProjectManager(Space $space): bool
    {
        return $this->hasSpaceRole($space, 'Project Manager');
    }

    public function isSpaceEmployee(Space $space): bool
    {
        return $this->hasSpaceRole($space, 'Employee');
    }

    /**
     * Projects where user is the manager
     */
    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'project_manager_id');
    }

    /**
     * Projects where user is a team member
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user');
    }
}

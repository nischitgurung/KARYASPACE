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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The spaces that the user belongs to.
     */
    public function spaces()
    {
        return $this->belongsToMany(Space::class)
                    ->withPivot('role_id') // Includes role_id from pivot table
                    ->withTimestamps();
    }

    /**
     * Check if the user has any of the specified roles within a given space.
     *
     * @param  \App\Models\Space  $space
     * @param  string|array  $roleNames
     * @return bool
     */
    public function hasSpaceRole(Space $space, string|array $roleNames): bool
    {
        if (is_string($roleNames)) {
            $roleNames = [$roleNames];
        }

        $roleIds = Role::whereIn('name', $roleNames)->pluck('id');

        if ($roleIds->isEmpty()) {
            return false;
        }

        return $this->spaces()
                    ->where('spaces.id', $space->id)
                    ->wherePivotIn('role_id', $roleIds)
                    ->exists();
    }

    /**
     * Check if the user is an Admin in the given space.
     *
     * @param  \App\Models\Space  $space
     * @return bool
     */
    public function isSpaceAdmin(Space $space): bool
    {
        return $this->hasSpaceRole($space, 'Admin');
    }

    /**
     * Check if the user is a Project Manager in the given space.
     *
     * @param  \App\Models\Space  $space
     * @return bool
     */
    public function isSpaceProjectManager(Space $space): bool
    {
        return $this->hasSpaceRole($space, 'Project Manager');
    }

    /**
     * Check if the user is an Employee in the given space.
     *
     * @param  \App\Models\Space  $space
     * @return bool
     */
    public function isSpaceEmployee(Space $space): bool
    {
        return $this->hasSpaceRole($space, 'Employee');
    }

    /**
     * Projects managed by the user (if they are a project manager).
     */
    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'project_manager_id');
    }

    /**
     * Projects where the user is a member.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user');
    }
}

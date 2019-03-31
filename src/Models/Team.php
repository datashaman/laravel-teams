<?php

namespace Datashaman\Teams\Models;

use Datashaman\Teams\Contracts\TeamInterface;
use Datashaman\Teams\TeamsUserInterface;
use Illuminate\Database\Eloquent\Model;

class Team extends Model implements TeamInterface
{
    /**
     * @var array
     */
    protected $appends = [
        'projectCount',
        'userCount',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    public function users()
    {
        return $this->belongsToMany(config('teams.user'));
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function userRoles()
    {
        return $this->hasMany(UserRole::class);
    }

    /**
     * @return int
     */
    public function getUserCountAttribute(): int
    {
        return $this->users()->count();
    }

    /**
     * @return int
     */
    public function getProjectCountAttribute(): int
    {
        return $this->projects()->count();
    }

    /**
     * @param TeamsUserInterface $user
     *
     * @return TeamsUserInterface
     */
    public function addUser(TeamsUserInterface $user): TeamsUserInterface
    {
        $exists = $this
            ->users()
            ->where('team_user.user_id', $user->id)
            ->exists();

        if (!$exists) {
            $this->users()->attach($user);
        }

        return $user->refresh();
    }

    /**
     * @param TeamsUserInterface $user
     *
     * @return TeamsUserInterface
     */
    public function removeUser(TeamsUserInterface $user): TeamsUserInterface
    {
        $exists = $this
            ->users()
            ->where('team_user.user_id', $user->id)
            ->exists();

        if ($exists) {
            $this->users()->detach($user);
        }

        return $user->refresh();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}

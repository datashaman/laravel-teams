<?php

namespace Datashaman\Teams\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
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
     * @param Model $user
     *
     * @return Model
     */
    public function addUser(Model $user): Model
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
     * @param Model $user
     *
     * @return Model
     */
    public function removeUser(Model $user): Model
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
     * @return int
     */
    public function getProjectCountAttribute()
    {
        return $this->projects()->count();
    }

    /**
     * @return int
     */
    public function getUserCountAttribute()
    {
        return $this->users()->count();
    }
}

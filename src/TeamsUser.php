<?php

namespace Datashaman\Teams;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait TeamsUser
{
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Models\Team::class);
    }

    public function userRoles(): HasMany
    {
        return $this->hasMany(Models\UserRole::class);
    }

    /**
     * @return string|array     $role
     * @return Models\Team|null $team
     *
     * @return bool
     */
    public function hasRole($roles, Models\Team $team = null): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        $teamId = is_null($team) ? null : $team->id;

        return $this
            ->userRoles()
            ->whereIn('role', $roles)
            ->where('team_id', $teamId)
            ->exists();
    }

    /**
     * @param string           $role
     * @param Models\Team|null $team
     */
    public function addRole(string $role, Models\Team $team = null)
    {
        if (in_array($role, config('teams.roles'))) {
            if ($role === 'TEAM_ADMIN' && !$team) {
                throw new TeamsException('Team must be specified');
            }

            if ($role === 'TEAM_ADMIN' && !$this->inTeam($team)) {
                throw new TeamsException('User must be in team');
            }

            $teamId = is_null($team) ? null : $team->id;

            $this->userRoles()->updateOrCreate(['role' => $role, 'team_id' => $teamId]);
        }
    }

    /**
     * @param string           $role
     * @param Models\Team|null $team
     */
    public function removeRole(string $role, Models\Team $team = null)
    {
        if (in_array($role, config('teams.roles'))) {
            $teamId = is_null($team) ? null : $team->id;

            $this->userRoles()
                ->where('role', $role)
                ->where('team_id', $teamId)
                ->delete();
        }
    }

    /**
     * @param Models\Team $user
     *
     * @return Models\Team
     */
    public function joinTeam(Models\Team $team): Models\Team
    {
        $this->teams()->attach($team);

        return $team->refresh();
    }

    /**
     * @return bool
     */
    public function inTeam(Models\Team $team): bool
    {
        return $this
            ->teams()
            ->where('team_user.team_id', $team->id)
            ->exists();
    }

    /**
     * @param Models\Team $team
     *
     * @return Models\Team
     */
    public function leaveTeam(Models\Team $team): Models\Team
    {
        $exists = $this
            ->teams()
            ->where('team_user.team_id', $team->id)
            ->exists();

        if ($exists) {
            $this->teams()->detach($team);
        }

        return $team->refresh();
    }
}

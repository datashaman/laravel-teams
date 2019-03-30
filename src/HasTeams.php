<?php

namespace Datashaman\Teams;

trait HasTeams
{
    public function teams()
    {
        return $this->belongsToMany(Models\Team::class);
    }

    public function userRoles()
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
                throw new TeamsException('You must specify the team');
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
    public function addTeam(Models\Team $team): Models\Team
    {
        $exists = $this
            ->teams()
            ->where('team_user.team_id', $team->id)
            ->exists();

        if (!$exists) {
            $this->teams()->attach($team);
        }

        return $team->refresh();
    }

    /**
     * @param Models\Team $user
     *
     * @return Models\Team
     */
    public function removeTeam(Models\Team $team): Models\Team
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

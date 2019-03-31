<?php

namespace Datashaman\Teams;

use Datashaman\Teams\Contracts\TeamInterface;
use Datashaman\Teams\Models\Team;
use Datashaman\Teams\Models\UserRole;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait TeamsUser
{
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function userRoles(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }

    /**
     * @return string|array     $role
     * @return TeamInterface|null $team
     *
     * @return bool
     */
    public function hasRole($roles, TeamInterface $team = null): bool
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
     * @param TeamInterface|null $team
     */
    public function addRole(string $role, TeamInterface $team = null)
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
     * @param TeamInterface|null $team
     */
    public function removeRole(string $role, TeamInterface $team = null)
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
     * @param TeamInterface $user
     *
     * @return TeamInterface
     */
    public function joinTeam(TeamInterface $team): TeamInterface
    {
        $this->teams()->attach($team);

        return $team->refresh();
    }

    /**
     * @return bool
     */
    public function inTeam(TeamInterface $team): bool
    {
        return $this
            ->teams()
            ->where('team_user.team_id', $team->id)
            ->exists();
    }

    /**
     * @param TeamInterface $team
     *
     * @return TeamInterface
     */
    public function leaveTeam(TeamInterface $team): TeamInterface
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

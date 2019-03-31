<?php

namespace Datashaman\Teams;

use Datashaman\Teams\Contracts\TeamInterface;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface TeamsUserInterface
{
    /**
     * @return BelongsToMany
     */
    public function teams(): BelongsToMany;

    /**
     * @return HasMany
     */
    public function userRoles(): HasMany;

    /**
     * @return string|array     $role
     * @return TeamInterface|null $team
     *
     * @return bool
     */
    public function hasRole($role, TeamInterface $team = null): bool;

    /**
     * @param string           $role
     * @param TeamInterface|null $team
     */
    public function addRole(string $role, TeamInterface $team = null);

    /**
     * @param string           $role
     * @param TeamInterface|null $team
     */
    public function removeRole(string $role, TeamInterface $team = null);

    /**
     * @param TeamInterface $user
     *
     * @return TeamInterface
     */
    public function joinTeam(TeamInterface $team): TeamInterface;

    /**
     * @return bool
     */
    public function inTeam(TeamInterface $team): bool;

    /**
     * @param TeamInterface $team
     *
     * @return TeamInterface
     */
    public function leaveTeam(TeamInterface $team): TeamInterface;
}

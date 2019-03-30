<?php

namespace Datashaman\Teams;

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
     * @return Models\Team|null $team
     *
     * @return bool
     */
    public function hasRole($role, Models\Team $team = null): bool;

    /**
     * @param string           $role
     * @param Models\Team|null $team
     */
    public function addRole(string $role, Models\Team $team = null);

    /**
     * @param string           $role
     * @param Models\Team|null $team
     */
    public function removeRole(string $role, Models\Team $team = null);

    /**
     * @param Models\Team $user
     *
     * @return Models\Team
     */
    public function joinTeam(Models\Team $team): Models\Team;

    /**
     * @return bool
     */
    public function inTeam(Models\Team $team): bool;

    /**
     * @param Models\Team $team
     *
     * @return Models\Team
     */
    public function leaveTeam(Models\Team $team): Models\Team;
}

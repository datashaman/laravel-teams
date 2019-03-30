<?php

namespace Datashaman\Teams\Policies;

use Datashaman\Teams\TeamsUserInterface;
use Illuminate\Auth\Access\HandlesAuthorization;

abstract class AbstractPolicy
{
    use HandlesAuthorization;

    /**
     * @param TeamsUserInterface $actingUser
     * @param string $ability
     */
    public function before(TeamsUserInterface $actingUser, string $ability)
    {
        if ($actingUser->hasRole('ADMIN')) {
            return true;
        }
    }
}

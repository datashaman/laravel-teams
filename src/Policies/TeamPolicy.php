<?php

namespace Datashaman\Teams\Policies;

use Datashaman\Teams\Models\Team;
use Datashaman\Teams\TeamsUserInterface;

class TeamPolicy extends AbstractPolicy
{
    /**
     * Determine whether the user can view the team index.
     *
     * @param TeamsUserInterface $user
     * @return mixed
     */
    public function index(TeamsUserInterface $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the team.
     *
     * @param TeamsUserInterface $user
     * @param Team $team
     * @return mixed
     */
    public function view(TeamsUserInterface $user, Team $team)
    {
        return $this->userIsInTeam($user, $team);
    }

    /**
     * Determine whether the user can create teams.
     *
     * @param TeamsUserInterface $user
     * @return mixed
     */
    public function create(TeamsUserInterface $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the team.
     *
     * @param TeamsUserInterface $user
     * @param Team $team
     * @return mixed
     */
    public function update(TeamsUserInterface $user, Team $team)
    {
        return $this->userIsInTeam($user, $team)
            && $user->hasRole('TEAM_ADMIN', $team);
    }

    /**
     * Determine whether the user can delete the team.
     *
     * @param TeamsUserInterface $user
     * @param Team $team
     * @return mixed
     */
    public function delete(TeamsUserInterface $user, Team $team)
    {
        return false;
    }

    /**
     * Determine whether the user can add a team user.
     *
     * @param TeamsUserInterface $user
     * @param Team $team
     * @return mixed
     */
    public function addUser(TeamsUserInterface $user, Team $team)
    {
        return $this->userIsInTeam($user, $team)
            && $user->hasRole('TEAM_ADMIN', $team);
    }

    /**
     * Determine whether the user can remove a team user.
     *
     * @param TeamsUserInterface $user
     * @param Team $team
     * @return mixed
     */
    public function removeUser(TeamsUserInterface $user, Team $team)
    {
        return $this->userIsInTeam($user, $team)
            && $user->hasRole('TEAM_ADMIN', $team);
    }
}

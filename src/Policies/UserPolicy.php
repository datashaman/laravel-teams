<?php

namespace Datashaman\Teams\Policies;

class UserPolicy extends AbstractPolicy
{
    /**
     * @param TeamsUserInterface $actingUser
     * @param TeamsUserInterface $user
     *
     * @return bool
     */
    protected function userIsTeamAdminInSameTeam(TeamsUserInterface $actingUser, TeamsUserInterface $user): bool
    {
        $adminTeamIds = $actingUser
            ->userRoles()
            ->where('user_roles.role', 'TEAM_ADMIN')
            ->pluck('user_roles.team_id');

        return $user
            ->teams()
            ->whereIn('teams.id', $adminTeamIds)
            ->exists();
    }

    /**
     * Determine whether the user can view the user index.
     *
     * @param TeamsUserInterface $actingUser
     * @return mixed
     */
    public function index(TeamsUserInterface $actingUser)
    {
        return false;
    }

    /**
     * Determine whether the user can view the user.
     *
     * @param TeamsUserInterface $actingUser
     * @param TeamsUserInterface $user
     * @return mixed
     */
    public function view(TeamsUserInterface $actingUser, TeamsUserInterface $user)
    {
        return $actingUser->id === $user->id;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param TeamsUserInterface $actingUser
     * @return mixed
     */
    public function create(TeamsUserInterface $actingUser)
    {
        return false;
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param TeamsUserInterface $actingUser
     * @param TeamsUserInterface $user
     * @return mixed
     */
    public function update(TeamsUserInterface $actingUser, TeamsUserInterface $user)
    {
        return $this->userIsTeamAdminInSameTeam($actingUser, $user);
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param TeamsUserInterface $actingUser
     * @param TeamsUserInterface $user
     * @return mixed
     */
    public function delete(TeamsUserInterface $actingUser, TeamsUserInterface $user)
    {
        return $this->userIsTeamAdminInSameTeam($actingUser, $user);
    }

    /**
     * Determine whether the user can add a role to the user.
     *
     * @param TeamsUserInterface $actingUser
     * @param TeamsUserInterface $user
     * @return mixed
     */
    public function addRole(TeamsUserInterface $actingUser)
    {
        return $actingUser->hasRole('ADMIN');
    }

    /**
     * Determine whether the user can remove a role from the user.
     *
     * @param TeamsUserInterface $actingUser
     * @param TeamsUserInterface $user
     * @return mixed
     */
    public function removeRole(TeamsUserInterface $actingUser)
    {
        return $actingUser->hasRole('ADMIN');
    }
}

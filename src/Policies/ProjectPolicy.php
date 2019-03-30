<?php

namespace Datashaman\Teams\Policies;

use Datashaman\Teams\Models\Project;
use Datashaman\Teams\Models\Team;
use Datashaman\Teams\TeamsUserInterface;

class ProjectPolicy extends AbstractPolicy
{
    /**
     * Determine whether the user can view the project index.
     *
     * @param TeamsUserInterface $user
     *
     * @return mixed
     */
    public function index(TeamsUserInterface $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the project.
     *
     * @param TeamsUserInterface $user
     * @param Project           $project
     *
     * @return mixed
     */
    public function view(TeamsUserInterface $user, Project $project)
    {
        return $this->userIsInTeam($user, $project->team);
    }

    /**
     * Determine whether the user can create projects.
     *
     * @param  TeamsUserInterface  $user
     *
     * @return mixed
     */
    public function create(TeamsUserInterface $user)
    {
        // Authorization checked in mutation
        return true;
    }

    /**
     * Determine whether the user can update the project.
     *
     * @param  TeamsUserInterface  $user
     * @param  Project  $project
     * @return mixed
     */
    public function update(TeamsUserInterface $user, Project $project)
    {
        return $this->userIsInTeam($user, $project->team)
            && $user->hasRole('TEAM_ADMIN', $project->team);
    }

    /**
     * Determine whether the user can delete the project.
     *
     * @param  TeamsUserInterface  $user
     * @param  Project  $project
     * @return mixed
     */
    public function delete(TeamsUserInterface $user, Project $project)
    {
        return $this->userIsInTeam($user, $project->team)
            && $user->hasRole('TEAM_ADMIN', $project->team);
    }
}

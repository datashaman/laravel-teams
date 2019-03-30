<?php

namespace Datashaman\Teams\Policies;

use Datashaman\Teams\TeamsUserInterface;
use Datashaman\Teams\Models\Project;

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
        return $user
            ->teams()
            ->whereHas(
                'projects',
                function ($q) use ($project) {
                    return $q->where('id', $project->id);
                }
            )
            ->exists();
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
        // Authorization checked in mutation
        return true;
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
        // Authorization checked in mutation
        return true;
    }

    /**
     * Determine whether the user can restore the project.
     *
     * @param  TeamsUserInterface    $user
     * @param  Project $project
     * @return mixed
     */
    public function restore(TeamsUserInterface $user, Project $project)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the project.
     *
     * @param  TeamsUserInterface    $user
     * @param  Project $project
     * @return mixed
     */
    public function forceDelete(TeamsUserInterface $user, Project $project)
    {
        //
    }

    /**
     * Determine whether the user can build the project.
     *
     * @param  TeamsUserInterface    $user
     * @param  Project $project
     * @return mixed
     */
    public function build(TeamsUserInterface $user, Project $project)
    {
        return $user
            ->teams()
            ->where('teams.id', $project->team->id)
            ->exists();
    }
}

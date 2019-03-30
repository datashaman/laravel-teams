<?php

namespace Datashaman\Teams\Tests\Policies;

use Datashaman\Teams\Models\Project;
use Datashaman\Teams\Models\Team;
use Datashaman\Teams\Tests\Fixtures\User;
use Datashaman\Teams\Tests\TestCase;

class ProjectPolicyTest extends TestCase
{
    public function testIndexAsAdmin()
    {
        $user = factory(User::class)->create();
        $user->addRole('ADMIN');
        $this->assertTrue($user->can('index', Project::class));
    }

    public function testUpdateAsUser()
    {
        $user = factory(User::class)->create();
        $this->assertFalse($user->can('index', Project::class));
    }

    public function testUpdateAsAdmin()
    {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create();
        $user->addRole('ADMIN');
        $this->assertTrue($user->can('update', $project));
    }

    public function testUpdateAsTeamAdmin()
    {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create();
        $user->joinTeam($project->team);
        $user->addRole('TEAM_ADMIN', $project->team);
        $this->assertTrue($user->can('update', $project));
    }

    public function testUpdateAsMember()
    {
        $team = factory(Team::class)->create();
        $project = factory(Project::class)->create(['team_id' => $team->id]);
        $user = factory(User::class)->create();
        $user->joinTeam($team);
        $this->assertFalse($user->can('update', $project));
    }

    public function testUpdateAsNonMember()
    {
        $team = factory(Team::class)->create();
        $project = factory(Project::class)->create(['team_id' => $team->id]);
        $user = factory(User::class)->create();
        $this->assertFalse($user->can('update', $project));
    }

}

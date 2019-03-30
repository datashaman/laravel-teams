<?php

namespace Datashaman\Teams\Tests\Policies;

use Datashaman\Teams\Models\Team;
use Datashaman\Teams\Tests\Fixtures\User;
use Datashaman\Teams\Tests\TestCase;

class TeamPolicyTest extends TestCase
{
    public function testIndexAsUser()
    {
        $user = factory(User::class)->create();
        $this->assertFalse($user->can('index', Team::class));
    }

    public function testIndexAsAdmin()
    {
        $user = factory(User::class)->create();
        $user->addRole('ADMIN');
        $this->assertTrue($user->can('index', Team::class));
    }

    public function testViewAsAdmin()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $user->addRole('ADMIN');
        $this->assertTrue($user->can('view', $team));
    }

    public function testViewAsTeamAdmin()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $user->joinTeam($team);
        $user->addRole('TEAM_ADMIN', $team);
        $this->assertTrue($user->can('view', $team));
    }

    public function testViewAsMember()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $user->joinTeam($team);
        $this->assertTrue($user->can('view', $team));
    }

    public function testViewAsNonMember()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $this->assertFalse($user->can('view', $team));
    }

    public function testCreateAsUser()
    {
        $user = factory(User::class)->create();
        $this->assertFalse($user->can('create', Team::class));
    }

    public function testCreateAsAdmin()
    {
        $user = factory(User::class)->create();
        $user->addRole('ADMIN');
        $this->assertTrue($user->can('create', Team::class));
    }

    public function testUpdateAsAdmin()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $user->addRole('ADMIN');
        $this->assertTrue($user->can('update', $team));
    }

    public function testUpdateAsTeamAdmin()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $user->joinTeam($team);
        $user->addRole('TEAM_ADMIN', $team);
        $this->assertTrue($user->can('update', $team));
    }

    public function testUpdateAsMember()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $user->joinTeam($team);
        $this->assertFalse($user->can('update', $team));
    }

    public function testUpdateAsNonMember()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $this->assertFalse($user->can('update', $team));
    }

    public function testDeleteAsUser()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $this->assertFalse($user->can('delete', $team));
    }

    public function testDeleteAsAdmin()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $user->addRole('ADMIN');
        $this->assertTrue($user->can('delete', $team));
    }

    public function testDeleteAsTeamAdmin()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $user->joinTeam($team);
        $user->addRole('ADMIN', $team);
        $this->assertFalse($user->can('delete', $team));
    }

    public function testAddUserAsAdmin()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $user->addRole('ADMIN');
        $this->assertTrue($user->can('addUser', $team));
    }

    public function testAddUserAsTeamAdmin()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $user->joinTeam($team);
        $user->addRole('TEAM_ADMIN', $team);
        $this->assertTrue($user->can('addUser', $team));
    }

    public function testAddUserAsMember()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $user->joinTeam($team);
        $this->assertFalse($user->can('addUser', $team));
    }

    public function testAddUserAsNonMember()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $this->assertFalse($user->can('addUser', $team));
    }

    public function testRemoveUserAsAdmin()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $user->addRole('ADMIN');
        $this->assertTrue($user->can('removeUser', $team));
    }

    public function testRemoveUserAsTeamAdmin()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $user->joinTeam($team);
        $user->addRole('TEAM_ADMIN', $team);
        $this->assertTrue($user->can('removeUser', $team));
    }

    public function testRemoveUserAsMember()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $user->joinTeam($team);
        $this->assertFalse($user->can('removeUser', $team));
    }

    public function testRemoveUserAsNonMember()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $this->assertFalse($user->can('removeUser', $team));
    }
}

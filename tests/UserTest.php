<?php

namespace Datashaman\Teams\Tests;

use Datashaman\Teams\Models;
use Datashaman\Teams\TeamsException;

class UserTest extends TestCase
{
    public function testTeamAddUser()
    {
        $team = factory(Models\Team::class)->create();
        $user = factory(Fixtures\User::class)->create();
        $team->addUser($user);
        $this->assertEquals(1, $user->teams->count());
        $this->assertEquals($team->id, $user->teams->first()->id);
    }

    public function testTeamRemoveUser()
    {
        $team = factory(Models\Team::class)->create();
        $user = factory(Fixtures\User::class)->create();
        $team->addUser($user);
        $team->removeUser($user);
        $this->assertEquals(0, $user->teams->count());
    }

    public function testAddAdminRole()
    {
        $user = factory(Fixtures\User::class)->create();
        $user->addRole('ADMIN');
        $this->assertEquals(1, $user->userRoles->count());
        $this->assertEquals('ADMIN', $user->userRoles->first()->role);
    }

    public function testRemoveAdminRole()
    {
        $user = factory(Fixtures\User::class)->create();
        $user->addRole('ADMIN');
        $user->removeRole('ADMIN');
        $this->assertEquals(0, $user->userRoles->count());
    }

    public function testAddTeamAdminRoleWithoutTeam()
    {
        $this->expectException(TeamsException::class);
        $this->expectExceptionMessage('Team must be specified');

        $team = factory(Models\Team::class)->create();
        $user = factory(Fixtures\User::class)->create();
        $team->addUser($user);
        $user->addRole('TEAM_ADMIN');
    }

    public function testAddTeamAdminRoleWithTeam()
    {
        $team = factory(Models\Team::class)->create();
        $user = factory(Fixtures\User::class)->create();
        $team->addUser($user);
        $user->addRole('TEAM_ADMIN', $team);

        $this->assertEquals(1, $user->userRoles->count());
        $userRole = $user->userRoles->first();
        $this->assertEquals('TEAM_ADMIN', $userRole->role);
        $this->assertEquals($team->id, $userRole->team_id);
    }

    public function testAddTeamAdminRoleWithNonMember()
    {
        $this->expectException(TeamsException::class);
        $this->expectExceptionMessage('User must be in team');

        $team = factory(Models\Team::class)->create();
        $user = factory(Fixtures\User::class)->create();
        $user->addRole('TEAM_ADMIN', $team);
    }
}

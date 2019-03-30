<?php

namespace Datashaman\Teams\Tests;

use Datashaman\Teams\Models;
use Datashaman\Teams\TeamsException;
use Hash;

class TeamsUserTest extends TestCase
{
    public function testTeams()
    {
        $team = factory(Models\Team::class)->create();
        $user = factory(Fixtures\User::class)->create();
        $this->assertEquals(0, $user->teams()->count());
        $team->addUser($user);
        $this->assertEquals(1, $user->teams()->count());
        $this->assertEquals($team->id, $user->teams()->first()->id);
    }

    public function testUserRoles()
    {
        $user = factory(Fixtures\User::class)->create();
        $this->assertEquals(0, $user->userRoles()->count());
        $user->addRole('ADMIN');
        $this->assertEquals(1, $user->userRoles()->count());
        $this->assertEquals('ADMIN', $user->userRoles()->first()->role);
    }

    public function testHasRole()
    {
        $user = factory(Fixtures\User::class)->create();
        $user->addRole('ADMIN');
        $this->assertTrue($user->hasRole('ADMIN'));
    }

    public function testHasRoleTeamAdmin()
    {
        $team = factory(Models\Team::class)->create();
        $user = factory(Fixtures\User::class)->create();
        $user->joinTeam($team);
        $this->assertFalse($user->hasRole('TEAM_ADMIN', $team));
        $user->addRole('TEAM_ADMIN', $team);
        $this->assertTrue($user->hasRole('TEAM_ADMIN', $team));
    }

    public function testHasRoleInTeam()
    {
        $team = factory(Models\Team::class)->create();
        $user = factory(Fixtures\User::class)->create();
        $user->joinTeam($team);
        $user->addRole('TEAM_ADMIN', $team);
        $this->assertTrue($user->hasRole('TEAM_ADMIN', $team));
    }

    public function testAddAdminRole()
    {
        $user = factory(Fixtures\User::class)->create();
        $user->addRole('ADMIN');
        $this->assertEquals(1, $user->userRoles->count());
        $this->assertEquals('ADMIN', $user->userRoles->first()->role);
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

    public function testRemoveAdminRole()
    {
        $user = factory(Fixtures\User::class)->create();
        $user->addRole('ADMIN');
        $user->removeRole('ADMIN');
        $this->assertEquals(0, $user->userRoles->count());
    }

    public function testRemoveTeamAdminRole()
    {
        $team = factory(Models\Team::class)->create();
        $user = factory(Fixtures\User::class)->create();
        $user->joinTeam($team);
        $user->addRole('TEAM_ADMIN', $team);
        $user->removeRole('TEAM_ADMIN', $team);
        $this->assertEquals(0, $user->userRoles->count());
    }

    public function testJoinTeam()
    {
        $team = factory(Models\Team::class)->create();
        $user = factory(Fixtures\User::class)->create();
        $user->joinTeam($team);
        $this->assertEquals(1, $user->teams->count());
        $this->assertEquals($team->id, $user->teams->first()->id);
    }

    public function testInTeam()
    {
        $team = factory(Models\Team::class)->create();
        $user = factory(Fixtures\User::class)->create();
        $this->assertFalse($user->inTeam($team));
        $user->joinTeam($team);
        $this->assertTrue($user->inTeam($team));
    }

    public function testLeaveTeam()
    {
        $team = factory(Models\Team::class)->create();
        $user = factory(Fixtures\User::class)->create();
        $user->joinTeam($team);
        $user->leaveTeam($team);
        $this->assertEquals(0, $user->teams->count());
    }

    public function testCreateUserCommand()
    {
        $params = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password,
        ];

        $result = $this->artisan('teams:create-user', $params)->run();

        $this->assertEquals(0, $result);

        $user = Fixtures\User::query()
            ->where('name', $params['name'])
            ->where('email', $params['email'])
            ->firstOrFail();

        $this->assertTrue(Hash::check($params['password'], $user->password));
    }
}

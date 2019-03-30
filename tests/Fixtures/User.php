<?php

namespace Datashaman\Teams\Tests\Fixtures;

use Datashaman\Teams\TeamsUserInterface;
use Datashaman\Teams\TeamsUser;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements TeamsUserInterface
{
    use TeamsUser;
}

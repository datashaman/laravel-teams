<?php

namespace Datashaman\Teams\Tests\Fixtures;

use Datashaman\Teams\TeamsUserInterface;
use Datashaman\Teams\TeamsUser;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements TeamsUserInterface
{
    use TeamsUser;
}

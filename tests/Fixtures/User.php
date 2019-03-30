<?php

namespace Datashaman\Teams\Tests\Fixtures;

use Datashaman\Teams\HasTeams;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasTeams;
}

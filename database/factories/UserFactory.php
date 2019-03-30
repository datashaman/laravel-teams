<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Datashaman\Teams\Tests\Fixtures\User::class, function (Faker $faker) {

    return [
        'name' => $faker->unique()->name,
        'email' => $faker->unique()->email,
        'password' => 'secret',
    ];
});

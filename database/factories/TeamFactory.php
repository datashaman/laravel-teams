<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Datashaman\Teams\Models\Team::class, function (Faker $faker) {
    $name = $faker->unique()->words(3, true);

    return [
        'id' => Str::slug($name),
        'name' => $name,
    ];
});

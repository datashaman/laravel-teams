<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Datashaman\Teams\Models\Team::class, function (Faker $faker) {
    $name = $faker->unique()->words(3, true);

    return [
        'name' => $name,
        'slug' => Str::slug($name),
    ];
});

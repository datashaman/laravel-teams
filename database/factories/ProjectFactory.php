<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Datashaman\Teams\Models\Project::class, function (Faker $faker, array $overrides = []) {
    $name = $faker->unique()->words(2, true);

    return [
        'id' => Str::slug($name),
        'team_id' => factory(Datashaman\Teams\Models\Team::class)->create()->id,
        'name' => $name,
    ];
});

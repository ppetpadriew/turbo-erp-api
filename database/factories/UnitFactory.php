<?php

$factory->define(App\Models\Unit::class, function (Faker\Generator $faker) {
    return [
        'code' => $faker->word,
        'description' => $faker->word,
    ];
});
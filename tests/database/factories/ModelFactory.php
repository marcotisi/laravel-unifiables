<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(MarcoTisi\Unifiables\Test\NewsTest::class, function (Faker\Generator $faker) {
    return [
        'title'        => $faker->sentence(3),
        'subtitle'     => $faker->sentence(10),
        'published_at' => $faker->dateTimeBetween('-2 years', '+1 years'),
    ];
});

$factory->define(\MarcoTisi\Unifiables\Test\EventTest::class, function (Faker\Generator $faker) {
    return [
        'name'        => $faker->sentence(3),
        'venue'     => $faker->address,
        'held_at' => $faker->dateTimeBetween('-2 years', '+1 years'),
    ];
});

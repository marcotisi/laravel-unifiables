<?php

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        factory()
            ->define(MarcoTisi\Unifiables\Test\EventTest::class, function (Faker\Generator $faker) {
                return [
                    'name'    => $faker->sentence(3),
                    'venue'   => $faker->address,
                    'held_at' => $faker->dateTimeBetween('-2 years', '+1 years'),
                ];
            })
            ->times(50)
            ->create();
    }
}

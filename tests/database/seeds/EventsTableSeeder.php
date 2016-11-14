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
        $factory = app(\Illuminate\Database\Eloquent\Factory::class);
        $factory
            ->define(\MarcoTisi\Unifiables\Test\Models\EventTest::class, function (Faker\Generator $faker) {
                return [
                    'name'    => $faker->sentence(3),
                    'venue'   => $faker->address,
                    'held_at' => $faker->dateTimeBetween('-2 years', '+1 years'),
                ];
            });
        $factory
            ->of(\MarcoTisi\Unifiables\Test\Models\EventTest::class)
            ->times(50)
            ->create();
    }
}

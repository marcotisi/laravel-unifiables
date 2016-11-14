<?php

use Illuminate\Database\Seeder;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $factory = app(\Illuminate\Database\Eloquent\Factory::class);
        $factory
            ->define(\MarcoTisi\Unifiables\Test\Models\NewsTest::class, function (Faker\Generator $faker) {
                return [
                    'title'        => $faker->sentence(3),
                    'subtitle'     => $faker->sentence(10),
                    'published_at' => $faker->dateTimeBetween('-2 years', '+1 years'),
                ];
            });
        $factory
            ->of(\MarcoTisi\Unifiables\Test\Models\NewsTest::class)
            ->times(50)
            ->create();
    }
}

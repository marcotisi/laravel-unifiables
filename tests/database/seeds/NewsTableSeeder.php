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
//        factory(\MarcoTisi\Unifiables\Test\NewsTest::class, 50)->create();
        factory()
            ->define(MarcoTisi\Unifiables\Test\NewsTest::class, function (Faker\Generator $faker) {
                return [
                    'title'        => $faker->sentence(3),
                    'subtitle'     => $faker->sentence(10),
                    'published_at' => $faker->dateTimeBetween('-2 years', '+1 years'),
                ];
            })
            ->times(50)
            ->create();
    }
}

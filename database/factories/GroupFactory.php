<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Group;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Group::class, function (Faker $faker) {
    $course = rand(1, 4);
    $year = $faker->unique()->randomDigit;
    $practice_start = $faker->date(2020 + $year . '-07-' . rand(1, 15));
    $practice_end = $faker->date(2020 + $year . '-07-' . rand(16, 30));
    return [
        'name' => 'НМТ-' . $course . $year . '39' . [29, 30, 31][rand(0,2)],
        'direction_code' => ['09.03.02', '15.03.04'][rand(0, 1)],
        'direction' => ['Информационные системы и технологии', 'Автоматизация технологических процессов и производств'][rand(0, 1)],
        'year' => 2020 + $year,
        'course' => $course,
        'practice_start' => $practice_start,
        'practice_end' => $practice_end,
    ];
});

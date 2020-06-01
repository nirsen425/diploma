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
    return [
        'name' => 'НМТ-' . $course . $year . '39' . [29, 30, 31][rand(0,2)],
        'year' => 2020 + $year,
        'direction_id' => rand(1, 2),
        'course_id' => $course,
    ];
});

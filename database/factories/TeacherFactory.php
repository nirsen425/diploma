<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Teacher;
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

$factory->define(Teacher::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName,
        'patronymic' => 'Allan',
        'surname' => $faker->lastName,
        'short_description' => $faker->paragraph(2, true),
        'full_description' => $faker->paragraph(5, true),
        'photo' => 'empty.png'
    ];
});

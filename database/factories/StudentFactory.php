<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Student;
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

$factory->define(Student::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName,
        'patronymic' => 'Allan',
        'surname' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'student_ticket' => '12345678',
        'status' => rand(0, 1)
    ];
});

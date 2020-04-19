<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
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

$factory->state(App\User::class, 'student', function ($faker) {
    return [
        'user_type_id' => 1,
    ];
});

$factory->state(App\User::class, 'teacher', function ($faker) {
    return [
        'user_type_id' => 2,
    ];
});

$factory->define(User::class, function (Faker $faker) {
    return [
        'login' => $faker->firstName . rand(1, 1000),
        'confirm' => 1,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'rights_id' => rand(1, 2)
    ];
});

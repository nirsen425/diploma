<?php

use App\User;
use Illuminate\Database\Seeder;

class TeachersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Формируем преподавателей, создавая пользователей связанных с преподавателями по связи 1 к 1
        factory(App\User::class, 15)->states('teacher')->create()->each(function ($u) {
            $u->teacher()->save(factory(App\Teacher::class)->make());
        });
    }
}

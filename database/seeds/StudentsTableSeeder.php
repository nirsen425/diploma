<?php

use Illuminate\Database\Seeder;
use App\Group;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groupIds = Group::all()->pluck('id')->toArray();

        // Формируем студентов, создавая пользователей связанных со студентами по связи 1 к 1
        factory(App\User::class, 50)->states('student')->create()->each(function ($u) use ($groupIds) {
            $u->student()->save(factory(App\Student::class)->make([
                'group_id' => $groupIds[array_rand($groupIds)]
            ]));
        });
    }
}

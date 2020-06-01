<?php

use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('courses')->insert([
            'course' => '1'
        ]);

        DB::table('courses')->insert([
            'course' => '2'
        ]);

        DB::table('courses')->insert([
            'course' => '3'
        ]);

        DB::table('courses')->insert([
            'course' => '4'
        ]);
    }
}

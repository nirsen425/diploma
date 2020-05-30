<?php

use Illuminate\Database\Seeder;

class PracticesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('practices')->insert([
            'practice_start' => '2020.06.29',
            'practice_end' => '2020.07.02',
            'practice_info' => 'Здесь будет какая-то информация',
            'direction_id' => '1',
            'course_id' => '1'
        ]);

        DB::table('practices')->insert([
            'practice_start' => '2020.06.29',
            'practice_end' => '2020.07.02',
            'practice_info' => 'Здесь будет какая-то информация',
            'direction_id' => '1',
            'course_id' => '2'
        ]);

        DB::table('practices')->insert([
            'practice_start' => '2020.06.29',
            'practice_end' => '2020.07.02',
            'practice_info' => 'Здесь будет какая-то информация',
            'direction_id' => '1',
            'course_id' => '3'
        ]);

        DB::table('practices')->insert([
            'practice_start' => '2020.06.29',
            'practice_end' => '2020.07.02',
            'practice_info' => 'Здесь будет какая-то информация',
            'direction_id' => '1',
            'course_id' => '4'
        ]);

        DB::table('practices')->insert([
            'practice_start' => '2020.06.29',
            'practice_end' => '2020.07.02',
            'practice_info' => 'Здесь будет какая-то информация',
            'direction_id' => '2',
            'course_id' => '1'
        ]);

        DB::table('practices')->insert([
            'practice_start' => '2020.06.29',
            'practice_end' => '2020.07.02',
            'practice_info' => 'Здесь будет какая-то информация',
            'direction_id' => '2',
            'course_id' => '2'
        ]);

        DB::table('practices')->insert([
            'practice_start' => '2020.06.29',
            'practice_end' => '2020.07.02',
            'practice_info' => 'Здесь будет какая-то информация',
            'direction_id' => '2',
            'course_id' => '3'
        ]);

        DB::table('practices')->insert([
            'practice_start' => '2020.06.29',
            'practice_end' => '2020.07.02',
            'practice_info' => 'Здесь будет какая-то информация',
            'direction_id' => '2',
            'course_id' => '4'
        ]);
    }
}

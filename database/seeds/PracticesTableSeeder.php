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
            'application_start' => '2020.05.04',
            'application_end' => '2020.07.26',
            'practice_start' => '2020.06.29',
            'practice_end' => '2020.07.02',
            'practice_info' => 'Информация для 09.03.03 1 курс',
            'direction_id' => '1',
            'course_id' => '1'
        ]);

        DB::table('practices')->insert([
            'application_start' => '2020.05.04',
            'application_end' => '2020.07.26',
            'practice_start' => '2020.06.29',
            'practice_end' => '2020.07.02',
            'practice_info' => 'Информация для 09.03.03 2 курс',
            'direction_id' => '1',
            'course_id' => '2'
        ]);

        DB::table('practices')->insert([
            'application_start' => '2020.05.04',
            'application_end' => '2020.07.26',
            'practice_start' => '2020.06.29',
            'practice_end' => '2020.07.02',
            'practice_info' => 'Информация для 09.03.03 3 курс',
            'direction_id' => '1',
            'course_id' => '3'
        ]);

        DB::table('practices')->insert([
            'application_start' => '2020.05.04',
            'application_end' => '2020.07.26',
            'practice_start' => '2020.06.29',
            'practice_end' => '2020.07.02',
            'practice_info' => 'Информация для 09.03.03 4 курс',
            'direction_id' => '1',
            'course_id' => '4'
        ]);

        DB::table('practices')->insert([
            'application_start' => '2020.05.04',
            'application_end' => '2020.07.26',
            'practice_start' => '2020.06.29',
            'practice_end' => '2020.07.02',
            'practice_info' => 'Информация для 15.03.04 1 курс',
            'direction_id' => '2',
            'course_id' => '1'
        ]);

        DB::table('practices')->insert([
            'application_start' => '2020.05.04',
            'application_end' => '2020.07.26',
            'practice_start' => '2020.06.29',
            'practice_end' => '2020.07.02',
            'practice_info' => 'Информация для 15.03.04 2 курс',
            'direction_id' => '2',
            'course_id' => '2'
        ]);

        DB::table('practices')->insert([
            'application_start' => '2020.05.04',
            'application_end' => '2020.07.26',
            'practice_start' => '2020.06.29',
            'practice_end' => '2020.07.02',
            'practice_info' => 'Информация для 15.03.04 3 курс',
            'direction_id' => '2',
            'course_id' => '3'
        ]);

        DB::table('practices')->insert([
            'application_start' => '2020.05.04',
            'application_end' => '2020.07.26',
            'practice_start' => '2020.06.29',
            'practice_end' => '2020.07.02',
            'practice_info' => 'Информация для 15.03.04 4 курс',
            'direction_id' => '2',
            'course_id' => '4'
        ]);
    }
}

<?php

use Illuminate\Database\Seeder;

class DirectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('directions')->insert([
            'direction' => '09.03.02',
            'direction_name' => 'Информационные системы и технологии'
        ]);

        DB::table('directions')->insert([
            'direction' => '15.03.04',
            'direction_name' => 'Автоматизация технологических процессов и производств'
        ]);
    }
}

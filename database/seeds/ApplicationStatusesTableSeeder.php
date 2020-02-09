<?php

use Illuminate\Database\Seeder;

class ApplicationStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('application_statuses')->insert([
            'title' => 'wait'
        ]);

        DB::table('application_statuses')->insert([
            'title' => 'confirmed'
        ]);

        DB::table('application_statuses')->insert([
            'title' => 'rejected'
        ]);
    }
}

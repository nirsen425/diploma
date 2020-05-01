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
            'title' => 'Ожидание'
        ]);

        DB::table('application_statuses')->insert([
            'title' => 'Подтверждена'
        ]);

        DB::table('application_statuses')->insert([
            'title' => 'Отклонена'
        ]);
    }
}

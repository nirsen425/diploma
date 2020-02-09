<?php

use Illuminate\Database\Seeder;

class ApplicationTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('application_types')->insert([
            'title' => 'practice'
        ]);

        DB::table('application_types')->insert([
            'title' => 'diploma'
        ]);
    }
}

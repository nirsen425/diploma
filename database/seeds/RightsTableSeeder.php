<?php

use Illuminate\Database\Seeder;

class RightsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rights')->insert([
            'title' => 'common'
        ]);

        DB::table('rights')->insert([
            'title' => 'admin'
        ]);
    }
}

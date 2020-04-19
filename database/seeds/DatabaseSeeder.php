<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RightsTableSeeder::class);
        $this->call(UserTypesTableSeeder::class);
        $this->call(ApplicationStatusesTableSeeder::class);
        $this->call(ApplicationTypesTableSeeder::class);
        $this->call(GroupsTableSeeder::class);
        $this->call(StudentsTableSeeder::class);
        $this->call(TeachersTableSeeder::class);
    }
}

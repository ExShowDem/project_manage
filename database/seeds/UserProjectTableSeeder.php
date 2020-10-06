<?php

use Illuminate\Database\Seeder;
use App\Models\UserProject;

class UserProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(UserProject::class, 100)->create();
    }
}

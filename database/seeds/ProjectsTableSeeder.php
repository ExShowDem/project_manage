<?php

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('projects')->insert([
            [
                'created_by' => 1,
                'project_type' => \App\Enums\ProjectType::COMPANY,
                'name' => 'Công ty Megaon',
                'code' => 'megaon',
                'description' => 'Công ty Megaon',
                'featured_image' => 'https://megaon.arcana.asia/assets/admin/images/megaon.png',
            ],
        ]);

        factory(Project::class, 3)->create();
    }
}

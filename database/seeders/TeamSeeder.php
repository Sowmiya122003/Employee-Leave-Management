<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::insert([
            [
                'id' => 1,
                'team_name' => 'Development',
                'description' => 'Responsible for designing, developing, testing, and maintaining software applications and systems to meet business requirements.',
            ],
            [
                'id' => 2,
                'team_name' => 'Support',
                'description' => 'Responsible for providing technical assistance, troubleshooting hardware and software issues, and ensuring smooth operation of IT systems for users.',
            ],
            [
                'id' => 3,
                'team_name' => 'QA',
                'description' => 'Responsible for verifying software quality by identifying defects, ensuring functionality meets requirements, and maintaining product reliability before release.',
            ],
            [
                'id' => 4,
                'team_name' => 'DevOps',
                'description' => 'Responsible for automating software deployment, managing infrastructure, and ensuring reliable, secure, and efficient delivery of applications.',
            ],
            [
                'id' => 5,
                'team_name' => 'UI/UX',
                'description' => 'Responsible for designing user-friendly interfaces and creating seamless user experiences to improve usability, accessibility, and customer satisfaction.',
            ],
            [
                'id' => 6,
                'team_name' => 'HR',
                'description' => 'Responsible for managing recruitment, employee relations, payroll, performance, training, and overall workforce administration within the organization.',
            ],
        ]);
    }
}

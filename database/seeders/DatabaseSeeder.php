<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Job;
use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        // \App\Models\User::factory(5)->create();

        // \App\Models\User::factory()->create([
        //     'email' => 'test@example.com',
        //     'email_verified_at' => now(),
        //     'password' => 'password',
        // ]);

        // \App\Models\Job::factory(40)->create();
        // \App\Models\Company::factory(20)->create();
        // \App\Models\UserInfo::factory(6)->create();


        # TINKER METHODS

        # FILL job_skill pivot table
        // $jobs = Job::all();
        // $skills = Skill::all();
        // foreach ($jobs as $job) {
        //     $randomSkills = $skills->random(mt_rand(1, 3));

        //     foreach ($randomSkills as $skill) {
        //         DB::table('job_skill')->insert([
        //             'job_id' => $job->id,
        //             'skill_id' => $skill->id,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }
        // }

        # Fill job_types table
        // use App\Models\JobType
        // $jobTypes = [
        //     "Full-Time",
        //     "Part-Time",
        //     "Remote",
        //     "On-Site",
        //     "Contract/Freelance",
        //     "Internship",
        //     "Temporary",
        //     "Freelance",
        //     "Commission-Based",
        //     "Seasonal",
        //     "Remote Part-Time",
        //     "Hybrid",
        // ];
        // foreach ($jobTypes as $jobType) {
        //      JobType::create(['job_type' => $jobType]);
        //     }

        # FILL job_job_types pivot table
        // use App\Models\JobType
        // use App\Models\JobType
        // $jobs = Job::all();
        // $jobTypes = JobType::all();
        // foreach ($jobs as $job) {
        //     $randomJobType = $jobTypes->random(mt_rand(1, 3));

        //     foreach ($randomJobType as $type) {
        //         DB::table('job_job_types')->insert([
        //             'job_id' => $job->id,
        //             'job_type_id' => $type->id,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }
        // }

        # Fill skills table
        // $programmingLanguages = [
        //     'Java', 'Python', 'C/C++', 'C#', 'Ruby', 'JavaScript', 'PHP', 'Swift', 'Kotlin',
        //     'HTML/CSS', 'JavaScript Frameworks (e.g., React, Angular, Vue.js)', 'Node.js', 'Front-end Design and Responsive UI',
        //     'Android Development (Java/Kotlin)', 'iOS Development (Swift/Objective-C)', 'Cross-platform Mobile Frameworks (e.g., React Native, Flutter)',
        //     'MySQL', 'PostgreSQL', 'SQL Server', 'Oracle',
        //     'MongoDB', 'Cassandra', 'Redis',
        //     'AWS (Amazon Web Services)', 'Azure', 'Google Cloud Platform',
        //     'Docker', 'Kubernetes', 'Continuous Integration and Continuous Deployment (CI/CD) tools', 'Infrastructure as Code (IaC)',
        //     'Data Analysis (Python, R)', 'Machine Learning (TensorFlow, scikit-learn)', 'Data Visualization (Tableau, D3.js)',
        //     'Information Security', 'Network Security', 'Ethical Hacking',
        //     'Test Automation (Selenium, Cypress)', 'Unit Testing', 'Test-Driven Development (TDD)',
        //     'Git', 'GitHub/GitLab/Bitbucket',
        //     'Linux/Unix', 'Windows Server',
        //     'Sorting Algorithms', 'Search Algorithms', 'Graph Algorithms',
        //     'TCP/IP', 'Network Protocols', 'Network Security',
        //     'Singleton', 'Observer', 'Factory',
        //     'Agile Methodologies (Scrum, Kanban)', 'Jira', 'Trello',
        //     'Natural Language Processing (NLP)', 'Computer Vision', 'Robotics Frameworks',
        //     'Arduino', 'Raspberry Pi', 'Embedded C/C++',
        //     'VMware', 'VirtualBox',
        //     'Apache', 'Nginx', 'Server Administration',
        // ];
        // foreach ($programmingLanguages as $skill) {
        //      Skill::create(['name' => $skill, 'category' => 'Programming Languages']);
        //     }

    }
}

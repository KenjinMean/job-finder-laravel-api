<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillsTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        $programmingLanguages = [
            'Java', 'Python', 'C/C++', 'C#', 'Ruby', 'JavaScript', 'PHP', 'Swift', 'Kotlin',
            'HTML/CSS', 'JavaScript Frameworks (e.g., React, Angular, Vue.js)', 'Node.js', 'Front-end Design and Responsive UI',
            'Android Development (Java/Kotlin)', 'iOS Development (Swift/Objective-C)', 'Cross-platform Mobile Frameworks (e.g., React Native, Flutter)',
            'MySQL', 'PostgreSQL', 'SQL Server', 'Oracle',
            'MongoDB', 'Cassandra', 'Redis',
            'AWS (Amazon Web Services)', 'Azure', 'Google Cloud Platform',
            'Docker', 'Kubernetes', 'Continuous Integration and Continuous Deployment (CI/CD) tools', 'Infrastructure as Code (IaC)',
            'Data Analysis (Python, R)', 'Machine Learning (TensorFlow, scikit-learn)', 'Data Visualization (Tableau, D3.js)',
            'Information Security', 'Network Security', 'Ethical Hacking',
            'Test Automation (Selenium, Cypress)', 'Unit Testing', 'Test-Driven Development (TDD)',
            'Git', 'GitHub/GitLab/Bitbucket',
            'Linux/Unix', 'Windows Server',
            'Sorting Algorithms', 'Search Algorithms', 'Graph Algorithms',
            'TCP/IP', 'Network Protocols', 'Network Security',
            'Singleton', 'Observer', 'Factory',
            'Agile Methodologies (Scrum, Kanban)', 'Jira', 'Trello',
            'Natural Language Processing (NLP)', 'Computer Vision', 'Robotics Frameworks',
            'Arduino', 'Raspberry Pi', 'Embedded C/C++',
            'VMware', 'VirtualBox',
            'Apache', 'Nginx', 'Server Administration',
        ];

        foreach ($programmingLanguages as $skill) {
            Skill::create(['name' => $skill]);
        }
    }
}

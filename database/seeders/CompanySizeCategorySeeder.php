<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanySizeCategory;

class CompanySizeCategorySeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        $categories = [
            'Micro',
            'Small',
            'Medium',
            'Large',
            'Enterprise',
            'Large Enterprise',
            'Global Corporation',
        ];

        foreach ($categories as $category) {
            CompanySizeCategory::create(['size' => $category]);
        }
    }
}

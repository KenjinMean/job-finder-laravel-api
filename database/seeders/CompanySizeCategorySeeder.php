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
            'Below 10',
            '10-50',
            '51-200',
            '201-500',
            '501-1,000',
            '1,001-5,000',
            'Over 5,000',
        ];

        foreach ($categories as $category) {
            CompanySizeCategory::create(['size' => $category]);
        }
    }
}

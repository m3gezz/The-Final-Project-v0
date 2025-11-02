<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Web Development', 'Mobile Development', 'Game Development',
            'Artificial Intelligence', 'Machine Learning', 'Data Science',
            'Cybersecurity', 'DevOps & Cloud', 'UI/UX Design', 'E-Commerce',
            'FinTech', 'Healthcare', 'Environment & Sustainability',
            'Social Good', 'Education', 'Entertainment', 'Sports', 'Music'
        ];

        foreach ($categories as $category) {
            Category::create(['category' => $category]);
        }
    }
}

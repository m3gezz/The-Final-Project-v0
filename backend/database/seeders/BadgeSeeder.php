<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            ['badge' => 'Top Contributor', 'description' => 'Awarded for contributing high-quality projects'],
            ['badge' => 'Team Player', 'description' => 'Given to users who actively participate in team projects'],
            ['badge' => 'Innovator', 'description' => 'For users who create unique and innovative projects'],
            ['badge' => 'Bug Hunter', 'description' => 'Awarded for reporting and fixing critical bugs'],
            ['badge' => 'Mentor', 'description' => 'Given to users who help and guide others'],
            ['badge' => 'Early Adopter', 'description' => 'For users who adopt new features quickly'],
            ['badge' => 'Community Leader', 'description' => 'Recognized for leading and organizing community events'],
            ['badge' => 'Problem Solver', 'description' => 'For solving challenging problems effectively'],
            ['badge' => 'Rising Star', 'description' => 'Given to promising new users'],
            ['badge' => 'Master Coder', 'description' => 'Awarded for completing complex projects successfully'],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}

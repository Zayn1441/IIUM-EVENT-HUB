<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'AI & Machine Learning Workshop',
                'description' => 'Join us for an intensive workshop on AI and Machine Learning fundamentals. Learn about neural networks, deep learning, and real-world applications.',
                'category' => 'Academic',
                'date' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'time' => '09:00:00',
                'location' => 'KICT Auditorium',
                'capacity' => 150,
                'organizer' => 'KICT Student Society',
                'is_starpoints' => true,
                'image_path' => null, // Placeholder in view
            ],
            [
                'title' => 'Annual Cultural Night 2026',
                'description' => 'Experience the vibrant diversity of IIUM! Join us for an evening of cultural performances, food festivals, and connecting with the global community.',
                'category' => 'Cultural',
                'date' => Carbon::now()->addDays(12)->format('Y-m-d'),
                'time' => '19:00:00',
                'location' => 'IIUM Main Hall',
                'capacity' => 500,
                'organizer' => 'Student Representative Council',
                'is_starpoints' => true,
                'image_path' => null,
            ],
            [
                'title' => 'Futsal Championship Finals',
                'description' => 'The most anticipated sports event of the semester! Watch the best futsal teams from every Mahallah compete for the championship trophy.',
                'category' => 'Sports',
                'date' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'time' => '16:00:00',
                'location' => 'IIUM Sports Complex',
                'capacity' => 300,
                'organizer' => 'Sports Center',
                'is_starpoints' => false,
                'image_path' => null,
            ],
            [
                'title' => 'Islamic Finance Summit 2026',
                'description' => 'A gathering of industry leaders and scholars to discuss the future of Islamic Finance in the digital age.',
                'category' => 'Academic',
                'date' => Carbon::now()->addDays(20)->format('Y-m-d'),
                'time' => '08:30:00',
                'location' => 'Exhibition Hall A',
                'capacity' => 200,
                'organizer' => 'KENMS',
                'is_starpoints' => true,
                'image_path' => null,
            ],
        ];

        $userId = \App\Models\User::first()->id ?? null;

        foreach ($events as $event) {
            $event['user_id'] = $userId;
            Event::create($event);
        }
    }
}

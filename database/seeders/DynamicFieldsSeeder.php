<?php

namespace Database\Seeders;

use App\Models\PreparingFor;
use App\Models\StudentClass;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DynamicFieldsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Classes
        $classes = [
            ['name' => 'Class 11', 'order' => 1],
            ['name' => 'Class 12', 'order' => 2],
            ['name' => 'Repeater / Dropper', 'order' => 3],
            ['name' => 'Class 10 (Foundation)', 'order' => 4],
        ];

        foreach ($classes as $c) {
            StudentClass::updateOrCreate(
                ['slug' => Str::slug($c['name'])],
                [
                    'name' => $c['name'],
                    'display_order' => $c['order'],
                    'is_active' => true,
                ]
            );
        }

        // 2. Seed Indian States & UTs
        $states = [
            ['name' => 'Andhra Pradesh', 'code' => 'AP', 'order' => 1],
            ['name' => 'Delhi (NCT)', 'code' => 'DL', 'order' => 2],
            ['name' => 'Karnataka', 'code' => 'KA', 'order' => 3],
            ['name' => 'Kerala', 'code' => 'KL', 'order' => 4],
            ['name' => 'Maharashtra', 'code' => 'MH', 'order' => 5],
            ['name' => 'Tamil Nadu', 'code' => 'TN', 'order' => 6],
            ['name' => 'Telangana', 'code' => 'TS', 'order' => 7],
            ['name' => 'Uttar Pradesh', 'code' => 'UP', 'order' => 8],
            ['name' => 'Bihar', 'code' => 'BR', 'order' => 9],
            ['name' => 'Gujarat', 'code' => 'GJ', 'order' => 10],
            ['name' => 'Haryana', 'code' => 'HR', 'order' => 11],
            ['name' => 'Madhya Pradesh', 'code' => 'MP', 'order' => 12],
            ['name' => 'Punjab', 'code' => 'PB', 'order' => 13],
            ['name' => 'Rajasthan', 'code' => 'RJ', 'order' => 14],
            ['name' => 'West Bengal', 'code' => 'WB', 'order' => 15],
            ['name' => 'Assam', 'code' => 'AS', 'order' => 16],
            ['name' => 'Odisha', 'code' => 'OD', 'order' => 17],
            ['name' => 'Jharkhand', 'code' => 'JH', 'order' => 18],
            ['name' => 'Uttarakhand', 'code' => 'UK', 'order' => 19],
            ['name' => 'Himachal Pradesh', 'code' => 'HP', 'order' => 20],
        ];

        foreach ($states as $s) {
            State::updateOrCreate(
                ['name' => $s['name']],
                [
                    'code' => $s['code'],
                    'display_order' => $s['order'],
                    'is_active' => true,
                ]
            );
        }

        // 3. Seed Target Exams (Preparing For)
        $exams = [
            [
                'name' => 'AEEE (Amrita Engineering Entrance Exam)',
                'short_name' => 'AEEE',
                'description' => 'Amrita Vishwa Vidyapeetham Engineering Admission',
                'order' => 1,
            ],
            [
                'name' => 'JEE Main',
                'short_name' => 'JEE Main',
                'description' => 'Joint Entrance Examination for NITs & IIITs',
                'order' => 2,
            ],
            [
                'name' => 'JEE Advanced',
                'short_name' => 'JEE Adv',
                'description' => 'Indian Institutes of Technology (IITs)',
                'order' => 3,
            ],
            [
                'name' => 'NEET UG',
                'short_name' => 'NEET',
                'description' => 'National Eligibility cum Entrance Test for Medical',
                'order' => 4,
            ],
            [
                'name' => 'KEAM (Kerala Engineering)',
                'short_name' => 'KEAM',
                'description' => 'Kerala Engineering Architecture Medical',
                'order' => 5,
            ],
            [
                'name' => 'BITSAT',
                'short_name' => 'BITSAT',
                'description' => 'Birla Institute of Technology and Science',
                'order' => 6,
            ],
            [
                'name' => 'MHT CET',
                'short_name' => 'MHT CET',
                'description' => 'Maharashtra Common Entrance Test',
                'order' => 7,
            ],
        ];

        foreach ($exams as $e) {
            PreparingFor::updateOrCreate(
                ['name' => $e['name']],
                [
                    'short_name' => $e['short_name'],
                    'description' => $e['description'],
                    'display_order' => $e['order'],
                    'is_active' => true,
                ]
            );
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function resources()
    {
        $resources = [
            [
                'category' => 'Lesson Plans',
                'items' => [
                    ['name' => 'Complete Lesson Plan Template', 'type' => 'PDF', 'size' => '245 KB'],
                    ['name' => 'Quick Lesson Outline Template', 'type' => 'PDF', 'size' => '128 KB'],
                    ['name' => 'Multi-Week Curriculum Planner', 'type' => 'PDF', 'size' => '312 KB'],
                ]
            ],
            [
                'category' => 'Activity Sheets',
                'items' => [
                    ['name' => 'Bible Story Coloring Pages (50 pages)', 'type' => 'PDF', 'size' => '8.2 MB'],
                    ['name' => 'Word Search Collection', 'type' => 'PDF', 'size' => '1.5 MB'],
                    ['name' => 'Crossword Puzzles', 'type' => 'PDF', 'size' => '980 KB'],
                    ['name' => 'Memory Verse Cards', 'type' => 'PDF', 'size' => '2.1 MB'],
                ]
            ],
            [
                'category' => 'Craft Templates',
                'items' => [
                    ['name' => 'Noah\'s Ark Craft Template', 'type' => 'PDF', 'size' => '456 KB'],
                    ['name' => 'David\'s Sling Craft', 'type' => 'PDF', 'size' => '234 KB'],
                    ['name' => 'Nativity Scene Cutouts', 'type' => 'PDF', 'size' => '1.8 MB'],
                ]
            ],
            [
                'category' => 'Teaching Guides',
                'items' => [
                    ['name' => 'Age-Appropriate Teaching Methods', 'type' => 'PDF', 'size' => '567 KB'],
                    ['name' => 'Classroom Management Tips', 'type' => 'PDF', 'size' => '423 KB'],
                    ['name' => 'Engaging Discussion Techniques', 'type' => 'PDF', 'size' => '389 KB'],
                ]
            ],
        ];

        return view('pages.resources', compact('resources'));
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BlogPost;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'id' => 1,
                'title' => 'Teaching Bible Stories to Young Children',
                'excerpt' => 'Effective methods for engaging young minds with biblical narratives and making lessons memorable.',
                'content' => <<<'EOT'
Teaching Bible stories to young children requires creativity, patience, and understanding of child development. Here are some proven strategies that will help you connect with your students and make biblical truths come alive.

First, use visual aids whenever possible. Children are visual learners, and pictures, props, and illustrations help them understand and remember the stories. Consider using flannel boards, puppets, or simple costumes to act out the stories.

Second, keep it simple and age-appropriate. Focus on the main point of the story rather than every detail. For preschoolers, a 5-10 minute story is ideal, while elementary students can handle 15-20 minutes.

Third, make it interactive. Ask questions throughout the story, let children act out parts, or use hand motions. When children participate, they engage more deeply with the material.

Fourth, connect the story to their lives. Help children see how biblical principles apply to their everyday experiences. Use examples they can relate to, like sharing toys, being kind to siblings, or telling the truth.

Finally, repeat and review. Children learn through repetition. Review previous lessons, sing songs about the stories, and encourage parents to reinforce the lessons at home.

Remember, your enthusiasm is contagious! When you\'re excited about the Bible stories, your students will be too.
EOT,
                'published_at' => '2024-01-15',
                'author' => 'Sarah Johnson',
                'status' => 'published',
            ],
            [
                'id' => 2,
                'title' => 'Using Multimedia in Sunday School',
                'excerpt' => 'How to effectively incorporate videos, audio, and interactive elements in your lessons.',
                'content' => <<<'EOT'
Modern technology offers amazing opportunities to enhance Bible teaching and engage today\'s tech-savvy children. Here\'s how to effectively use multimedia in your Sunday school classroom.

Video clips can bring Bible stories to life in ways that capture children\'s attention. Use short, high-quality videos (3-5 minutes) to introduce a story or reinforce a key point. Many free resources are available online, but always preview content first to ensure it aligns with your teaching goals.

Audio elements like background music, sound effects, and narrated stories can create atmosphere and help children focus. Consider playing soft instrumental music during craft time or using sound effects (like thunder or animal sounds) during storytelling.

Interactive presentations using tools like PowerPoint or Google Slides can make lessons more dynamic. Include images, simple animations, and questions that prompt discussion. Keep text minimal and use large, readable fonts.

However, remember that technology should enhance, not replace, personal interaction. The most important element of Sunday school is the relationship between teacher and student. Use multimedia as a tool, not a crutch.

Practical tips: Test all technology before class, have a backup plan if equipment fails, and don\'t let technical difficulties derail your lesson. Keep the focus on the biblical message, not the medium.

Balance is key. Combine multimedia with hands-on activities, discussion, and traditional storytelling for a well-rounded learning experience.
EOT,
                'published_at' => '2024-01-10',
                'author' => 'Michael Davis',
                'status' => 'published',
            ],
            [
                'id' => 3,
                'title' => 'Creating an Engaging Classroom Environment',
                'excerpt' => 'Tips for setting up a Sunday school space that promotes learning and spiritual growth.',
                'content' => <<<'EOT'
The physical environment of your Sunday school classroom plays a crucial role in how children learn and engage with biblical teachings. A well-designed space can minimize distractions, encourage participation, and create a welcoming atmosphere.

Start with the basics: ensure adequate lighting, comfortable seating appropriate for the age group, and a temperature-controlled room. Children can\'t focus if they\'re uncomfortable.

Create distinct areas for different activities. Have a storytelling corner with cushions or a rug where children gather for Bible stories. Set up a craft station with supplies organized and easily accessible. If space allows, include a quiet corner for prayer or reflection.

Use wall space wisely. Display colorful Bible verse posters, a prayer wall where children can post requests, and student artwork. Rotate displays regularly to keep the environment fresh and relevant to current lessons.

Organization is essential. Label storage bins, keep supplies stocked, and maintain a clean, clutter-free space. When transitions between activities are smoother.

Consider the sensory experience. Use pleasant scents (like cinnamon during Christmas lessons), play soft background music, and incorporate tactile elements in your lessons.

Most importantly, make it a safe and welcoming space. Greet each child by name, establish clear behavioral expectations, and create an atmosphere where questions are encouraged and every child feels valued.

Your classroom should reflect the love and joy of Christ, making children excited to come and learn about God each week.
EOT,
                'published_at' => '2024-01-05',
                'author' => 'Jennifer Martinez',
                'status' => 'published',
            ]
        ];

        foreach ($posts as $post) {
            BlogPost::create($post);
        }
    }
}

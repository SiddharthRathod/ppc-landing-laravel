<?php

namespace Database\Seeders;

use App\Models\Post;
use Database\Seeders\Concerns\CopiesDemoImages;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    use CopiesDemoImages;

    /**
     * The three guides from the design. Bodies are short placeholders
     * until the client sends the real articles.
     */
    public function run(): void
    {
        $posts = [
            [
                'slug' => 'corporate-video-guide-saudi-companies',
                'title' => 'A corporate video guide for Saudi companies',
                'category' => 'Corporate video',
                'image' => 'blog-corporate-video.webp',
                'excerpt' => 'Brand films, CEO messages and testimonials: what to plan before the cameras arrive.',
                'body' => '<p>A good corporate video starts long before the shoot. Decide who the film is for, the one message they should remember, and where it will be watched.</p>'
                    .'<h2>Plan before you film</h2><ul><li>One clear audience and one main message.</li><li>Arabic, English or both: plan subtitles and voice-over early.</li><li>Locations, permits and the people who need to be on camera.</li></ul>'
                    .'<h2>What a typical shoot looks like</h2><p>Most corporate films are filmed in one or two days with a small crew, then edited over one to two weeks, with two rounds of feedback.</p>',
                'published_at' => now()->subDays(30),
            ],
            [
                'slug' => 'safety-induction-videos-saudi-worksites',
                'title' => 'Safety induction videos for Saudi worksites',
                'category' => 'Safety video',
                'image' => 'blog-safety-video.webp',
                'excerpt' => 'How filmed and animated inductions help crews learn site rules in Arabic, English and Urdu.',
                'body' => '<p>Safety inductions are watched by every new worker on site, often in several languages. A clear film makes the rules easier to remember than a slide deck.</p>'
                    .'<h2>Film, animation or both</h2><p>Real footage shows the actual site and equipment. 2D or 3D animation shows hazards that cannot be filmed safely, such as falls or gas leaks.</p>'
                    .'<h2>Languages</h2><p>We record voice-over and subtitles in the languages your crews speak, so one film covers the whole workforce.</p>',
                'published_at' => now()->subDays(20),
            ],
            [
                'slug' => 'construction-timelapse-saudi-projects',
                'title' => 'Construction timelapse for Saudi projects',
                'category' => 'Timelapse',
                'image' => 'blog-timelapse.webp',
                'excerpt' => 'Solar-powered cameras, live monitoring and a finished film for the project handover.',
                'body' => '<p>A construction timelapse records the whole build, from groundworks to handover, and gives stakeholders a live view of progress.</p>'
                    .'<h2>The setup</h2><ul><li>Solar-powered 4K to 8K cameras that run for the full project.</li><li>Remote monitoring, so a camera problem is fixed the same week.</li><li>Images stored online and shared with the project team.</li></ul>'
                    .'<h2>The final film</h2><p>At handover the images are edited into a short film for the opening, the website and social media.</p>',
                'published_at' => now()->subDays(10),
            ],
        ];

        foreach ($posts as $post) {
            Post::updateOrCreate(
                ['slug' => $post['slug']],
                [...$post, 'image' => $this->demoImage($post['image'])],
            );
        }
    }
}

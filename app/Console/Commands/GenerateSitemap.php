<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap.xml for SEO - includes all posts, categories, tags, and static pages';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔄 Generating sitemap.xml...');

        $sitemap = $this->generateSitemap();

        // Save to public folder
        $path = public_path('sitemap.xml');
        File::put($path, $sitemap);

        $this->info("✅ Sitemap generated successfully at: {$path}");
        $this->info("🌐 Access it at: " . url('/sitemap.xml'));

        return Command::SUCCESS;
    }

    /**
     * Generate the sitemap XML content
     */
    private function generateSitemap(): string
    {
        $urls = [];

        // 1. Static Pages (High Priority)
        $staticPages = [
            [
                'loc' => url('/'),
                'lastmod' => now()->toW3cString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => route('about'),
                'lastmod' => now()->toW3cString(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => route('contact'),
                'lastmod' => now()->toW3cString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
        ];

        $urls = array_merge($urls, $staticPages);
        $this->info("  📄 Added " . count($staticPages) . " static pages");

        // 2. Blog Posts (High Priority)
        $posts = Post::published()
            ->orderBy('published_at', 'desc')
            ->get();

        foreach ($posts as $post) {
            $urls[] = [
                'loc' => route('post.show', $post->slug),
                'lastmod' => $post->updated_at->toW3cString(),
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ];
        }
        $this->info("  📝 Added {$posts->count()} blog posts");

        // 3. Categories
        $categories = Category::withCount('posts')
            ->having('posts_count', '>', 0)
            ->get();

        foreach ($categories as $category) {
            $urls[] = [
                'loc' => route('category.show', $category->slug),
                'lastmod' => $category->updated_at->toW3cString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }
        $this->info("  📂 Added {$categories->count()} categories");

        // 4. Tags
        $tags = Tag::withCount('posts')
            ->having('posts_count', '>', 0)
            ->get();

        foreach ($tags as $tag) {
            $urls[] = [
                'loc' => route('tag.show', $tag->slug),
                'lastmod' => $tag->updated_at->toW3cString(),
                'changefreq' => 'weekly',
                'priority' => '0.6',
            ];
        }
        $this->info("  🏷️  Added {$tags->count()} tags");

        // 5. Special listing pages
        $listingPages = [
            [
                'loc' => route('posts.most-liked'),
                'lastmod' => now()->toW3cString(),
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
            [
                'loc' => route('posts.most-read'),
                'lastmod' => now()->toW3cString(),
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
        ];

        $urls = array_merge($urls, $listingPages);
        $this->info("  📊 Added " . count($listingPages) . " listing pages");

        // Build XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . PHP_EOL;
        $xml .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $xml .= '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9' . PHP_EOL;
        $xml .= '        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>' . PHP_EOL;

        $this->info("  📍 Total URLs: " . count($urls));

        return $xml;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate and return sitemap.xml dynamically
     */
    public function index(): Response
    {
        $urls = [];

        // 1. Static Pages
        $urls[] = [
            'loc' => url('/'),
            'lastmod' => now()->toW3cString(),
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];

        $urls[] = [
            'loc' => route('about'),
            'lastmod' => now()->toW3cString(),
            'changefreq' => 'monthly',
            'priority' => '0.8',
        ];

        $urls[] = [
            'loc' => route('contact'),
            'lastmod' => now()->toW3cString(),
            'changefreq' => 'monthly',
            'priority' => '0.7',
        ];

        // 2. Blog Posts
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

        // 5. Listing pages
        $urls[] = [
            'loc' => route('posts.most-liked'),
            'lastmod' => now()->toW3cString(),
            'changefreq' => 'daily',
            'priority' => '0.8',
        ];

        $urls[] = [
            'loc' => route('posts.most-read'),
            'lastmod' => now()->toW3cString(),
            'changefreq' => 'daily',
            'priority' => '0.8',
        ];

        // Build XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}

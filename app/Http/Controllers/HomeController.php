<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Testimonial;
use App\Models\Setting;
use App\Models\Article;
use App\Models\Team;
use App\Models\Partner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $packages = Package::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $allTestimonialsCount = Testimonial::query()->where('is_active', true)->count();

        $testimonials = Testimonial::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('id')
            ->take(7)
            ->get();

        $galleries = Gallery::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $faqs = Faq::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $settings = Setting::pluck('value', 'key')->toArray();

        $articles = Article::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        $teams = Team::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $partners = Partner::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        return view('welcome', compact('packages', 'testimonials', 'allTestimonialsCount', 'galleries', 'faqs', 'articles', 'teams', 'partners', 'settings'));
    }

    public function gallery(Request $request)
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        $galleriesQuery = Gallery::query()->where('is_active', true);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $galleriesQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('category_label', 'like', '%' . $search . '%');
            });
        }

        // Get all active galleries to group by album
        $allGalleries = $galleriesQuery->orderBy('order')->orderBy('id')->get();

        $albums = $allGalleries->groupBy(function($item) {
            return trim($item->category_label) ?: 'Umum';
        })->map(function($items, $name) {
            $coverItem = $items->firstWhere('type', 'photo') ?? $items->first();
            
            $mappedItems = $items->map(function($item) {
                return [
                    'type' => $item->type,
                    'src' => $item->image_url,
                    'title' => $item->title,
                    'category' => $item->type === 'video' ? ($item->video_platform === 'youtube' ? 'YouTube' : 'Instagram Reel') : ($item->category_label ?? 'Foto'),
                    'video_id' => $item->video_id,
                    'video_platform' => $item->video_platform,
                ];
            })->values();

            return (object) [
                'name' => $name,
                'cover_url' => $coverItem ? $coverItem->image_url : null,
                'items' => $mappedItems,
                'count' => $items->count(),
                'last_updated' => $items->max('updated_at'),
            ];
        })->sortByDesc('last_updated');

        // Paginate collection manually
        $page = $request->query('page', 1);
        $perPage = 12;
        $paginatedAlbums = new \Illuminate\Pagination\LengthAwarePaginator(
            $albums->forPage($page, $perPage)->values(),
            $albums->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('gallery', compact('paginatedAlbums', 'settings'));
    }

    public function testimonials(Request $request)
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        $testimonials = Testimonial::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('id')
            ->paginate(12);

        return view('testimonials', compact('testimonials', 'settings'));
    }

    public function articles(Request $request)
    {
        $articles = Article::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('id', 'desc')
            ->paginate(6)
            ->withQueryString();

        $settings = Setting::pluck('value', 'key')->toArray();

        return view('articles.index', compact('articles', 'settings'));
    }

    public function showArticle($slug)
    {
        $article = Article::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $settings = Setting::pluck('value', 'key')->toArray();

        // Get 3 other active articles as recommendations
        $recommendedArticles = Article::where('id', '!=', $article->id)
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        return view('articles.show', compact('article', 'recommendedArticles', 'settings'));
    }

    public function tagArticles($tag)
    {
        $cleanTag = '#' . str_replace('#', '', $tag);
        $articles = Article::where('is_active', true)
            ->where('tags', 'like', "%{$cleanTag}%")
            ->orderBy('order')
            ->orderBy('id', 'desc')
            ->paginate(9);

        $settings = Setting::pluck('value', 'key')->toArray();

        return view('articles.tag', compact('articles', 'tag', 'settings'));
    }

    public function showPackage($slug)
    {
        $package = Package::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $settings = Setting::pluck('value', 'key')->toArray();

        // Get other active packages as recommendations
        $otherPackages = Package::where('id', '!=', $package->id)
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('id')
            ->take(3)
            ->get();

        return view('packages.show', compact('package', 'otherPackages', 'settings'));
    }

    public function robots()
    {
        $robotsContent = Setting::getValue('seo_robots_txt');
        
        if (empty(trim($robotsContent))) {
            $siteUrl = url('/');
            $robotsContent = "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /login\nDisallow: /register\n\nSitemap: {$siteUrl}/sitemap.xml";
        }
        
        return response($robotsContent, 200)
            ->header('Content-Type', 'text/plain');
    }

    public function sitemap()
    {
        $enabled = Setting::getValue('seo_sitemap_enabled');
        if ($enabled === '0') {
            abort(404);
        }
        
        $siteUrl = url('/');
        $packages = Package::where('is_active', true)->get();
        $articles = Article::where('is_active', true)->get();
        
        $xml = [];
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // 1. Homepage
        $xml[] = '  <url>';
        $xml[] = "    <loc>{$siteUrl}/</loc>";
        $xml[] = '    <changefreq>daily</changefreq>';
        $xml[] = '    <priority>1.0</priority>';
        $xml[] = '  </url>';
        
        // 2. Gallery
        $xml[] = '  <url>';
        $xml[] = "    <loc>{$siteUrl}/galeri</loc>";
        $xml[] = '    <changefreq>weekly</changefreq>';
        $xml[] = '    <priority>0.7</priority>';
        $xml[] = '  </url>';

        // 2b. Articles Index
        $xml[] = '  <url>';
        $xml[] = "    <loc>{$siteUrl}/artikel</loc>";
        $xml[] = '    <changefreq>weekly</changefreq>';
        $xml[] = '    <priority>0.7</priority>';
        $xml[] = '  </url>';
        
        // 3. Packages
        foreach ($packages as $package) {
            $xml[] = '  <url>';
            $xml[] = "    <loc>" . route('packages.show', $package->slug) . "</loc>";
            $xml[] = '    <lastmod>' . $package->updated_at->toAtomString() . '</lastmod>';
            $xml[] = '    <changefreq>weekly</changefreq>';
            $xml[] = '    <priority>0.8</priority>';
            $xml[] = '  </url>';
        }
        
        // 4. Articles
        foreach ($articles as $article) {
            $xml[] = '  <url>';
            $xml[] = "    <loc>" . route('articles.show', $article->slug) . "</loc>";
            $xml[] = '    <lastmod>' . $article->updated_at->toAtomString() . '</lastmod>';
            $xml[] = '    <changefreq>weekly</changefreq>';
            $xml[] = '    <priority>0.6</priority>';
            $xml[] = '  </url>';
        }

        // 5. Unique Tags
        $tags = [];
        foreach ($articles as $art) {
            if ($art->tags) {
                $artTags = explode(',', $art->tags);
                foreach ($artTags as $t) {
                    $tClean = trim(str_replace('#', '', $t));
                    if (!empty($tClean)) {
                        $tags[$tClean] = true;
                    }
                }
            }
        }
        foreach (array_keys($tags) as $tag) {
            $xml[] = '  <url>';
            $xml[] = "    <loc>" . route('public.articles.tag', $tag) . "</loc>";
            $xml[] = '    <changefreq>weekly</changefreq>';
            $xml[] = '    <priority>0.5</priority>';
            $xml[] = '  </url>';
        }
        
        $xml[] = '</urlset>';
        
        return response(implode("\n", $xml), 200)
            ->header('Content-Type', 'application/xml');
    }

    public function terms()
    {
        $settings = Setting::pluck('value', 'key');

        return view('terms', compact('settings'));
    }

    public function privacy()
    {
        $settings = Setting::pluck('value', 'key');

        return view('privacy', compact('settings'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GlobalSearchController extends Controller
{
    /**
     * Perform global search and return grouped JSON results.
     */
    public function search(Request $request)
    {
        $q = $request->string('q')->trim()->toString();

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        // 1. Search Packages
        $packages = Package::query()
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('airline', 'like', "%{$q}%")
                    ->orWhere('hotel', 'like', "%{$q}%")
                    ->orWhere('hotel_makkah', 'like', "%{$q}%")
                    ->orWhere('hotel_madinah', 'like', "%{$q}%");
            })
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'title' => $item->name,
                'subtitle' => 'Paket Umrah · Rp ' . number_format($item->price, 0, ',', '.') . ' · ' . $item->airline,
                'url' => route('admin.packages.edit', $item),
                'icon' => 'package',
                'category' => 'Paket Umrah'
            ]);

        // 2. Search Articles
        $articles = Article::query()
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('author', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%");
            })
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'title' => $item->title,
                'subtitle' => 'Artikel · Oleh ' . $item->author . ' · ' . ($item->category ?? 'Umum'),
                'url' => route('admin.articles.edit', $item),
                'icon' => 'book-open',
                'category' => 'Artikel'
            ]);

        // 3. Search Users
        $users = User::query()
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            })
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'title' => $item->name,
                'subtitle' => 'Pengguna · ' . $item->email . ' · Role: ' . ucfirst($item->role),
                'url' => route('admin.users.index', ['search' => $item->name]),
                'icon' => 'users',
                'category' => 'Pengguna'
            ]);

        // 4. Search Testimonials
        $testimonials = Testimonial::query()
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%")
                    ->orWhere('message', 'like', "%{$q}%");
            })
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'title' => $item->name,
                'subtitle' => 'Testimoni · ' . ($item->location ?? 'Indonesia') . ' · "' . Str::limit($item->message, 40) . '"',
                'url' => route('admin.testimonials.edit', $item),
                'icon' => 'message-square-quote',
                'category' => 'Testimoni'
            ]);

        // 5. Search FAQs
        $faqs = Faq::query()
            ->where(function ($query) use ($q) {
                $query->where('question', 'like', "%{$q}%")
                    ->orWhere('answer', 'like', "%{$q}%");
            })
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'title' => $item->question,
                'subtitle' => 'FAQ · ' . Str::limit($item->answer, 60),
                'url' => route('admin.faqs.edit', $item),
                'icon' => 'help-circle',
                'category' => 'FAQ'
            ]);

        // Merge all results into a single flat array
        $results = collect()
            ->concat($packages)
            ->concat($articles)
            ->concat($users)
            ->concat($testimonials)
            ->concat($faqs);

        return response()->json($results);
    }
}

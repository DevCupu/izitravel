<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Article;
use App\Models\Team;
use App\Models\Partner;
use App\Models\Setting;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {


        // Content counts
        $totalPackages = Package::count();
        $totalTestimonials = Testimonial::count();
        $totalGalleries = Gallery::count();
        $totalFaqs = Faq::count();
        $totalArticles = Article::count();
        $totalTeams = Team::count();
        $totalPartners = Partner::count();
        $totalContent = $totalPackages + $totalTestimonials + $totalGalleries + $totalFaqs + $totalArticles + $totalTeams + $totalPartners;

        // Active content counts
        $activePackages = Package::where('is_active', true)->count();
        $activeTestimonials = Testimonial::where('is_active', true)->count();
        $activeGalleries = Gallery::where('is_active', true)->count();
        $activeFaqs = Faq::where('is_active', true)->count();
        $activeArticles = Article::where('is_active', true)->count();
        $activeTeams = Team::where('is_active', true)->count();
        $activePartners = Partner::where('is_active', true)->count();



        // Content distribution for doughnut chart
        $contentDistribution = [
            ['label' => 'Paket Umrah', 'count' => $totalPackages, 'color' => '#3b82f6'],
            ['label' => 'Testimoni', 'count' => $totalTestimonials, 'color' => '#f59e0b'],
            ['label' => 'Galeri', 'count' => $totalGalleries, 'color' => '#10b981'],
            ['label' => 'FAQ', 'count' => $totalFaqs, 'color' => '#8b5cf6'],
            ['label' => 'Artikel', 'count' => $totalArticles, 'color' => '#ec4899'],
            ['label' => 'Tim Kami', 'count' => $totalTeams, 'color' => '#6366f1'],
            ['label' => 'Mitra Maskapai', 'count' => $totalPartners, 'color' => '#14b8a6'],
        ];

        // Departure schedule trend (Next 6 months) — one query grouped in PHP instead of 6 separate counts
        $rangeStart = now()->startOfMonth();
        $packagesByMonth = Package::where('is_active', true)
            ->whereBetween('departure_date', [$rangeStart, $rangeStart->copy()->addMonths(6)])
            ->get(['departure_date'])
            ->groupBy(fn ($package) => $package->departure_date->format('Y-m'));

        $departureTrend = collect();
        for ($i = 0; $i < 6; $i++) {
            $monthDate = $rangeStart->copy()->addMonths($i);

            $departureTrend->push([
                'label' => $monthDate->translatedFormat('M Y'),
                'short_label' => $monthDate->translatedFormat('M'),
                'count' => $packagesByMonth->get($monthDate->format('Y-m'), collect())->count(),
            ]);
        }

        // Content status breakdown (Active vs Draft)
        $contentStatusBreakdown = [
            'labels' => ['Paket', 'Testimoni', 'Galeri', 'FAQ', 'Artikel', 'Tim', 'Mitra'],
            'active' => [$activePackages, $activeTestimonials, $activeGalleries, $activeFaqs, $activeArticles, $activeTeams, $activePartners],
            'draft' => [
                max(0, $totalPackages - $activePackages),
                max(0, $totalTestimonials - $activeTestimonials),
                max(0, $totalGalleries - $activeGalleries),
                max(0, $totalFaqs - $activeFaqs),
                max(0, $totalArticles - $activeArticles),
                max(0, $totalTeams - $activeTeams),
                max(0, $totalPartners - $activePartners),
            ],
        ];

        // Upcoming departures
        $upcomingDepartures = Package::where('is_active', true)
            ->where('departure_date', '>=', now())
            ->orderBy('departure_date')
            ->take(4)
            ->get();

        // --- SEO & Site Integrity Health Assessment ---
        $seoChecklist = [];
        
        $basicScore = 0;
        $eeatScore = 0;
        $advancedScore = 0;
        $packagesScore = 0;
        $articlesScore = 0;

        // 1. General Site Settings (25%)
        $siteName = Setting::getValue('site_name');
        $siteDesc = Setting::getValue('site_description');
        $gaId = Setting::getValue('seo_google_analytics_id');

        if ($siteName) {
            $basicScore += 8;
            $seoChecklist[] = ['category' => 'basic', 'label' => 'Nama Website dikonfigurasi', 'status' => 'pass'];
        } else {
            $seoChecklist[] = ['category' => 'basic', 'label' => 'Nama Website belum diisi', 'status' => 'fail', 'fix' => route('admin.settings.index')];
        }

        if ($siteDesc) {
            $basicScore += 8;
            $descLength = strlen($siteDesc);
            if ($descLength >= 120 && $descLength <= 160) {
                $basicScore += 4;
                $seoChecklist[] = ['category' => 'basic', 'label' => 'Deskripsi Meta Website optimal (120-160 karakter)', 'status' => 'pass'];
            } else {
                $seoChecklist[] = ['category' => 'basic', 'label' => 'Deskripsi Meta Website ada, namun panjangnya (' . $descLength . ' karakter) kurang optimal. Idealnya 120-160 karakter.', 'status' => 'warning', 'fix' => route('admin.settings.index')];
            }
        } else {
            $seoChecklist[] = ['category' => 'basic', 'label' => 'Deskripsi Meta Website kosong (Buruk untuk SEO)', 'status' => 'fail', 'fix' => route('admin.settings.index')];
        }

        if ($gaId) {
            $basicScore += 5;
            $seoChecklist[] = ['category' => 'basic', 'label' => 'Google Analytics ID terpasang (' . $gaId . ')', 'status' => 'pass'];
        } else {
            $seoChecklist[] = ['category' => 'basic', 'label' => 'Google Analytics ID belum dipasang', 'status' => 'warning', 'fix' => route('admin.settings.index')];
        }

        // 2. E-E-A-T Credibility (20%)
        $ppiuNumber = Setting::getValue('footer_ppiu_number');
        $gmapsLink = Setting::getValue('contact_gmaps');
        $contactAddress = Setting::getValue('contact_address');

        if ($ppiuNumber) {
            $eeatScore += 10;
            $seoChecklist[] = ['category' => 'eeat', 'label' => 'Nomor Izin PPIU Umrah terdaftar', 'status' => 'pass'];
        } else {
            $seoChecklist[] = ['category' => 'eeat', 'label' => 'Nomor Izin PPIU Umrah belum diisi (Sangat penting untuk kredibilitas E-E-A-T Google)', 'status' => 'fail', 'fix' => route('admin.settings.index')];
        }

        if ($gmapsLink) {
            $eeatScore += 5;
            $seoChecklist[] = ['category' => 'eeat', 'label' => 'Google Maps tersemat', 'status' => 'pass'];
        } else {
            $seoChecklist[] = ['category' => 'eeat', 'label' => 'Google Maps belum disematkan', 'status' => 'warning', 'fix' => route('admin.settings.index')];
        }

        if ($contactAddress) {
            $eeatScore += 5;
            $seoChecklist[] = ['category' => 'eeat', 'label' => 'Alamat Kantor terdaftar', 'status' => 'pass'];
        } else {
            $seoChecklist[] = ['category' => 'eeat', 'label' => 'Alamat Kantor belum diisi', 'status' => 'warning', 'fix' => route('admin.settings.index')];
        }

        // 3. Advanced SEO & Webmaster (15%)
        $gConsole = Setting::getValue('seo_google_console_verification');
        $ogImage = Setting::getValue('seo_og_image');
        $ogDesc = Setting::getValue('seo_og_description');
        $robotsTxt = Setting::getValue('seo_robots_txt');
        $sitemapEnabled = Setting::getValue('seo_sitemap_enabled');

        if ($gConsole) {
            $advancedScore += 4;
            $seoChecklist[] = ['category' => 'advanced', 'label' => 'Google Search Console Verification Tag terpasang', 'status' => 'pass'];
        } else {
            $seoChecklist[] = ['category' => 'advanced', 'label' => 'Google Search Console Verification Tag belum diisi', 'status' => 'warning', 'fix' => route('admin.settings.index')];
        }

        if ($ogImage) {
            $advancedScore += 4;
            $seoChecklist[] = ['category' => 'advanced', 'label' => 'Gambar Preview Share (OG Image) kustom telah diunggah', 'status' => 'pass'];
        } else {
            $seoChecklist[] = ['category' => 'advanced', 'label' => 'Gambar Preview Share (OG Image) kustom belum diunggah', 'status' => 'warning', 'fix' => route('admin.settings.index')];
        }

        if ($ogDesc) {
            $advancedScore += 3;
            $seoChecklist[] = ['category' => 'advanced', 'label' => 'Kustom Deskripsi Share (OG Description) terkonfigurasi', 'status' => 'pass'];
        } else {
            $seoChecklist[] = ['category' => 'advanced', 'label' => 'Kustom Deskripsi Share (OG Description) belum dikonfigurasi', 'status' => 'warning', 'fix' => route('admin.settings.index')];
        }

        $advancedScore += 2; // Default robots.txt is always valid & present
        if (!empty(trim($robotsTxt))) {
            $seoChecklist[] = ['category' => 'advanced', 'label' => 'Berkas Robots.txt telah dikustomisasi', 'status' => 'pass'];
        } else {
            $seoChecklist[] = ['category' => 'advanced', 'label' => 'Berkas Robots.txt menggunakan konfigurasi bawaan (Default)', 'status' => 'pass'];
        }

        if ($sitemapEnabled !== '0') {
            $advancedScore += 2;
            $seoChecklist[] = ['category' => 'advanced', 'label' => 'Sitemap XML otomatis diaktifkan', 'status' => 'pass'];
        } else {
            $seoChecklist[] = ['category' => 'advanced', 'label' => 'Sitemap XML otomatis dinonaktifkan', 'status' => 'warning', 'fix' => route('admin.settings.index')];
        }

        // 4. Travel Packages Integrity (20%)
        $packagesCount = Package::count();
        if ($packagesCount > 0) {
            $activePackagesQuery = Package::where('is_active', true);
            $activePackagesCount = $activePackagesQuery->count();

            if ($activePackagesCount > 0) {
                // Check images
                $packagesWithoutImage = Package::where('is_active', true)->where(function($query) {
                    $query->whereNull('image')->orWhere('image', '');
                })->count();
                $packageImageScore = max(0, 8 - (($packagesWithoutImage / $activePackagesCount) * 8));
                $packagesScore += $packageImageScore;
                if ($packagesWithoutImage > 0) {
                    $seoChecklist[] = ['category' => 'packages', 'label' => $packagesWithoutImage . ' paket aktif tidak memiliki gambar cover', 'status' => 'warning', 'fix' => route('admin.packages.index')];
                } else {
                    $seoChecklist[] = ['category' => 'packages', 'label' => 'Semua paket aktif memiliki gambar cover', 'status' => 'pass'];
                }

                // Check pricing
                $packagesWithoutPrice = Package::where('is_active', true)->where(function($query) {
                    $query->whereNull('price')->orWhere('price', '<=', 0);
                })->count();
                $packagePriceScore = max(0, 4 - (($packagesWithoutPrice / $activePackagesCount) * 4));
                $packagesScore += $packagePriceScore;
                if ($packagesWithoutPrice > 0) {
                    $seoChecklist[] = ['category' => 'packages', 'label' => $packagesWithoutPrice . ' paket aktif tidak memiliki harga valid', 'status' => 'fail', 'fix' => route('admin.packages.index')];
                } else {
                    $seoChecklist[] = ['category' => 'packages', 'label' => 'Semua paket aktif memiliki harga valid', 'status' => 'pass'];
                }

                // Check dates
                $packagesWithoutDate = Package::where('is_active', true)->whereNull('departure_date')->count();
                $packageDateScore = max(0, 8 - (($packagesWithoutDate / $activePackagesCount) * 8));
                $packagesScore += $packageDateScore;
                if ($packagesWithoutDate > 0) {
                    $seoChecklist[] = ['category' => 'packages', 'label' => $packagesWithoutDate . ' paket aktif tidak memiliki tanggal keberangkatan', 'status' => 'warning', 'fix' => route('admin.packages.index')];
                } else {
                    $seoChecklist[] = ['category' => 'packages', 'label' => 'Semua paket aktif memiliki tanggal keberangkatan', 'status' => 'pass'];
                }
            } else {
                $packagesScore += 20; // default pass if no active packages
                $seoChecklist[] = ['category' => 'packages', 'label' => 'Tidak ada paket aktif untuk dievaluasi', 'status' => 'pass'];
            }
        } else {
            $packagesScore += 20;
            $seoChecklist[] = ['category' => 'packages', 'label' => 'Belum ada paket umrah yang dibuat', 'status' => 'warning', 'fix' => route('admin.packages.create')];
        }

        // 5. Articles Quality & SEO (20%)
        $articlesCount = Article::count();
        if ($articlesCount > 0) {
            $activeArticlesQuery = Article::where('is_active', true);
            $activeArticlesCount = $activeArticlesQuery->count();

            if ($activeArticlesCount > 0) {
                // Check images
                $articlesWithoutImage = Article::where('is_active', true)->where(function($query) {
                    $query->whereNull('image')->orWhere('image', '');
                })->count();
                $articleImageScore = max(0, 8 - (($articlesWithoutImage / $activeArticlesCount) * 8));
                $articlesScore += $articleImageScore;
                if ($articlesWithoutImage > 0) {
                    $seoChecklist[] = ['category' => 'articles', 'label' => $articlesWithoutImage . ' artikel aktif tidak memiliki gambar cover', 'status' => 'warning', 'fix' => route('admin.articles.index')];
                } else {
                    $seoChecklist[] = ['category' => 'articles', 'label' => 'Semua artikel aktif memiliki gambar cover', 'status' => 'pass'];
                }

                // Check excerpt
                $articlesWithoutExcerpt = Article::where('is_active', true)->where(function($query) {
                    $query->whereNull('excerpt')->orWhere('excerpt', '');
                })->count();
                $articleExcerptScore = max(0, 12 - (($articlesWithoutExcerpt / $activeArticlesCount) * 12));
                $articlesScore += $articleExcerptScore;
                if ($articlesWithoutExcerpt > 0) {
                    $seoChecklist[] = ['category' => 'articles', 'label' => $articlesWithoutExcerpt . ' artikel aktif tidak memiliki kutipan/meta deskripsi', 'status' => 'fail', 'fix' => route('admin.articles.index')];
                } else {
                    $seoChecklist[] = ['category' => 'articles', 'label' => 'Semua artikel aktif memiliki kutipan/meta deskripsi', 'status' => 'pass'];
                }
            } else {
                $articlesScore += 20;
                $seoChecklist[] = ['category' => 'articles', 'label' => 'Tidak ada artikel aktif untuk dievaluasi', 'status' => 'pass'];
            }
        } else {
            $articlesScore += 20;
            $seoChecklist[] = ['category' => 'articles', 'label' => 'Belum ada artikel yang dibuat', 'status' => 'warning', 'fix' => route('admin.articles.create')];
        }

        $seoScore = round(min(100, max(0, $basicScore + $eeatScore + $advancedScore + $packagesScore + $articlesScore)));
        
        $seoBreakdown = [
            'basic' => ['label' => 'Informasi Dasar', 'score' => round($basicScore), 'max' => 25, 'color' => 'bg-blue-500'],
            'eeat' => ['label' => 'Kredibilitas E-E-A-T', 'score' => round($eeatScore), 'max' => 20, 'color' => 'bg-emerald-500'],
            'advanced' => ['label' => 'SEO & Webmaster', 'score' => round($advancedScore), 'max' => 15, 'color' => 'bg-indigo-500'],
            'packages' => ['label' => 'Integritas Paket', 'score' => round($packagesScore), 'max' => 20, 'color' => 'bg-amber-500'],
            'articles' => ['label' => 'Kualitas Artikel', 'score' => round($articlesScore), 'max' => 20, 'color' => 'bg-pink-500'],
        ];

        return view('admin.dashboard', compact(

            'totalPackages',
            'totalTestimonials',
            'totalGalleries',
            'totalFaqs',
            'totalArticles',
            'totalTeams',
            'totalPartners',
            'totalContent',
            'activePackages',
            'activeTestimonials',
            'activeGalleries',
            'activeFaqs',
            'activeArticles',
            'activeTeams',
            'activePartners',

            'contentDistribution',
            'departureTrend',
            'contentStatusBreakdown',
            'upcomingDepartures',
            'seoScore',
            'seoChecklist',
            'seoBreakdown',
        ));
    }
}

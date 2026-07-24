<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/artikel/{slug}', [HomeController::class, 'showArticle'])->name('articles.show');
Route::get('/paket/{slug}', [HomeController::class, 'showPackage'])->name('packages.show');
Route::get('/galeri', [HomeController::class, 'gallery'])->name('public.gallery');
Route::get('/syarat-ketentuan', [HomeController::class, 'terms'])->name('public.terms');
Route::get('/kebijakan-privasi', [HomeController::class, 'privacy'])->name('public.privacy');
Route::get('/robots.txt', [HomeController::class, 'robots'])->name('public.robots');
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('public.sitemap');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/global-search', [\App\Http\Controllers\Admin\GlobalSearchController::class, 'search'])->name('global-search');

    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    Route::resource('packages', AdminPackageController::class)->except('show');
    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::resource('testimonials', AdminTestimonialController::class)->except('show');
    Route::post('galleries/rename-album', [AdminGalleryController::class, 'renameAlbum'])->name('galleries.rename-album');
    Route::post('galleries/delete-album', [AdminGalleryController::class, 'deleteAlbum'])->name('galleries.delete-album');
    Route::resource('galleries', AdminGalleryController::class);
    Route::resource('faqs', AdminFaqController::class)->except('show');
    Route::resource('articles', \App\Http\Controllers\Admin\ArticleController::class)->except('show');
    Route::resource('teams', \App\Http\Controllers\Admin\TeamController::class)->except('show');
    Route::resource('partners', \App\Http\Controllers\Admin\PartnerController::class)->except('show');
});

require __DIR__.'/auth.php';

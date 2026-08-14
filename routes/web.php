<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\GlobalSearchController;
use App\Http\Controllers\Admin\JemaahController as AdminJemaahController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\PackageJemaahController as AdminPackageJemaahController;
use App\Http\Controllers\Admin\PackageJemaahImportController as AdminPackageJemaahImportController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\RegistrationChecklistController as AdminRegistrationChecklistController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JemaahTrackingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/artikel', [HomeController::class, 'articles'])->name('public.articles.index');
Route::get('/artikel/{slug}', [HomeController::class, 'showArticle'])->name('articles.show');
Route::get('/artikel/tag/{tag}', [HomeController::class, 'tagArticles'])->name('public.articles.tag');
Route::get('/paket/{slug}', [HomeController::class, 'showPackage'])->name('packages.show');
Route::get('/galeri', [HomeController::class, 'gallery'])->name('public.gallery');
Route::get('/testimoni', [HomeController::class, 'testimonials'])->name('public.testimonials');
Route::get('/lacak-jemaah', [JemaahTrackingController::class, 'index'])
    ->middleware('throttle:20,1')
    ->name('public.jemaah.tracking');
Route::get('/lacak-jemaah/hasil', [JemaahTrackingController::class, 'result'])
    ->middleware('throttle:20,1')
    ->name('public.jemaah.tracking.result');
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
    Route::get('/global-search', [GlobalSearchController::class, 'search'])->name('global-search');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::resource('packages', AdminPackageController::class)->except('show');

    Route::get('jemaah/search', [AdminJemaahController::class, 'search'])->name('jemaah.search');
    Route::get('jemaah/{jemaah}', [AdminJemaahController::class, 'show'])->name('jemaah.show');
    Route::patch('jemaah/{jemaah}', [AdminJemaahController::class, 'update'])->name('jemaah.update');

    Route::get('packages/{package}/jemaah', [AdminPackageJemaahController::class, 'index'])->name('packages.jemaah.index');
    Route::post('packages/{package}/jemaah', [AdminPackageJemaahController::class, 'store'])->name('packages.jemaah.store');
    Route::post('packages/{package}/jemaah/bulk-pic', [AdminPackageJemaahController::class, 'bulkUpdatePic'])->name('packages.jemaah.bulk-pic');
    Route::post('packages/{package}/jemaah/bulk-status', [AdminPackageJemaahController::class, 'bulkUpdateStatus'])->name('packages.jemaah.bulk-status');
    Route::delete('packages/{package}/jemaah/bulk-destroy', [AdminPackageJemaahController::class, 'bulkDestroy'])->name('packages.jemaah.bulk-destroy');
    Route::delete('packages/{package}/jemaah/{registration}', [AdminPackageJemaahController::class, 'destroy'])->name('packages.jemaah.destroy');
    Route::patch('packages/{package}/jemaah/{registration}/pic', [AdminPackageJemaahController::class, 'updatePic'])->name('packages.jemaah.pic.update');
    Route::get('packages/{package}/jemaah/export', [AdminPackageJemaahController::class, 'export'])->name('packages.jemaah.export');
    Route::get('packages/{package}/jemaah/import/template', [AdminPackageJemaahImportController::class, 'template'])->name('packages.jemaah.import.template');
    Route::post('packages/{package}/jemaah/import', [AdminPackageJemaahImportController::class, 'preview'])->name('packages.jemaah.import.preview');
    Route::post('packages/{package}/jemaah/import/confirm', [AdminPackageJemaahImportController::class, 'confirm'])->name('packages.jemaah.import.confirm');

    Route::patch('registrations/{registration}/items/{type}', [AdminRegistrationChecklistController::class, 'update'])->name('registrations.items.update');
    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::resource('testimonials', AdminTestimonialController::class)->except('show');
    Route::post('galleries/rename-album', [AdminGalleryController::class, 'renameAlbum'])->name('galleries.rename-album');
    Route::post('galleries/delete-album', [AdminGalleryController::class, 'deleteAlbum'])->name('galleries.delete-album');
    Route::resource('galleries', AdminGalleryController::class);
    Route::resource('faqs', AdminFaqController::class)->except('show');
    Route::resource('articles', ArticleController::class)->except('show');
    Route::resource('teams', TeamController::class)->except('show');
    Route::resource('partners', PartnerController::class)->except('show');
});

require __DIR__.'/auth.php';

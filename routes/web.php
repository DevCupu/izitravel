<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\AiSettingController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\Admin\CampaignAdController as AdminCampaignAdController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ChatLogController as AdminChatLogController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\GlobalSearchController;
use App\Http\Controllers\Admin\JemaahController as AdminJemaahController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\PackageJemaahController as AdminPackageJemaahController;
use App\Http\Controllers\Admin\PackageJemaahImportController as AdminPackageJemaahImportController;
use App\Http\Controllers\Admin\DocumentVisaController as AdminDocumentVisaController;
use App\Http\Controllers\Admin\OperationsController as AdminOperationsController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\RegistrationChecklistController as AdminRegistrationChecklistController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\WhatsAppCsController as AdminWhatsAppCsController;
use App\Http\Controllers\ChatRouterController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\CsPasswordController;
use App\Http\Controllers\Admin\CsAccountController;
use App\Http\Controllers\Admin\LeadStageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageRecommendationController;
use App\Http\Controllers\JemaahTrackingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/artikel', [HomeController::class, 'articles'])->name('public.articles.index');
Route::get('/artikel/{slug}', [HomeController::class, 'showArticle'])->name('articles.show');
Route::get('/artikel/tag/{tag}', [HomeController::class, 'tagArticles'])->name('public.articles.tag');
Route::get('/paket/{slug}', [HomeController::class, 'showPackage'])->name('packages.show');
Route::get('/rekomendasi-paket', [PackageRecommendationController::class, 'index'])->name('public.packages.recommend');
Route::post('/rekomendasi-paket', [PackageRecommendationController::class, 'recommend'])
    ->middleware('throttle:10,1')
    ->name('public.packages.recommend.submit');
Route::post('/rekomendasi-paket/cari', [PackageRecommendationController::class, 'searchPrompt'])
    ->middleware('throttle:10,1')
    ->name('public.packages.recommend.prompt');
Route::get('/galeri', [HomeController::class, 'gallery'])->name('public.gallery');
Route::get('/testimoni', [HomeController::class, 'testimonials'])->name('public.testimonials');
Route::get('/lacak-jemaah', [JemaahTrackingController::class, 'index'])
    ->middleware('throttle:20,1')
    ->name('public.jemaah.tracking');
Route::get('/lacak-jemaah/hasil', [JemaahTrackingController::class, 'result'])
    ->middleware('throttle:20,1')
    ->name('public.jemaah.tracking.result');
Route::post('/lacak-jemaah/hasil', [JemaahTrackingController::class, 'result'])
    ->middleware('throttle:20,1')
    ->name('public.jemaah.tracking.search');
Route::get('/syarat-ketentuan', [HomeController::class, 'terms'])->name('public.terms');
Route::get('/kebijakan-privasi', [HomeController::class, 'privacy'])->name('public.privacy');
Route::get('/robots.txt', [HomeController::class, 'robots'])->name('public.robots');
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('public.sitemap');
Route::get('/chat', [ChatRouterController::class, 'redirect'])
    ->middleware('throttle:20,1')
    ->name('public.chat');

// Fire-and-forget click beacon: the /chat page POSTs here (keepalive beacon,
// 204 response, never waited on) the moment the visitor taps "Lanjut ke
// WhatsApp" or the auto-redirect fires — so admin can tell "who really opened
// WhatsApp" from "who just saw the landing page" (see ChatRouterService::markClicked).
Route::post('/chat/click', [ChatRouterController::class, 'trackClick'])
    ->middleware('throttle:90,5')
    ->name('public.chat.click');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin', 'record.activity'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/global-search', [GlobalSearchController::class, 'search'])->name('global-search');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/ai-settings', [AiSettingController::class, 'index'])->name('ai-settings.index');
    Route::post('/ai-settings', [AiSettingController::class, 'update'])->name('ai-settings.update');
    Route::post('/ai-settings/verify', [AiSettingController::class, 'verify'])->name('ai-settings.verify');

    // Pengaturan Sistem (System Layer)
    Route::get('/system', [SystemSettingController::class, 'index'])->name('system.index');
    Route::post('/system', [SystemSettingController::class, 'update'])->name('system.update');
    Route::post('/system/clear-cache', [SystemSettingController::class, 'clearCache'])->name('system.clear-cache');
    Route::get('/system/logs', [SystemSettingController::class, 'getLogs'])->name('system.logs');
    Route::post('/system/clear-logs', [SystemSettingController::class, 'clearLogs'])->name('system.clear-logs');
    Route::get('/system/backup-db', [SystemSettingController::class, 'backupDatabase'])->name('system.backup-db');
    Route::get('/system/preview-maintenance', [SystemSettingController::class, 'previewMaintenance'])->name('system.preview-maintenance');
    Route::post('/system/optimize-db', [SystemSettingController::class, 'optimizeDatabase'])->name('system.optimize-db');

    Route::resource('packages', AdminPackageController::class)->except('show');

    Route::get('jemaah/search', [AdminJemaahController::class, 'search'])->name('jemaah.search');
    Route::get('jemaah/{jemaah}', [AdminJemaahController::class, 'show'])->name('jemaah.show');
    Route::patch('jemaah/{jemaah}', [AdminJemaahController::class, 'update'])->name('jemaah.update');

    Route::get('documents', [AdminDocumentVisaController::class, 'index'])->name('documents.index');
    Route::post('documents', [AdminDocumentVisaController::class, 'store'])->name('documents.store');
    Route::patch('documents/registrations/{registration}/checklist/{type}', [AdminDocumentVisaController::class, 'updateChecklist'])->name('documents.checklist.update');
    Route::patch('documents/checklist/bulk', [AdminDocumentVisaController::class, 'bulkUpdateChecklist'])->name('documents.checklist.bulk-update');
    Route::patch('documents/registrations/{registration}/visa-status', [AdminDocumentVisaController::class, 'updateVisaStatus'])->name('documents.visa-status.update');
    Route::patch('documents/jemaah/{jemaah}/passport-expiry', [AdminDocumentVisaController::class, 'updatePassportExpiry'])->name('documents.passport-expiry.update');

    Route::get('operations', [AdminOperationsController::class, 'index'])->name('operations.index');
    Route::get('operations/manifest', [AdminOperationsController::class, 'manifest'])->name('operations.manifest');
    Route::patch('operations/registrations/{registration}/group', [AdminOperationsController::class, 'updateGroup'])->name('operations.group.update');
    Route::patch('operations/registrations/bulk-group', [AdminOperationsController::class, 'bulkUpdateGroup'])->name('operations.group.bulk-update');
    Route::post('operations/packages/{package}/rooms', [AdminOperationsController::class, 'storeRoom'])->name('operations.rooms.store');
    Route::patch('operations/registrations/{registration}/room', [AdminOperationsController::class, 'assignRoom'])->name('operations.room.assign');
    Route::patch('operations/registrations/bulk-room', [AdminOperationsController::class, 'bulkAssignRoom'])->name('operations.room.bulk-assign');

    Route::get('packages/{package}/jemaah', function (\App\Models\Package $package) {
        request()->merge(['package_id' => $package->id]);
        return app(AdminDocumentVisaController::class)->index(request());
    })->name('packages.jemaah.index');
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
    Route::post('articles/upload-image', [ArticleController::class, 'uploadImage'])->name('articles.upload-image');
    Route::resource('articles', ArticleController::class)->except('show');
    Route::resource('teams', TeamController::class)->except('show');
    Route::resource('partners', PartnerController::class)->except('show');
    Route::resource('promos', PromoController::class)->except('show');

    Route::resource('whatsapp-cs', AdminWhatsAppCsController::class)
        ->parameters(['whatsapp-cs' => 'cs'])
        ->except('show');
    Route::resource('campaigns', AdminCampaignController::class)->except('show');
    Route::resource('campaigns.ads', AdminCampaignAdController::class)
        ->except(['index', 'show'])
        ->scoped();
    Route::get('chat-logs', [AdminChatLogController::class, 'index'])->name('chat-logs.index');

    Route::post('whatsapp-cs/{cs}/account', [CsAccountController::class, 'store'])->name('whatsapp-cs.account.store');
    Route::post('whatsapp-cs/{cs}/account/reset-password', [CsAccountController::class, 'resetPassword'])->name('whatsapp-cs.account.reset');
    Route::patch('whatsapp-cs/{cs}/account/email', [CsAccountController::class, 'updateEmail'])->name('whatsapp-cs.account.email');
    Route::patch('whatsapp-cs/{cs}/account/status', [CsAccountController::class, 'toggle'])->name('whatsapp-cs.account.status');

    Route::prefix('crm')->name('crm.')->group(function () {
        Route::get('/', [CrmController::class, 'dashboard'])->name('dashboard');
        Route::get('leads', [CrmController::class, 'index'])->name('leads.index');
        Route::get('leads/create', [CrmController::class, 'create'])->name('leads.create');
        Route::post('leads', [CrmController::class, 'store'])->name('leads.store');
        Route::get('leads/archived', [CrmController::class, 'archivedLeads'])->name('leads.archived');
        Route::post('leads/bulk-archive', [CrmController::class, 'bulkArchiveLeads'])->name('leads.bulk-archive');
        Route::post('leads/bulk-restore', [CrmController::class, 'bulkRestoreLeads'])->name('leads.bulk-restore');
        Route::patch('leads/{lead}/restore', [CrmController::class, 'restoreLead'])->name('leads.restore');
        Route::delete('leads/{lead}', [CrmController::class, 'archiveLead'])->name('leads.archive');
        Route::get('leads/{lead}', [CrmController::class, 'show'])->name('leads.show');
        Route::patch('leads/{lead}', [CrmController::class, 'update'])->name('leads.update');
        Route::patch('leads/{lead}/pipeline-details', [CrmController::class, 'pipelineDetails'])->name('leads.pipeline-details');
        Route::patch('leads/{lead}/stage', [CrmController::class, 'stage'])->name('leads.stage');
        Route::post('leads/{lead}/notes', [CrmController::class, 'note'])->name('leads.notes');
        Route::post('leads/{lead}/followups', [CrmController::class, 'followup'])->name('leads.followups');
        Route::patch('followups/{followup}', [CrmController::class, 'updateFollowup'])->name('followups.update');
        Route::delete('followups/{followup}', [CrmController::class, 'archiveFollowup'])->name('followups.archive');
        Route::patch('followups/{followup}/complete', [CrmController::class, 'completeFollowup'])->name('followups.complete');
        Route::get('tasks', [CrmController::class, 'tasks'])->name('tasks');
        Route::post('tasks/bulk-complete', [CrmController::class, 'bulkCompleteFollowups'])->name('tasks.bulk-complete');
        Route::post('tasks/bulk-archive', [CrmController::class, 'bulkArchiveFollowups'])->name('tasks.bulk-archive');
        Route::get('tasks/archived', [CrmController::class, 'archivedFollowups'])->name('tasks.archived');
        Route::post('tasks/bulk-restore', [CrmController::class, 'bulkRestoreFollowups'])->name('tasks.bulk-restore');
        Route::patch('followups/{followup}/restore', [CrmController::class, 'restoreFollowup'])->name('followups.restore');
        Route::get('pipeline', [CrmController::class, 'pipeline'])->name('pipeline');
        Route::post('pipeline/bulk', [CrmController::class, 'bulkPipeline'])->name('pipeline.bulk');
        Route::get('pipeline/stages', [LeadStageController::class, 'index'])->name('stages.index');
        Route::post('pipeline/stages', [LeadStageController::class, 'store'])->name('stages.store');
        Route::patch('pipeline/stages/{stage}', [LeadStageController::class, 'update'])->name('stages.update');
        Route::patch('pipeline/stages/{stage}/move', [LeadStageController::class, 'move'])->name('stages.move');
        Route::delete('pipeline/stages/{stage}', [LeadStageController::class, 'destroy'])->name('stages.destroy');
        Route::get('report', [CrmController::class, 'report'])->name('report');
    });
});

Route::middleware(['auth', 'cs', 'record.activity'])->prefix('cs')->name('cs.')->group(function () {
    Route::get('change-password', [CsPasswordController::class, 'edit'])->name('password.edit');
    Route::put('change-password', [CsPasswordController::class, 'update'])->name('password.update');
    Route::get('/', [CrmController::class, 'dashboard'])->name('dashboard');
    Route::view('guide', 'cs.guide')->name('guide');
    Route::get('leads', [CrmController::class, 'index'])->name('leads.index');
    Route::get('leads/create', [CrmController::class, 'create'])->name('leads.create');
    Route::post('leads', [CrmController::class, 'store'])->name('leads.store');
    Route::get('leads/{lead}', [CrmController::class, 'show'])->name('leads.show');
    Route::patch('leads/{lead}', [CrmController::class, 'update'])->name('leads.update');
    Route::patch('leads/{lead}/pipeline-details', [CrmController::class, 'pipelineDetails'])->name('leads.pipeline-details');
    Route::patch('leads/{lead}/stage', [CrmController::class, 'stage'])->name('leads.stage');
    Route::post('leads/{lead}/notes', [CrmController::class, 'note'])->name('leads.notes');
    Route::post('leads/{lead}/followups', [CrmController::class, 'followup'])->name('leads.followups');
    Route::patch('followups/{followup}', [CrmController::class, 'updateFollowup'])->name('followups.update');
    Route::delete('followups/{followup}', [CrmController::class, 'archiveFollowup'])->name('followups.archive');
    Route::patch('followups/{followup}/complete', [CrmController::class, 'completeFollowup'])->name('followups.complete');
    Route::get('tasks', [CrmController::class, 'tasks'])->name('tasks');
    Route::post('tasks/bulk-complete', [CrmController::class, 'bulkCompleteFollowups'])->name('tasks.bulk-complete');
    Route::post('tasks/bulk-archive', [CrmController::class, 'bulkArchiveFollowups'])->name('tasks.bulk-archive');
    Route::get('pipeline', [CrmController::class, 'pipeline'])->name('pipeline');
    Route::post('pipeline/bulk', [CrmController::class, 'bulkPipeline'])->name('pipeline.bulk');
});

require __DIR__.'/auth.php';

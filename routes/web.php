<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MakerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPortfolioController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Studio\CodeController;
use App\Http\Controllers\Studio\ContentController;
use App\Http\Controllers\Studio\DashboardController;
use App\Http\Controllers\Studio\PortfolioController;
use App\Http\Controllers\Studio\PreviewController;
use App\Http\Controllers\Studio\SettingsController;
use App\Http\Controllers\Studio\UploadController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/explore', ExploreController::class)->name('explore');
Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
Route::get('/templates/{template}/preview', [TemplateController::class, 'preview'])->name('templates.preview');
Route::get('/docs/code', [PageController::class, 'codeGuide'])->name('docs.code');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [PageController::class, 'robots'])->name('robots');

Route::get('/p/{slug}', [PublicPortfolioController::class, 'show'])->name('portfolios.show');
Route::get('/u/{username}', [MakerController::class, 'show'])->name('makers.show');
Route::get('/report/{slug}', [ReportController::class, 'create'])->name('reports.create');
Route::post('/report/{slug}', [ReportController::class, 'store'])->name('reports.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/signup', [AuthController::class, 'showRegister'])->name('signup');
    Route::post('/signup', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::prefix('dashboard/pages')->name('studio.portfolios.')->group(function () {
        Route::get('/create', [PortfolioController::class, 'create'])->name('create');
        Route::post('/', [PortfolioController::class, 'store'])->name('store');

        Route::get('/{portfolio}/content', [ContentController::class, 'edit'])->name('content.edit');
        Route::put('/{portfolio}/content', [ContentController::class, 'update'])->name('content.update');

        Route::get('/{portfolio}/code', [CodeController::class, 'edit'])->name('code.edit');
        Route::put('/{portfolio}/code', [CodeController::class, 'update'])->name('code.update');
        Route::get('/{portfolio}/export', [CodeController::class, 'export'])->name('export');

        Route::get('/{portfolio}/preview', PreviewController::class)->name('preview');
        Route::get('/{portfolio}/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/{portfolio}/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/{portfolio}/publish', [SettingsController::class, 'publish'])->name('publish');
        Route::delete('/{portfolio}', [SettingsController::class, 'destroy'])->name('destroy');
    });

    Route::post('/dashboard/uploads', UploadController::class)->name('studio.uploads.store');

    Route::get('/settings', [ProfileController::class, 'edit'])->name('settings.edit');
    Route::put('/settings/profile', [ProfileController::class, 'update'])->name('settings.profile');
    Route::put('/settings/password', [ProfileController::class, 'updatePassword'])->name('settings.password');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::post('/pages/{portfolio}/feature', [AdminController::class, 'toggleFeature'])->name('pages.feature');
        Route::post('/pages/{portfolio}/unpublish', [AdminController::class, 'unpublish'])->name('pages.unpublish');
        Route::post('/reports/{report}/resolve', [AdminController::class, 'resolve'])->name('reports.resolve');
    });
});

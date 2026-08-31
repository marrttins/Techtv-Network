<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BlogController;

Route::get('/', [BlogController::class, 'index']);
Route::get('/about', [BlogController::class, 'about']);
Route::get('/advertise', [BlogController::class, 'advertise']);
Route::get('/contact', [BlogController::class, 'contact']);
Route::get('/privacy-policy', [BlogController::class, 'privacyPolicy']);
Route::get('/terms-of-service', [BlogController::class, 'termsOfService']);
Route::get('/terms', [BlogController::class, 'termsOfService']);
Route::get('/cookie-policy', [BlogController::class, 'cookiePolicy']);
Route::get('/editorial-policy', [BlogController::class, 'editorialPolicy']);
Route::get('/post/{slug}', [BlogController::class, 'show']);
Route::get('/category/{slug}', [BlogController::class, 'category']);
Route::get('/tag/{slug}', [BlogController::class, 'tag']);
Route::get('/search', [BlogController::class, 'search']);
Route::post('/post/{slug}/comment', [BlogController::class, 'storeComment']);
Route::post('/newsletter/subscribe', [BlogController::class, 'subscribeNewsletter']);
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index']);
Route::get('/feed', [BlogController::class, 'feed']);
Route::get('/rss.xml', [BlogController::class, 'feed']);
Route::get('/ads.txt', [BlogController::class, 'adsTxt']);

// Admin Authentication Routes
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\AdminVideoController;
use App\Http\Controllers\AdminMediaController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminAdController;
use App\Http\Controllers\AdminPopupController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::get('/login', [AdminController::class, 'loginForm'])->name('login');
Route::post('/login', [AdminController::class, 'login']);
Route::match(['get', 'post'], '/logout', [AdminController::class, 'logout'])->name('logout');
Route::match(['get', 'post'], '/admin/logout', [AdminController::class, 'logout']);

// Password Reset via Email OTP Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.email');
Route::get('/verify-otp', [ForgotPasswordController::class, 'showVerifyForm'])->name('password.otp');
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.otp.verify');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// Protected Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
    
    // Admin Post & Video CRUD
    Route::middleware(['permission:manage_posts'])->group(function () {
        Route::post('/admin/posts/bulk-action', [AdminPostController::class, 'bulkAction']);
        Route::resource('/admin/posts', AdminPostController::class)->except(['show']);
        Route::post('/admin/videos/live-stream', [AdminVideoController::class, 'updateLiveStream'])->name('admin.videos.live');
        Route::resource('/admin/videos', AdminVideoController::class)->except(['show'])->names('admin.videos');
    });
    
    // Category CRUD
    Route::middleware(['permission:manage_categories_menus'])->group(function () {
        Route::get('/admin/categories', [AdminController::class, 'categories']);
        Route::post('/admin/categories', [AdminController::class, 'storeCategory']);
        Route::delete('/admin/categories/{id}', [AdminController::class, 'deleteCategory']);
        Route::post('/admin/categories/{id}/delete', [AdminController::class, 'deleteCategory']);
        
        // Menu Management
        Route::post('/admin/menus', [AdminController::class, 'storeMenu']);
        Route::delete('/admin/menus/{id}', [AdminController::class, 'deleteMenu']);
        Route::post('/admin/menus/{id}/delete', [AdminController::class, 'deleteMenu']);
        Route::post('/admin/menus/{id}/items', [AdminController::class, 'storeMenuItem']);
        Route::post('/admin/menu-items/reorder', [AdminController::class, 'updateMenuItemOrder']);
        Route::delete('/admin/menu-items/{id}', [AdminController::class, 'deleteMenuItem']);
        Route::post('/admin/menu-items/{id}/delete', [AdminController::class, 'deleteMenuItem']);
    });
    
    // Comment Moderation
    Route::middleware(['permission:manage_comments'])->group(function () {
        Route::get('/admin/comments', [AdminController::class, 'comments']);
        Route::post('/admin/comments/{id}/approve', [AdminController::class, 'approveComment']);
        Route::post('/admin/comments/{id}/deny', [AdminController::class, 'denyComment']);
        Route::delete('/admin/comments/{id}', [AdminController::class, 'deleteComment']);
    });
    
    // Newsletters List
    Route::middleware(['permission:manage_newsletters'])->group(function () {
        Route::get('/admin/newsletters', [AdminController::class, 'newsletters']);
    });

    Route::get('/admin/media/api', [AdminMediaController::class, 'api'])->name('admin.media.api');
    
    // Media Library
    Route::middleware(['permission:manage_media'])->group(function () {
        Route::get('/admin/media', [AdminMediaController::class, 'index'])->name('admin.media.index');
        Route::post('/admin/media/upload', [AdminMediaController::class, 'upload'])->name('admin.media.upload');
        Route::delete('/admin/media/delete', [AdminMediaController::class, 'destroy'])->name('admin.media.delete');
    });

    // Users Management
    Route::middleware(['permission:manage_users'])->group(function () {
        Route::resource('/admin/users', AdminUserController::class);
    });

    // Ads & Popups Management
    Route::middleware(['permission:manage_ads_popups'])->group(function () {
        Route::resource('/admin/ads', AdminAdController::class)->except(['show']);
        Route::resource('/admin/popups', AdminPopupController::class)->except(['show']);
    });

    // Admin Profile & Password Change
    Route::get('/admin/profile', [AdminUserController::class, 'profile'])->name('admin.profile');
    Route::put('/admin/profile', [AdminUserController::class, 'updateProfile'])->name('admin.profile.update');
    Route::put('/admin/profile/password', [AdminUserController::class, 'updatePassword'])->name('admin.profile.password');

    // Settings Management
    Route::middleware(['permission:manage_settings'])->group(function () {
        Route::get('/admin/settings', [AdminSettingsController::class, 'edit']);
        Route::post('/admin/settings', [AdminSettingsController::class, 'update']);
    });
});


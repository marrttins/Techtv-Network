<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Post;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        // Share site settings globally to all views
        if (!app()->runningInConsole()) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                    $siteSettings = \Illuminate\Support\Facades\DB::table('settings')->pluck('value', 'key')->all();
                    \Illuminate\Support\Facades\View::share('siteSettings', $siteSettings);
                }
            } catch (\Exception $e) {
                // Safe fallback in case migrations haven't run yet
            }
        }

        // Share recent posts to all views for the footer
        View::composer('layouts.layout', function ($view) {
            $footerRecentPosts = Post::where('status', 'publish')
                ->orderBy('published_at', 'desc')
                ->take(2)
                ->get();
            
            $activePopup = \App\Models\Popup::where('is_active', true)->first();

            $view->with([
                'footerRecentPosts' => $footerRecentPosts,
                'activePopup' => $activePopup
            ]);
        });
    }
}

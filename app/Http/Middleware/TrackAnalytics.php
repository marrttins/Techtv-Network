<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackAnalytics
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Process only for successful, frontend GET page requests
        if ($request->isMethod('GET') 
            && $response->getStatusCode() === 200 
            && !$request->ajax()
            && !str_starts_with($request->path(), 'admin') 
            && !str_starts_with($request->path(), 'login')) {
            
            $today = now()->toDateString();
            
            // Check if the request is a post detail page view
            $isPostView = str_starts_with($request->path(), 'post/') || request()->is('post/*');
            
            // Calculate mock active impressions count for location rendering
            $impressions = 0;
            if ($request->path() === '/' || $request->path() === '') {
                // Homepage has under_slider, above_latest, under_popular, under_must_read ad containers
                $impressions = 4;
            } else {
                // Other sections (post detail, categories) generally display at least 1 ad (sidebar or post body)
                $impressions = 1;
            }

            try {
                DB::table('analytics')->updateOrInsert(
                    ['date' => $today],
                    [
                        'views' => DB::raw('views + ' . ($isPostView ? 1 : 0)),
                        'impressions' => DB::raw('impressions + ' . $impressions),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            } catch (\Exception $e) {
                // Silently ignore log insertion failures to avoid breaking page render
            }
        }

        return $response;
    }
}

<?php

namespace App\Providers;

use App\Models\Post;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        // Share sidebar data with the sidebar partial
        View::composer('partials.sidebar', function ($view) {
            $view->with([
                'mostLikedPosts' => Post::published()
                    ->orderByDesc('likes_count')
                    ->take(5)
                    ->get(),
                'mostReadPosts' => Post::published()
                    ->orderByDesc('views')
                    ->take(5)
                    ->get(),
            ]);
        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
         if (env('VERCEL_ENV') === 'production') {
            $vercelUrl = env('APP_URL');
            if (!$vercelUrl) {
                // Fallback menggunakan VERCEL_URL bawaan
                $vercelUrl = 'https://' . env('VERCEL_URL');
            }
            URL::forceRootUrl($vercelUrl);
            $this->app['url']->forceScheme('https');
        }
        
        // Memastikan APP_URL dipakai untuk aset (perbaikan terakhir)
        if (env('APP_URL')) {
             $this->app['url']->assetRoot(env('APP_URL'));
        }
    }
}

<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

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
        $this->setupLogViewer();
        JsonResource::withoutWrapping();

        if ($this->app->environment() === 'production') {
            URL::forceScheme('https');
        }
    }

    private function setupLogViewer(): void
    {
        LogViewer::auth(fn(Request $request) => config('app.env') !== 'production');
    }
}

<?php

namespace Omnia\Oalivechat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Omnia\Oalivechat\Middleware\AdminMiddleware;

class LiveChatServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // $this->app['router']->aliasMiddleware('adminLiveChat', AdminMiddleware::class);

        $this->app->make('Omnia\Oalivechat\controllers\LiveChatController');
        $this->app->make('Omnia\Oalivechat\controllers\AdminLiveChatController');

        $this->loadViewsFrom(__DIR__.'/views','liveChat');
        // $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            $view->with('authenticatedUser', Auth::user());
        });

        include __DIR__.'/routes.php';
    }
}

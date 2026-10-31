<?php

namespace App\Providers;

use App\Notifications\Channels\Discord\DiscordWebhook;
use App\Services\ModuleService;
use App\Support\ThemeViewFinder;
use App\Support\Utils;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Paginator::useBootstrap();
        View::share('moduleSvc', app(ModuleService::class));

        activity()->disableLogging();

        // TODO: See if Filament Shield changes the way they do this by default since Filament 4 because of the new structure. If not, leave it as is
        /*FilamentShield::configurePermissionIdentifierUsing(
            fn ($resource) => str($resource)
                ->afterLast('\\')
                ->beforeLast('Resource')
                ->lower()
                ->toString()
        );*/

        Notification::extend('discord_webhook', function ($app) {
            return app(DiscordWebhook::class);
        });


    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Configure Guzzle HTTP client with SSL verification disabled for development
        $this->app->bind(GuzzleClient::class, function ($app) {
            $config = [
                'timeout' => 30,
                'connect_timeout' => 10,
                'headers' => [
                    'User-Agent' => 'phpVMS/' . config('app.version', '7.x'),
                ],
            ];
            
            // Disable SSL verification in development/local environments
            if (config('app.env') !== 'production') {
                $config['verify'] = false;
            }
            
            return new GuzzleClient($config);
        });

        // Bind our HttpClient with proper SSL configuration
        $this->app->bind(\App\Support\HttpClient::class, function ($app) {
            return new \App\Support\HttpClient($app->make(GuzzleClient::class));
        });

        $this->app->singleton('view.finder', function ($app) {
            return new ThemeViewFinder(
                $app['files'],
                $app['config']['view.paths'],
                null
            );
        });

        // Only load the IDE helper if it's included and enabled
        if (config('app.debug') === true) {
            /* @noinspection NestedPositiveIfStatementsInspection */
            /* @noinspection PhpFullyQualifiedNameUsageInspection */
            if (class_exists(IdeHelperServiceProvider::class)) {
                /* @noinspection PhpFullyQualifiedNameUsageInspection */
                $this->app->register(IdeHelperServiceProvider::class);
            }

            if (config('app.debug_toolbar') === true) {
                Utils::enableDebugToolbar();
            } else {
                Utils::disableDebugToolbar();
            }
        }
    }
}

<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Backups;
use App\Filament\Plugins\LanguageSwitcherPlugin;
use App\Filament\Plugins\ModuleLinksPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\SetActiveLanguage::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationItems([
                \Filament\Navigation\NavigationItem::make('Go back to Air Jamaica Virtual')
                    ->url(url('/'))
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->sort(-1),
                \Filament\Navigation\NavigationItem::make(__('common.view_logs'))
                    ->url(url('/admin/log-viewer'), shouldOpenInNewTab: true)
                    ->icon('heroicon-o-document-text')
                    ->group(\App\Models\Enums\NavigationGroup::Developers->getLabel())
                    ->sort(3),
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup(\App\Models\Enums\NavigationGroup::Shield->getLabel()),
                FilamentSpatieLaravelBackupPlugin::make()
                    ->usingPage(Backups::class),
                ModuleLinksPlugin::make(),
                LanguageSwitcherPlugin::make(),
            ])
            ->bootUsing(function () {
                activity()->enableLogging();
            })
            ->brandName('Air Jamaica Virtual')
            ->brandLogo(asset('assets/img/air-jamaica-logo.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('assets/img/air-jamaica-logo.png'))
            ->unsavedChangesAlerts()
            ->spa(false)
            ->errorNotifications()
            ->viteTheme('resources/css/filament/admin/theme.css');
    }
}

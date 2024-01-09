<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\View\View;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->login()
            ->registration()
            ->profile()
            ->path('/')
            ->userMenuItems([
                MenuItem::make()
                ->label('Admin Panel')
                ->icon('heroicon-o-cog-6-tooth')
                ->url('/admin')
                ->visible(fn(): bool => Auth::user()->is_admin)
            ])
            ->brandName('Something')
            ->brandLogo(fn(): View => \view('filament.logo'))
//                ->brandLogo(asset('images/brand.png'))
            ->brandLogoHeight(fn() => \auth()->check() ? '1.6rem' : '4rem')
//            ->darkModeBrandLogo('')
            ->favicon(asset('images/favicon.png'))
            ->colors([
                'primary' => Color::hex('#9A3B3B'),
//                'secondary' => Color::hex('#B6BBC4'),
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'info' => Color:: Blue,
                'gray' => Color::Slate,
                'danger' => Color::Red,
            ])
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
//                Widgets\FilamentInfoWidget::class,
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

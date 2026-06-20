<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            static $settings;

            $settings ??= Schema::hasTable('site_settings') ? SiteSetting::allAsArray() : [];

            $websiteTitle = filled($settings['website_title'] ?? null)
                ? $settings['website_title']
                : ($settings['store_name'] ?? null);
            $websiteLogo = $settings['website_logo'] ?? null;
            $websiteFavicon = $settings['website_favicon'] ?? null;

            $view->with('siteSettings', $settings);
            $view->with('websiteTitle', filled($websiteTitle) ? $websiteTitle : 'ShopSphere');
            $view->with('websiteLogo', $websiteLogo);
            $view->with('websiteLogoUrl', $this->settingAssetUrl($websiteLogo));
            $view->with('websiteFavicon', $websiteFavicon);
            $view->with('websiteFaviconUrl', $this->settingAssetUrl($websiteFavicon));
        });
    }

    private function settingAssetUrl(?string $path): ?string
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return route('settings.assets.show', ['path' => $path]);
    }
}

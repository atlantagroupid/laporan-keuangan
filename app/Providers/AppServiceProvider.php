<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Pagination\Paginator;
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
        // Set Carbon locale to Indonesia
        \Carbon\Carbon::setLocale('id');

        // Use Bootstrap 5 for Pagination
        Paginator::useBootstrapFive();

        // Share setting globally for all views that use main layout
        // Share setting globally for all views that use main layout or guest layout
        View::composer(['layouts.main', 'profile.edit', 'layouts.guest'], function ($view) {
            $setting = null;

            if (auth()->check()) {
                $user = auth()->user();
                // Get or create user setting
                $setting = $user->setting ?? $user->setting()->create(['app_title' => 'Laporan Keuangan']);
            } else {
                // Guest mode: Use Super Admin settings or default
                $superAdmin = User::where('role', 'super_admin')->first();
                $setting = $superAdmin ? $superAdmin->setting : new Setting(['app_title' => 'Laporan Keuangan']);
            }

            // Always try to sync logo from Super Admin if available
            $superAdmin = User::where('role', 'super_admin')->first();
            if ($superAdmin && $superAdmin->setting && $superAdmin->setting->app_logo) {
                if ($setting) {
                    $setting->app_logo = $superAdmin->setting->app_logo;
                } else {
                    $setting = new Setting(['app_title' => 'Laporan Keuangan']);
                    $setting->app_logo = $superAdmin->setting->app_logo;
                }
            }

            // Ensure setting is never null for views
            if (!$setting) {
                $setting = new Setting(['app_title' => 'Laporan Keuangan']);
            }

            $view->with('setting', $setting);
        });
    }
}

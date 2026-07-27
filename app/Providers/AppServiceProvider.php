<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->ensureStorageFrameworkDirectories();
    }

    /**
     * On-device, the NativePHP bundle extractor drops empty directories, so a
     * fresh install can boot WITHOUT storage/framework/* — and Blade then
     * fails with "Please provide a valid cache path" before any screen
     * renders. Recreate the directories (and re-point the compiled-view path,
     * which resolved to '' at config load when the directory was missing)
     * before any provider boots.
     */
    protected function ensureStorageFrameworkDirectories(): void
    {
        foreach (['framework/cache/data', 'framework/sessions', 'framework/testing', 'framework/views'] as $directory) {
            $path = storage_path($directory);

            if (! is_dir($path)) {
                @mkdir($path, 0755, true);
            }
        }

        if (blank(config('view.compiled'))) {
            config(['view.compiled' => storage_path('framework/views')]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}

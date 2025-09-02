<?php

namespace Kamoca\JwtDatabaseBlacklist\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Kamoca\JwtDatabaseBlacklist\Providers\Storage\Illuminate;
use Tymon\JWTAuth\Contracts\Providers\Storage;

class BlacklistServiceProvider extends ServiceProvider
{
    /**
     * Bootstrapping services (publishing migration)
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../database/migrations/create_jwt_blacklist_table.php.stub' => $this->getMigrationFileName('create_jwt_blacklist_table.php')
        ], 'jwt-blacklist-migrations');
    }

    /**
     * Register services (binding the storage)
     */
    public function register(): void
    {
        $this->app->bind(Storage::class, Illuminate::class);
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     */
    protected function getMigrationFileName(string $migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make([$this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR])
            ->flatMap(fn ($path) => $filesystem->glob($path.'*_'.$migrationFileName))
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}

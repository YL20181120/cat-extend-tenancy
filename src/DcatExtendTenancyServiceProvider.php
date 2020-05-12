<?php

namespace Yl20181120\DcatExtendTenancy;

use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Exceptions\TenantDoesNotExistException;
use Stancl\Tenancy\StorageDrivers\RedisStorageDriver;
use Stancl\Tenancy\Tenant;
use Stancl\Tenancy\TenantManager;

class DcatExtendTenancyServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $extension = DcatExtendTenancy::make();

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, DcatExtendTenancy::NAME);
        }

        if ($lang = $extension->lang()) {
            $this->loadTranslationsFrom($lang, DcatExtendTenancy::NAME);
        }

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom($extension->migrations());
            $this->publishes([__DIR__ . '/../resources/lang' => resource_path('lang')], DcatExtendTenancy::NAME . '-lang');
            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], DcatExtendTenancy::NAME . '-migrations');
        }
        $this->app->booted(function () use ($extension) {
            $extension->routes(__DIR__ . '/../routes/web.php');
        });
        config(['tenancy.storage_drivers.db.custom_columns' =>
            [
                'name',
                'expired_at',
                'created_at',
                'updated_at'
            ]
        ]);
        $this->bootEvents();
    }

    public function bootEvents()
    {
        // 这里主要是将数据库缓存到 redis, 这样 saas 站就可以配置 redis driver
        tenancy()->hook('tenant.updated', function (TenantManager $tenantManager, Tenant $tenant) {
            /** @var RedisStorageDriver $redisDriver */
            $redisDriver = resolve(RedisStorageDriver::class);
            try {
                $redisDriver->findById($tenant->id);
                $redisDriver->updateTenant($tenant);
            } catch (TenantDoesNotExistException $exception) {
                $redisDriver->createTenant($tenant);
            }
        });
        tenancy()->hook('tenant.created', function (TenantManager $tenantManager, Tenant $tenant) {
            /** @var RedisStorageDriver $redisDriver */
            $redisDriver = resolve(RedisStorageDriver::class);
            try {
                $redisDriver->findById($tenant->id);
                $redisDriver->updateTenant($tenant);
            } catch (TenantDoesNotExistException $exception) {
                $redisDriver->createTenant($tenant);
            }
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}

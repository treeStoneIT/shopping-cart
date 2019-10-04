<?php

namespace Treestoneit\ShoppingCart\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Treestoneit\ShoppingCart\CartManager;
use Treestoneit\ShoppingCart\CartServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            CartServiceProvider::class
        ];
    }

    protected function migrate()
    {
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__.'/migrations');

        $this->artisan('migrate');
    }

    protected function cart(): CartManager
    {
        return $this->app['cart'];
    }
}

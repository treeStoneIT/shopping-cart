<?php

namespace Treestoneit\ShoppingCart;

use Treestoneit\ShoppingCart\Models\Cart;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('shopping-cart.php'),
            ], 'config');
        }

    }

    /**
     * Register the application services.
     * TODO dedicated cart factory class
     * TODO replace Laravel framework facades with contracts
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'shopping-cart');

        $this->app->singleton('cart', function ($app) {
            if ($app['session']->has('cart')) {
                return CartManager::fromSessionIdentifier($app['session']->get('cart'));
            }

            if ($app['auth']->check()) {
                return CartManager::fromUserId($app['auth']->user());
            }

            return new CartManager(new Cart());
        });

        $this->app->alias('cart', CartManager::class);
        $this->app->alias('cart', CartContract::class);
    }
}

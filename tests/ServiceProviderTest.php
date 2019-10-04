<?php

namespace Treestoneit\ShoppingCart\Tests;

use Treestoneit\ShoppingCart\CartContract;
use Treestoneit\ShoppingCart\CartManager;

class ServiceProviderTest extends TestCase
{
    public function testBindsCartToContainer()
    {
        $this->assertInstanceOf(CartManager::class, $this->app['cart']);
    }

    public function testAliasesCartContract()
    {
        $this->assertInstanceOf(CartManager::class, $this->app[CartContract::class]);
    }

    public function testAliasesCartManager()
    {
        $this->assertInstanceOf(CartManager::class, $this->app[CartManager::class]);
    }
}

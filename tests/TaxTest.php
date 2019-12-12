<?php

namespace Treestoneit\ShoppingCart\Tests;

use Treestoneit\ShoppingCart\Tests\Fixtures\TaxableProduct;

class TaxTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->migrate();

        $this->cart()->add(TaxableProduct::create([
            'name' => 'Tennis Shoes',
            'price' => 5.99,
        ]), 2);
    }

    public function testCalculatesTaxInFlatMode()
    {
        $this->app['config']->set([
            'shopping-cart.tax.mode' => 'flat',
            'shopping-cart.tax.rate' => 12,
        ]);

        $this->assertEquals(1.44, $this->cart()->tax());
    }

    public function testCalculatesTaxInPerItemMode()
    {
        $this->app['config']->set(['shopping-cart.tax.mode' => 'per-item']);

        $this->assertEquals(1.20, $this->cart()->tax());
    }

    public function testCalculatesTaxWithArbitraryRateInput()
    {
        $this->assertEquals(0.96, $this->cart()->tax(8));
    }
}

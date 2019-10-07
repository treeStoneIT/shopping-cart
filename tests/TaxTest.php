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
        ]));
    }

    public function testCalculatesTaxInFlatMode()
    {
        $this->app['config']->set([
            'shopping-cart.tax.mode' => 'flat',
            'shopping-cart.tax.rate' => 12,
        ]);

        $this->assertEquals(0.72, $this->cart()->tax());
    }

    public function testCalculatesTaxInPerItemMode()
    {
        $this->app['config']->set(['shopping-cart.tax.mode' => 'per-item']);

        $this->assertEquals(0.60, $this->cart()->tax());
    }

    public function testCalculatesTaxWithArbitraryRateInput()
    {
        $this->assertEquals(0.48, $this->cart()->tax(8));
    }
}

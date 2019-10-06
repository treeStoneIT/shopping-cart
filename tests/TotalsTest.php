<?php

namespace Treestoneit\ShoppingCart\Tests;

use Treestoneit\ShoppingCart\Tests\Fixtures\Product;

class TotalsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->migrate();

        $this->cart()->add(Product::create([
            'name' => 'Tennis Shoes',
            'price' => 5.99,
        ]));

        $this->cart()->add(Product::create([
            'name' => 'Pizza Slice',
            'price' => 3.99,
        ]));
    }

    public function testRetrievesContent()
    {
        $this->assertCount(2, $this->cart()->content());
    }

    public function testGivesSubtotal()
    {
        $this->assertEquals(9.98, $this->cart()->subtotal());
    }
}

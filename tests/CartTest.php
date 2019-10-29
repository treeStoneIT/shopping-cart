<?php

namespace Treestoneit\ShoppingCart\Tests;

use Treestoneit\ShoppingCart\Tests\Fixtures\TaxableProduct as Product;

class CartTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->migrate();

        $this->cart()->add(Product::create([
            'name' => 'Tennis Shoes',
            'price' => 5.99,
        ]));
    }

    public function testStoresCartIdentifierInSession()
    {
        $this->assertEquals(1, $this->app['session']->get('cart'));
    }

    public function testAddsItemsToContent()
    {
        $this->cart()->add(Product::create(['name' => 'Pizza Bagel', 'price' => 10.99]));

        $this->assertCount(2, $this->cart());
    }

    public function testUpdatesItem()
    {
        $this->cart()->update(1, 2);

        $this->assertEquals(2, $this->cart()->content()[0]->quantity);
    }

    public function testRemovesItemFromContent()
    {
        $this->cart()->remove(1);

        $this->assertCount(0, $this->cart()->content());
    }

    public function testDestroysEntireCartInstance()
    {
        $this->cart()->destroy();

        $this->assertFalse($this->cart()->getModel()->exists);
    }

    public function testDestroyingCartRemovesCachedTotals()
    {
        $this->assertGreaterThan(0, $this->cart()->subtotal());
        $this->assertGreaterThan(0, $this->cart()->tax());

        $this->cart()->destroy();

        $this->assertEquals(0, $this->cart()->subtotal());
        $this->assertEquals(0, $this->cart()->tax());
    }
}

<?php

namespace Treestoneit\ShoppingCart\Tests;

use Treestoneit\ShoppingCart\Tests\Fixtures\Product;

class CartItemOptionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->migrate();

        $product = Product::create([
            'name' => 'Tennis Shoes',
            'price' => 20.99,
            'options' => [
                'color' => ['aquamarine', 'black'],
            ],
        ]);

        $this->cart()->add($product, 1, ['color' => 'aquamarine']);
    }

    public function testAddsItemWithOptions()
    {
        $this->assertEquals('aquamarine', $this->cart()->items()->first()->options['color'], 'Stored options did not match provided options.');
    }

    public function testChangesItemOptions()
    {
        $this->cart()->updateOptions(1, ['color' => 'black']);

        $this->assertEquals('black', $this->cart()->items()->first()->options['color'], 'Stored options did not match updated options.');
    }

    public function testIgnoresInvalidOption()
    {
        $this->cart()->updateOptions(1, ['color' => 'ugly-brown']);

        $this->assertEquals('aquamarine', $this->cart()->items()->first()->options['color'], 'Invalid option was not ignored.');
    }
}

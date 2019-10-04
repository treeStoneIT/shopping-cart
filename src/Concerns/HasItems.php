<?php

namespace Treestoneit\ShoppingCart\Concerns;

use Treestoneit\ShoppingCart\Models\CartItemCollection;

trait HasItems
{
    /**
     * @var \Treestoneit\ShoppingCart\Models\Cart
     */
    protected $cart;

    /**
     * Get the cart contents.
     *
     * @return \Treestoneit\ShoppingCart\Models\CartItemCollection|\Treestoneit\ShoppingCart\Models\CartItem[]
     */
    public function items(): CartItemCollection
    {
        return $this->cart->items;
    }
}

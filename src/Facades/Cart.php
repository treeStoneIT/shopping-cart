<?php

namespace Treestoneit\ShoppingCart\Facades;

use Illuminate\Support\Facades\Facade as Base;

/**
 * @see \Treestoneit\ShoppingCart\CartManager
 */
class Cart extends Base
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cart';
    }
}

<?php

namespace Treestoneit\ShoppingCart\Tests\Fixtures;

use Treestoneit\ShoppingCart\Taxable;

class TaxableProduct extends Product implements Taxable
{
    protected $table = 'products';

    /**
     * Get the tax rate for this item.
     *
     * @return int|float
     */
    public function getTaxRate()
    {
        return 10;
    }
}

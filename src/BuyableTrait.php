<?php

namespace Treestoneit\ShoppingCart;

use Exception;

trait BuyableTrait
{
    /**
     * Get the identifier of the Buyable item.
     *
     * @return int|string
     */
    public function getBuyableIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the description or title of the Buyable item.
     *
     * @return string
     */
    public function getBuyableDescription()
    {
        throw new Exception('Buyable description has not been set.');
    }

    /**
     * Get the price of the Buyable item.
     *
     * @return float|null
     */
    public function getBuyablePrice()
    {
        throw new Exception('Buyable price has not been set.');
    }

    /**
     * Any extra fees not based on product quantity.
     *
     * @return float|int
     */
    public function getExtraFees()
    {
        return 0;
    }

    /**
     * An array of options (color, size, etc.) for this buyable item.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return [];
    }
}

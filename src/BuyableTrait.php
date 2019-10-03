<?php

namespace Treestoneit\ShoppingCart;

use Exception;

trait BuyableTrait
{
    /**
     * Get the identifier of the Buyable item.
     *
     * @param  null  $options
     * @return int|string
     */
    public function getBuyableIdentifier($options = null)
    {
        return $this->getKey();
    }

    /**
     * Get the description or title of the Buyable item.
     *
     * @param  null  $options
     * @return string
     */
    public function getBuyableDescription($options = null)
    {
        throw new Exception('Buyable description has not been set.');
    }

    /**
     * Get the price of the Buyable item.
     *
     * @param  null  $options
     * @return float|null
     */
    public function getBuyablePrice($options = null)
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
}

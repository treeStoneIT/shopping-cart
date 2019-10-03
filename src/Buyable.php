<?php

namespace Treestoneit\ShoppingCart;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface Buyable
{
    /**
     * Get the identifier of the Buyable item.
     *
     * @param  null  $options
     * @return int|string
     */
    public function getBuyableIdentifier($options = null);

    /**
     * Get the description or title of the Buyable item.
     *
     * @param  null  $options
     * @return string
     */
    public function getBuyableDescription($options = null);

    /**
     * Get the price of the Buyable item.
     *
     * @param  null  $options
     * @return float|null
     */
    public function getBuyablePrice($options = null);

    /**
     * Any extra fees not based on product quantity.
     *
     * @return float|int
     */
    public function getExtraFees();
}

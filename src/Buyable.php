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
     * @return int|string
     */
    public function getBuyableIdentifier();

    /**
     * Get the description or title of the Buyable item.
     *
     * @return string
     */
    public function getBuyableDescription();

    /**
     * Get the price of the Buyable item.
     *
     * @return float|null
     */
    public function getBuyablePrice();

    /**
     * Any extra fees not based on product quantity.
     *
     * @return float|int
     */
    public function getExtraFees();

    /**
     * An array of options (color, size, etc.) for this buyable item.
     *
     * @return array
     */
    public function getOptions(): array;
}

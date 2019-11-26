<?php

namespace Treestoneit\ShoppingCart\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Treestoneit\ShoppingCart\Buyable;

class Product extends Model implements Buyable
{
    protected $guarded = [];

    protected $casts = ['options' => 'array'];

    /**
     * Get the identifier of the Buyable item.
     *
     * @return int|string
     */
    public function getBuyableIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the description or title of the Buyable item.
     *
     * @return string
     */
    public function getBuyableDescription()
    {
        return $this->name;
    }

    /**
     * Get the price of the Buyable item.
     *
     * @return float|null
     */
    public function getBuyablePrice()
    {
        return $this->price;
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
        return $this->options ?? [];
    }
}

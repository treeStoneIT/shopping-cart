<?php

namespace Treestoneit\ShoppingCart\Concerns;

use Treestoneit\ShoppingCart\Models\CartItem;
use Treestoneit\ShoppingCart\Models\CartItemCollection;
use Treestoneit\ShoppingCart\Taxable;
use Closure;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

trait CalculatesTotals
{
    /**
     * @var float
     */
    protected $subtotal = 0.0;

    /**
     * @var float
     */
    protected $tax = 0.0;

    /**
     * Get the cart contents.
     *
     * @return \Treestoneit\ShoppingCart\Models\CartItemCollection
     */
    public function content(): CartItemCollection
    {
        return $this->items();
    }

    /**
     * Get the number of items in the cart.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->items()->count();
    }

    /**
     * Get the subtotal of items in the cart.
     *
     * @return int|float
     */
    public function subtotal(): float
    {
        if (! $this->subtotal) {
            $this->subtotal = $this->items()->sumRounded(function (CartItem $item) {
                return $item->subtotal;
            });
        }

        return $this->subtotal;
    }

    /**
     * Get the tax for items in the cart.
     *
     * @param  int|float|null  $rate
     * @return float
     */
    public function tax($rate = null): float
    {
        if (! $this->tax) {
            $this->tax = $this->items()->sumRounded($this->getTaxAmountForItem($rate));
        }

        return $this->tax;
    }

    /**
     * Figure out how to calculate tax for the cart items.
     *
     * @param  int|float|null  $rate
     * @return \Closure
     */
    protected function getTaxAmountForItem($rate = null): Closure
    {
        if (! $rate && Config::get('shopping-cart.tax.mode') == 'flat') {
            $rate = Config::get('shopping-cart.tax.rate');
        }

        return function (CartItem $item) use ($rate) {
            if (! $rate) {
                if (! $item->buyable instanceof Taxable) {
                    throw new InvalidArgumentException('No flat rate was supplied and buyable is not instance of '.Taxable::class);
                }

                $rate = $item->buyable->getTaxRate();
            }

            return $item->price * $rate;
        };
    }
}

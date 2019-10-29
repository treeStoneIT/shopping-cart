<?php

namespace Treestoneit\ShoppingCart\Concerns;

use Treestoneit\ShoppingCart\Buyable;
use Treestoneit\ShoppingCart\Models\Cart;
use Treestoneit\ShoppingCart\Models\CartItem;
use Illuminate\Support\Facades\Session;

trait ManagesCartItems
{
    use HasItems;

    /**
     * Get the cart model instance.
     *
     * @return \Treestoneit\ShoppingCart\Models\Cart
     */
    public function getModel(): Cart
    {
        return $this->cart;
    }

    /**
     * Add an item to the cart.
     *
     * @param  \Treestoneit\ShoppingCart\Buyable  $buyable
     * @param  int  $quantity
     * @return \Treestoneit\ShoppingCart\CartManager
     */
    public function add(Buyable $buyable, int $quantity = 1): self
    {
        $item = $this->items()->first(function (CartItem $cartItem) use ($buyable) {
            return $cartItem->buyable_id === $buyable->getBuyableIdentifier();
        });

        // If the item already exists in the cart, we'll
        // just update the quantity by the given value.
        if ($item) {
            $item->increment('quantity', $quantity);

            return $this;
        }

        if (! $this->cart->exists) {
            $this->cart->save();
        }

        $this->cart->items()->save(
            $newItem = CartItem::make(['quantity' => $quantity])->buyable()->associate($buyable)
        );

        // By default Eloquent doesn't add the new item into the items
        // array on the cart, so we have to do that ourselves.
        $this->cart->items->add($newItem);

        $this->cart->push();

        $this->refreshCart();

        return $this;
    }

    /**
     * Change the quantity of an item in the cart.
     *
     * @param  int  $item
     * @param  int  $quantity
     * @return \Treestoneit\ShoppingCart\CartManager
     * @throws \Exception
     */
    public function update(int $item, int $quantity):self
    {
        if ($quantity <= 0) {
            return $this->remove($item);
        }

        if (! $this->items()->contains($item)) {
            return $this;
        }

        $this->items()->find($item)->update(['quantity' => $quantity]);

        return $this;
    }

    /**
     * Remove an item from the cart.
     *
     * @param  int  $item
     * @return static
     * @throws \Exception
     */
    public function remove(int $item): self
    {
        $key = $this->items()->search(function (CartItem $i) use ($item) {
            return $i->getKey() == $item;
        });

        $this->items()->pull($key)->delete();

        if ($this->items()->isEmpty()) {
            return $this->destroy();
        }

        return $this;
    }

    /**
     * Destroy the cart instance.
     *
     * @return static
     */
    public function destroy()
    {
        $this->cart->delete();

        $this->refreshCart(new Cart());

        return $this;
    }

    /**
     * Toggle the session key, and recalculate totals.
     *
     * @param  \Treestoneit\ShoppingCart\Models\Cart|null  $cart
     * @return static
     */
    public function refreshCart(Cart $cart = null): self
    {
        if ($cart) {
            $this->cart = $cart;
        }

        $cart = $cart ?? $this->cart;

        if ($cart->exists) {
            $cart->loadMissing('items.buyable');

            Session::put('cart', $cart->getKey());
        } else {
            Session::forget('cart');
        }

        $this->clearCached();

        return $this;
    }

    /**
     * Persist the cart contents to the database.
     *
     * @return static
     */
    protected function persist(): self
    {
        Session::put('cart', $this->cart->getKey());

        return $this;
    }
}

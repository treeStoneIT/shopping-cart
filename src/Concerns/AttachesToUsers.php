<?php

namespace Treestoneit\ShoppingCart\Concerns;

use Treestoneit\ShoppingCart\Models\Cart as CartModel;
use Illuminate\Contracts\Auth\Authenticatable;

trait AttachesToUsers
{
    /**
     * Load the given user's shopping cart.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return static
     */
    public function loadUserCart(Authenticatable $user): self
    {
        // If the user doesn't yet have a saved cart, we'll
        // just attach the current one to the user and exit.
        if (! $cart = CartModel::whereUserId($user->getAuthIdentifier())->with('items')->first()) {
            return $this->attachTo($user);
        }

        // If the current cart is empty, we'll load the saved one.
        if ($this->items()->isEmpty()) {
            return $this->refreshCart($cart);
        }

        // Otherwise, we'll overwrite the saved cart with the current one.
        // TODO add a strategy to be able to merge with the saved cart
        return $this->overwrite($user);
    }

    /**
     * Attach the current cart to the given user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return static
     */
    public function attachTo(Authenticatable $user): self
    {
        $this->cart->fill([
            'user_id' => $user->getAuthIdentifier(),
        ]);

        if ($this->cart->exists) {
            $this->cart->save();
        }

        return $this;
    }

    /**
     * Delete any old carts belonging to the given user and attach
     * the current cart to them.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return static
     */
    protected function overwrite(Authenticatable $user): self
    {
        CartModel::whereUserId($user->getAuthIdentifier())
                 ->whereKeyNot($this->cart->getKey())
                 // Delete them using Eloquent so that events will be fired
                 // and trigger deletion of cart items as well.
                 ->get()->each->delete();

        return $this->attachTo($user);
    }
}

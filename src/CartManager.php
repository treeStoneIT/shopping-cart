<?php

namespace Treestoneit\ShoppingCart;

// TODO When session is destroyed, delete cart not attached to user

use Countable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Support\Traits\Macroable;
use Treestoneit\ShoppingCart\Models\Cart;

class CartManager implements Countable, CartContract
{
    use Concerns\ManagesCartItems;
    use Concerns\CalculatesTotals;
    use Concerns\AttachesToUsers;
    use ForwardsCalls;
    use Macroable {
        __call as macroCall;
    }

    /**
     * CartManager constructor.
     *
     * @param  \Treestoneit\ShoppingCart\Models\Cart|\Illuminate\Database\Eloquent\Model  $cart
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;

        $this->refreshCart();
    }

    /**
     * Instantiate the cart manager with a cart id saved in the current session.
     *
     * @param  string  $identifier
     * @return static
     */
    public static function fromSessionIdentifier($identifier): self
    {
        $cart = Cart::findOrNew($identifier);

        return new static($cart);
    }

    /**
     * Instantiate the cart manager with the cart attached to the currently authenticated user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return static
     */
    public static function fromUserId(Authenticatable $user): self
    {
        return new static(Cart::where('user_id', $user->getAuthIdentifier())->firstOrNew([
            'user_id' => $user->getAuthIdentifier(),
        ]));
    }

    /**
     * Pass dynamic method calls to the items collection.
     *
     * @param  string  $method
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $arguments);
        }

        return $this->forwardCallTo($this->items(), $method, $arguments);
    }
}

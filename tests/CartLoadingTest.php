<?php

namespace Treestoneit\ShoppingCart\Tests;

use PHPUnit\Framework\TestCase;
use Treestoneit\ShoppingCart\Buyable;
use Treestoneit\ShoppingCart\BuyableTrait;
use Treestoneit\ShoppingCart\Facades\Cart;
use Treestoneit\ShoppingCart\Models\Cart as CartModel;
use Treestoneit\ShoppingCart\Models\CartItem;

class User extends Auth
{
    protected $guarded = [];
}

class Product extends Model implements Buyable
{
    use BuyableTrait;
}

class CartLoadingTest extends TestCase
{
    /**
     * @var \App\User
     */
    private $user;

    /**
     * @var \Treestoneit\ShoppingCart\Models\Cart
     */
    private $cart;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['id' => 1]);

        Cart::refreshCart($this->cart = new CartModel(['id' => 1]));
    }

    public function testAttachesCurrentCartToUserIfUserDoesntHaveSavedCart()
    {
        Cart::loadUserCart($this->user);

        self::assertEquals($this->user['id'], $this->cart['user_id'], 'Didn\'t attach cart to user.');
    }

    public function testLoadsSavedCartIfCurrentCartIsEmpty()
    {
        $savedCart = CartModel::create(['user_id' => $this->user['id']]);

        $savedCart->items()->save(
            CartItem::make(['quantity' => 1])->buyable()->associate(factory(Product::class)->create())
        );

        Cart::loadUserCart($this->user);

        self::assertFalse($this->cart->is($savedCart));
        self::assertTrue(Cart::getModel()->is($savedCart), 'Didn\'t load saved cart.');
    }

    public function testOverwritesSavedCartIfCurrentCartIsNotEmpty()
    {
        $savedCart = CartModel::create(['user_id' => $this->user['id']]);

        $savedCart->items()->save(
            CartItem::make(['quantity' => 1])->buyable()->associate(factory(Product::class)->create())
        );

        Cart::add(factory(Product::class)->create());

        Cart::loadUserCart($this->user);

        self::assertFalse($this->cart->is($savedCart));
        self::assertTrue(Cart::getModel()->is($this->cart), 'Didn\'t overwrite saved cart.');
    }
}

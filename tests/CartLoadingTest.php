<?php

namespace Treestoneit\ShoppingCart\Tests;

use Illuminate\Foundation\Auth\User as Auth;
use Treestoneit\ShoppingCart\Models\Cart as CartModel;
use Treestoneit\ShoppingCart\Models\CartItem;
use Treestoneit\ShoppingCart\Tests\Fixtures\Product;

class User extends Auth
{
    protected $attributes = [
        'name' => 'Avraham',
        'email' => 'avraham@bomshteyn.com',
        'password' => '$2y$10$TN2/TBbL54M9EqXyNe.LduTYLn7hK2RdpAgOVnCdLTkfXG5Wir2Da',
    ];
}

class CartLoadingTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var \Treestoneit\ShoppingCart\Models\Cart
     */
    private $cart;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->migrate();

        $this->user = User::create();

        $this->cart()->refreshCart($this->cart = CartModel::create());
    }

    public function testAttachesCurrentCartToUserIfUserDoesntHaveSavedCart()
    {
        $this->cart()->loadUserCart($this->user);

        self::assertEquals($this->user['id'], $this->cart['user_id'], 'Didn\'t attach cart to user.');
    }

    public function testLoadsSavedCartIfCurrentCartIsEmpty()
    {
        $savedCart = CartModel::create(['user_id' => $this->user['id']]);

        $savedCart->items()->save(
            CartItem::make(['quantity' => 1])->buyable()->associate(Product::create([
                'name' => 'Heinz Ketchup',
                'price' => 1.99
            ]))
        );

        $this->cart()->loadUserCart($this->user);

        self::assertFalse($this->cart->is($savedCart));
        self::assertTrue($this->cart()->getModel()->is($savedCart), 'Didn\'t load saved cart.');
    }

    public function testOverwritesSavedCartIfCurrentCartIsNotEmpty()
    {
        $savedCart = CartModel::create(['user_id' => $this->user['id']]);

        $savedCart->items()->save(
            CartItem::make(['quantity' => 1])->buyable()->associate(Product::create([
                'name' => 'Heinz Ketchup',
                'price' => 1.99
            ]))
        );

        $this->cart()->add(Product::create([
            'name' => 'Rice Noodles',
            'price' => 1000
        ]));

        $this->cart()->loadUserCart($this->user);

        self::assertFalse($this->cart->is($savedCart));
        self::assertTrue($this->cart()->getModel()->is($this->cart), 'Didn\'t overwrite saved cart.');
    }
}

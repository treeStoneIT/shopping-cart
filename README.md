# A Database Driven Shopping Cart for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/treestoneit/shopping-cart.svg?style=flat-square)](https://packagist.org/packages/treestoneit/shopping-cart)
[![Total Downloads](https://img.shields.io/packagist/dt/treestoneit/shopping-cart.svg?style=flat-square)](https://packagist.org/packages/treestoneit/shopping-cart)

This is a simple shopping cart implementation for Laravel 6+. It automatically serializes your cart to the database and loads the related product models.

## Usage

To get started, add the `Buyable` interface to your model.

```php
use Illuminate\Database\Eloquent\Model;
use Treestoneit\ShoppingCart\Buyable;
use Treestoneit\ShoppingCart\BuyableTrait;

class Product extends Model implements Buyable
{
    use BuyableTrait;
}
```

Make sure you implement the `getBuyableDescription` and `getBuyablePrice` methods with the respective product description and product price.

Now you can add products to the cart.
```php
use Treestoneit\ShoppingCart\Facades\Cart;

$product = Product::create(['name' => 'Pizza Slice', 'price' => 1.99]);
$quantity = 2;

Cart::add($product, $quantity);
```

To retrieve the cart contents:
```php
Cart::content();
// or
Cart::items();
```

To retrieve the total:
```php
Cart::subtotal();
```

You can update the quantity of an item in the cart. The first argument is the primary id of the related `CartItem`.
```php
$item = Cart:content()->first();

Cart::update($item->id, $item->quantity + 5);
```

Or remove the item completely.
```php
Cart::remove($item->id);
```

### Options
To add item-specific options (such as size or color) to an item in the cart, first register available options in your `Buyable` instance.
```php
class Product extends Model implements Buyable
{
    // ...
    
    public function getOptions(): array
    {
        return [
            'size' => ['18 inch', '36 inch'],
            'color' => ['white', 'blue', 'black'],
        ];
    }
}
```

Then you just pass an associative array as the third parameter of `Cart::add`.
```php
Cart::add($product, 3, ['color' => 'white'];
```
Any invalid options will be silently removed from the array.

You can also add or change options of an item currently in the cart by calling `Cart::updateOption`.
```php
$item = Cart:content()->first();

// Update a single option
Cart::updateOption($item->id, 'color', 'black');

// Update multiple options at once
Cart::updateOptions($item->id, [
    'color' => 'black',
    'size' => '36 inch',
]);
``` 
The options array will be available on the `CartItem` instance as `$item->options`.

### Attaching to Users

You can attach a cart instance to a user, so that their cart from a previous session can be retrieved. Attaching a cart to a user is acheived by calling the `attachTo` method, passing in an instance of `Illuminate\Contracts\Auth\Authenticatable`.

```php
class RegisterController
{
    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        Cart::attachTo($user);
    }
}
``` 

Then when the user logs in, you can call the `loadUserCart` method, again passing the user instance.

```php
class LoginController
{
    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        Cart::loadUserCart($user);
    }
}
```

### Dependency Injection

If you're not a facade person, you can use the container to inject the shopping cart instance by type-hinting the `Treestoneit\ShoppingCart\CartManager` class, or the `Treestoneit\ShoppingCart\CartContract` interface.

### Tax

The shopping cart can calculate the total tax of the items in the cart. Just call
```php
$rate = 13; // The tax rate as a percentage

Cart::tax($rate);
```

You can also set a default tax rate in the included config file.
```php
// config/shopping-cart.php

    'tax' => [
        'rate' => 6,
    ],
```

Then just call `Cart::tax` without a parameter.
```php
Cart::tax();
```

If some of your items have different tax rates applicable to them, or are tax-free, no problem. First modify the config file:
```php
// config/shopping-cart.php

    'tax' => [
        'mode' => 'per-item',
    ],
```
Then, set the tax rate per item by implementing the `Taxable` interface and defining a `getTaxRate` method.
```php
use Treestoneit\ShoppingCart\Taxable;

class Product extends Model implements Buyable, Taxable
{
    /**
     * Calculate the tax here based on a database column, or whatever you will.
     *
     * @return int|float
     */
    public function getTaxRate()
    {
        if ($this->tax_rate) {
            return $this->tax_rate;
        }

        if (! $this->taxable) {
            return 0;
        }

        return 8;
    }
```

Now your items will have their custom tax rate applied to them when calling `Cart::tax`. 

## Installation

You can install the package via composer:

```bash
composer require treestoneit/shopping-cart
```

To publish the config file and migrations, run
```bash
php artisan vendor:publish --provider="Treestoneit\ShoppingCart\CartServiceProvider"
```

And run the included database migrations.

```bash
php artisan migrate
```

## Testing

``` bash
composer test
```

## Starter (demo) Repository
If you would like to see a starter/demo implementation using this shopping cart please check out our [laravel-commerce repository](https://github.com/treeStoneIT/laravel-commerce)

## Roadmap

Some things I didn't get around to yet:

- Clear cart instance which has not been attached to a user when session is destroyed.
- Add an Artisan command that will clear any unattached carts (these two might be mutually exclusive)
- Add ability to configure cart merging strategy when `loadUserCart` is called

## Credits

- Created by [Avraham Appel](https://github.com/treestoneit)
- Initial development sponsored by [Bomshteyn Consulting](https://bomshteyn.com)
- Inspired by [LaravelShoppingcart package](https://github.com/Crinsane/LaravelShoppingcart) by [@Crisane](https://github.com/Crinsane)

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).

# Changelog

All notable changes to `shopping-cart` will be documented in this file.

## 1.1.2 - 2019-12-12

Fixed bug where tax calculation did not respect product quantity

## 1.1.1 - 2019-11-26

Added `updateOption` method

## 1.1.0 - 2019-11-26

Added:
- Ability to provide an options array when adding products. If you've already included this package in your project and want to use this feature, you'll need to add a nullable JSON column called `options` to your `cart_items` table.
```php
Schema::table('cart_items', function (Blueprint $table) {
    $table->json('options')->nullable();
});
```

## 1.0.4 - 2019-11-04

Migrations are now publishable

## 1.0.3 - 2019-10-29

- Fixed a bug where cached totals weren't clearing on `destroy()`

## 1.0.2 - 2019-10-24

- Migration optimization
- Fixed `count` method to return total quantity

## 1.0.1 - 2019-10-07

- Default tax rate was changed to 0

## 1.0.0 - 2019-10-06

- Initial release

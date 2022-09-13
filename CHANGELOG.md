# Changelog

All notable changes to `shopping-cart` will be documented in this file.

## 1.5 - 2022-09-13

- add support for php 8.1 & Laravel 9

## 1.4.3 - 2021-02-18

- Update config file: keep the default values, but look for env variables if available.

## 1.4.2 - 2021-02-02

- Upgrade illuminate/database to version 7.30.4 or later - fix for GHSA-3p32-j457-pg5x

## 1.4.1 - 2021-01-21

- Fix for CVE-2021-21263
- add support for php 8

## 1.4.0 - 2020-09-13

- Laravel 8 support
- drop support for PHP 7.2 as EOL is around the corner

## 1.3.0 - 2020-05-03

- Enable Variable pricing based on Product Options

## 1.2.0 - 2020-04-05

- add support for Laravel 7

## 1.1.3 - 2020-01-16

Fixed removing non-existing item bug ([4c38021](https://github.com/treeStoneIT/shopping-cart/commit/4c3802147efbef0066e05fe2f5f2810e2d8ea32f))

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

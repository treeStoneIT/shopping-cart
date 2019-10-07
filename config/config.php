<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Tax settings
     |--------------------------------------------------------------------------
     */
    'tax' => [
        /*
         * The tax mode to use when calculating the tax total. You can either specify
         * a single tax rate to be charged for every item, or you can have your Buyable
         * class implement the Taxable interface and define a getTaxRate method on the
         * class. You can also simply pass your own tax rate to the CartManager::tax() method.
         *
         * Acceptable modes are 'flat' and 'per-item'.
         */
        'mode' => 'flat',

        /*
         * This tax rate will be used with 'flat' mode.
         */
        'rate' => 0,
    ]

];

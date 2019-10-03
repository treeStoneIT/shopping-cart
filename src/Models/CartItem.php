<?php

namespace Treestoneit\ShoppingCart\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Cart\Models\CartItem
 *
 * @property int $id
 * @property int $cart_id
 * @property int $buyable_id
 * @property string $buyable_type
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Treestoneit\ShoppingCart\Buyable $buyable
 * @property-read mixed $description
 * @property-read float|int $extra_fees
 * @property-read float|null $price
 * @property-read float $subtotal
 * @property-read float $total
 * @method static \Illuminate\Database\Eloquent\Builder|\Treestoneit\ShoppingCart\Models\CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Treestoneit\ShoppingCart\Models\CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Treestoneit\ShoppingCart\Models\CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Treestoneit\ShoppingCart\Models\CartItem whereBuyableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Treestoneit\ShoppingCart\Models\CartItem whereBuyableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Treestoneit\ShoppingCart\Models\CartItem whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Treestoneit\ShoppingCart\Models\CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Treestoneit\ShoppingCart\Models\CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Treestoneit\ShoppingCart\Models\CartItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Treestoneit\ShoppingCart\Models\CartItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CartItem extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The buyable instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function buyable()
    {
        return $this->morphTo('buyable');
    }

    /**
     * Create a new collection instance.
     *
     * @param  array  $models
     * @return \Treestoneit\ShoppingCart\Models\CartItemCollection
     */
    public function newCollection(array $models = [])
    {
        return new CartItemCollection($models);
    }

    /**
     * Get the name of the item.
     */
    public function getDescriptionAttribute()
    {
        return $this->buyable->getBuyableDescription();
    }

    /**
     * Get the base price of the item.
     *
     * @return float|null
     */
    public function getPriceAttribute()
    {
        return $this->buyable->getBuyablePrice();
    }

    /**
     * Get the price * quantity.
     *
     * @return float
     */
    public function getSubtotalAttribute()
    {
        return round(
            $this->buyable->getBuyablePrice() * $this->attributes['quantity'],
            2
        );
    }

    /**
     * Get the extra fees for this product.
     *
     * @return float|int
     */
    public function getExtraFeesAttribute()
    {
        return $this->buyable->getExtraFees();
    }

    /**
     * Get the total for this item.
     *
     * @return float
     */
    public function getTotalAttribute()
    {
        return round(
            $this->getSubtotalAttribute() + $this->getExtraFeesAttribute(),
            2
        );
    }
}

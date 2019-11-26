<?php

namespace Treestoneit\ShoppingCart\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * App\Cart\Models\CartItem
 *
 * @property int $id
 * @property int $cart_id
 * @property int $buyable_id
 * @property string $buyable_type
 * @property int $quantity
 * @property array $options
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Treestoneit\ShoppingCart\Buyable $buyable
 * @property-read mixed $description
 * @property-read float|int $extra_fees
 * @property-read float|null $price
 * @property-read float $subtotal
 * @property-read float $total
 * @property-read string $identifier
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['options' => 'array'];

    /**
     * The unique, option specific identifier for this cart item.
     *
     * @var string
     */
    protected $identifier;

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

    /**
     * Get the unique identifier for this cart item.
     *
     * @return string
     */
    public function getIdentifierAttribute(): string
    {
        if (is_null($this->identifier)) {
            $this->identifier = $this->makeIdentifier();
        }

        return $this->identifier;
    }

    /**
     * Add options to this cart item.
     *
     * @param  array  $options
     */
    public function setOptionsAttribute(array $options)
    {
        $this->attributes['options'] = json_encode(array_merge(
            $this->options ?? [],
            $this->validateOptions($options)
        ));
    }

    /**
     * Make sure that only the enumerated option values for this buyable are present in the options array.
     *
     * @param  array  $options
     * @return array
     */
    protected function validateOptions(array $options): array
    {
        $defaults = $this->buyable->getOptions();

        return array_filter($options, function ($value, $key) use ($defaults) {
            if (! array_key_exists($key, $defaults)) {
                return false;
            }

            return $defaults[$key] == '*' || in_array($value, $defaults[$key]);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Create a unique identifier for this cart item.
     *
     * @return string
     */
    protected function makeIdentifier(): string
    {
        return md5($this->buyable_id.$this->buyable_type.serialize(Arr::sort($this->options)));
    }
}

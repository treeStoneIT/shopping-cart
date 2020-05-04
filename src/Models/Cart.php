<?php

namespace Treestoneit\ShoppingCart\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Cart\Models\Cart.
 *
 * @property int $id
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Treestoneit\ShoppingCart\Models\CartItemCollection|\Treestoneit\ShoppingCart\Models\CartItem[] $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Treestoneit\ShoppingCart\Models\Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Treestoneit\ShoppingCart\Models\Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Treestoneit\ShoppingCart\Models\Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Treestoneit\ShoppingCart\Models\Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Treestoneit\ShoppingCart\Models\Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Treestoneit\ShoppingCart\Models\Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Treestoneit\ShoppingCart\Models\Cart whereUserId($value)
 * @mixin \Eloquent
 */
class Cart extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Add a deleting listener to delete all items.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (self $cart) {
            return $cart->items()->delete();
        });
    }

    /**
     * The items in this cart instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}

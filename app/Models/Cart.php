<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'quantity',
        'amount',
        'price',
        'status',
    ];

    /* Get the product associated with the cart item */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /* Get the order that this cart item belongs to (if any) */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope: Get all products in the current user's cart.
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getUserCartItems()
    {
        return self::with('product')->where('user_id', Auth::id())->whereNull('order_id')->get();
    }

    /* Get the total price of the current user's cart */
    public static function getUserCartTotal()
    {
        return self::where('user_id', Auth::id())->whereNull('order_id')->sum('price');
    }

    /* Get the total quantity of items in the current user's cart */
    public static function getUserCartQuantity()
    {
        return self::where('user_id', Auth::id())->whereNull('order_id')->sum('quantity');
    }
}

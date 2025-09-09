<?php
// app\Helpers\Helper.php

namespace App\Helpers;

use App\Models\{Message, Category, PostTag, PostCategory, Order, Wishlist, Shipping, Cart, ProductCategory};
use Illuminate\Support\Facades\Auth;

class Helper
{
    public static function messageList()
    {
        return Message::whereNull('read_at')->orderBy('created_at', 'desc')->get();
    }
    /* Get unread messages list */
    public static function unreadMessages()
    {
        return Message::whereNull('read_at')->latest()->get();
    }

    /* Get all categories with children */
    public static function allCategories()
    {
        return (new Category())->getAllParentWithChild();
    }

    /* Get categories for header (only data, not HTML) */
    public static function headerCategories()
    {
        return (new Category())->getAllParentWithChild();
    }

    /* Product categories list */
    public static function productCategories(string $option = 'all')
    {
        return $option === 'all' ? Category::latest()->get() : Category::has('products')->latest()->get();
    }

    /* Post tag list */
    public static function postTags(string $option = 'all')
    {
        return $option === 'all' ? PostTag::latest()->get() : PostTag::has('posts')->latest()->get();
    }

    /* Post category list */
    public static function postCategories(string $option = 'all')
    {
        return $option === 'all' ? PostCategory::latest()->get() : PostCategory::has('posts')->latest()->get();
    }

    /* Authenticated user ID (or null) */
    private static function userId($user_id = null)
    {
        if (Auth::check()) {
            return $user_id ?: Auth::id();
        }
        return null;
    }

    /* Cart count */
    public static function cartCount($user_id = null)
    {
        $uid = self::userId($user_id);
        return $uid ? Cart::where('user_id', $uid)->whereNull('order_id')->sum('quantity') : 0;
    }

    /* All cart products */
    public static function cartProducts($user_id = null)
    {
        $uid = self::userId($user_id);
        return $uid ? Cart::with('product')->where('user_id', $uid)->whereNull('order_id')->get() : collect();
    }

    /* Total cart price */
    public static function cartTotal($user_id = null)
    {
        $uid = self::userId($user_id);
        return $uid ? Cart::where('user_id', $uid)->whereNull('order_id')->sum('price') : 0;
    }

    /* Wishlist count */
    public static function wishlistCount($user_id = null)
    {
        $uid = self::userId($user_id);
        return $uid ? Wishlist::where('user_id', $uid)->whereNull('cart_id')->sum('quantity') : 0;
    }

    /* Wishlist products */
    public static function wishlistProducts($user_id = null)
    {
        $uid = self::userId($user_id);
        return $uid ? Wishlist::with('product')->where('user_id', $uid)->whereNull('cart_id')->get() : collect();
    }

    /* Wishlist total price */
    public static function wishlistTotal($user_id = null)
    {
        $uid = self::userId($user_id);
        return $uid ? Wishlist::where('user_id', $uid)->whereNull('cart_id')->sum('price') : 0;
    }

    /* Grand total (order + shipping) */
    public static function orderGrandTotal($order_id, $user_id = null)
    {
        $order = Order::with('shipping')->find($order_id);
        if (!$order) {
            return 0;
        }
        $shipping_price = (float) optional($order->shipping)->price ?? 0;
        $order_price = $order->carts()->sum('price');
        return number_format((float) ($order_price + $shipping_price), 2, '.', '');
    }

    /* Monthly earnings from delivered orders */
    public static function monthlyEarnings()
    {
        $deliveredOrders = Order::where('status', 'delivered')->with('carts')->get();
        $price = 0;
        foreach ($deliveredOrders as $order) {
            $price += $order->carts->sum('price');
        }
        return number_format((float) $price, 2, '.', '');
    }

    /* Shipping methods */
    public static function shippingMethods()
    {
        return Shipping::latest()->get();
    }

    public static function getAllCategory()
    {
        return Category::with('children')->whereNull('parent_id')->get();
    }
    public static function getHeaderCategory()
    {
        return ProductCategory::with('children')
            ->whereNull('parent_category_id')
            ->get();
    }

    public static function getAllProductFromWishlist()
    {
        if (auth()->check()) {
            return Wishlist::where('user_id', auth()->id())->get();
        }
        return collect();
    }

    public static function totalWishlistPrice()
    {
        $total = 0;
        if (auth()->check()) {
            $wishlist = Wishlist::where('user_id', auth()->id())->get();
            foreach ($wishlist as $item) {
                $total += $item->product->price ?? 0;
            }
        }
        return $total;
    }


    public static function getAllProductFromCart()
    {
        if (auth()->check()) {
            return Cart::with('product')
                ->where('user_id', auth()->id())
                ->get();
        }
        return collect(); // return empty collection if not logged in
    }

    /* Get total price of all products in cart */
    public static function totalCartPrice()
    {
        $total = 0;
        if (auth()->check()) {
            $cartItems = Cart::with('product')
                ->where('user_id', auth()->id())
                ->get();

            foreach ($cartItems as $item) {
                $total += $item->price * $item->quantity;
            }
        }
        return $total;
    }
}

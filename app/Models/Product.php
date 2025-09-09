<?php

namespace App\Models;

use App\Models\ProductReview;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'description',
        'cat_id',
        'child_cat_id',
        'price',
        'brand_id',
        'discount',
        'status',
        'photo',
        'size',
        'stock',
        'is_featured',
        'condition',
    ];

    /* Relationships */
    // Category (main)
    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }

    // Sub Category (child)
    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'child_cat_id');
    }

    // Related products (same category)
    public function relatedProducts()
    {
        return $this->hasMany(Product::class, 'cat_id', 'cat_id')->active()->latest()->limit(8);
    }

    // Product reviews
    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id')->with('user_info')->active()->latest();
    }

    // Cart items (that belong to an order)
    public function carts()
    {
        return $this->hasMany(Cart::class)->whereNotNull('order_id');
    }

    // Wishlist items
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class)->whereNotNull('cart_id');
    }

    // Brand relation
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /* Query Scopes */

    // Active products only
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /* Custom Static Methods */

    // Get all products with categories
    public static function getAllProduct()
    {
        return self::with(['category', 'subCategory'])->latest()->paginate(10);
    }

    // Get single product by slug with relations
    public static function getProductBySlug($slug)
    {
        return self::with(['category', 'relatedProducts', 'reviews'])->where('slug', $slug)->first();
    }

    // Count only active products
    public static function countActiveProduct()
    {
        return self::active()->count();
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1)->where('status', 'active');
    }
}

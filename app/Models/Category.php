<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'photo',
        'status',
        'is_parent',
        'parent_id',
        'added_by',
    ];
    
    // Parent Category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Child Categories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->active();
    }

    // Products directly under this category
    public function products()
    {
        return $this->hasMany(Product::class, 'cat_id')->active();
    }

    // Products under subcategories
    public function subProducts()
    {
        return $this->hasMany(Product::class, 'child_cat_id')->active();
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    // Only active categories
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Only parent categories (no parent_id)
    public function scopeParents($query)
    {
        return $query->where('is_parent', 1)->whereNull('parent_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Static Helpers
    |--------------------------------------------------------------------------
    */

    // Get all categories with their parent
    public static function getAllCategory($perPage = 10)
    {
        return self::with('parent')->latest()->paginate($perPage);
    }

    // Shift child categories to parent
    public static function shiftChild($catIds)
    {
        return self::whereIn('id', (array) $catIds)->update(['is_parent' => 1]);
    }

    // Get children list by parent ID
    public static function getChildByParentID($id)
    {
        return self::where('parent_id', $id)->active()->orderBy('id')->pluck('title', 'id');
    }

    // Get all parent categories with children
    public static function getAllParentWithChild()
    {
        return self::parents()->active()->with('children')->orderBy('title')->get();
    }

    // Get products by category slug
    public static function getProductByCat($slug)
    {
        return self::where('slug', $slug)->with('products')->first();
    }

    // Get products by subcategory slug
    public static function getProductBySubCat($slug)
    {
        return self::where('slug', $slug)->with('subProducts')->first();
    }

    // Count active categories
    public static function countActiveCategory()
    {
        return self::active()->count();
    }
}

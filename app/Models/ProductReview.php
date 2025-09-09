<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'product_id', 'rate', 'review', 'status'];

    public function user_info()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }

    public static function getAllReview()
    {
        return ProductReview::with('user_info')->paginate(10);
    }

    public static function getAllUserReview()
    {
        return ProductReview::where('user_id', auth()->user()->id)->with('user_info')->paginate(10);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

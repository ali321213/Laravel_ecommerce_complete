<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostCategory extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'status'];

    public function posts()
    {
        return $this->hasMany(Post::class, 'post_cat_id')
            ->where('status', 'active')
            ->latest();
    }

    public static function getBlogByCategory(string $slug)
    {
        return self::with('posts')
            ->where('slug', $slug)
            ->first();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

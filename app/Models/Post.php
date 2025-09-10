<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'description',
        'photo',
        'quote',
        'tags',
        'post_cat_id',
        'post_tag_id',
        'added_by',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'post_cat_id');
    }

    public function tag()
    {
        return $this->belongsTo(PostTag::class, 'post_tag_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class)->whereNull('parent_id')->where('status', 'active')->with('user')->latest();
    }

    public function allComments()
    {
        return $this->hasMany(PostComment::class)
            ->where('status', 'active');
    }

    // Query scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Static Methods
    public static function getAllPosts()
    {
        return self::with(['category', 'author'])->latest()->paginate(10);
    }

    public static function getPostBySlug(string $slug)
    {
        return self::with(['tag', 'author'])
            ->where('slug', $slug)
            ->active()
            ->first();
    }

    public static function getBlogByTag(string $tag)
    {
        return self::where('tags', $tag)->paginate(8);
    }

    public static function countActivePosts(): int
    {
        return self::active()->count();
    }
}

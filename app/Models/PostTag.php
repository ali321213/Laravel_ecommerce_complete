<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostTag extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'status'];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // (Optional) If you plan to relate tags to posts later:
    public function posts()
    {
        return $this->hasMany(Post::class, 'post_tag_id');
    }
}

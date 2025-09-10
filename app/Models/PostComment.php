<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'comment',
        'replied_comment',
        'parent_id',
        'status',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->where('status', 'active')
            ->latest();
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Static methods
    public static function getAllComments()
    {
        return self::with('user')->latest()->paginate(10);
    }

    public static function getAllUserComments()
    {
        return self::where('user_id', auth()->id())
            ->with('user')
            ->latest()
            ->paginate(10);
    }
}

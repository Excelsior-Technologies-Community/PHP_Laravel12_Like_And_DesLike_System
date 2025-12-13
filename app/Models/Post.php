<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Like;   // ✅ IMPORTANT

class Post extends Model
{
    use HasFactory;

    // ✅ Add image here
    protected $fillable = ['title', 'body', 'image'];

    // only likes
    public function likes()
    {
        return $this->hasMany(Like::class)->where('like', true);
    }

    // only dislikes
    public function dislikes()
    {
        return $this->hasMany(Like::class)->where('like', false);
    }
}

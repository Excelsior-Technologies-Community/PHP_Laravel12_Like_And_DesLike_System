<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function likes() {
    return $this->hasMany(Like::class);
}

// check if user has liked
public function hasLiked($postId) {
    return $this->likes()->where('post_id', $postId)->where('like', true)->exists();
}

// check if user has disliked
public function hasDisliked($postId) {
    return $this->likes()->where('post_id', $postId)->where('like', false)->exists();
}

// toggle logic
public function toggleLikeDislike($postId, $like) {
    $existing = $this->likes()->where('post_id', $postId)->first();
    if ($existing) {
        if ($existing->like == $like) {
            $existing->delete();
            return ['hasLiked' => false, 'hasDisliked' => false];
        }
        $existing->update(['like' => $like]);
    } else {
        $this->likes()->create(['post_id' => $postId, 'like' => $like]);
    }

    return [
        'hasLiked' => $this->hasLiked($postId),
        'hasDisliked' => $this->hasDisliked($postId)
    ];
}

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLikes extends Model
{
    use HasFactory;

    protected $table = 'user_likes';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'post_id',
        'created_at',
        'updated_at',
    ];

    public static function findUserPostLike($userId, $postId)
    {
        return self::where('user_id', $userId)
            ->where('post_id', $postId)
            ->first();
    }

    public static function createUserPostLike($userId, $postId)
    {
        return self::create([
            'user_id' => $userId,
            'post_id' => $postId,
        ]);
    }

    public static function deleteUserPostLike($userId, $postId): void
    {
        self::where('user_id', $userId)
            ->where('post_id', $postId)
            ->delete();
    }
}

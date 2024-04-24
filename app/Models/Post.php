<?php

namespace App\Models;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static select(BigInteger $string, string $string1, string $string2, string $string3)
 * @method static where(string $string, $username)
 */
class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $primaryKey = 'id';

    protected $fillable = [
        'username',
        'titulo',
        'descricao',
        'likes',
        'created_at',
        'updated_at',
    ];

    public static function createPost(array $data): int
    {
        $post = self::create([
            'username' => $data['username'],
            'titulo' => $data['titulo'],
            'descricao' => $data['descricao'],
        ]);

        return $post->id;
    }

    public static function findPostByUsername(string $username): Collection
    {
        return self::where('username', $username)->get();
    }

    public static function findPostById(int $id): ?Post
    {
        return self::find($id);
    }

    public static function findAllPosts(): Collection
    {
        return self::all();
    }

    public static function incrementLikes(int $postId): ?int
    {
        $post = self::find($postId);
        if ($post) {
            $post->increment('likes');
            return $post->likes;
        }
        return null;
    }

    public static function decrementLikes(int $postId): ?int
    {
        $post = self::find($postId);
        if ($post) {
            $post->decrement('likes');
            return $post->likes;
        }
        return null;
    }

    public static function editPost(int $postId, array $data): bool
    {
        $post = self::find($postId);
        if ($post) {
            $post->titulo = $data['titulo'] ?? $post->titulo;
            $post->descricao = $data['descricao'] ?? $post->descricao;
            return $post->save();
        }
        return false;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function Laravel\Prompts\select;

/**
 * @method static create(array $array)
 * @method static select(string $string)
 * @method static where(string $string, $id)
 */
class PostImage extends Model
{
    use HasFactory;

    protected $table = 'post_images';

    protected $primaryKey = 'id';

    protected $fillable = [
        'caminho_img',
        'post_id',
        'created_at',
        'updated_at',
    ];

    public static function insertImage($array)
    {
        return self::create([
            'caminho_img' => $array['caminho_img'],
            'post_id' => $array['post_id'],
        ]);
    }

    public static function getAllImages($id)
    {
        return self::where('post_id', $id)->get();
    }

    public static function deleteImagesPost($postId, $urlImage): void
    {
        self::where('post_id', $postId)
            ->where('caminho_img', $urlImage)
            ->get()
            ->each->delete();
    }
}

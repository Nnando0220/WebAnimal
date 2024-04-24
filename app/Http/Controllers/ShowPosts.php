<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use App\Models\UserLikes;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class ShowPosts extends Controller
{
    public function displayAllPosts(): Application|Factory|\Illuminate\Foundation\Application|View
    {
        $posts = Post::findAllPosts();
        $user = User::findUserByUsername(User::getUsernameById(Auth::id()));
        $data_user = [
            'url_photo' => $user->photo_url,
            'username' => $user->username,
        ];
        
        if ($posts) {
            $dataPosts = [];
            
            foreach ($posts->reverse() as $post) {
                $url_images_post = $this->recuperarImagesPost($post->id);
                $usuarioLikePost = UserLikes::findUserPostLike($user->id, $post->id);
                $dataPostagem = Carbon::parse($post->created_at);
                $diference = $dataPostagem->diffForHumans();
                
                $data_post = [
                    'id' => $post->id,
                    'username' => $post->username,
                    'titulo' => $post->titulo,
                    'descricao' => $post->descricao,
                    'likes' => $post->likes,
                    'data' => $diference,
                    'url_imagens' => $url_images_post,
                    'user_post_like' => $usuarioLikePost ? true : false,
                ];
                
                $dataPosts[] = $data_post;
            }

            return view('postagens', [
                'dataPosts' => $dataPosts,
                'data_user' => $data_user,
            ]);
        }

        return view('postagens', [
            'data_user' => $data_user,
        ]);
    }

    private function recuperarImagesPost($id): array
    {
        $imagens = PostImage::getAllImages($id);
        $urls_images = [];
        
        foreach ($imagens as $imagem) {
            $urls_images[] = $imagem->caminho_img;
        }
        
        return $urls_images;
    }
}

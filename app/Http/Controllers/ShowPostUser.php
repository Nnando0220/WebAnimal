<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use App\Models\UserLikes;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class ShowPostUser extends Controller
{
    public function displayPostsUser($username): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = User::findUserByUsername($username);
        $posts = Post::findPostByUsername($username);
        $user_logged = User::findUser(Auth::id());
        Carbon::setLocale('pt_BR');
        $dateCreated = Carbon::parse($user->created_at);
        $dateFormatted = $dateCreated->translatedFormat('j F Y');
        $isUserPage = $user->id === Auth::id();
        $isUserPage = $isUserPage ?: false;

        $data_user = [
            'nome' => $user->name,
            'url_photo_auth' => $user_logged->photo_url,
            'url_photo' => $user->photo_url,
            'data_inscricao' => $dateFormatted,
            'username_auth' => $user_logged->username,
            'username' => $user->username,
            'user_page' => $isUserPage
        ];

        $dataPosts = [];
        if ($posts) {
            foreach ($posts->reverse() as $post) {
                $url_images_post = $this->recuperateImagesPost($post->id);
                $userLikePost = UserLikes::findUserPostLike($user_logged->id, $post->id);
                $datePost = Carbon::parse($post->created_at);
                $difference = $datePost->diffForHumans();

                $data_post = [
                    'id' => $post->id,
                    'username' => $post->username,
                    'titulo' => $post->titulo,
                    'descricao' => $post->descricao,
                    'likes' => $post->likes,
                    'data' => $difference,
                    'url_imagens' => $url_images_post,
                    'user_post_like' => (bool)$userLikePost,
                ];

                $dataPosts[] = $data_post;
            }
        }

        return view('perfil', [
            'dataPosts' => $dataPosts,
            'data_user' => $data_user,
        ]);
    }

    private function recuperateImagesPost($id): array
    {
        $images = PostImage::getAllImages($id);
        $urls_images = [];
        foreach ($images as $image) {
            $urls_images[] = $image->caminho_img;
        }
        return $urls_images;
    }
}

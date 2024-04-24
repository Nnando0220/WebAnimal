<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;

class PostsController extends Controller
{
    public function verifyData(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'titulo' => ['required', 'max:255'],
            'descricao' => ['required', 'max:255'],
            'imagens_postagens.*' => 'image|mimes:jpeg,png,jpg|required',
        ]);

        $findError = false;

        if ($request->hasFile('imagens_postagens')) {
            $sendImages = $request->file('imagens_postagens');

            $validatedImages = $this->validateImages($sendImages);

            if ($validatedImages) {
                $postId = $this->insertDataPost($validatedData);
                foreach ($sendImages as $image) {
                    $nameImage = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $contentImage = Image::make($image)->resize(500, 500)->encode();
                    $uploadSuccess = Storage::disk('azure')->put("photoposts/$nameImage", $contentImage);

                    if (!$uploadSuccess) {
                        $findError = true;
                    } else {
                        $azureUrl = Storage::disk('azure')->url("photoposts/$nameImage");
                        $azureUrl = preg_replace('#([^:])//+#', '$1/', $azureUrl);
                        $this->insertPostImages($azureUrl, $postId);
                    }
                }
            } else {
                $findError = true;
            }
        }

        if ($findError) {
            Alert::error('Erro!', 'Ocorreu um erro na postagem. Tente novamente!')->showConfirmButton('OK');
            return redirect()->to(route('postagens'))->with('error', 'Ocorreu um erro na postagem. Tente novamente!');
        } else {
            Alert::success('Sucesso!', 'A postagem foi realizada com sucesso. Veja também em seu Perfil!')->showConfirmButton('OK');
            return redirect()->route('postagens')->with('success', 'A postagem foi realizada com sucesso. Veja também em seu Perfil!');
        }
    }

    private function validateImages($sendImages): bool|RedirectResponse
    {
        foreach ($sendImages as $image) {
            if (!$image->isValid()) {
                Alert::error('Erro!', 'Imagem postada não é válida')->showConfirmButton('OK');
                return redirect()->back()->withErrors(['imagens_postagens.*' => 'Imagem postada não é válida'])->withInput();
            }
        }

        return true;
    }

    private function insertPostImages($azureUrl, $postId): void
    {
        $imagePost = PostImage::insertImage([
            'caminho_img' => $azureUrl,
            'post_id' => $postId,
        ]);

        if ($imagePost) {
            Alert::success('Sucesso!', 'A postagem foi realizada com sucesso. Veja também em seu Perfil!')->showConfirmButton('OK');
            redirect()->route('postagens');
        } else {
            Alert::error('Erro!', 'Ocorreu um erro na postagem. Tente novamente!')->showConfirmButton('OK');
            redirect()->route('postagens');
        }
    }

    private function insertDataPost(array $array): int|RedirectResponse
    {
        $user = User::findUser(Auth::id());

        $postId = Post::createPost([
            'username' => $user->username,
            'titulo' => $array['titulo'],
            'descricao' => $array['descricao'],
        ]);

        if ($postId){
            return $postId;
        }else{
            Alert::error('Erro!', 'Ocorreu um erro na postagem. Tente novamente!')->showConfirmButton('OK');
            return redirect()->route('postagens')->with('error', 'Ouve um erro na postagem. Tente novamente!');
        }
    }
}

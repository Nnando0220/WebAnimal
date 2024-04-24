<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use RealRashid\SweetAlert\Facades\Alert;

class UsersPosts extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $posts = Post::findPostByUsername(User::getUsernameById($id));
        if ($posts) {
            $dataPosts = [];
            foreach ($posts as $post) {
                $url_images_post = $this->recuperateImagesPost($post->id);
                Carbon::setLocale('pt_BR');
                $dateCreated = new Carbon($post->created_at);
                $dateUpdated = new Carbon($post->updated_at);
                $dateCreatedFormatted = $dateCreated->setTimezone('America/Bahia')->format('d/m/Y H:i:s');
                $dateUpdatedFormatted = $dateUpdated->setTimezone('America/Bahia')->format('d/m/Y H:i:s');
                $data_post = [
                    'id' => $post->id,
                    'username' => $post->username,
                    'titulo' => $post->titulo,
                    'descricao' => $post->descricao,
                    'likes' => $post->likes,
                    'data_criacao' => $dateCreatedFormatted,
                    'data_edicao' => $dateUpdatedFormatted,
                    'url_imagens' => $url_images_post,
                ];
                $dataPosts[] = $data_post;
            }
            return view('crud.user_posts',[
                'data_post' => $dataPosts,
                'user_id' => $id,
            ]);
        }
        return view('crud.user_posts');
    }

    private function recuperateImagesPost($id): array
    {
        $images = PostImage::getAllImages($id);
        $urls_images = [];
        foreach ($images as $image){
            $urls_images[] = $image->caminho_img;
        }
        return $urls_images;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id, Request $request)
    {
        try {

            $validatedData = $request->validate([
                'titulo'=> ['required', 'max:255'],
                'descricao' => ['required', 'max:255'],
                'imagens_postagens.*'=> 'image|mimes:jpeg,png,jpg|required',
            ]);

            if ($request->hasFile('imagens_postagens')) {
                $send_images = $request->file('imagens_postagens');

                if ($this->validateImage($send_images)) {
                    $postId = $this->insertDataPost($validatedData, $id);
                    foreach ($send_images as $image) {
                        $azureUrl = $this->getUrl($image);
                        $this->insertionsPostageImages($azureUrl, $postId, $id);
                    }
                }
            } else {
                Alert::error('Erro!', 'Não há imagem para postagem. Tente novamente.')->showConfirmButton('OK');
                return redirect()
                    ->route('crud.pagina.posts', ['modal' => 'opened', 'userId' => $id])
                    ->withInput();
            }

        } catch (ValidationException $exception) {
            Alert::error('Erro!', 'Ouve erro na postagem. Tente novamente.')->showConfirmButton('OK');
            return redirect()
                ->route('crud.pagina.posts', ['modal' => 'opened', 'userId' => $id])
                ->withErrors($exception->validator->errors()->all())
                ->withInput();
        } catch (Exception $e) {
            Alert::error('Erro!', 'Ouve erro na postagem. Tente novamente.')->showConfirmButton('OK');
            return redirect()
                ->route('crud.pagina.posts', ['modal' => 'opened', 'userId' => $id])
                ->withErrors($e->getMessage())
                ->withInput();
        }
        return redirect()->route('crud.pagina.posts', ['userId' => $id]);
    }

    /**
     * @throws Exception
     */
    private function validateImage($sendImages): bool|RedirectResponse
    {
        foreach ($sendImages as $image) {
            if (!$image->isValid()) {
                throw new Exception('Imagem da postagem não é válida');
            }
        }

        return true;
    }

    private function insertionsPostageImages($azureUrl, $postId, $id): void
    {
        $imagePost = PostImage::insertImage([
            'caminho_img' => $azureUrl,
            'post_id' => $postId,
        ]);

        if ($imagePost) {
            Alert::success('Sucesso!', 'A postagem foi realizada com sucesso.')->showConfirmButton('OK');
        } else {
            Alert::error('Erro!', 'Ocorreu um erro na postagem. Tente novamente!')->showConfirmButton('OK');
        }
        redirect()
            ->route('crud.pagina.posts', ['modal' => 'opened', 'userId' => $id]);
    }

    /**
     * @throws Exception
     */
    private function insertDataPost(array $array, $id): int|RedirectResponse
    {
        $user = User::findUser($id);

        $post_id = Post::createPost([
            'username' => $user->username,
            'titulo' => $array['titulo'],
            'descricao' => $array['descricao'],
        ]);

        if ($post_id){
            return $post_id;
        }else{
            throw new Exception('Ocorreu um erro na postagem. Tente novamente!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $userId, $postId)
    {
        try {

            $validatedData = $request->validate([
                'titulo'.$postId => ['nullable', 'max:255'],
                'descricao'.$postId => ['nullable', 'max:255'],
                'imagens_postagens.*'.$postId => 'image|mimes:jpeg,png,jpg|nullable',
            ]);

            if (Post::editPost($postId, $validatedData) && $this->editImages($request, $postId, $userId)){
                Alert::success('Sucesso!', 'A postagem foi éditada com sucesso.')->showConfirmButton('OK');
                return redirect()
                    ->route('crud.pagina.posts', ['userId' => $userId, 'modalEdit' => 'opened', 'postId' => $postId]);
            }

        } catch (ValidationException $exception){
            Alert::error('Erro!', 'Postagem não foi editada. Tente novamente!')->showConfirmButton('OK');
            return redirect()->route('crud.pagina.posts', ['userId' => $userId, 'modalEdit' => 'opened', 'postId' => $postId])
                ->withErrors($exception->validator->errors()->all())
                ->withInput();
        } catch (Exception $e){
            Alert::error('Erro!', 'Ocorreu um erro na postagem. Tente novamente!')->showConfirmButton('OK');
            return redirect()->route('crud.pagina.posts', ['userId' => $userId, 'modalEdit' => 'opened', 'postId' => $postId])
                ->withErrors($e->getMessage())
                ->withInput();
        }
        return redirect('crud.editar.post', ['userId' => $userId, 'postId' => $postId]);
    }

    /**
     * @throws Exception
     */
    private function editImages($request, $postId, $userId): bool|RedirectResponse
    {
        try {
            
            $deleteSelectImages = array_keys($request->input('seletorImagens' . $postId, []));

            if ($request->hasFile('imagens_postagens'.$postId) && $deleteSelectImages) {
                $send_images = $request->file('imagens_postagens'.$postId);
                if ($this->validateImage($send_images)) {
                    foreach ($deleteSelectImages as $image){
                        if ($this->deleteFilePhotoPostsAzure($image)){
                            PostImage::deleteImagesPost($postId, $image);
                        }
                    }
                    foreach ($send_images as $image) {
                        $azureUrl = $this->getUrl($image);
                        $this->insertionsEditPostageImages($azureUrl, $postId);
                    }
                    return true;
                }
            }

            if ($deleteSelectImages) {
                foreach ($deleteSelectImages as $image){
                    if ($this->deleteFilePhotoPostsAzure($image)){
                        PostImage::deleteImagesPost($postId, $image);
                    }
                }
                return true;
            }

            if ($request->hasFile('imagens_postagens'.$postId)){
                $send_images = $request->file('imagens_postagens'.$postId);
                if ($this->validateImage($send_images)) {
                    foreach ($send_images as $image) {
                        $azureUrl = $this->getUrl($image);
                        $this->insertionsEditPostageImages($azureUrl, $postId);
                    }
                    return true;
                }
            }
        
        } catch (Exception $e){
            throw new Exception('Erro ao editar imagens da postagem.'.$e->getMessage());
        }

        return redirect()->route('crud.pagina.posts', ['userId' => $userId, 'postId' => $postId]);
    }

    /**
     * @throws Exception
     */
    private function deleteFilePhotoPostsAzure($urlImagePost): bool
    {
        try {
            $newNamePhoto = parse_url($urlImagePost, PHP_URL_PATH);
            $infoBlob = pathinfo($newNamePhoto);
            Storage::disk('azure')->delete("photoposts/$infoBlob[basename]");
            return true;
        } catch (ServiceException $exception){
            throw new Exception('A deleção da imagem não foi realizada.'.$exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    private function insertionsEditPostageImages(array|string|null $azureUrl, $postId): void
    {
        $imagePost = PostImage::insertImage([
            'caminho_img' => $azureUrl,
            'post_id' => $postId,
        ]);

        if (!$imagePost) {
            throw new Exception('Erro ao inserir imagem na postagem!');
        }
    }

    /**
     * @param mixed $image
     * @return array|string|string[]|null
     */
    private function getUrl(mixed $image): string|array|null
    {
        $nameImage = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $contentImage = Image::make($image)->resize(500, 500)->encode();
        Storage::disk('azure')->put("photoposts/$nameImage", $contentImage);
        $azureUrl = Storage::disk('azure')->url("photoposts/$nameImage");
        return preg_replace('#([^:])//+#', '$1/', $azureUrl);
    }

    /**
     * Remove the specified resource from storage.
     * @throws Exception
     */
    public function destroy(Request $request): JsonResponse
    {
        $post = Post::findPostById($request->postId);

        if (!$post){
            return response()->json(['error']);
        }

        $postImages = $this->recuperateImagesPost($request->postId);

        foreach ($postImages as $image){
            if (!$this->deleteFilePhotoPostsAzure($image)){
                return response()->json(['error']);
            }
        }

        if ($post->delete()){
            return response()->json(['success']);
        }

        return response()->json(['error']);
    }
}

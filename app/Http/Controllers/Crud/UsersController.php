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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use RealRashid\SweetAlert\Facades\Alert;

class UsersController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $users = User::findAllUsers();
        $dataUsers = [];

        if ($users) {
            foreach ($users as $user) {
                if ($user->role !== 'admin') {
                    Carbon::setLocale('pt_BR');
                    $dateCreated = new Carbon($user->created_at);
                    $dateUpdated = new Carbon($user->updated_at);
                    $dateCreatedFormatted = $dateCreated->setTimezone('America/Bahia')->format('d/m/Y H:i:s');
                    $dateUpdatedFormatted = $dateUpdated->setTimezone('America/Bahia')->format('d/m/Y H:i:s');
                    $data_user = [
                        'id' => $user->id,
                        'username' => $user->username,
                        'photo_url' => $user->photo_url,
                        'nome' => $user->name,
                        'email' => $user->email,
                        'data_criacao' => $dateCreatedFormatted,
                        'data_edicao' => $dateUpdatedFormatted,
                    ];
                    $dataUsers[] = $data_user;
                }
            }
        }

        return view('crud.users', [
            'data_users' => $dataUsers,
        ]);
    }

    public function create(Request $request): RedirectResponse
    {
        try {
            
            $request->validate([
                'email' => ['required', 'email', 'regex:/@gmail\.com$/'],
            ]);

            $validatedData = $request->validate([
                'email_cadastro' => 'required|email|unique:users,email',
                'nome_usuario' => 'required|unique:users,username',
                'nome_completo' => ['required', 'regex:/^[a-zA-Z\s]*$/', 'max:255'],
                'senha_cadastro' => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!])(?!.*[\s])(?!.*[\\/:*?"<>|])[a-zA-Z\d@#$%^&+=!]{8,}$/'],
                'imagem_perfil' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($request->hasFile('imagem_perfil')) {
                $imagePath = $this->processImageAndUpload($request->file('imagem_perfil'));
            } else {
                $imageNull = "https://cs210032002e0478393.blob.core.windows.net/photoperfil/image_perfil_null.png";
            }
        } catch (ValidationException $exception) {
            Alert::error('Erro!', 'Usuario não cadastrado.')->showConfirmButton('OK');
            return redirect()
                ->route('crud.pagina.users', ['modal' => 'opened'])
                ->withErrors($exception->validator->errors()->all())
                ->withInput();
        } catch (Exception $e) {
            Alert::error('Erro!', 'Usuario não cadastrado.')->showConfirmButton('OK');
            return redirect()
                ->route('crud.pagina.users', ['modal' => 'opened'])
                ->withErrors($e->getMessage())
                ->withInput();
        }
        $this->insertDataUserRegister($validatedData, $imagePath ?? $imageNull);

        return redirect()->route('crud.pagina.users');
    }

    /**
     * @throws Exception
     */
    private function processImageAndUpload($image): string
    {
        if (!$image || !$image->isValid()) {
            throw new Exception('Imagem de perfil não é válida');
        }

        $nomeImage = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $contentImage = Image::make($image)->resize(100, 100)->encode();
        $successUpload = Storage::disk('azure')->put("photoperfil/$nomeImage", $contentImage);

        if (!$successUpload) {
            throw new Exception('Erro ao fazer upload da imagem');
        }

        $azureUrl = Storage::disk('azure')->url("photoperfil/$nomeImage");
        return preg_replace('#([^:])//+#', '$1/', $azureUrl);
    }

    private function insertDataUserRegister($array, $imagePath): void
    {
        $user = User::createUser([
            'name' => $array['nome_completo'],
            'username' => $array['nome_usuario'],
            'email' => $array['email_cadastro'],
            'password' => $array['senha_cadastro'],
            'photo_url' => $imagePath,
        ]);

        if ($user) {
            User::changeTimeVerifyEmail($array['email_cadastro']);
            Alert::success('Sucesso!', 'Usuário criado.')->showConfirmButton('OK');
        } else {
            Alert::error('Erro!', 'Erro ao cadastrar. Tente novamente.')->showConfirmButton('OK');
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        try {
            $user = User::findUser($id);

            $request->validate([
                'email_editavel'.$id => ['required', 'email', function ($attribute, $value, $fail) {
                    if (!strpos($value, '@gmail.com')) {
                        $fail('O campo email deve ser um endereço do Gmail.');
                    }
                }],
            ]);

            $validatedData = $request->validate([
                'email_editavel'.$id => [
                    'nullable',
                    'email',
                    Rule::unique('users', 'email')->ignore($id),
                ],
                'nome_usuario'.$id => [
                    'min:6',
                    'nullable',
                    Rule::unique('users', 'username')->ignore($id),
                ],
                'nome_completo'.$id => ['nullable', 'regex:/^[a-zA-Z\s]*$/', 'max:255'],
                'senha_editavel'.$id => [
                    'nullable',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!])(?!.*[\s])(?!.*[\\/:*?"<>|])[a-zA-Z\d@#$%^&+=!]{8,}$/',
                ],
                'imagem_perfil'.$id => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($request->hasFile('imagem_perfil'.$id)) {
                $imageNull = "https://cs210032002e0478393.blob.core.windows.net/photoperfil/image_perfil_null.png";
                if ($user->photo_url !== $imageNull){
                    if ($this->deleteFilePhotoPerfil($user->photo_url)){
                        $imagePath = $this->processImageAndUpload($request->file('imagem_perfil'.$id));
                        if (User::editUser($validatedData, $id, $imagePath)){
                            Alert::success('Sucesso!', 'Usuário editado.')->showConfirmButton('OK');
                        } else {
                            Alert::error('Erro!', 'Erro ao editar usuário. Tente novamente.')->showConfirmButton('OK');
                        }
                    }
                }
                $imagePath = $this->processImageAndUpload($request->file('imagem_perfil'.$id));
                if (User::editUser($validatedData, $id, $imagePath)){
                    Alert::success('Sucesso!', 'Usuário editado.')->showConfirmButton('OK');
                } else {
                    Alert::error('Erro!', 'Erro ao editar usuário. Tente novamente.')->showConfirmButton('OK');
                }
            }

            $imagePath = null;
            if (User::editUser($validatedData, $id, $imagePath)){
                Alert::success('Sucesso!', 'Usuário editado.')->showConfirmButton('OK');
            } else {
                Alert::error('Erro!', 'Erro ao editar usuário. Tente novamente.')->showConfirmButton('OK');
            }


        } catch (ValidationException $exception) {
            Alert::error('Erro!', 'Usuario não foi editado.')->showConfirmButton('OK');
            return redirect()
                ->route('crud.pagina.users', ['modalEdit' => 'opened', 'userId' => $id])
                ->withErrors($exception->validator->errors()->all())
                ->withInput();
        } catch (Exception $e){
            Alert::error('Erro!', 'Usuario não foi editado.')->showConfirmButton('OK');
            return redirect()
                ->route('crud.pagina.users', ['modalEdit' => 'opened', 'userId' => $id])
                ->withErrors($e->getMessage())
                ->withInput();
        }
        return redirect()->route('crud.pagina.users');
    }

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
    private function deleteFilePhotoPerfil($pathUrlImage): bool
    {
        try {
            $newNamePhoto = parse_url($pathUrlImage, PHP_URL_PATH);
            $infoBlob = pathinfo($newNamePhoto);
            Storage::disk('azure')->delete("photoperfil/$infoBlob[basename]");
            return true;
        } catch (ServiceException $exception){
            throw new Exception('A deleção da imagem não foi realizada.'.$exception->getMessage());
        }
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
     * Remove the specified resource from storage.
     * @throws Exception
     */
    public function destroy(string $id): JsonResponse
    {
        $user = User::findUser($id);
        $posts = Post::findPostByUsername($user->username);
        $user->photo_url === "https://cs210032002e0478393.blob.core.windows.net/photoperfil/image_perfil_null.png" || $this->deleteFilePhotoPerfil($user->photo_url);
        if ($posts->count() > 0){
            foreach ($posts as $post){
                $posts_images = $this->recuperateImagesPost($post->id);
                foreach ($posts_images as $post_image){
                    if (!$this->deleteFilePhotoPostsAzure($post_image)){
                        return response()->json(['error']);
                    }
                }
            }
        }

        if (!$user){
            return response()->json(['error']);
        }

        if ($user->delete()){
            Log::info('Usuário deletado');
            return response()->json(['success']);
        }

        return response()->json(['error']);
    }
}

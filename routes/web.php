<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::middleware('preventCache')->group(function () {
        Route::get('/logado/postagens', [App\Http\Controllers\ShowPosts::class, 'displayAllPosts'])->name('postagens');

        Route::get('/perfil/{username}', [App\Http\Controllers\ShowPostUser::class, 'displayPostsUser'])->name('exibir.perfil');

        Route::post('/like/{postId}', [App\Http\Controllers\ApplyLikePost::class, 'giveLikePost'])->name('aplicar.like.post');

        Route::get('/deslogado', [App\Http\Controllers\LogoutUser::class, 'logout'])->name('logout');

        Route::get('/redefinir-email/', function (){
            return view('verifica_email_redefinicao');
        })->name('exibir.redefinicao.email');
        Route::post('/redefinir-email', [App\Http\Controllers\RedefineEmail::class, 'verifyEmailReset'])->name('verificar.email.redefinir');
        Route::get('/redefinir-email/{token}', [App\Http\Controllers\RedefineEmail::class, 'pageResetEmail'])->middleware('verificar.token')->name('pagina.redefinir.email');
        Route::post('/redefinir-email/realizar', [App\Http\Controllers\RedefineEmail::class, 'redefineEmail'])->name('redefinir.email');
    });

    Route::post('/postagem', [App\Http\Controllers\PostsController::class, 'verifyData'])->name('fazer.postagem');

    Route::get('/voltar-perfil', [App\Http\Controllers\BackProfile::class, 'voltarPerfil'])->name('voltar.perfil');
});

Route::middleware(['guest', 'preventCache'])->group(function () {
    Route::get('/', function () {
        return view('login_cadastro');
    })->name('login.cadastro');

    Route::prefix('autorizar')->group(function () {
        Route::post('/login', [App\Http\Controllers\UserAuth::class, 'login'])->name('login');
        Route::post('/cadastro', [App\Http\Controllers\UserAuth::class, 'verifyRegisterEmail'])->name('verificar.email.cadastro');
        Route::get('/cadastro/{token}', [App\Http\Controllers\UserAuth::class, 'pageRegisterUser'])->middleware('verificar.token')->name('cadastrar.usuario');
        Route::post('/verificar-username', [App\Http\Controllers\UserAuth::class, 'verifyUseUsername'])->name('verificar.username');
        Route::post('/cadastro/realizar', [App\Http\Controllers\UserAuth::class, 'registerUser'])->name('cadastro.usuario');
    });
});

Route::get('/redefinir-senha', function (){
    return view('verifica_email_redefinicao_senha');
})->name('verificacao.email');
Route::post('/redefinir-senha', [App\Http\Controllers\RedefinePassword::class, 'verificationEmail'])->middleware('preventCache')->name('verificar.email.redefinicao');
Route::get('/redefinir-senha/{token}', [App\Http\Controllers\RedefinePassword::class, 'pageResetPassword'])->middleware('preventCache')->middleware('verificar.redefinicao.senha')->name('pagina.redefinir.senha');
Route::post('/redefinir-senha/confirmar', [App\Http\Controllers\RedefinePassword::class, 'resetPassword'])->middleware('preventCache')->name('redefinir.senha');

Route::get('/contato-sobre/', function (){
    return view('contato_sobre');
})->name('pagina.contato.sobre');
Route::post('/contato-sobre/formulario', [App\Http\Controllers\SupportContact::class, 'formSupportContact'])->name('formulario.contato.suporte');

Route::middleware(['guest:admin', 'preventCache'])->group(function (){
    Route::get('/login/', function () {
        return view('crud.login');
    })->name('crud.pagina.login');
    Route::post('/logar/', [App\Http\Controllers\Crud\LoginController::class, 'login'])->name('crud.login');
});

Route::middleware(['auth:admin', 'preventCache'])->group(function (){
    Route::prefix('admin')->group(function () {
        Route::get('/logado/users/', [App\Http\Controllers\Crud\UsersController::class, 'index'])->middleware('preventCache')->name('crud.pagina.users');
        Route::post('/criar-usuario/', [App\Http\Controllers\Crud\UsersController::class, 'create'])->name('crud.criar.usuario');
        Route::post('/editar-usuario/{id}/', [App\Http\Controllers\Crud\UsersController::class, 'edit'])->name('crud.editar.usuario');
        Route::delete('/deletar-usuario/{id}', [App\Http\Controllers\Crud\UsersController::class, 'destroy'])->name('crud.deletar.usuario');

        Route::get('/posts-usuario/{userId}/', [App\Http\Controllers\Crud\UsersPosts::class, 'index'])->name('crud.pagina.posts');
        Route::post('/criar-post/{userId}/', [App\Http\Controllers\Crud\UsersPosts::class, 'create'])->name('crud.criar.post');
        Route::post('/editar-post/{userId}/{postId}/', [App\Http\Controllers\Crud\UsersPosts::class, 'edit'])->name('crud.editar.post');
        Route::delete('/deletar-post/{userId}/{postId}', [App\Http\Controllers\Crud\UsersPosts::class, 'destroy'])->name('crud.deletar.post');

        Route::get('/logout/', [App\Http\Controllers\Crud\LogoutCrud::class, 'logout'])->middleware('preventCache')->name('crud.logout');
    });
});



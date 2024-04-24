<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Laravel Crud WebAnimal</title>
        <link rel="canonical" href="{{ route('crud.pagina.users')}}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="{{ asset('js/crud/modals.js') }}"></script>
        <script src="{{ asset('js/logout_confirmado.js') }}"></script>
        <script src="{{ asset('js/crud/delete_users_posts.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        @include('sweetalert::alert')
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Novo usuário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('crud.criar.usuario') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="nome_completo"  class="col-form-label">* Nome Completo: </label>
                                <input type="text" id="nome_completo" name="nome_completo" value="{{ old('nome_completo') }}" placeholder="Nome" required  class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="nome_usuario"  class="col-form-label">* Nome de Usuário: </label>
                                <input type="text" id="nome_usuario" name="nome_usuario" value="{{ old('nome_usuario') }}" placeholder="Nome de usuário" required  class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="email_cadastro"  class="col-form-label">* E-mail: </label>
                                <input type="email" id="email_cadastro" name="email_cadastro" value="{{ old('email_cadastro') }}" placeholder="Email" required  class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="senha_cadastro"  class="col-form-label">* Senha: </label>
                                <input type="password" id="senha_cadastro" name="senha_cadastro" placeholder="Senha" required  class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="imagem_perfil"  class="col-form-label">Imagem de Perfil: </label>
                                <input type="file" id="imagem_perfil" name="imagem_perfil" value="{{ old('imagem_perfil') }}" accept=".jpeg, .jpg, .png"  class="form-control">
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <ul class="p-0 m-0" style="list-style: none;">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="modal-footer">
                                <button type="button" id="btn-close" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary">Cadastrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <h2>Admin WebAnimal</h2>
                        </div>
                        <div class="col-md-6 text-end">
                            <a id="criarUsuarioBtn" href="#" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Criar usuário</a>
                            <a href="{{ route('crud.logout') }}" onclick="confirmation(event)" class="btn btn-danger">Logout</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <table class="table table-hover table-sm table-striped">
                        <thead class="table-dark">
                        <tr>
                            <th>Id</th>
                            <th>Foto perfil</th>
                            <th>Username</th>
                            <th>Nome</th>
                            <th>Data de criação</th>
                            <th>Data de edição</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($data_users)
                            @foreach($data_users as $index => $dataUser)
                                <tr>
                                    <td>{{ ++$index }}</td>
                                    <td class="align-middle"><img class="table-bordered rounded-circle" style="width: 50px; height: 50px; object-fit: cover;" src="{{ $dataUser['photo_url'] }}" alt="foto perfil"></td>
                                    <td class="align-middle">{{ $dataUser['username'] }}</td>
                                    <td class="align-middle">{{ $dataUser['nome'] }}</td>
                                    <td class="align-middle">{{ $dataUser['data_criacao'] }}</td>
                                    <td class="align-middle">{{ $dataUser['data_edicao'] }}</td>
                                    <td class="align-middle">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-primary">
                                                <a href="{{ route('crud.pagina.posts', ['userId' => $dataUser['id']]) }}" style="color: white; text-decoration: none;">
                                                    Ver Posts
                                                </a>
                                            </button>
                                            <button href="#"  class="btn btn-secondary edit-button" data-user-id="{{ $dataUser['id'] }}" data-bs-toggle="modal" data-bs-target="exampleModalEdit_{{ $dataUser['id'] }}" onclick="openEditModalUser('{{ $dataUser['id'] }}')">Editar</button>
                                            <div class="modal fade edit-button" id="exampleModalEdit_{{ $dataUser['id'] }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Editar usuário</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('crud.editar.usuario', ['id' => $dataUser['id']]) }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="mb-3">
                                                                    <label for="nome_completo{{ $dataUser['id'] }}"  class="col-form-label">Nome Completo: </label>
                                                                    <input type="text" id="nome_completo{{ $dataUser['id'] }}" name="nome_completo{{ $dataUser['id'] }}" value="{{ $dataUser['nome'] }}" placeholder="Nome" class="form-control">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="nome_usuario{{ $dataUser['id'] }}"  class="col-form-label">Nome de Usuário: </label>
                                                                    <input type="text" id="nome_usuario{{ $dataUser['id'] }}" name="nome_usuario{{ $dataUser['id'] }}" value="{{ $dataUser['username'] }}" placeholder="Nome de usuário" class="form-control">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="email_editavel{{ $dataUser['id'] }}"  class="col-form-label">E-mail: </label>
                                                                    <input type="email" id="email_editavel{{ $dataUser['id'] }}" name="email_editavel{{ $dataUser['id'] }}" value="{{ $dataUser['email'] }}" placeholder="Email" class="form-control">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="senha_editavel{{ $dataUser['id'] }}"  class="col-form-label">Senha: </label>
                                                                    <input type="password" id="senha_editavel{{ $dataUser['id'] }}" name="senha_editavel{{ $dataUser['id'] }}" placeholder="Senha" class="form-control">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="imagem_perfil{{ $dataUser['id'] }}"  class="col-form-label">Imagem de Perfil: </label>
                                                                    <input type="file" id="imagem_perfil{{ $dataUser['id'] }}" name="imagem_perfil{{ $dataUser['id'] }}" value="{{ $dataUser['photo_url'] }}" accept=".jpeg, .jpg, .png" class="form-control">
                                                                </div>
                                                                @if ($errors->any())
                                                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                                        <ul class="p-0 m-0" style="list-style: none;">
                                                                            @foreach($errors->all() as $error)
                                                                                <li>{{ $error }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                @endif
                                                                <div class="modal-footer">
                                                                    <button type="button" id="btn-close{{ $dataUser['id'] }}" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                                    <button type="submit" class="btn btn-primary">Editar</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="btn btn-danger" onclick="deleteUser('{{ $dataUser['id'] }}')">Deletar</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="align-middle" colspan="8">Nenhum dado disponível</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="theme-color" content="#000000">
        <meta name="description" content="Cadastrar o usuário">
        <link rel="canonical" href="{{ route('cadastrar.usuario', ['token' => $token]) }}">
        <title>Cadastro</title>
        <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
        <link rel="stylesheet" href="{{ asset('css/cadastro.css') }}">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="{{ asset('js/hamburger_menu.js') }}"></script>
        <script src="{{ asset('js/cadastro_login.js') }}"></script>
        <script src="{{ asset('js/slide_login_cadastro.js') }}"></script>
        <script src="{{ asset('js/verificar_nome_usuario.js') }}"></script>
        <script src="{{ asset('js/verificar_senha_cadastro.js') }}"></script>
        <script src="{{ asset('js/estilizar_seletor_images.js') }}"></script>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/fontawesome.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css"/>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        @include('sweetalert::alert')
        <header class="cabecalho">
            <div id="main_container_header">
                <div id="container_logo">
                    <a href="{{ route('login.cadastro') }}">
                        <figure>
                            <img src="{{ asset('images/logo.png') }}" alt="logo web animal" title="logo web animal">
                        </figure>
                    </a>
                </div>
                <div id="nome_site">
                    <h3>WebAnimal</h3>
                </div>
                <div class="botao-menu" onclick="toggleMenu()">
                    &#9776;
                </div>
                <div id="container-nav" class="menu-hamburger">
                    <nav>
                        <ul>
                            <li><a href="{{ route('login.cadastro') }}">Página Inicial</a></li>
                            <li><a id="espacamento" href="{{ route('pagina.contato.sobre') }}">Sobre / Contato</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p class="p-0 m-0">{{ session('error') }}</p>
            </div>
        @endif
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p class="p-0 m-0">{{ session('success') }}</p>
            </div>
        @endif
        <main class="main-container">
            <div class="login-cadastro-container">
                <div class="forms-container">
                    <div class="cadastro-form">
                        <h2>Cadastro</h2>
                        <form action="{{ route('cadastro.usuario') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="token" hidden value="{{ $token }}">
                            <div class="container">
                                <label for="nome_completo">* Nome Completo: </label>
                                <input type="text" id="nome_completo" name="nome_completo" placeholder="Nome" value="{{ old('nome_completo') }}" required>
                                <span style="font-size: 0.8em;">(Digite sem acentos)</span>
                            </div>
                            <div class="container">
                                <label for="nome_usuario">* Nome de Usuário: </label>
                                <input type="text" id="nome_usuario" name="nome_usuario" placeholder="Nome de usuário" value="{{ old('nome_usuario') }}" required>
                                <span id="nome_usuario_disponivel" class="status-message">Nome de usuário deve ter no minímo 6 caracteres. <br>Somente letras e números. <br>Saia do campo para verificar.</span>
                            </div>
                            <div class="container">
                                <label for="email_cadastro">* E-mail: </label>
                                <input type="email" id="email_cadastro" name="email_cadastro" placeholder="Email" value="{{ $email }}" required>
                                <span style="font-size: 0.8em;">(Utilize apenas Gmail)</span>
                            </div>
                            <div class="container">
                                <label for="senha_cadastro">* Senha: </label>
                                <input type="password" id="senha_cadastro" name="senha_cadastro" placeholder="Senha" required>
                                <small class="form-text text-muted">
                                    A senha deve conter pelo menos:
                                    <br><span id="lowercaseIcon"><i class="fas fa-times-circle  text-danger"></i></span> Uma letra minúscula
                                    <br><span id="uppercaseIcon"><i class="fas fa-times-circle text-danger"></i></span> Uma letra maiúscula
                                    <br><span id="numberIcon"><i class="fas fa-times-circle text-danger"></i></span> Um número
                                    <br><span id="specialCharIcon"><i class="fas fa-times-circle text-danger"></i></span> Um caractere especial @#$%^&+=!
                                    <br><span id="minCaracteres"><i class="fas fa-times-circle text-danger"></i></span> Minímo de 8 caracteres
                                </small>
                            </div>
                            <div class="elementor-field-type-upload elementor-field-group elementor-column elementor-field-group-field_53c80dc elementor-col-50 elementor-field-required">
                                <label for="imagem_perfil"  class="elementor-field-label">
                                    Clique aqui para anexar arquivo
                                </label>
                                <input type="file" id="imagem_perfil" name="imagem_perfil" accept=".jpeg, .jpg, .png" class="elementor-field elementor-size-lg  elementor-upload-field" aria-required="true" onchange="displaySelectedFiles(this)">
                                <span style="font-size: 0.8em;">(Formatos permitidos: .jpeg, .jpg, .png)</span>
                                <div id="selectedFiles"></div>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <ul class="p-0 m-0" style="list-style: none;">
                                        @foreach($errors->all() as $error)
                                            <li>{{$error}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <input type="submit" value="Cadastrar">
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <p>&copy; 2023 WebAnimal - Todos os direitos reservados</p>
        </footer>
    </body>
</html>

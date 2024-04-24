<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="theme-color" content="#000000">
        <meta name="description" content="Redefinir senha do usuário">
        <link rel="canonical" href="{{ route('pagina.redefinir.senha', ['token' => $token]) }}">
        <title>Redefinição de senha</title>
        <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
        <link rel="stylesheet" href="{{ asset('css/redefinir_senha.css') }}">
        <script src="{{ asset('js/hamburger_menu.js') }}"></script>
        <script src="{{ asset('js/verificar_senha_redefinicao.js') }}"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                            @if(@Auth::user())
                                <li><a href="{{ route('postagens') }}">Página Inicial</a></li>
                            @else
                                <li><a href="{{ route('login.cadastro') }}">Página Inicial</a></li>
                            @endif
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
        <main>
            <div class="container">
                <form action="{{ route('redefinir.senha') }}" method="POST">
                    @csrf
                    <input type="text" name="token" hidden value="{{ $token }}">
                    <h2>Redefinir Senha</h2>
                    <div class="input-group">
                        <label for="email">* Email:</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="input-group">
                        <label for="nova_senha">* Nova Senha:</label>
                        <input type="password" id="nova_senha" name="nova_senha" required>
                        <small class="form-text text-muted">
                            A senha deve conter pelo menos:
                            <br><span id="lowercaseIcon"><i class="fas fa-times-circle  text-danger"></i></span> Uma letra minúscula
                            <br><span id="uppercaseIcon"><i class="fas fa-times-circle text-danger"></i></span> Uma letra maiúscula
                            <br><span id="numberIcon"><i class="fas fa-times-circle text-danger"></i></span> Um número
                            <br><span id="specialCharIcon"><i class="fas fa-times-circle text-danger"></i></span> Um caractere especial @#$%^&+=!
                            <br><span id="minCaracteres"><i class="fas fa-times-circle text-danger"></i></span> Minímo de 8 caracteres
                        </small>
                    </div>
                    <div class="input-group">
                        <label for="nova_senha_confirmation">* Confirmar Senha:</label>
                        <input type="password" id="nova_senha_confirmation" name="nova_senha_confirmation" required>
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
                    <div class="input-group">
                        <button type="submit">Redefinir Senha</button>
                    </div>
                </form>
            </div>
        </main>
        <footer>
            <p>&copy; 2023 WebAnimal - Todos os direitos reservados</p>
        </footer>
    </body>
</html>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="theme-color" content="#000000">
        <meta name="description" content="Verificar E-mail para redefinição">
        <link rel="canonical" href="{{ route('exibir.redefinicao.email') }}">
        <title>Redefinição de E-mail</title>
        <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="{{ asset('css/verificacao_email.css') }}">
        <script src="{{ asset('js/hamburger_menu.js') }}"></script>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        @include('sweetalert::alert')
        <header class="cabecalho">
            <div id="main_container_header">
                <div id="container_logo">
                    <a href="{{ route('postagens') }}">
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
                            <li><a href="{{ route('postagens') }}">Página Inicial</a></li>
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
                <h2>Redefinição de E-mail</h2>
                <form action="{{ route('verificar.email.redefinir') }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <label for="email">* E-mail:</label>
                        <input type="email" id="email" name="email" required>
                        <span style="font-size: 0.8em;">(Utilize apenas Gmail)</span>
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
                        <button type="submit">Enviar E-mail</button>
                    </div>
                    <div class="container-links-form">
                        <a href="{{ route('voltar.perfil') }}" class="link-login">Voltar perfil</a>
                    </div>
                </form>
            </div>
        </main>
        <footer>
            <p>&copy; 2023 WebAnimal - Todos os direitos reservados</p>
        </footer>
    </body>
</html>

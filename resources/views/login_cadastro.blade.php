<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta name="google-site-verification" content="-g-Tqj49oKA-CvKm46WqmxrO5RJBphVw9aUwB5xETvM"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="theme-color" content="#000000">
        <meta name="description" content=
        "
            Explore um mundo encantador de ternura e diversidade com nossa comunidade de postagens de fotos de animais. 
            De cãezinhos fofos a felinos elegantes, e de pássaros coloridos a criaturas exóticas, mergulhe em uma galeria repleta de vida e alegria. 
            Compartilhe seus momentos mais adoráveis ou descubra novos amigos peludos para adicionar um toque de alegria ao seu dia. 
            Junte-se a nós e celebre a beleza dos animais através de imagens que aquecem o coração.
        "
        >
        <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
        <link rel="canonical" href="{{ route('login.cadastro') }}">
        <title>Login | Cadastro</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="{{ asset('css/login_cadastro.css') }}">
        <script src="{{ asset('js/hamburger_menu.js') }}"></script>
        <script src="{{ asset('js/cadastro_login.js') }}"></script>
        <script src="{{ asset('js/slide_login_cadastro.js') }}"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
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
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <p class="m-0">{{ session('error') }}</p>
            </div>
        @endif
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <p class="m-0">{{ session('success') }}</p>
            </div>
        @endif
        <main class="main-container">
            <div class="login-cadastro-container">
                <div class="forms-container">
                    <div class="login-form active">
                        <form class="login" action="{{ route('login') }}" method="POST">
                            @csrf
                            <h2>Login</h2>
                            <div class="container">
                                <label for="email">E-mail: </label>
                                <input type="email" id="email" name="email" placeholder="Email" required>
                            </div>
                            <div class="container">
                                <label for="password">Senha: </label>
                                <input type="password" id="password" name="password" placeholder="senha" required>
                            </div>
                            <div class="container">
                                <div class="form-check">
                                    <input class="form-check-input" style="border-color: black" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <ul class="list-unstyled" style="margin: 0; padding: 0;">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <input type="submit" value="Entrar">
                            <div class="container-links-form">
                                <a href="{{ route('verificacao.email') }}" class="link-recuperar-senha">Recuperar senha</a>
                            </div>
                            <div class="container-links-form">
                                <a href="#" class="link-cadastrar">Cadastre-se</a>
                            </div>
                        </form>
                    </div>
                    <div class="cadastro-form">
                        <h2>Cadastro</h2>
                        <form action="{{ route('verificar.email.cadastro') }}" method="POST">
                            @csrf
                            <div class="container">
                                <label for="email_cadastro">* E-mail: </label>
                                <input type="email" id="email_cadastro" name="email_cadastro" placeholder="Email" required>
                                <span style="font-size: 0.8em;">(Utilize apenas Gmail)</span>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <ul class="list-unstyled" style="margin: 0; padding: 0;">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <input type="submit" value="Verificar E-mail">
                            <div class="container-links-form">
                                <a href="#" class="link-login">Já tenho uma conta</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="slide-container">
                <div class="slider">
                    <div class="slide">
                        <img src="{{ asset('images/divulgacao_1.png') }}" alt="Imagem divulgação">
                    </div>
                    <div class="slide">
                        <img src="{{ asset('images/divulgacao_2.png') }}" alt="Imagem divulgação">
                    </div>
                    <div class="slide">
                        <img src="{{ asset('images/divulgacao_3.png') }}" alt="Imagem divulgação">
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <p>&copy; 2023 WebAnimal - Todos os direitos reservados</p>
        </footer>
    </body>
</html>

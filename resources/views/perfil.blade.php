<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="theme-color" content="#000000">
        <meta name="description" content="Postagens de fotos do usuario {{ $data_user['username'] }}">
        <link rel="canonical" href="{{ route('exibir.perfil', ['username' => $data_user['username']]) }}">
        <title>Postagens | {{ $data_user['username'] }}</title>
        <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
        <script src="{{ asset('js/hamburger_menu.js') }}"></script>
        <script src="{{ asset('js/img_perfil_formulario_postagem.js') }}"></script>
        <script src="{{ asset('js/carrossel_postagem.js') }}"></script>
        <script src="{{ asset('js/likes_postagem.js') }}"></script>
        <script src="{{ asset('js/logout_confirmado.js') }}"></script>
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
                        <img src="{{ asset('images/logo.png') }}" alt="logo web animal" title="logo web animal">
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
                            <li class="dropdown">
                                @if($data_user['user_page'])
                                    <img src="{{ $data_user['url_photo'] }}" alt="" class="dropbtn" >
                                @else
                                    <img src="{{ $data_user['url_photo_auth'] }}" alt="" class="dropbtn" >
                                @endif
                                <div class="dropdown-content">
                                    <a href="{{ route('exibir.perfil', ['username' => $data_user['username_auth']]) }}" class="perfil-config">Perfil</a>
                                </div>
                            </li>
                            <li><a href="{{ route('postagens') }}">Página Inicial</a></li>
                            <li><a id="espacamento" href="{{ route('pagina.contato.sobre') }}">Sobre / Contato</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
        <main>
            <div class="main_container">
                <div class="perfil_usuario">
                    <img src="{{ $data_user['url_photo'] }}" alt="Foto de perfil">
                    <div class="info-user">
                        <h2>{{ $data_user['nome'] }}</h2>
                        <p>Criado em {{ $data_user['data_inscricao'] }}</p>
                        <p>Usuário: {{ $data_user['username'] }}</p>
                    </div>
                    @if($data_user['user_page'])
                        <div class="buttons-user">
                            <a href="{{ route('exibir.redefinicao.email') }}" class="btn btn-outline-warning">Alterar E-mail</a>
                            <a href="{{ route('verificacao.email') }}" class="btn btn-outline-warning">Alterar Senha</a>
                            <a href="{{ route('logout') }}" onclick="confirmation(event)" class="btn btn-outline-danger" data-confirm-delete="true">Sair</a>
                        </div>
                    @else
                    @endif
                </div>
                <div class="posts-users">
                    @foreach($dataPosts as $index => $data_post)
                        @if(count($data_post['url_imagens'])===1)
                            <div class="main_post">
                                <div class="main_sub_container_primeiro">
                                    @foreach($data_post['url_imagens'] as $imagem)
                                        <img src="{{ $imagem }}" alt="Imagem do postada" class="w-100">
                                    @endforeach
                                    <div class="main_conteudo_post">
                                        <h3><b>{{ $data_post['titulo'] }}</b></h3>
                                        <h5><span class="main_data">{{ $data_post['data'] }}</span></h5>
                                        <p>Usuário: {{ $data_post['username'] }}</p>
                                        @if($data_post['user_post_like'])
                                            <button class="btn btn-outline-primary btn-like active" data-post-id="{{ $data_post['id'] }}">
                                                <i class="bi-heart"></i> Like
                                                <span class="like-count">{{ $data_post['likes'] }}</span>
                                            </button>
                                        @else
                                            <button class="btn btn-outline-primary btn-like" data-post-id="{{ $data_post['id'] }}">
                                                <i class="bi-heart"></i> Like
                                                <span class="like-count">{{ $data_post['likes'] }}</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="main_sub_container_segundo">
                                    <p>{{ $data_post['descricao'] }}</p>
                                </div>
                            </div>
                        @else
                            <div class="main_post">
                                <div class="main_sub_container_primeiro">
                                    <div id="carouselExample{{ $index }}" class="carousel slide">
                                        <ol class="carousel-indicators">
                                            @foreach($data_post['url_imagens'] as $key => $imagem)
                                                <li data-target="#carouselExample{{ $index }}" data-slide-to="{{ $key }}" class="@if($key === 0) active @endif"></li>
                                            @endforeach
                                        </ol>
                                        <div class="carousel-inner">
                                            @foreach($data_post['url_imagens'] as $key => $imagem)
                                                <div class="carousel-item @if($key === 0) active @endif">
                                                    <img src="{{ $imagem }}" class="d-block w-100" alt="Imagem do postada">
                                                </div>
                                            @endforeach
                                        </div>
                                        <a class="carousel-control-prev" href="#carouselExample{{ $index }}" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Anterior</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselExample{{ $index }}" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Próximo</span>
                                        </a>
                                    </div>
                                    <div class="main_conteudo_post">
                                        <h3><b>{{ $data_post['titulo'] }}</b></h3>
                                        <h5><span class="main_data">{{ $data_post['data'] }}</span></h5>
                                        <p>Usuário: {{ $data_post['username'] }}</p>
                                        @if($data_post['user_post_like'])
                                            <button class="btn btn-outline-primary btn-like active" data-post-id="{{ $data_post['id'] }}">
                                                <i class="bi-heart"></i> Like
                                                <span class="like-count">{{ $data_post['likes'] }}</span>
                                            </button>
                                        @else
                                            <button class="btn btn-outline-primary btn-like" data-post-id="{{ $data_post['id'] }}">
                                                <i class="bi-heart"></i> Like
                                                <span class="like-count">{{ $data_post['likes'] }}</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="main_sub_container_segundo">
                                    <p>
                                        {{ $data_post['descricao'] }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </main>
        <footer>
            <footer>
                <p>&copy; 2023 WebAnimal - Todos os direitos reservados</p>
            </footer>
        </footer>
    </body>
</html>

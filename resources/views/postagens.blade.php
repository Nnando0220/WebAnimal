<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="theme-color" content="#000000">
        <meta name="description" content=
        "
            Descubra um universo cativante de momentos compartilhados pelos usuários cadastrados em nossa plataforma. 
            De registros adoráveis de cães brincalhões a retratos impressionantes de gatos preguiçosos, esta timeline oferece uma jornada emocionante através das experiências dos amantes de animais. 
            Explore uma variedade de postagens únicas e emocionantes, cada uma capturando a essência e a beleza do reino animal. 
            Junte-se à nossa comunidade e mergulhe em uma galeria infinita de fotos encantadoras, onde cada imagem conta uma história especial.
        "
        >
        <link rel="canonical" href="{{ route('postagens') }}">
        <title>Todas Postagens</title>
        <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="{{ asset('css/postagens.css') }}">
        <script src="{{ asset('js/hamburger_menu.js') }}"></script>
        <script src="{{ asset('js/img_perfil_formulario_postagem.js') }}"></script>
        <script src="{{ asset('js/carrossel_postagem.js') }}"></script>
        <script src="{{ asset('js/likes_postagem.js') }}"></script>
        <script src="{{ asset('js/logout_confirmado.js') }}"></script>
        <script src="{{ asset('js/estilizar_seletor_images.js') }}"></script>
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
                                <img src="{{ $data_user['url_photo'] }}" alt="" class="dropbtn">
                                <div class="dropdown-content">
                                    <a href="{{ route('exibir.perfil', ['username' => $data_user['username']]) }}" class="perfil-config">Perfil</a>
                                    <a href="{{ route('logout') }}" onclick="confirmation(event)"  class="perfil-config">Sair</a>
                                </div>
                            </li>
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
        <main>
            <div class="main_container">
                <div class="main_post_form">
                    <h2 class="mostrar-forms"><a href="#">Clique para Nova Postagem!!!<i class="indicator fas fa-chevron-down"></i></a></h2>
                    <form class="forms" action="{{ route('fazer.postagem') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="titulo">* Título:</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="form-group">
                            <label for="descricao">* Descrição:</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="4" required></textarea>
                        </div>
                        <div class="elementor-field-type-upload elementor-field-group elementor-column elementor-field-group-field_53c80dc elementor-col-50 elementor-field-required">
                            <label for="imagens_postagens"  class="elementor-field-label">
                                Clique aqui para anexar arquivo
                            </label>
                            <input type="file" id="imagens_postagens" name="imagens_postagens[]" accept=".jpeg, .jpg, .png" class="elementor-field elementor-size-lg  elementor-upload-field" required aria-required="true" multiple onchange="displaySelectedFiles(this)">
                            <span style="font-size: 0.8em;">(Formatos permitidos: .jpeg, .jpg, .png)</span>
                            <div id="selectedFiles"></div>
                        </div>
                        <input type="submit" value="Publicar">
                    </form>
                </div>
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
                                    <p>Usuário: <a href="{{ route('exibir.perfil', ['username' => $data_post['username']]) }}">{{ $data_post['username'] }}</a></p>
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
                                    <p>Usuário: <a href="{{ route('exibir.perfil', ['username' => $data_post['username']]) }}">{{ $data_post['username'] }}</a></p>
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
        </main>
        <footer>
            <p>&copy; 2023 WebAnimal - Todos os direitos reservados</p>
        </footer>
    </body>
</html>

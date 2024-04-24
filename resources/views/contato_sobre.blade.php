<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="theme-color" content="#000000">
        <meta name="description" content="Conecte-se à nossa vibrante comunidade de amantes de animais! Faça login ou cadastre-se no nosso site para compartilhar as fotos mais adoráveis dos seus pets. Além disso, estamos aqui para oferecer suporte e responder às suas perguntas. Explore nossa seção de suporte e contato para obter assistência personalizada, esclarecer dúvidas ou reportar qualquer problema. Junte-se a nós para vivenciar uma experiência única de troca de histórias e momentos especiais. Celebre a alegria e a fofura dos animais de estimação enquanto faz parte da nossa rede social dedicada aos amigos peludos.">
        <title>Contatos | Sobre</title>
        <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
        <link rel="canonical" href="{{ route('pagina.contato.sobre') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="{{ asset('css/contato_sobre.css') }}">
        <script src="{{ asset('js/hamburger_menu.js') }}"></script>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-ArrY4TfDq9fdZVFBgJFxEy6tnqXcv9D7lMVa7DWI2ZHjL8Vpj7f/o9SjpSH0SszkSOzfuEuSf70BgkUr4Rvq4Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        @extends('sweetalert::alert')
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
        <main class="container">
            <section>
                <h2>Telefone e Endereço</h2>
                <div class="card">
                    <div class="card-body">
                        <ul>
                            <li>Email: webanimal99@gmail.com</li>
                            <li>Telefone: (11) 1234-5678</li>
                            <li>Endereço: Rua dos Animais Felizes, 123 - Cidade Alegre</li>
                        </ul>
                    </div>
                </div>
            </section>
            <section>
                <h2>Formulário Para Contato</h2>
                <form action="{{ route('formulario.contato.suporte') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="nome" class="form-label">* Nome:</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">* Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <span style="font-size: 0.8em;">(Utilize apenas Gmail)</span>
                    </div>
                    <div class="mb-3">
                        <label for="mensagem" class="form-label">* Mensagem:</label>
                        <textarea class="form-control" maxlength="1000" id="mensagem" name="mensagem" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="opcoes" class="form-label">* Escolha uma opção:</label>
                        <select class="form-select" id="opcoes" name="opcoes" required>
                            <option value="vazio"></option>
                            <option value="feedback">Enviar Feedback</option>
                            <option value="suporte">Pedir Suporte</option>
                        </select>
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
                    <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
                </form>
            </section>
            <section>
                <h2>Sobre Nós</h2>
                <div>
                    <h2>Conectando Amantes de Animais</h2>
                    <p>Somos uma comunidade apaixonada por animais, dedicada a compartilhar e apreciar os momentos especiais de seus pets. Aqui, acreditamos que cada foto conta uma história única e queremos ajudar você a compartilhar essas histórias com o mundo.</p>
                    <p>Nossa plataforma foi criada para unir amantes de animais, proporcionando um espaço onde você pode não apenas compartilhar fotos incríveis de seus pets, mas também se conectar com outros donos de animais, trocar experiências e receber feedback positivo.</p>
                </div>
                <div class="testimonial">
                    <blockquote class="blockquote">
                        <p class="mb-0">"Esta comunidade de amantes de animais é incrível! Consegui compartilhar as melhores fotos do meu pet e ainda conhecer pessoas com interesses semelhantes. Recomendo a todos os apaixonados por animais!"</p>
                        <footer class="blockquote-footer">Membro Satisfeito</footer>
                    </blockquote>
                </div>
            </section>
        </main>
        <footer class="bg-dark p-4 text-center">
            <p>Email: webanimal99@gmail.com</p>
            <p>Redes Sociais:
                <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i> Instagram</a> |
                <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook"></i> Facebook</a>
            </p>
            <p>Telefone: (11) 1234-5678</p>
            <p>Direitos autorais reservados &copy; 2024 Empresa de Rede de Social de Fotos de Animais</p>
        </footer>
    </body>
</html>

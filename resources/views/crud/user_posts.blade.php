<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Laravel Crud WebAnimal</title>
        <link rel="canonical" href="{{ route('crud.pagina.posts', ['userId' => $user_id]) }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="{{ asset('js/logout_confirmado.js') }}"></script>
        <script src="{{ asset('js/carrossel_postagem.js') }}"></script>
        <script src="{{ asset('js/crud/modals.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('css/crud/posts_users.css') }}">
        <script src="{{ asset('js/crud/delete_users_posts.js') }}"></script>
        <script src="{{ asset('js/estilizar_seletor_images.js') }}"></script>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        @include('sweetalert::alert')
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Criar post</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('crud.criar.post', ['userId' => $user_id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="titulo"  class="col-form-label">* Titulo:</label>
                                <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" placeholder="Título" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="descricao"  class="col-form-label">* Descrição:</label>
                                <textarea id="descricao" name="descricao" value="{{ old('descricao') }}" placeholder="Digite algo para descrever a sua postagem." rows="4" class="form-control" required></textarea>
                            </div>
                            <div class="elementor-field-type-upload elementor-field-group elementor-column elementor-field-group-field_53c80dc elementor-col-50 elementor-field-required">
                                <label for="imagens_postagens"  class="elementor-field-label">
                                    Clique aqui para anexar arquivo
                                </label>
                                <input type="file" id="imagens_postagens" name="imagens_postagens[]" accept=".jpeg, .jpg, .png" value="{{ old('imagens_postagens') }}" class="elementor-field elementor-size-lg  elementor-upload-field" required="required" aria-required="true" onchange="displaySelectedFiles(this)" multiple>
                                <div class="selectedFiles" id="selectedFiles"></div>
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary">Postar</button>
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
                        <div class="col-md-6 text-right">
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap">Criar post</a>
                            <a href="{{ route('crud.pagina.users') }}" class="btn btn-secondary">Usuários</a>
                            <a href="{{ route('crud.logout') }}" onclick="confirmation(event)" class="btn btn-danger">Logout</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th>Id</th>
                                <th>Título</th>
                                <th>Foto(s)</th>
                                <th>Descrição</th>
                                <th>Likes</th>
                                <th>Data de criação</th>
                                <th>Data de edição</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($data_post)
                                @foreach($data_post as $index => $dataPost)
                                    <div class="modal fade" id="exampleModalEdit_{{ $dataPost['id'] }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Editar post</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('crud.editar.post', ['userId' => $user_id, 'postId' => $dataPost['id']]) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="titulo{{ $dataPost['id'] }}"  class="col-form-label">Titulo: </label>
                                                            <input type="text" id="titulo{{ $dataPost['id'] }}" name="titulo{{ $dataPost['id'] }}" value="{{ $dataPost['titulo'] }}" placeholder="Titulo" class="form-control">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="descricao{{ $dataPost['id'] }}"  class="col-form-label">Descrição: </label>
                                                            <textarea id="descricao{{ $dataPost['id'] }}" name="descricao{{ $dataPost['id'] }}" placeholder="{{ $dataPost['descricao'] }}" class="form-control"></textarea>
                                                        </div>
                                                        <div class="elementor-field-type-upload elementor-field-group elementor-column elementor-field-group-field_53c80dc elementor-col-50 elementor-field-required">
                                                            <label for="imagens_postagens_{{ $dataPost['id'] }}" class="elementor-field-label">
                                                                Clique aqui para anexar arquivo
                                                            </label>
                                                            <input type="file" id="imagens_postagens_{{ $dataPost['id'] }}" name="imagens_postagens{{ $dataPost['id'] }}[]" accept=".jpeg, .jpg, .png" class="elementor-field elementor-size-lg  elementor-upload-field" aria-required="true" onchange="displaySelectedFiles(this)" multiple>
                                                            <div class="selectedFiles" id="selectedFiles_{{ $dataPost['id'] }}"></div>
                                                        </div>
                                                        @if(count($dataPost['url_imagens']) > 1)
                                                            <div class="mb-3">
                                                                <label class="col-form-label">Deletar Imagens:</label>
                                                                <div>
                                                                    @foreach($dataPost['url_imagens'] as $indexImg => $imagem)
                                                                        <div class="custom-checkbox">
                                                                            <input type="checkbox" id="imagemCheckbox{{ $dataPost['id'] }}_{{ $indexImg }}" name="seletorImagens{{ $dataPost['id'] }}[{{ $imagem }}]" class="custom-control-input" value="{{ $indexImg }}">
                                                                            <label class="custom-control-label" for="imagemCheckbox{{ $dataPost['id'] }}_{{ $indexImg }}">
                                                                                <img src="{{ $imagem }}" alt="Imagem {{ $indexImg + 1 }}" class="img-thumbnail" style="height: 100px; width: 100px">
                                                                            </label>
                                                                        </div>
                                                                        @if(($indexImg + 1) % 3 == 0)
                                                                            <br>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
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
                                                            <button type="button" id="btn-close{{ $dataPost['id'] }}" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                                            <button type="submit" class="btn btn-primary">Editar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td>{{ $dataPost['titulo'] }}</td>
                                        <td>
                                            @if(count($dataPost['url_imagens']) === 1)
                                                <img src="{{ $dataPost['url_imagens'][0] }}" alt="Imagem postada" style="width: 100px;">
                                            @else
                                                <div id="carouselExample{{ $index }}" class="carousel slide" style="width: 100px; height: 100px">
                                                    <ol class="carousel-indicators">
                                                        @foreach($dataPost['url_imagens'] as $key => $imagem)
                                                            <li data-target="#carouselExample{{ $index }}" data-slide-to="{{ $key }}" class="@if($key === 0) active @endif"></li>
                                                        @endforeach
                                                    </ol>
                                                    <div class="carousel-inner">
                                                        @foreach($dataPost['url_imagens'] as $key => $imagem)
                                                            <div class="carousel-item @if($key === 0) active @endif">
                                                                <img src="{{ $imagem }}" class="d-block w-100" alt="Imagem postada">
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
                                            @endif
                                        </td>
                                        <td>{{ $dataPost['descricao'] }}</td>
                                        <td>{{ $dataPost['likes'] }}</td>
                                        <td>{{ $dataPost['data_criacao'] }}</td>
                                        <td>{{ $dataPost['data_edicao'] }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if(count($dataPost['url_imagens']) === 1)
                                                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#lightboxModalOneImg{{ $index }}">Ver Imagens</a>
                                                    <div class="modal fade" id="lightboxModalOneImg{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="lightboxModalLabel{{ $index }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-body">
                                                                    <img src="{{ $dataPost['url_imagens'][0] }}" alt="Imagem postada" style="width: 100%; height: auto">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#lightboxModalMoreImgs{{ $index }}">Ver Imagem</a>
                                                    <div class="modal fade" id="lightboxModalMoreImgs{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="lightboxModalLabel{{ $index }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-body">
                                                                    <div id="carouselLightbox{{ $index }}" class="carousel slide" data-ride="carousel">
                                                                        <ol class="carousel-indicators">
                                                                            @foreach($dataPost['url_imagens'] as $key => $imagem)
                                                                                <li data-target="#carouselLightbox{{ $index }}" data-slide-to="{{ $key }}" class="@if($key === 0) active @endif"></li>
                                                                            @endforeach
                                                                        </ol>
                                                                        <div class="carousel-inner">
                                                                            @foreach($dataPost['url_imagens'] as $key => $imagem)
                                                                                <div class="carousel-item @if($key === 0) active @endif">
                                                                                    <img src="{{ $imagem }}" class="d-block w-100" alt="Imagem postada">
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                        <a class="carousel-control-prev" href="#carouselLightbox{{ $index }}" role="button" data-slide="prev">
                                                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                            <span class="sr-only">Anterior</span>
                                                                        </a>
                                                                        <a class="carousel-control-next" href="#carouselLightbox{{ $index }}" role="button" data-slide="next">
                                                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                            <span class="sr-only">Próximo</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <button href="#" class="btn btn-secondary edit-button" data-user-id="{{ $dataPost['id'] }}" data-toggle="modal" data-target="#exampleModalEdit_{{ $dataPost['id'] }}" onclick="openEditModalPost('{{ $dataPost['id'] }}')">Editar</button>
                                                <button class="btn btn-danger" onclick="deleteUserPost('{{ $user_id }}', '{{ $dataPost['id'] }}')">Deletar</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8">Nenhum dado disponível</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

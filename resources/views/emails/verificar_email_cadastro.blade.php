<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Verificação de cadastro</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                margin: 0;
                padding: 20px;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                background: #f9f9f9;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            h1 {
                color: #333;
            }
            p {
                color: #666;
            }
            .button {
                display: inline-block;
                padding: 10px 20px;
                margin-top: 20px;
                background-color: #007bff;
                color: #fff;
                text-decoration: none;
                border-radius: 4px;
            }
            .container a.button {
                color: #ffffff !important;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Verificação de cadastro</h1>
            <p>
                Clique no link abaixo para fazer seu cadastro.<br>
                Ele possui validade de 24 horas!<br>
            </p>
            <a href="{{ route('cadastrar.usuario', ['token' => $token, ]) }}" class="button">Fazer cadastro</a>
        </div>
    </body>
</html>

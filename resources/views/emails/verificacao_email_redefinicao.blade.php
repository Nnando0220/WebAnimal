<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Redefinição de E-mail</title>
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
            <h1>Redefinir E-mail</h1>
            <p>
                Você solicitou a redefinição de E-mail. Clique no link abaixo para redefinir seu E-mail.<br>
                Ele possui validade de 24 horas!<br>
            </p>
            <a href="{{ route('pagina.redefinir.email', ['token' => $token, ]) }}" class="button">Redefinir E-mail</a>
        </div>
    </body>
</html>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Senha Alterada com Sucesso</title>
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
            <h1>Senha Alterada com Sucesso!</h1>
            <p>Sua senha foi alterada recentemente.</p>
            <p>Se você realizou esta alteração, ignore este e-mail.</p>
            <a href="{{ route('login.cadastro') }}" class="button">Visite nosso site</a>
            <p>Caso não tenha alterado sua senha, entre em contato conosco imediatamente.</p>
            <a href="{{ route('pagina.contato.sobre') }}" class="button">Contato / Suporte</a>
        </div>
    </body>
</html>

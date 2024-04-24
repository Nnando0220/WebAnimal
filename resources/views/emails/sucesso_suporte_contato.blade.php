<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Contato bem-sucedido</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
            }
            .container {
                max-width: 600px;
                margin: 20px auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            h1 {
                color: #4caf50;
            }
            p {
                color: #333;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Contato bem-sucedido!</h1>
            <p>Obrigado por entrar em contato. Recebemos suas informações com sucesso. Entraremos em contato em breve.</p>

            <p>Detalhes do contato:</p>
            <ul>
                <li><strong>Nome:</strong> {{ $data['nome'] }}</li>
                <li><strong>Email:</strong> {{ $data['email'] }}</li>
                <li><strong>Mensagem:</strong> {{ $data['mensagem'] }}</li>
                <li><strong>Opção Escolhida:</strong> {{ $data['opcoes'] }}</li>
            </ul>

            <p>Atenciosamente,<br> WebAnimal</p>
        </div>
    </body>
</html>

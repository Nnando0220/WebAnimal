<!DOCTYPE html>
<html>
<head>
    <title>Interacao Suporte Contato</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            color: #333;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        li strong {
            display: inline-block;
            width: 120px;
            font-weight: bold;
            color: #555;
        }
    </style>
</head>
    <body>
        <h1>Interacao Suporte Contato</h1>

        <p>Detalhes da interacao:</p>

        <ul>
            <li><strong>Nome:</strong> {{ $data['nome'] }}</li>
            <li><strong>Email:</strong> {{ $data['email'] }}</li>
            <li><strong>Mensagem:</strong> {{ $data['mensagem'] }}</li>
            <li><strong>Opção Escolhida:</strong> {{ $data['opcoes'] }}</li>
        </ul>
    </body>
</html>

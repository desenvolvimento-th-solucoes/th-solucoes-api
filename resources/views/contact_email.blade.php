<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>E-mail de Contato</title>
    <link rel="stylesheet" href="../css/email.css">
</head>
<body>
    <div class="container">
        <div class="top">
            <h1>E-mail de Contato</h1>
        </div>
        <div class="middle">
            <h2 class="title">Prezado(a) Colaborador,</h2>
            <p>Espero que esta mensagem encontre você bem. Gostaríamos de informar que um usuário entrou em contato conosco através do nosso serviço, e estamos encaminhando a mensagem para a sua atenção.</p>
            <p>Aqui estão os detalhes do contato:</p>
            <ul>
                <li>NOME: {{ $name ?? null }}</li>
                <li>ENDEREÇIO DE E-MAIL: {{ $email ?? null }}</li>
            </ul>
            <div class="body">
                <p>Resumo da Mensagem: {{ $body ?? null }}</p>
                <p>Pedimos que você tome as medidas apropriadas para responder a esta mensagem o mais breve possível. Se necessário, entre em contato diretamente com o usuário usando o endereço de e-mail fornecido acima.</p>
                <p>Agradecemos pela atenção e pelo suporte contínuo ao nosso serviço. Se você tiver alguma dúvida ou precisar de assistência adicional, não hesite em entrar em contato conosco.</p>
                <p>Atenciosamente, Suporte TH Soluções.</p>
            </div>
        </div>
    </div>
</body>
</html>
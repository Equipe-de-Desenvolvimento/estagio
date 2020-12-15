<html>
    <head>
        <style>
            a{
                font-family: arial;
                text-decoration: none;
                text-align: center;
            }
            body{
                text-align: center;
            }
        </style>
        <meta charset="utf-8">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <title>Tela de Confirmação </title>
    </head>
    <body>
        <h1 align="center">Tela de Confirmação</h1> 
            <div   style="text-align: center;" > 
                <h2><?= @$paciente[0]->paciente; ?></h2>
                <a  class=""    href="<?= base_url(); ?>ambulatorio/exame/whatsappgravarconfirmacao/<?= $agenda_exame_id ?>/<?= $paciente_id ?>" ><button class="btn btn-success">Clique aqui para confirmar </button></a>
            </div>  
    </body>
</html>


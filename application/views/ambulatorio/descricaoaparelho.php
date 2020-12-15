<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Descrição</title>
        
        <style>
            body{
                font-family: arial;
                background-color: silver;
            }
        </style>
    </head>
    <body>
        <form method="post" action="<?= base_url()?>ambulatorio/exame/alterarobsaparelho">
        <table border="0" width="100%">
            <tr>
                <td>Descrição</td>
            </tr>
            <tr>
              <td><textarea name="descricao" id="descricao" style="width: 100%;  " placeholder="Descrição" required=""><?= @$obj[0]->descricao_fila; ?></textarea></td>
            <input type="hidden" name="aparelho_gasto_sala_id"  id="aparelho_gasto_sala_id" value="<?= @$obj[0]->aparelho_gasto_sala_id ;?>">
            </tr>
            <tr>
                <td><input type="submit" name="Enviar" value="Enviar"></td>
            </tr>
        </table>
     </form>
    </body>
</html>

<html>
    <head>
        <meta charset="utf-8">
        <title>Anotações</title>
        <style>
            body{
                background-color: silver;
            }
            textarea{
                width: 100%;
               
            }
        </style>
    </head>
    <body>
        <form action="<?= base_url() ?>internacao/internacao/gravaranotacoesfarmacia/<?= $lista[0]->internacao_id; ?>" method="post"> 
        <table width="100%" border="0" >
            <tr>
                <td>Anotações</td>
            </tr>
            <tr>
                <td > <textarea name="anotacoes" id="anotacoes" ><?= @$lista[0]->anotacoes; ?></textarea></td>
            </tr>
              <tr>
                  <td > <input type="submit" value="Enviar" > </td>
            </tr>
        </table>
            <input type="hidden" name="internacao_anotacoes_id" value="<?= @$lista[0]->internacao_anotacoes_id; ?>">
        </form>
    </body>
</html>

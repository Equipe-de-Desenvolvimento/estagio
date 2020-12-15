<html>
    <head>
        <meta charset="utf-8">
        <title>observacao</title>
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
        <form action="<?= base_url() ?>internacao/internacao/gravarobservacaomedicamento" method="post"> 
        <table width="100%" border="0" >
            <tr>
                <td>Observação</td>
            </tr>
            <tr  >
                <td > <textarea name="observacao" id="observacao" ><?= @$lista[0]->observacao; ?></textarea></td>
            </tr>
              <tr  >
                  <td > <input type="submit" value="Enviar" > </td>
            </tr>
        </table>
            <input type="hidden" name="internacao_prescricao_id" value="<?= $internacao_prescricao_id; ?>">
        
        </form>
    </body>
</html>

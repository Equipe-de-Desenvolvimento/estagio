<html>
    <head>
        <title>Obs</title>
        <style>
            body{
                background-color:silver;
            }
        </style>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        
    </head>
    <body>
        <form method="post" action="<?= base_url()?>ambulatorio/guia/gravartransformaorcamentoTCD/<?= $orcamento_id; ?>">
            <table>
                <tr>
                    <td>Observação</td>
                </tr>
                <tr>
                    <td>
                        
                        <textarea name="obs" id="obs" rows="13" cols="59" ></textarea>
                    </td>
                </tr>
                <tr>
                    <td><button type="submit">Enviar</button></td>
                </tr>
            </table>
        </form>

    </body>
</html>

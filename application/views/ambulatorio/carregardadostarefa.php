<html>
    <head>
        <title></title>
        <meta charset="utf-8">
        <style>
            body{
                background-color: silver;
            }h3{
                font-size: 98%;
            }
        </style>
    </head>
    <body>

        <table border=1 cellspacing=0 cellpadding=2 bordercolor="666633"  >
            <tr>
                <td>Paciente</td>
                <td><?= $paciente[0]->paciente ?></td>
            </tr>            
            <tr>
                <td> Médico Responsável </td>
                <td><?= $paciente[0]->medico_responsavel; ?></td>
            </tr>
            <tr>
                <td> Data </td>
                <td><?= date('d/m/Y', strtotime($paciente[0]->data)); ?></td>
            </tr>
            <tr>
                <td> Status </td>
                <td><?= $paciente[0]->status; ?></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;"> Descrição </td>
              
            </tr>
            <tr>
                <td colspan="2" ><div style=" word-wrap: break-word;
   max-width: 480px;"> <?= $paciente[0]->observacao; ?></div> </td>
            </tr>
        </table>
        
        <h3><a href="#">Vizualizar Arquivos </a></h3>
        <div>
            <table> 
                    <tr>                       
                        <?
                        $paciente_id = $paciente[0]->paciente_id;
                        $tarefa_medico_id = $paciente[0]->tarefa_medico_id;
                        $arquivo_pasta = directory_map("./upload/arquivostarefa/$paciente_id/$tarefa_medico_id");
                        if ($arquivo_pasta != false) {
                            sort($arquivo_pasta);
                        }
                   
                        $i = 0;
                        if ($arquivo_pasta != false) {
                            foreach ($arquivo_pasta as $value) {
                                $i++;
                                ?> 
                                <td width="10px"><img  width="50px" height="50px" onclick="javascript:window.open('<?= base_url() . "upload/arquivostarefa/" . $paciente_id . "/" . $tarefa_medico_id . "/" . $value ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');" src="<?= base_url() . "img/paste_on.gif" ?>"><br><? echo substr($value, 0, 10) ?><br></td>
                                <?
                                if ($i == 8) {
                                    ?>
                                <!--</tr><tr>-->
                                    <?
                                }
                            }
                        }
                        ?>
                    </tr>
                   
            </table>
        </div> <!-- Final da DIV content -->
 

    </body>
</html>

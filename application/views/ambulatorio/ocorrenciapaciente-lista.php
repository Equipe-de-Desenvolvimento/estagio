 
<html>
    <head>
        <title>Ocorrência</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="<?= base_url() ?>css/estilo.css" rel="stylesheet" type="text/css" /> 
        <link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
        <style>
            thead tr td{
                font-size:15px;
            }
        </style>
    </head>
    <body>
        <table BORDER RULES=rows width="100%">
            <thead>
             <tr>
                <td>Titulo</td>
                <td>Situação</td>
                <td>Data/Hora</td>
                <td>Responsável</td>
             </tr>
             </thead>
             <tbody>
                <?php 
                    foreach($ocorrencias as $item){
                        ?>
                    <tr style="cursor:pointer;background-color: #dff0d8;" onclick="javascript:window.location.href = '<?= base_url() . "ambulatorio/laudo/responderocorrencia/" . $item->atendimento_ocorrencia_id; ?>';"> 
                        <td><?=   wordwrap( $item->assunto, 40, "<br />\n",true)?></td>
                        <td><?=  $item->situacao; ?></td>
                        <td><?=  date('d/m/Y H:i:s',strtotime($item->data_cadastro)); ?></td>
                        <td><?=  $item->responsavel; ?></td> 
                    </tr>
                <?
                }
                ?>
            </tbody>
        </table>
    </body>
</html>

<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style>
            body{
                background-color: silver;
            }    
        </style>
    </head>
    <body>
        <table border="1"  cellspacing=0 cellpadding=2 width="100%">
            <tr>
                <th>Operador Cadastro</th>
                <th>Data Cadastro</th>
                <th>Operador Atualização</th>
                <th>Data Atualização</th> 
                <?php foreach($agenda as $item){?>
                    <tr>
                        <td><?= $item->op_cadastro; ?></td>
                        <td><?= date('d/m/Y H:i:s',strtotime($item->data_cadastro)); ?></td>
                        <td><?= $item->op_atualizacao; ?></td>
                        <td><?
                         if($item->data_atualizacao != ""){
                            echo date('d/m/Y  H:i:s',strtotime($item->data_atualizacao)); 
                          }
                        ?></td>
                    </tr>
                <?php }?>
            </tr>
        </table>
    </body>
</html>

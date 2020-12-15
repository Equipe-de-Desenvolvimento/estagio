<html>
    <head>
        <title>impresssao</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style>
            td{
               font-family: arial;
               font-size: 13px;
            }
            th{
               font-family: arial;
               font-size: 14px;
            }
        </style>
    </head>
    <body>
        <table   border=1 cellspacing=0 cellpadding=2  width="100%">
            <tr>
                <th>Procedimento</th>
                <th>Valor Unit.</th>
                <th>Quantidade</th>
                <th>Valor total</th>
            </tr>
            <?php 
            $valor_total = 0;
            
              foreach($procedimentos as $item){
                  $valor_total += $item->valor_tcd * $item->quantidade;
                  ?>
                <tr>
                    <td><?= $item->procedimento; ?></td>
                    <td>R$ <?= number_format($item->valor_tcd, 2, ',', '.') ; ?></td>
                    <td><?= $item->quantidade; ?></td>
                    <td>R$ <?= number_format($item->valor_tcd * $item->quantidade, 2, ',', '.') ; ?></td>
                </tr> 
            <?}?>
                
                <tr> 
                    <th colspan="3">Valor Geral</th>
                    <th>R$ <?= number_format($valor_total, 2, ',', '.'); ?></th>
                </tr>
        </table>
    </body>
</html>

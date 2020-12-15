<html>
    <head>
        <title>Relatório</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style>
            td{
                font-size: 12px;
                font-family: arial;
                padding: 4px;
            }
            th{
                font-size: 13px;
                font-family: arial;
                 
            }
        </style>
    </head>
    <body>
        <h2>Relatório TCD</h2>
        <h3>Período: <?= $txtdata_inicio; ?> a <?= $txtdata_fim; ?> </h3>
        <h3>Empresa: <?= (count($empresa) > 0) ? $empresa[0]->nome: "TODOS"; ?></h3>
        <hr>
        
        <?php if(count($relatorio) > 0){?>
        <table   cellspacing=0 cellpadding=2  width="100%">
            <thead>
            <tr>
                <th>Paciente</th>
                <th width="84px;">Valor Total</th>
                <th width="84px;">Valor TCD</th>
                <th>Operador Cadastro</th>
                <th width="133px;">Data Cadastro</th>
                <th  width="140px;">Empresa</th>
                <th  width="91px;">Faturado</th>
                <th  width="91px;">Valor Restante</th>
                <th>Observação</th>
            </tr>
            </thead>
            <tbody >
            <?php foreach($relatorio as $item){ 
                 $procedimentos = $this->guia->listarprocedimentostcd($item->ambulatorio_orcamento_item_array);  
            ?> 
                <tr>
                    <td><?= $item->paciente; ?> </td>
                    <td>R$ <?= number_format($item->valor,2,",","."); ?> </td>
                    <td>R$ <?= number_format($item->valor_tcd,2,",","."); ?>  </td>
                    <td><?= $item->op_cadastro; ?>  </td>
                    <td><?= date('d/m/Y H:i:s',strtotime($item->data_cadastro)); ?>  </td>
                    <td><?= $item->empresa; ?>  </td>
                    <td><?= ($item->confirmado == "t") ? "Faturado" : ""; ?> </td>
                    <? if($item->confirmado == "t" || ($item->confirmado == "t" && $item->valor_restante == 0.00)){?>
                        <td>QUITADO</td>
                    <?}else{?>
                        <td><?= $item->valor_restante; ?>  </td>
                    <?}?>
                    <td><?= $item->observacao; ?> </td>
                </tr> 
               <?php  foreach($procedimentos as $value){ ?>
                <tr> 
                     <td colspan="1"> </td>
                    <td colspan="7"><?= $value->procedimento; ?></td>
                </tr>
               <? } ?>
                <tr>
                    <td colspan="9" ><hr></td>
                </tr>
          <?  }  ?>
          </tbody>
        </table>
        <?php }else{
           echo "<h3>Nenhum registro encontrado.</h3>"; 
        }
      ?>

    </body>
</html>

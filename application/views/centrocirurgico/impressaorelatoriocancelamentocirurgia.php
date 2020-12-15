<html>
    <head>
        <title>Relatório</title>
          <meta charset="utf-8">
        <style>
            tr:nth-child(2n+2) {
                background: #ccc;
                font-family: arial;
            }
            h4,th,td{
                font-family: arial;
            }
            td{
                font-size: 13px;
            }
        </style>
    </head>
    <body> 
        <?php  
        echo "<h4>Relatório Cancelamento Cirurgia</h4>";
        echo "<h4>Período: ".$_POST['txtdata_inicio']." à ".$_POST['txtdata_fim']. "</h4>";
         
        if ($_POST['convenio'] != "TODOS") {
            echo "<h4>Convênio: ".$convenio[0]->nome."</h4>";
        }else{
            echo "<h4>Convênio: TODOS<h4>";
        }
        echo "<hr>";
       if (count($relatorio) > 0) { 
        ?> 
        <table  border=0 cellspacing=0 cellpadding=2 width="100%" >
            <thead>
            <tr>
                <th style="border:1px solid black;">Paciente</th>
                <th style="border:1px solid black;">Médico Solicitante</th>
                <th style="border:1px solid black;">Operador</th>
                <th style="border:1px solid black;">Convênio</th>
                <th style="border:1px solid black;">Data Prevista</th>
                <th style="border:1px solid black;">Situação Orçamento</th>
                <th style="border:1px solid black;">Situação Convênio</th>
                <th style="border:1px solid black;">Data Exclusão</th>
                <th style="border:1px solid black;">Operador Exclusão</th>
            </tr>
            </thead>
            <tbody>
                
            <?php 
            $status_anterior = "";
            $status="";
            foreach($relatorio as $item){
                
                if ($status_anterior != $item->excluido) {
                    if ($item->excluido == 't') {
                        $status = "Excluidos";
                    }else{
                        $status = "Ativos";
                        
                    }
                    echo "<thead><tr><td ><h3>".$status."</h3></td></tr></thead>";
                }
                $status_anterior = $item->excluido;
                  $situacao = '';
                   if ($item->orcamento == 't') {
                     if ($item->situacao == 'ENCAMINHADO_CONVENIO') {
                            $situacao = "<font color='blue'>ENCAMINHADO PARA O CONVÊNIO";
                     } elseif ($item->situacao == 'GUIA_FEITA') {
                                    $situacao = "<font color='green'>GUIA FEITA";
                     } elseif ($item->situacao == 'ENCAMINHADO_PACIENTE') {
                                     $situacao = "<font color='green'>ENCAMINHADO PARA O PACIENTE";
                     } elseif ($item->situacao == 'ORCAMENTO_COMPLETO') {
                                    $situacao = "<font color='green'>ORÇAMENTO COMPLETO";
                     } elseif ($item->situacao == 'ORCAMENTO_INCOMPLETO') {
                                    $situacao = "<font color='blue'>ORÇAMENTO EM ABERTO";
                     } elseif ($item->situacao == 'EQUIPE_MONTADA') {
                                    $situacao = "<font color='green'>EQUIPE MONTADA";
                     } elseif ($item->situacao == 'EQUIPE_NAO_MONTADA') {
                                    $situacao = "<font color='blue'>EQUIPE EM ABERTO";
                     } elseif ($item->situacao == 'LIBERADA') {
                                    $situacao = "<font color='green'>LIBERADA";
                     } else {
                                    $situacao = "<font color='blue'>ABERTA";
                        }
                     } else {
                                $situacao = '';
                     }
                
                
            $situacao_convenio = ''; 
             if ($item->situacao_convenio == 'ENCAMINHADO_CONVENIO') {
                   $situacao_convenio = "<font color='blue'>ENCAMINHADO PARA O CONVÊNIO";
             } elseif ($item->situacao_convenio == 'GUIA_FEITA') {
                   $situacao_convenio = "<font color='green'>GUIA FEITA";
             } elseif ($item->situacao_convenio == 'ENCAMINHADO_PACIENTE') {
                  $situacao_convenio = "<font color='green'>ENCAMINHADO PARA O PACIENTE";
             } elseif ($item->situacao_convenio == 'EQUIPE_MONTADA') {
                   $situacao_convenio = "<font color='green'>EQUIPE MONTADA";
             } elseif ($item->situacao_convenio == 'EQUIPE_NAO_MONTADA') {
                  $situacao_convenio = "<font color='blue'>EQUIPE EM ABERTO";
             } elseif ($item->situacao_convenio == 'LIBERADA') {
                  $situacao_convenio = "<font color='green'>LIBERADA";
             } else {
                  $situacao_convenio = "<font color='blue'>ABERTA";
             }
                
                ?>
                <tr>
                   <td ><?php echo $item->nome; ?></td>
                   <td ><?php echo $item->medico_solicitante; ?></td>
                   <td ><?php echo $item->operador; ?></td>
                   <td ><?php echo $item->convenio; ?></td>
                   <td ><?php echo date("d/m/Y",strtotime($item->data_prevista)); ?></td>
                   <td ><?php echo $situacao; ?></td>
                   <td ><?php echo $situacao_convenio; ?></td>
                   <td ><?php 
                   if ($item->data_exclusao != "") { 
                      echo date("d/m/Y",strtotime($item->data_exclusao)); 
                   }
                   ?></td>
                   <td ><?php echo $item->operador_exclusao; ?></td>
                </tr>
                <?
            }
            
            ?>
                
            </tbody>
        </table>
        
            <?php }else{
                echo "<h3>Não há resultados para esta consulta.</h3>";
            }
?>
    </body>
</html>

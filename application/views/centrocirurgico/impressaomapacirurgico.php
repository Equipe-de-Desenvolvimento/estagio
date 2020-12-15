 
<html>
    <head>
        <meta charset="utf-8">
        <title>Relatório</title>
        <style>
            td{
                font-size: 13px;
                font-family: arial;
            }
             th{
                font-size: 14px;
                font-family: arial;
         
               
            }
            tr:nth-child(2n+2) {
                background: #ccc;
            }
            h3{
                   
                font-family: arial; 
            }
        </style>
    </head>
    <body>
        <h3>Dia: <?= date('d/m/Y', strtotime($data)); ?></h3>
        <h3>Empresa: <?= $cabecalho[0]->empresa; ?></h3>
        <hr>
        <? if(count($relatorio) > 0){?>
        <table border=1 cellspacing=0 cellpadding=2  width="100%">
            <thead>
                <tr >
                    <th>Situação</th>
                    <th>Cirurgião</th>
                    <th>Hospital</th>
                    <th>Paciente</th>
                    <th >Horario</th>
                    <th>Convênio</th>
                    <th>Procedimento</th>                  
                    <th>Anestesista</th>
                    <th>Telefone</th>
                    <th>Idade</th>
                    <th>Observação</th>
                </tr>
           </thead>
           <tbody>
            <?php 
            $qtde_cirurgia = 0;
            foreach($relatorio as $item){
                $qtde_cirurgia++;
                  if ($item->nascimento != '') {
                    $dataFuturo = date("Y-m-d");
                    $dataAtual = $item->nascimento;
                    $date_time = new DateTime($dataAtual);
                    $diff = $date_time->diff(new DateTime($dataFuturo));
                    $idade = $diff->format('%Y'); 
                  } else {
                    $idade = '';
                  }
                  $anestesista_select = $this->centrocirurgico_m->listacalendarioanestesistaautocomplete($item->solicitacao_cirurgia_id);
                  $procedimento_select = $this->centrocirurgico_m->listacalendarioprocedimentoautocomplete($item->solicitacao_cirurgia_id);
                    if ($item->autorizado == 't') {
                        $situacao = 'Autorizada';
                    } else {
                        $situacao = 'Solicitada';
                    }
                    
                    if (count($anestesista_select) > 0) {
                        $anestesista = $anestesista_select[0]->nome;
                    } else {
                        $anestesista = '';
                    }
                    if (count($procedimento_select) > 0) {
                        $procedimento = $procedimento_select[0]->nome;
                    } else {
                        $procedimento = '';
                    }
                ?>
           <tr>
               <td><?= $situacao;?></td>
               <td><?= $item->cirurgiao;?></td>
               <td><?= $item->hospital;?></td>
               <td><?= $item->nome;?></td>
               <td align='center'><?= $item->hora_prevista." as ". $item->hora_prevista_fim;?></td>
               <td><?= $item->convenio;?></td>
               <td><?= $procedimento;?></td>             
               <td><?= $anestesista; ?></td>
               <td><?if ($item->celular != "") {
                           echo  $item->celular; 
                        }
                        if ($item->telefone != "") {
                            echo " / ".$item->telefone;
                        }?></td>
               
               
               
               <td><?= $idade;?></td>
               <td><?= $item->observacao;?></td> 
           </tr>
           
           
           <?
            }
            ?>
            <tr><td colspan='3'><font size='4'><b>Qtde de Cirurgias: <?=$qtde_cirurgia?></b></font></td></tr>
           </tbody>
        </table>
        <? }else{
            echo "<h3>Nenhum resultado encontrado para essa pesquisa</h3>";
        }
?>

    </body>
</html>

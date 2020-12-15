<meta charset="UTF-8">
<title>Relatorio Manter Gastos</title>
<div class="content"> <!-- Inicio da DIV content -->
<? if (count($empresa) > 0) { ?>
        <h4><?= $empresa[0]->razao_social; ?></h4>
    <? } else { ?>
        <h4>TODAS AS CLINICAS</h4>
    <? } ?>
    <h4>
        RELATORIO GASTOS PRESCRIÇÕES
    </h4>
    <h4>PERIODO: <?= $data_inicio; ?> ate <?= $data_fim; ?></h4>

    <hr>
    <style>
        
        .background{
            background-color: #d3d3d3;
        }

    </style>
    <?

    if(count($operadores) > 0){

        $valortotalgeral = 0;
        $medicamentos = array();
    foreach($operadores as $value){
        $relatorio = $this->internacao_m->relatorioconsumoprescricao($value->operador);
        // echo '<pre>';
        // print_r($relatorio);
        // die;
        $valortotal = 0;

        if(count($relatorio) > 0){
            ?>
            <h4>Operador: <?=$relatorio[0]->operador?></h4>
                <table border="1" cellspacing=0 cellpadding=2 width="100%">
                    <thead>
                        <tr class="background">
                            <th class="tabela_header">Paciente</th>
                            <th class="tabela_header">Leito Solicitado</th>
                            <th class="tabela_header">Medicamento</th>
                            <th class="tabela_header">Qtde M.</th>
                            <th class="tabela_header">Aprazamento</th>
                            <th class="tabela_header">Dias</th>
                            <th class="tabela_header">Leito Ministrado</th>
                            <th class="tabela_header">Data Ministrada</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?

                        foreach($relatorio as $item){
                            if($item->data == '' || $item->data == NULL){
                                $data = date("d/m/Y", strtotime(str_replace('-', '/', $item->data_ministrada)));
                                $data.= ' - '.substr($item->data_ministrada, 11, 5);
                            }else{
                                $data = date("d/m/Y", strtotime(str_replace('-', '/', $item->data)));
                                $data.= ' - '.$item->horario;
                            }
                                if(@$medicamentos[$item->medicamento] == ''){
                                    $medicamentos[$item->medicamento] = $item->qtde_ministrada;
                                }else{
                                    $medicamentos[$item->medicamento] = $medicamentos[$item->medicamento] + $item->qtde_ministrada;
                                }

                                $valortotal = $valortotal + $item->qtde_ministrada;

                            ?>
                             <tr>
                                <td><?=$item->paciente?></td>
                                <td><?=$item->leito_solicitado.'<br>'.$item->enf_solicitado?></td>
                                <td><?=$item->medicamento?></td>
                                <td><?=$item->qtde_ministrada?></td>
                                <td><?=$item->aprasamento.'/'.$item->aprasamento?></td>
                                <td><?=$item->dias?></td>
                                <td><?=$item->leito.'<br>'.$item->enf?></td>
                                <td><?=$data?></td>
                             </tr>
                            <?
                        }
                        ?>
                    </tbody>

                    <tfoot>
                        <tr class="background">
                            <th colspan="6" align='left'>Consumo Total de Medicamentos:</th>
                            <th colspan="2" align='left'><?=$valortotal?></th>
                        </tr>
                    </tfoot>

                </table>
            <?
            $valortotalgeral = $valortotalgeral + $valortotal;
        }
        
    }
     echo '<hr>';
     echo '<br>';
    //  echo '<pre>';
    //  print_r($medicamentos);
    $TotalGeral = 0;
     ?>
        <table border='1'>
            <tr class="background">
            <th class="tabela_header" colspan="2">Consumo de Medicamentos:</th>
            </tr>
            <tr class="background">
            <th class="tabela_header">Medicamentos</th>
            <th class="tabela_header">Qtde</th>
            </tr>
            <? foreach($medicamentos as $key=>$value){
                $TotalGeral = $TotalGeral + $value;?>
            <tr>
            <td><?=$key?></td>
            <td><?=$value?></td>
            </tr>
            <? } ?>
            <tr class="background">
            <th class="tabela_header" colspan="2">Consumo Total: <?=$TotalGeral?></th>
            </tr>
        </table>
     <?
    } else{
        echo '<h3>Não Existe Resultados Para Essa Busca</h3>';
    }
    ?>
</div>
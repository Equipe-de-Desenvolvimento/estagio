<meta charset="UTF-8">
<title>Relatorio Manter Gastos</title>
<div class="content"> <!-- Inicio da DIV content -->
<? if (count($empresa) > 0) { ?>
        <h4><?= $empresa[0]->razao_social; ?></h4>
    <? } else { ?>
        <h4>TODAS AS CLINICAS</h4>
    <? } ?>
    <h4>
        RELATORIO MANTER GASTOS
    </h4>
    <h4>PERIODO: <?= str_replace("-", "/", date("d-m-Y", strtotime($txtdata_inicio))); ?> ate <?= str_replace("-", "/", date("d-m-Y", strtotime($txtdata_fim))); ?></h4>

    <hr>
    <style>
        
        .background{
            background-color: #d3d3d3;
        }

    </style>
    <?

    if(count($operadores) > 0){

        $valortotalgeral = 0;
    foreach($operadores as $value){
        $relatorio = $this->exame->relatoriooperadorgasto($value->operador);
        $valortotal = 0;

        if(count($relatorio) > 0){
            ?>
            <h4>Operador: <?=$relatorio[0]->operador?></h4>
                <table border="1" cellspacing=0 cellpadding=2 width="100%">
                    <thead>
                        <tr class="background">
                            <th class="tabela_header">Nome Gasto</th>
                            <th class="tabela_header">Preço</th>
                            <th class="tabela_header">Data</th>
                            <th class="tabela_header">Horario</th>
                            <th class="tabela_header">Status</th>
                            <th class="tabela_header">Observação</th>
                            <?if($_POST['gerar'] != "pdf" && $_POST['gerar'] != "planilha"){?>
                            <th class="tabela_header">Anexo</th>
                            <?}?>
                        </tr>
                    </thead>

                    <tbody>
                        <?
                        foreach($relatorio as $item){
                            $data = date("d/m/Y", strtotime(str_replace('-', '/', $item->data_gasto)));
                            $valortotal = $valortotal + $item->preco;
                            ?>
                             <tr>
                                <td><?=$item->nome?></td>
                                <td><?=number_format($item->preco, 2, ',', '.')?></td>
                                <td><?=$data?></td>
                                <td><?=$item->horario_inicial ." - ".$item->horario_final?></td>
                                <td><?=$item->status?></td>
                                <td><?=$item->observacao?></td>
                                <?if($_POST['gerar'] != "pdf" && $_POST['gerar'] != "planilha"){?>
                                <td><a  onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/anexararquivogastooperador/<?= $item->manter_gasto_id ?>', '', 'height=460, width=800, left='+(window.innerWidth-800)/2+', top='+(window.innerHeight-460)/2);" >Anexo</a></td>
                                <?}?>
                             </tr>
                            <?
                        }
                        ?>
                    </tbody>

                    <tfoot>
                        <tr class="background">
                            <th colspan="5" align='left'>Valor Total:</th>
                            <th colspan="2" align='left'><?=number_format($valortotal, 2, ',', '.')?></th>
                        </tr>
                    </tfoot>

                </table>
            <?
            $valortotalgeral = $valortotalgeral + $valortotal;
        }
        
    }
     echo '<hr>';
     echo '<br>';
     ?>
        <table border='1'>
            <tr class="background">
            <th class="tabela_header">Valor Total Geral:</th>
            <th class="tabela_header"><?=number_format($valortotalgeral, 2, ',', '.')?></th>
            </tr>
        </table>
     <?
    } else{
        echo '<h3>Não Existe Resultados Para Essa Busca</h3>';
    }
    ?>
</div>
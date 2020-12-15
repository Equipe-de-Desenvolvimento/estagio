<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<div class="content"> <!-- Inicio da DIV content -->
    <? if (count($empresa) > 0) { ?>
        <h4><?= $empresa[0]->razao_social; ?></h4>
    <? } else { ?>
        <h4>TODAS AS CLINICAS</h4>
    <? } ?>
    <h4>Convênio: <?= (@$convenios > 0) ? $convenios[0]->nome : 'TODOS'; ?></h4>
    <h4>Relatório de Faltas</h4>
    <h4>PERIODO: <?= str_replace("-", "/", date("d-m-Y", strtotime($txtdata_inicio))); ?> ate <?= str_replace("-", "/", date("d-m-Y", strtotime($txtdata_fim))); ?></h4>
    <? if (count(@$_POST['operador']) > 0 && !in_array('0', @$_POST['operador'])) { ?>
        <h4>Operador: <?
            @$cont_virgula = count($operador);

            foreach ($operador as $value) {
                echo $value->nome;
                if (@$cont_virgula <= 1) {
                    
                } else {
                    @$cont_v++;
                    if (@$cont_v == @$cont_virgula) {
                        
                    } else {
                        echo ",";
                    }
                }
            }
            ?></h4>
    <? } ?>

    <? if (count(@$_POST['operador']) == 0 || in_array('0', @$_POST['operador'])) { ?>
        <h3>Operador: TODOS</h3>
    <? } ?>
    <hr>
    <table border="1">
        <thead>
            <tr>
                <th class="tabela_header" width="400px;">Nome</th>
                <? if (@$_POST['data_mostrar'] == 'SIM') { ?>
                    <th class="tabela_header" width="150px;">Data</th>
                <? } ?>
                <? if (@$_POST['procedimento'] == 'SIM') { ?>
                    <th class="tabela_header" width="70px;">Procedimento</th>
                <? } ?>
                <th class="tabela_header" width="250px;">Cidade</th>
                <th class="tabela_header" width="290px;">Telefone</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $paciente = "";
            $contador = 0;
            if (count($relatorio) > 0) {
                $b = "";
//                var_dump($b) ; die;
                foreach ($relatorio as $item) {
                    if (substr($item->data, 5, 2) != $b) {
                        if (substr($item->data, 5, 2) == "01") {
                            $mes = 'JANEIRO';
                        }
                        if (substr($item->data, 5, 2) == "02") {
                            $mes = 'FEVEREIRO';
                        }
                        if (substr($item->data, 5, 2) == "03") {
                            $mes = 'MARÇO';
                        }
                        if (substr($item->data, 5, 2) == "04") {
                            $mes = 'ABRIL';
                        }
                        if (substr($item->data, 5, 2) == "05") {
                            $mes = 'MAIO';
                        }
                        if (substr($item->data, 5, 2) == "06") {
                            $mes = 'JUNHO';
                        }
                        if (substr($item->data, 5, 2) == "07") {
                            $mes = 'JULHO';
                        }
                        if (substr($item->data, 5, 2) == "08") {
                            $mes = 'AGOSTO';
                        }
                        if (substr($item->data, 5, 2) == "09") {
                            $mes = 'SETEMBRO';
                        }
                        if (substr($item->data, 5, 2) == "10") {
                            $mes = 'OUTUBRO';
                        }

                        if (substr($item->data, 5, 2) == "11") {
                            $mes = 'NOVEMBRO';
                        }

                        if (substr($item->data, 5, 2) == "12") {
                            $mes = 'DEZEMBRO';
                        }
                        echo "<tr>
                    <td style='text-align: center' colspan='5' ><h4>" . $mes . "</h4></td>
                    
                              </tr> ";
                    }
                    ?>
                    <tr>
                        <td  width="400px;"><?= $item->paciente ?></td>
                        <? if (@$_POST['data_mostrar'] == 'SIM') { ?>
                            <td width="150px;"><?= str_replace("-", "/", date("d-m-Y", strtotime($item->data))); ?></td>
                        <? } ?>
                        <? if (@$_POST['procedimento'] == 'SIM') { ?>
                            <td><?= $item->procedimento; ?></td>    
                        <? } ?>
                        <td width="250px;"><?= $item->cidade ?></td>
                        <td width="290px;"><?= $item->telefone ?>/ <?= $item->celular ?></td>
                    </tr>        
                    <?
                    $b = substr($item->data, 5, 2);
                    @$contaragenda{$item->operador_atualizacao} ++;
                }
            }
            ?>
        </tbody>
    </table>

</div> <!-- Final da DIV content -->



<?
if (count($relatorio) > 0) {

    if (count(@$_POST['operador']) == 0 || in_array('0', @$_POST['operador'])) {
        ?>

    <? } else { ?> 
        <br>
        <hr>
        <br> 
        <table border="2"   >
            <tr class="background">
                <th>Nome</th>
                <th>Quantidade</th>
            </tr>
            <?php
//                    
//        echo "<pre>";
//        print_r($relatorio);

            foreach ($relatorio as $item) {
                if (@$verificaat{$item->secretaria}{$item->operador_atualizacao} >= 1) {
                    
                } else {
                    ?>
                    <tr>
                        <td><?= @$item->secretaria ?></td>
                        <td><?= @$contaragenda{$item->operador_atualizacao}; ?></td>
                    </tr> 
                    <?
                    @$verificaat{$item->secretaria}{$item->operador_atualizacao} ++;
                }
            }
            ?>
        </table>
        <?
    }
}
?>


<br><br> 


<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript">



    $(function () {
        $("#accordion").accordion();
    });

</script>

<?
//echo "<pre>";
//print_r($relatorio);
?>
<!--<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">-->
<link href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css" />
<!--<script type="text/javascript" src="<?= base_url() ?>js/scripts.js" ></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>
<?
$MES = date("m");

switch ($MES) {
    case 1 : $MES = 'Janeiro';
        break;
    case 2 : $MES = 'Fevereiro';
        break;
    case 3 : $MES = 'Mar&ccedil;o';
        break;
    case 4 : $MES = 'Abril';
        break;
    case 5 : $MES = 'Maio';
        break;
    case 6 : $MES = 'Junho';
        break;
    case 7 : $MES = 'Julho';
        break;
    case 8 : $MES = 'Agosto';
        break;
    case 9 : $MES = 'Setembro';
        break;
    case 10 : $MES = 'Outubro';
        break;
    case 11 : $MES = 'Novembro';
        break;
    case 12 : $MES = 'Dezembro';
        break;
        break;
}
?>
<meta charset="UTF-8">
<div class="content"> <!-- Inicio da DIV content -->
    <? if ($medico != 0 && $recibo == 'SIM') { ?>
        <div>
            <p style="text-align: center;"><img align = 'center'  width='300px' height='150px' src="<?= base_url() . "img/cabecalho.jpg" ?>"></p>
        </div>
    <? } ?>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />

    <? if (count($empresa) > 0) { ?>
        <h4><?= $empresa[0]->razao_social; ?></h4>
    <? } else { ?>
        <h4>TODAS AS CLINICAS</h4>
    <? } ?>
    <h4>Medico Convenios</h4>
    <? $sit = ($situacao == '') ? "TODOS" : (($situacao == '0') ? 'ABERTO' : 'FINALIZADO' ); ?>
    <h4>SITUAÇÃO: <?= $sit ?></h4>
    <h4>PERIODO: <?= substr($txtdata_inicio, 8, 2) . "/" . substr($txtdata_inicio, 5, 2) . "/" . substr($txtdata_inicio, 0, 4); ?> ate <?= substr($txtdata_fim, 8, 2) . "/" . substr($txtdata_fim, 5, 2) . "/" . substr($txtdata_fim, 0, 4); ?></h4>
    <? if ($revisor == 0) { ?>
        <h4>Revisor: TODOS</h4>
    <? } else { ?>
        <h4>Revisor: <?= $revisor[0]->operador; ?></h4>
        <?
    }
    if ($medico == 0) {
        ?>
        <h4>Medico: TODOS</h4>
    <? } else { ?>
        <h4>Medico: <?= $medico[0]->operador; ?></h4>
    <? } ?>

    <? if (count(@$sala) > 0) { ?>
        <h4>SALA: <?= @$sala[0]->nome; ?></h4>
    <? } else { ?>
        <h4>TODAS AS SALAS</h4>
    <? } ?>
    <?
    if ($_POST['faturamento'] == 't') {
        $faturamento = 'Faturado';
    } elseif ($_POST['faturamento'] == 'f') {
        $faturamento = 'Não Faturado';
    } else {
        $faturamento = 'TODOS';
    }
    ?>
    <h4>FATURAMENTO: <?= $faturamento ?></h4>

    <hr>
    <?
    // echo '<pre>';
    // print_r($relatorio);
    $totalconsulta = 0;
    if ($contador > 0 || count($relatoriocirurgico) > 0 || count($relatoriohomecare) > 0 || count($relatorio) > 0 || count($relatorioProducaoImp) > 0) {
        $totalperc = 0;
        $valor_recebimento = 0;
        ?>

        <? if (count($relatorio) > 0): ?>
      <table border="1" style="  font-size:12px; width: 100%;">
                <thead>
                <tr>
                        <td colspan="10"  style=" font-size:12px; width: 100%;"><center>PRODUÇÃO AMBULATORIAL</center></td>
                </tr>
                </thead>
            </table>

            
            <?if($_POST['gerarpdf'] == 'SIM'){?>
                <table border="1">
                <thead>
                     
                <tr>
                    <th class="tabela_header"><font size="-1">Convenio</th>
                    <th class="tabela_header"><font size="-1">Nome</th>
                    <th class="tabela_header"><font size="-1">Medico</th>
                    <th class="tabela_header" width="100px;" title="Data do agendamento. Data onde o paciente foi agendado"><font size="-1">Data Agend.</th>
                    <th class="tabela_header" width="100px;" title="Data do atendimento. Data em que foi enviado da sala de espera"><font size="-1">Data Atend.</th>
                    <th class="tabela_header" width="100px;" title="Data de recebimento. Data em que o relatorio se baseia"><font size="-1">Data Receb.</th>
                    <th class="tabela_header"><font size="-1">Qtde</th>
                    <th class="tabela_header" width="220px;"><font size="-1">Procedimento</th>
                    <? if ($clinica == 'SIM') { ?>
                        <th class="tabela_header" ><font size="-1">Valor Bruto</th>
                        <th class="tabela_header" ><font size="-1">ISS</th>
                        <th class="tabela_header" ><font size="-1">Valor Liquido</th>
                    <? } ?>
                    <? if ($_POST['forma_pagamento'] == 'SIM') { ?>
                        <th class="tabela_header" ><font size="-1">F. Pagamento Cartão</th>
                        <th class="tabela_header" ><font size="-1">F. Pagamento Dinheiro</th>
                    <? } ?>
                    <? if ($clinica == 'SIM') { ?>    

                    <? } ?>
                    <th class="tabela_header" width="80px;"><font size="-1">Indice/Valor</th>
                    <th class="tabela_header" width="80px;"><font size="-1">Valor Medico</th>
                    <? if ($_POST['promotor'] == 'SIM') { ?>
                        <th class="tabela_header" width="80px;"><font size="-1">Indice/Valor Promotor</th>
                        <th class="tabela_header" width="80px;"><font size="-1">Valor Promotor</th>   
                        <th class="tabela_header" width="80px;"><font size="-1">Promotor</th>   
                    <? } ?>
                    <? if ($_POST['laboratorio'] == 'SIM') { ?>
                        <th class="tabela_header" width="80px;"><font size="-1">Indice/Valor Laboratório</th>
                        <th class="tabela_header" width="80px;"><font size="-1">Valor Laboratório</th>   
                        <th class="tabela_header" width="80px;"><font size="-1">Laboratório</th>   
                    <? } ?>

                    <? if ($mostrar_taxa == 'SIM') { ?>
                        <th class="tabela_header" ><font size="-1">Taxa Administração</th>
                    <? } ?>


                    <? if ($clinica == 'SIM') { ?>    
                        <th class="tabela_header"><font size="-1">Revisor</th>
                    <? } ?>



                    <? if ($solicitante == 'SIM') { ?>
                        <th class="tabela_header" width="80px;"><font size="-1">Solicitante</th>
                    <? } ?>
                    <? if ($_POST['tipo_desconto'] == 'SIM') { ?>
                        <th class="tabela_header" width="80px;"><font size="-1">Desconto Especial</th>
                    <? } ?>

                </tr>
                </thead>
                <tbody>
                    <?php
                    $dados = array();
                    $vlrTotalDinheiro = 0;
                    $vlrTotalCartao = 0;
                    $i = 0;
                    $valor = 0;
                    $valortotal = 0;
                    $convenio = "";
                    $y = 0;
                    $qtde = 0;
                    $qtdetotal = 0;
                    $resultado = 0;
                    $simbolopercebtual = " %";
                    $iss = 0;
                    $perc = 0;
                    $totalgeral = 0;
                    $percpromotor = 0;
                    $totalgeralpromotor = 0;
                    $totalpercpromotor = 0;
                    $perclaboratorio = 0;
                    $totalgerallaboratorio = 0;
                    $totalperclaboratorio = 0;
                    
                    $totalretorno = 0;
                    $taxaAdministracao = 0;
                    $valor_total = 0;
                    $valor_total_calculo = 0;
                    $valor_credito = 0;
                    $producao_paga = 'f';
                    $descontoTotal = 0;
                    $valorLiquidoTotal = 0;
                    $pisoMedico= 0;
                    $sem_repeti = 0;
                    $amb_antg = 1;
                    foreach ($relatorio as $item) :
                        $sem_repeti = $item->ambulatorio_laudo_id;
                        $cor = '';
                        $title_desconto = '';
                        if($sem_repeti == $amb_antg){
                            continue;
                        }
                        $i++;
                        $procedimentopercentual = $item->procedimento_convenio_id;
                        $descontoAtual = 0;
//            $medicopercentual = $item->medico_parecer1;
                        $medicopercentual = $item->operador_id;
                        if ($item->grupo != "RETORNO") {
                            $totalconsulta++;
                        } else {
                            $totalretorno++;
                        }
                        $tipo_desconto = '';

                        if ($item->tipo_desconto == 'medico') {
                            $tipo_desconto = 'Desconto com Permissão do Médico';
                        }
                        if ($item->tipo_desconto == 'clinica') {
                            $tipo_desconto = 'Desconto com Permissão da Clinica';
                        }
                        if ($item->tipo_desconto == 'medico_clinica') {
                            $tipo_desconto = 'Desconto do Médico e da Clinica';
                        }

                        $pisoMedico = $item->piso_medico;
                        
                        if ($item->producao_paga == 't') {
                            $producao_paga = 't';
                        }

                        if ($empresa_permissao[0]->faturamento_novo == 't') {
                            $descontoForma = $this->guia->listardescontoTotal($item->agenda_exames_id);
                            // var_dump($descontoForma);
                            if (count($descontoForma) > 0) {
                                $descontoTotal += $descontoForma[0]->desconto;
                                $descontoAtual = $descontoForma[0]->desconto;
                            }
                        }
    
                        
                        if ($empresa_permissao[0]->faturamento_novo == 't') {

                            if($empresa_permissao[0]->producao_por_valor == 't'){
                            

                                if($item->valores_materias_odontologia != ''){
                                    $valor_total = $item->valor_pago - $item->valores_materias_odontologia;
                                    $title_desconto = 'Descontando o Valor de '.$item->valores_materias_odontologia.' referente aos Valores do Materias da Odontologia Valor Bruto Real '.$item->valor_pago;
                                    $cor = 'red';
                                }else{
                                    $valor_total = $item->valor_pago;
                                }

                            }else{
                                //$valor_total = $item->valor_pago;
                                $valor_total = ($item->valor * $item->quantidade) - @$descontoAtual;   
                            }                
                        } else {
                            $valor_total = $item->valor_total;
                        }
    
//                        $valor_total_formas = $item->valor1 + $item->valor2 + $item->valor3 + $item->valor4;
//                        $valor_total = $valor_total_formas + $item->desconto_ajuste1 + $item->desconto_ajuste2 + $item->desconto_ajuste3 + $item->desconto_ajuste4;
                        if ($item->forma_pagamento1 != 1000 && $item->forma_pagamento2 != 1000 && $item->forma_pagamento3 != 1000 && $item->forma_pagamento4 != 1000 && $item->forma_pagamento1 != 2000 && $item->forma_pagamento2 != 2000 && $item->forma_pagamento3 != 2000 && $item->forma_pagamento4 != 2000) {
                            if ($empresa_permissao[0]->faturamento_novo == 't') {
//                                $valor_total_calculo = $item->valor_pago;
                            if($empresa_permissao[0]->producao_por_valor == 't'){

                                if($item->valores_materias_odontologia != ''){
                                    $valor_total_calculo = $item->valor_pago - $item->valores_materias_odontologia;
                                }else{
                                    $valor_total_calculo = $item->valor_pago;
                                }

                            }else{
                                //$valor_total = $item->valor_pago;
                                $valor_total_calculo = $item->valor * $item->quantidade;
                            } 
                            
                            } else {
                                $valor_total_calculo = $item->valor_total;
                            }
                        } else {
                            if ($item->forma_pagamento1 == 1000) {
                                $valorSemCreditoTotal = $item->valor2 + $item->valor3 + $item->valor4;
                                $valor_credito = $valor_credito + $item->valor1;
                            }
                            if ($item->forma_pagamento2 == 1000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor3 + $item->valor4;
                                $valor_credito = $valor_credito + $item->valor2;
                            }
                            if ($item->forma_pagamento3 == 1000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor2 + $item->valor4;
                                $valor_credito = $valor_credito + $item->valor3;
                            }
                            if ($item->forma_pagamento4 == 1000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor2 + $item->valor3;
                                $valor_credito = $valor_credito + $item->valor4;
                            }
                            if ($item->forma_pagamento1 == 2000) {
                                $valorSemCreditoTotal = $item->valor2 + $item->valor3 + $item->valor4;
                                $valor_tcd = $valor_tcd + $item->valor1;
                            }
                            if ($item->forma_pagamento2 == 2000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor3 + $item->valor4;
                                $valor_tcd = $valor_tcd + $item->valor2;
                            }
                            if ($item->forma_pagamento3 == 2000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor2 + $item->valor4;
                                $valor_tcd = $valor_tcd + $item->valor3;
                            }
                            if ($item->forma_pagamento4 == 2000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor2 + $item->valor3;
                                $valor_tcd = $valor_tcd + $item->valor4;
                            }
                            $valor_total_calculo = $valorSemCreditoTotal * $item->quantidade;
                        }

                        if ($item->tipo_desconto == 'clinica' || $item->tipo_desconto == 'medico_clinica') {
                            // @$valor_total_calculo = 0;
                            // Se o desconto for por parte da clinica, não vai ser contabilizado o valor recebido pela mesma. 
                        }
    
                        ?>
                        <tr>
                            <td><font size="-2"><?= $item->convenio; ?></td>
                            <td><font size="-2"><?= $item->paciente; ?></td>
                            <td><font size="-2"><?= $item->medico; ?></td>
                            <td><font size="-2">
                                <?
                                $modificado = "";
                                $onclick = "";
                                if ($item->data_antiga != "") {
                                    $modificado = " ** ";
                                }

                                echo $modificado,
                                substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4),
                                ($item->sala_pendente != "f") ? " (PENDENTE)" : "",
                                $modificado;
                                ?>
                            </td>
                            <td ><font size="-2"><?= date('d/m/Y', strtotime($item->data_laudo)); ?></td>
                            <td ><font size="-2">

                                <?
                                if ($empresa_permissao[0]->faturamento_novo == 't') {
                                    echo date('d/m/Y', strtotime($item->data_producao));
                                } else {
                                    ?>
                                    <?= date('d/m/Y', strtotime($item->data_producao)); ?>
                                    <?
                                }
                                ?>


                            </td>
                            <td ><font size="-2"><?= $item->quantidade; ?></td>

                            <td><font size="-2"><?= $item->procedimento; ?></td>
                            <? if ($clinica == 'SIM') { ?>

                                <td style='text-align: right;' title="<?=$title_desconto;?>"> <font size="-2" <?='color="'.$cor.'"';?>><?= number_format(@$valor_total, 2, ",", "."); ?></td>

                                <td style='text-align: right;' width="50"><font size="-2"><?= number_format($item->iss, 2, ",", "."); ?> (%)</td>
                                <?
//                                $valorLiqMed = ((float) $valor_total - ((float) $valor_total * ((float) $item->iss / 100)) - ((float) $valor_total * ((float) $item->taxa_administracao / 100))); 
                                ?>
                                <td style='text-align: right;'><font size="-2"><?= number_format(((float) $valor_total - ((float) $valor_total * ((float) $item->iss / 100))), 2, ",", "."); ?></td>
                                <?
                                $valorLiquidoTotal += $valor_total - ((float) $valor_total * ((float) $item->iss / 100));
                            }
                            if ($_POST['forma_pagamento'] == 'SIM') {
                                $vlrDinheiro = 0;
                                $vlrCartao = 0;
                                if ($item->forma_pagamento1 != 1000 && $item->forma_pagamento2 != 1000 && $item->forma_pagamento3 != 1000 && $item->forma_pagamento4 != 1000 && $item->forma_pagamento1 != 2000 && $item->forma_pagamento2 != 2000 && $item->forma_pagamento3 != 2000 && $item->forma_pagamento4 != 2000) {


                                    if ($item->cartao1 != 'f') {
                                        $vlrDinheiro += $item->valor1;
                                    } else {
                                        $vlrCartao += $item->valor1;
                                    }

                                    if ($item->cartao2 != 'f') {
                                        $vlrDinheiro += $item->valor2;
                                    } else {
                                        $vlrCartao += $item->valor2;
                                    }

                                    if ($item->cartao3 != 'f') {
                                        $vlrDinheiro += $item->valor3;
                                    } else {
                                        $vlrCartao += $item->valor3;
                                    }

                                    if ($item->cartao4 != 'f') {
                                        $vlrDinheiro += $item->valor4;
                                    } else {
                                        $vlrCartao += $item->valor4;
                                    }
                                }

                                $vlrTotalDinheiro += $vlrDinheiro;
                                $vlrTotalCartao += $vlrCartao;
                                ?>
                                <td style='text-align: right;'><font size="-2"><?= number_format($vlrDinheiro, 2, ",", "."); ?></td>
                                <td style='text-align: right;'><font size="-2"><?= number_format($vlrCartao, 2, ",", "."); ?></td>
                                <?
                            }

                            // DESCONTANDO O VALOR DO LABORATORIO
                            if ($_POST['laboratorio'] == 'SIM') {


                                if ($item->percentual_laboratorio == "t") {
                                    $simbolopercebtuallaboratorio = " %";

                                    $valorpercentuallaboratorio = $item->valor_laboratorio/* - ((float) $item->valor_laboratorio * ((float) $item->taxa_administracao / 100)) */;

                                    $perclaboratorio = $valor_total * ($valorpercentuallaboratorio / 100);
//                                    var_dump(@$empresa_permissao[0]->valor_laboratorio); die;
                                } else {
                                    $simbolopercebtuallaboratorio = "";
                                    $valorpercentuallaboratorio = $item->valor_laboratorio/* - ((float) $item->valor_laboratorio * ((float) $item->taxa_administracao / 100)) */;

//                                    $perclaboratorio = $valorpercentuallaboratorio;
                                    $perclaboratorio = $valorpercentuallaboratorio * $item->quantidade;
                                }
                                if (@$empresa_permissao[0]->valor_laboratorio == 't') {
                                    $valor_total = $valor_total - $perclaboratorio;
                                    $valor_total_calculo = $valor_total_calculo - $perclaboratorio;
                                }
                                $totalperclaboratorio = $totalperclaboratorio + $perclaboratorio;
                                $totalgerallaboratorio = $totalgerallaboratorio + $valor_total;
                            }
                            // EM CASO DE A CONDIÇÃO ABAIXO SER VERDADEIRA. O VALOR DO PROMOTOR VAI SER DESCONTADO DO MÉDICO
                            // NÃO DÁ CLINICA

                            if (@$empresa_permissao[0]->promotor_medico == 't' && $_POST['promotor'] == 'SIM') {
                                // MESMAS REGRAS ABAIXO PARA O PROMOTOR ABAIXO
//                                var_dump(@$empresa_permissao[0]->promotor_medico);
//                                die;
                                if ($item->percentual_promotor == "t") {
                                    $simbolopercebtualpromotor = " %";
                                    $valorpercentualpromotor = $item->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;

                                    $percpromotor = $valor_total * ($valorpercentualpromotor / 100);
                                } else {
                                    $simbolopercebtualpromotor = "";
                                    $valorpercentualpromotor = $item->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;

                                    $percpromotor = $valorpercentualpromotor * $item->quantidade;
                                }

                                // SE FOR PERCENTUAL, ELE CALCULA O TOTAL PELO PERCENTUAL
                                if ($item->percentual_medico == "t") {
                                    $simbolopercebtual = " %";

                                    $valorpercentualmedico = $item->valor_medico/* - ((float) $item->valor_medico * ((float) $item->taxa_administracao / 100)) */;

                                    $perc = $valor_total * ($valorpercentualmedico / 100);
                                    if ($item->valor_promotor != null) {
//                                        echo '<pre>';
                                        $perc = $perc - $percpromotor;
                                    }
                                } else {
                                    // SE FOR VALOR, É O VALOR * A QUANTIDADE
                                    $simbolopercebtual = "";
                                    $valorpercentualmedico = $item->valor_medico/* - ((float) $item->valor_medico * ((float) $item->taxa_administracao / 100)) */;

//                                    $perc = $valorpercentualmedico;

                                    $perc = $valorpercentualmedico * $item->quantidade;
                                    if ($item->valor_promotor != null) {
//                                        echo '<pre>';
                                        $perc = $perc - $percpromotor;
                                    }
                                }
//                                var_dump($item->valor_promotor);
//                                var_dump($perc);
//                                var_dump($percpromotor);
//                                die;

                                $totalperc = $totalperc + $perc;
//                                if ($item->forma_pagamento1 != 1000 && $item->forma_pagamento2 != 1000 && $item->forma_pagamento3 != 1000 && $item->forma_pagamento4 != 1000) {
                                $totalgeral = $totalgeral + $valor_total_calculo;
//                                }

                                $totalpercpromotor = $totalpercpromotor + $percpromotor;
                                $totalgeralpromotor = $totalgeralpromotor + $valor_total;
                            } else {
                                // SENÃO, VAI CONTINUAR DA FORMA QUE ERA ANTES
                                if ($item->percentual_medico == "t") {
                                    $simbolopercebtual = " %";

                                    $valorpercentualmedico = $item->valor_medico/* - ((float) $item->valor_medico * ((float) $item->taxa_administracao / 100)) */;

                                    $perc = $valor_total * ($valorpercentualmedico / 100);
                                } else {
                                    $simbolopercebtual = "";
                                    $valorpercentualmedico = $item->valor_medico/* - ((float) $item->valor_medico * ((float) $item->taxa_administracao / 100)) */;

//                                    $perc = $valorpercentualmedico;
                                    $perc = $valorpercentualmedico * $item->quantidade;
                                }

                                $totalperc = $totalperc + $perc;
//                                if ($item->forma_pagamento1 != 1000 && $item->forma_pagamento2 != 1000 && $item->forma_pagamento3 != 1000 && $item->forma_pagamento4 != 1000) {
                                $totalgeral = $totalgeral + $valor_total_calculo;
//                                }

                                if ($item->percentual_promotor == "t") {
                                    $simbolopercebtualpromotor = " %";

                                    $valorpercentualpromotor = $item->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;

                                    $percpromotor = $valor_total * ($valorpercentualpromotor / 100);
                                } else {
                                    $simbolopercebtualpromotor = "";
                                    $valorpercentualpromotor = $item->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;

//                                    $percpromotor = $valorpercentualpromotor;
                                    $percpromotor = $valorpercentualpromotor * $item->quantidade;
                                }

                                $totalpercpromotor = $totalpercpromotor + $percpromotor;
                                $totalgeralpromotor = $totalgeralpromotor + $valor_total;
                            }

                            @$tempoRecebimento[str_replace("-", "", $item->data_producao)][$item->medico_parecer1] = array(
                                "medico_nome" => @$item->medico,
                                "valor_recebimento" => @$tempoRecebimento[str_replace("-", "", $item->data_producao)][@$item->medico_parecer1]["valor_recebimento"] + $perc,
                                "data_recebimento" => $item->data_producao
                            );
                            ?>
                            <? if ($clinica == 'SIM') { ?>    

                            <? } ?>
                            <td style='text-align: right;'><font size="-2"><?= number_format($valorpercentualmedico, 2, ",", "") . $simbolopercebtual ?></td>
                            <td style='text-align: right;'><font size="-2"><?= number_format($perc, 2, ",", "."); ?></td>

                            <? if ($_POST['promotor'] == 'SIM') { ?>
                                <td style='text-align: right;'><font size="-2"><?= number_format($valorpercentualpromotor, 2, ",", "") . $simbolopercebtual ?></td>

                                <td style='text-align: right;'><font size="-2"><?= number_format($percpromotor, 2, ",", "."); ?></td>
                                <td style='text-align: left;'><font size="-2"><?= $item->indicacao ?></td>

                            <? }
                            ?>
                            <? if ($_POST['laboratorio'] == 'SIM') { ?>
                                <td style='text-align: right;'><font size="-2"><?= number_format($valorpercentuallaboratorio, 2, ",", "") . $simbolopercebtualpromotor ?></td>

                                <td style='text-align: right;'><font size="-2"><?= number_format($perclaboratorio, 2, ",", "."); ?></td>
                                <td style='text-align: left;'><font size="-2"><?= $item->laboratorio ?></td>

                            <? }
                            ?> 
                            <?
                            if($item->taxaadm_perc == 'SIM'){
                                $taxaAdm = ((float) $perc * ((float) $item->taxa_administracao / 100));
                            }else if($item->taxaadm_perc == 'NAO'){
                                $taxaAdm = $item->taxa_administracao;
                            }else{
                                $taxaAdm = 0;
                                $taxaAdministracao = $item->taxa_administracao;
                            }
                            
                            $taxaAdmStr = number_format($taxaAdm, 2, ',', '.');

                            if($item->taxaadm_perc == 'SIM'){
                                $taxaAdmStr = $item->taxa_administracao . " (%)";
                            }
                            if($item->taxaadm_perc == 'FIXO'){
                                $taxaAdmStr =  "FIXO";
                            }
                            ?>

                            <? if ($mostrar_taxa == 'SIM') { ?>
                                <td style='text-align: right;' width="50"><font size="-2"><?= $taxaAdmStr ?></td>
                                <?                                
                               if (@$item->valor_medico > 0) {
                                    @$taxaAdministracao += $taxaAdm;
                                }
                                ?>
                            <? } ?>
                            <? if ($clinica == 'SIM') { ?>    
                                <td><font size="-2"><?= $item->revisor; ?></td>
                            <? } ?>

                            <? if ($solicitante == 'SIM') { ?>
                                <td style='text-align: right;'><font size="-2"><?= $item->medicosolicitante; ?></td>
                            <? } ?>
                            <? if ($_POST['tipo_desconto'] == 'SIM') { ?>
                                <td style='text-align: right;'><font size="-2"><?= $tipo_desconto; ?></td>
                            <? } ?> 
                            <?
                            if ($empresa_permissao[0]->faturamento_novo == "t") {
                                ?> 
                                <!--<td style='text-align: right;'><font size="-2"><?= $item->observacao_pg_outro_dia; ?></td>-->
                                <?
                            } else {
                                
                            }
                            ?>
                        </tr>


                        <?php
                        $qtdetotal = $qtdetotal + $item->quantidade;
                        $amb_antg = $item->ambulatorio_laudo_id;
                    endforeach;

                    if ($_POST['somarcredito'] == 'SIM') {
                        $totalgeral = $totalgeral + $valor_credito;
                    }
//                    var_dump(@$totalgeral);
                    if ($_POST['promotor'] == 'SIM') {
//                        if (@$empresa_permissao[0]->promotor_medico == 't') {
                        $resultadototalgeral = $totalgeral - $totalperc - $totalpercpromotor;
//                            $totalperc = $totalperc - $totalpercpromotor;
//                        } else {
//                        $resultadototalgeral = $totalgeral - $totalperc - $totalpercpromotor;
//                        }
                    } else {
                        if (@$empresa_permissao[0]->valor_laboratorio == 't') {
                            $resultadototalgeral = $totalgeral - $totalperc;
                        } else {
                            $resultadototalgeral = $totalgeral - $totalperc - $totalperclaboratorio;
                        }
                    }
                    if ($empresa_permissao[0]->faturamento_novo == 't') {
                        $resultadototalgeral -= $descontoTotal;
                    }
                    ?>
                    <tr>
                        <td ><font size="-1">TOTAL</td>
                        <td  colspan="2" style='text-align: right;'><font size="-1">Nr. Procedimentos: <?= $qtdetotal; ?></td>
                        <? if ($clinica == 'SIM') { ?>
                            <? if ($_POST['somarcredito'] == 'SIM') { ?>
                                <td colspan="2" style='text-align: right;'><font size="-1">TOTAL CLINICA: <?= number_format($resultadototalgeral, 2, ",", "."); ?></td>
                                <td colspan="2" style='text-align: right;'><font size="-1">CRÉDITOS: <?= number_format($valor_credito, 2, ",", "."); ?></td>
                            <? } else { ?>
                                <td colspan="4" style='text-align: right;'><font size="-1">TOTAL CLINICA: <?= number_format($resultadototalgeral, 2, ",", "."); ?></td>
                            <? } ?>
                        <? } else { ?>
                            <td colspan="4" style='text-align: right;'><font size="-1">&nbsp;</td>
                        <? } ?>
                        <!--                            As váriaveis estão invertidas-->
                        <? if ($_POST['forma_pagamento'] == 'SIM') { ?>
                            <td colspan="2" style='text-align: right;'><font size="-1">T. CARTÃO: <?= number_format($vlrTotalDinheiro, 2, ",", "."); ?></td>
                            <td colspan="3" style='text-align: right;'><font size="-1">T. DINHEIRO: <?= number_format($vlrTotalCartao, 2, ",", "."); ?></td>
                        <? } ?>
                        <? if ($_POST['promotor'] == 'SIM') { ?>
                            <td colspan="2" style='text-align: right;'><font size="-1">TOTAL PROMOTOR: <?= number_format($totalpercpromotor, 2, ",", "."); ?></td>
                            <td colspan="2" style='text-align: right;'
                                title="Diferença entre o valor do médico e o valor do promotor."><font size="-1">DIFERENÇA: <?= number_format($totalperc - $totalpercpromotor, 2, ",", "."); ?></td>
                            <? }
                            ?>
                            <? if ($_POST['laboratorio'] == 'SIM') { ?>
                            <td colspan="2" style='text-align: right;'><font size="-1">TOTAL LABORATÓRIO: <?= number_format($totalperclaboratorio, 2, ",", "."); ?></td>
                        <? }
                        ?>
                        <td colspan="2" style='text-align: right;'><font size="-1">TOTAL MEDICO: <?= number_format($totalperc, 2, ",", "."); ?></td>
                        <td colspan="3" style='text-align: right;'><font size="-1">TOTAL LÍQUIDO: <?= number_format($valorLiquidoTotal - $totalperc, 2, ",", "."); ?></td>
                    </tr>
                </tbody>
            </table>

            <? if ($empresa_permissao[0]->faturamento_novo == 't') { ?>
                <br>
                <br>
                <table border="1">
                    <tr>
                        <td>
                            TOTAL BRUTO 
                        </td>
                        <td>
                            <?= number_format($resultadototalgeral + $totalperc + $descontoTotal, 2, ",", "."); ?>

                        </td>
                    </tr>

                    <tr>
                        <td>
                            TOTAL DESCONTO
                        </td>
                        <td>
                            <?= number_format($descontoTotal, 2, ",", "."); ?>         
                        </td>
                    </tr>
                    <tr>
                        <td>
                            TOTAL LÍQUIDO 
                        </td>
                        <td>
                            <?= number_format($resultadototalgeral + $totalperc, 2, ",", "."); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            PRODUÇÃO MÉDICA
                        </td>
                        <td>
                            <?= number_format($totalperc, 2, ",", "."); ?> 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            SALDO
                        </td>
                        <td>
                            <?= number_format($resultadototalgeral, 2, ",", "."); ?>          
                        </td>
                    </tr>
                </table>
                <br>
                <br>
            <? } ?>


                
            <?}?>
            <table border="1">
                <thead>
                     
                <tr>
                    <th class="tabela_header"><font size="-1">Convenio</th>
                    <th class="tabela_header"><font size="-1">Nome</th>
                    <th class="tabela_header"><font size="-1">Medico</th>
                    <th class="tabela_header" width="100px;" title="Data do agendamento. Data onde o paciente foi agendado"><font size="-1">Data Agend.</th>
                    <th class="tabela_header" width="100px;" title="Data do atendimento. Data em que foi enviado da sala de espera"><font size="-1">Data Atend.</th>
                    <th class="tabela_header" width="100px;" title="Data de recebimento. Data em que o relatorio se baseia"><font size="-1">Data Receb.</th>
                    <th class="tabela_header"><font size="-1">Qtde</th>
                    <th class="tabela_header" width="220px;"><font size="-1">Procedimento</th>
                    <? if ($clinica == 'SIM') { ?>
                        <th class="tabela_header" ><font size="-1">Valor Bruto</th>
                        <th class="tabela_header" ><font size="-1">ISS</th>
                        <th class="tabela_header" ><font size="-1">Valor Liquido</th>
                    <? } ?>
                    <? if ($_POST['forma_pagamento'] == 'SIM') { ?>
                        <th class="tabela_header" ><font size="-1">F. Pagamento Cartão</th>
                        <th class="tabela_header" ><font size="-1">F. Pagamento Dinheiro</th>
                    <? } ?>
                    <? if ($clinica == 'SIM') { ?>    

                    <? } ?>
                    <th class="tabela_header" width="80px;"><font size="-1">Indice/Valor</th>
                    <th class="tabela_header" width="80px;"><font size="-1">Valor Medico</th>
                    <? if ($_POST['promotor'] == 'SIM') { ?>
                        <th class="tabela_header" width="80px;"><font size="-1">Indice/Valor Promotor</th>
                        <th class="tabela_header" width="80px;"><font size="-1">Valor Promotor</th>   
                        <th class="tabela_header" width="80px;"><font size="-1">Promotor</th>   
                    <? } ?>
                    <? if ($_POST['laboratorio'] == 'SIM') { ?>
                        <th class="tabela_header" width="80px;"><font size="-1">Indice/Valor Laboratório</th>
                        <th class="tabela_header" width="80px;"><font size="-1">Valor Laboratório</th>   
                        <th class="tabela_header" width="80px;"><font size="-1">Laboratório</th>   
                    <? } ?>

                    <? if ($mostrar_taxa == 'SIM') { ?>
                        <th class="tabela_header" ><font size="-1">Taxa Administração</th>
                    <? } ?>


                    <? if ($clinica == 'SIM') { ?>    
                        <th class="tabela_header"><font size="-1">Revisor</th>
                    <? } ?>



                    <? if ($solicitante == 'SIM') { ?>
                        <th class="tabela_header" width="80px;"><font size="-1">Solicitante</th>
                    <? } ?>
                    <? if ($_POST['tipo_desconto'] == 'SIM') { ?>
                        <th class="tabela_header" width="80px;"><font size="-1">Desconto Especial</th>
                    <? } ?>

                </tr>
                </thead>
                <tbody>
                    <?php
                    $dados = array();
                    $vlrTotalDinheiro = 0;
                    $vlrTotalCartao = 0;
                    $i = 0;
                    $valor = 0;
                    $valortotal = 0;
                    $convenio = "";
                    $y = 0;
                    $qtde = 0;
                    $qtdetotal = 0;
                    $resultado = 0;
                    $simbolopercebtual = " %";
                    $iss = 0;
                    $perc = 0;
                    $totalgeral = 0;
                    $percpromotor = 0;
                    $totalgeralpromotor = 0;
                    $totalpercpromotor = 0;
                    $perclaboratorio = 0;
                    $totalgerallaboratorio = 0;
                    $totalperclaboratorio = 0;
                    
                    $totalretorno = 0;
                    $taxaAdministracao = 0;
                    $valor_total = 0;
                    $valor_total_calculo = 0;
                    $valor_credito = 0;
                    $producao_paga = 'f';
                    $descontoTotal = 0;
                    $valorLiquidoTotal = 0;
                    $pisoMedico= 0;
                    $sem_repeti = 0;
                    $amb_antg = 1;
                    $valor_bruto = 0;
                    $valor_liquido = 0;
                    foreach ($relatorio as $item) :
                        $sem_repeti = $item->ambulatorio_laudo_id;
                        $cor = '';
                        $title_desconto = '';
                        if($sem_repeti == $amb_antg){
                            continue;
                        }
                        $i++;
                        $procedimentopercentual = $item->procedimento_convenio_id;
                        $descontoAtual = 0;
//            $medicopercentual = $item->medico_parecer1;
                        $medicopercentual = $item->operador_id;
                        if ($item->grupo != "RETORNO") {
                            $totalconsulta++;
                        } else {
                            $totalretorno++;
                        }
                        $tipo_desconto = '';

                        if ($item->tipo_desconto == 'medico') {
                            $tipo_desconto = 'Desconto com Permissão do Médico';
                        }
                        if ($item->tipo_desconto == 'clinica') {
                            $tipo_desconto = 'Desconto com Permissão da Clinica';
                        }
                        if ($item->tipo_desconto == 'medico_clinica') {
                            $tipo_desconto = 'Desconto do Médico e da Clinica';
                        }

                        $pisoMedico = $item->piso_medico;
                        
                        if ($item->producao_paga == 't') {
                            $producao_paga = 't';
                        }

                        if ($empresa_permissao[0]->faturamento_novo == 't') {
                            $descontoForma = $this->guia->listardescontoTotal($item->agenda_exames_id);
                            // var_dump($descontoForma);
                            if (count($descontoForma) > 0) {
                                $descontoTotal += $descontoForma[0]->desconto;
                                $descontoAtual = $descontoForma[0]->desconto;
                            }
                        }
    
                        
                        if ($empresa_permissao[0]->faturamento_novo == 't') {

                            if($empresa_permissao[0]->producao_por_valor == 't'){  
                                if($item->valores_materias_odontologia != ''){
                                    $valor_total = $item->valor_pago - $item->valores_materias_odontologia;
                                    $title_desconto = 'Descontando o Valor de '.$item->valores_materias_odontologia.' referente aos Valores do Materias da Odontologia Valor Bruto Real '.$item->valor_pago;
                                    $cor = 'red';
                                }else{
                                    $valor_total = $item->valor_pago;
                                }

                            }else{
                                //$valor_total = $item->valor_pago;
                                $valor_total = ($item->valor * $item->quantidade) - @$descontoAtual;   
                            }                
                        } else {
                            $valor_total = $item->valor_total;
                        }
    
//                        $valor_total_formas = $item->valor1 + $item->valor2 + $item->valor3 + $item->valor4;
//                        $valor_total = $valor_total_formas + $item->desconto_ajuste1 + $item->desconto_ajuste2 + $item->desconto_ajuste3 + $item->desconto_ajuste4;
                        if ($item->forma_pagamento1 != 1000 && $item->forma_pagamento2 != 1000 && $item->forma_pagamento3 != 1000 && $item->forma_pagamento4 != 1000 && $item->forma_pagamento1 != 2000 && $item->forma_pagamento2 != 2000 && $item->forma_pagamento3 != 2000 && $item->forma_pagamento4 != 2000) {
                            if ($empresa_permissao[0]->faturamento_novo == 't') {
//                                $valor_total_calculo = $item->valor_pago;
                            if($empresa_permissao[0]->producao_por_valor == 't'){

                                if($item->valores_materias_odontologia != ''){
                                    $valor_total_calculo = $item->valor_pago - $item->valores_materias_odontologia;
                                }else{
                                    $valor_total_calculo = $item->valor_pago;
                                }

                            }else{
                                //$valor_total = $item->valor_pago;
                                $valor_total_calculo = $item->valor * $item->quantidade;
                            } 
                            
                            } else {
                                $valor_total_calculo = $item->valor_total;
                            }
                        } else {
                            if ($item->forma_pagamento1 == 1000) {
                                $valorSemCreditoTotal = $item->valor2 + $item->valor3 + $item->valor4;
                                $valor_credito = $valor_credito + $item->valor1;
                            }
                            if ($item->forma_pagamento2 == 1000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor3 + $item->valor4;
                                $valor_credito = $valor_credito + $item->valor2;
                            }
                            if ($item->forma_pagamento3 == 1000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor2 + $item->valor4;
                                $valor_credito = $valor_credito + $item->valor3;
                            }
                            if ($item->forma_pagamento4 == 1000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor2 + $item->valor3;
                                $valor_credito = $valor_credito + $item->valor4;
                            }
                            if ($item->forma_pagamento1 == 2000) {
                                $valorSemCreditoTotal = $item->valor2 + $item->valor3 + $item->valor4;
                                $valor_tcd = $valor_tcd + $item->valor1;
                            }
                            if ($item->forma_pagamento2 == 2000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor3 + $item->valor4;
                                $valor_tcd = $valor_tcd + $item->valor2;
                            }
                            if ($item->forma_pagamento3 == 2000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor2 + $item->valor4;
                                $valor_tcd = $valor_tcd + $item->valor3;
                            }
                            if ($item->forma_pagamento4 == 2000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor2 + $item->valor3;
                                $valor_tcd = $valor_tcd + $item->valor4;
                            }
                            $valor_total_calculo = $valorSemCreditoTotal * $item->quantidade;
                        }

                        if ($item->tipo_desconto == 'clinica' || $item->tipo_desconto == 'medico_clinica') {
                            // @$valor_total_calculo = 0;
                            // Se o desconto for por parte da clinica, não vai ser contabilizado o valor recebido pela mesma. 
                        }
    $valor_bruto = $valor_total;
                        ?>
                        <tr>
                            <td><font size="-2"><?= $item->convenio; ?></td>
                            <td><font size="-2"><?= $item->paciente; ?></td>
                            <td><font size="-2"><?= $item->medico; ?></td>
                            <td><font size="-2">
                                <?
                                $modificado = "";
                                $onclick = "";
                                if ($item->data_antiga != "") {
                                    $modificado = " ** ";
                                }

                                echo $modificado,
                                substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4),
                                ($item->sala_pendente != "f") ? " (PENDENTE)" : "",
                                $modificado;
                                ?>
                            </td>
                            <td ><font size="-2"><?= date('d/m/Y', strtotime($item->data_laudo)); ?></td>
                            <td ><font size="-2">

                                <?
                                if ($empresa_permissao[0]->faturamento_novo == 't') {
                                    echo date('d/m/Y', strtotime($item->data_producao));
                                } else {
                                    ?>
                                    <?= date('d/m/Y', strtotime($item->data_producao)); ?>
                                    <?
                                }
                                ?>


                            </td>
                            <td ><font size="-2"><?= $item->quantidade; ?></td>

                            <td><font size="-2"><?= $item->procedimento; ?></td>
                            <? if ($clinica == 'SIM') { ?>

                                <td style='text-align: right;' title="<?=$title_desconto;?>"> <font size="-2" <?='color="'.$cor.'"';?>><?= @number_format(@$valor_total, 2, ",", "."); ?></td>

                                <td style='text-align: right;' width="50"><font size="-2"><?= number_format($item->iss, 2, ",", "."); ?> (%)</td>
                                <?
                                $valor_liquido = ((float) $valor_total - ((float) $valor_total * ((float) $item->iss / 100)));
//                                $valorLiqMed = ((float) $valor_total - ((float) $valor_total * ((float) $item->iss / 100)) - ((float) $valor_total * ((float) $item->taxa_administracao / 100))); 
                                ?>
                                <td style='text-align: right;'><font size="-2"><?= number_format(((float) $valor_total - ((float) $valor_total * ((float) $item->iss / 100))), 2, ",", "."); ?></td>
                                <?
                                $valorLiquidoTotal += $valor_total - ((float) $valor_total * ((float) $item->iss / 100));
                            }
                            if ($_POST['forma_pagamento'] == 'SIM') {
                                $vlrDinheiro = 0;
                                $vlrCartao = 0;
                                if ($item->forma_pagamento1 != 1000 && $item->forma_pagamento2 != 1000 && $item->forma_pagamento3 != 1000 && $item->forma_pagamento4 != 1000 && $item->forma_pagamento1 != 2000 && $item->forma_pagamento2 != 2000 && $item->forma_pagamento3 != 2000 && $item->forma_pagamento4 != 2000) {


                                    if ($item->cartao1 != 'f') {
                                        $vlrDinheiro += $item->valor1;
                                    } else {
                                        $vlrCartao += $item->valor1;
                                    }

                                    if ($item->cartao2 != 'f') {
                                        $vlrDinheiro += $item->valor2;
                                    } else {
                                        $vlrCartao += $item->valor2;
                                    }

                                    if ($item->cartao3 != 'f') {
                                        $vlrDinheiro += $item->valor3;
                                    } else {
                                        $vlrCartao += $item->valor3;
                                    }

                                    if ($item->cartao4 != 'f') {
                                        $vlrDinheiro += $item->valor4;
                                    } else {
                                        $vlrCartao += $item->valor4;
                                    }
                                }

                                $vlrTotalDinheiro += $vlrDinheiro;
                                $vlrTotalCartao += $vlrCartao;
                                ?>
                                <td style='text-align: right;'><font size="-2"><?= number_format($vlrDinheiro, 2, ",", "."); ?></td>
                                <td style='text-align: right;'><font size="-2"><?= number_format($vlrCartao, 2, ",", "."); ?></td>
                                <?
                            }

                            // DESCONTANDO O VALOR DO LABORATORIO
                            if ($_POST['laboratorio'] == 'SIM') {


                                if ($item->percentual_laboratorio == "t") {
                                    $simbolopercebtuallaboratorio = " %";

                                    $valorpercentuallaboratorio = $item->valor_laboratorio/* - ((float) $item->valor_laboratorio * ((float) $item->taxa_administracao / 100)) */;

                                    $perclaboratorio = $valor_total * ($valorpercentuallaboratorio / 100);
//                                    var_dump(@$empresa_permissao[0]->valor_laboratorio); die;
                                } else {
                                    $simbolopercebtuallaboratorio = "";
                                    $valorpercentuallaboratorio = $item->valor_laboratorio/* - ((float) $item->valor_laboratorio * ((float) $item->taxa_administracao / 100)) */;

//                                    $perclaboratorio = $valorpercentuallaboratorio;
                                    $perclaboratorio = $valorpercentuallaboratorio * $item->quantidade;
                                }
                                if (@$empresa_permissao[0]->valor_laboratorio == 't') {
                                    $valor_total = $valor_total - $perclaboratorio;
                                    $valor_total_calculo = $valor_total_calculo - $perclaboratorio;
                                }
                                $totalperclaboratorio = $totalperclaboratorio + $perclaboratorio;
                                $totalgerallaboratorio = $totalgerallaboratorio + $valor_total;
                            }
                            // EM CASO DE A CONDIÇÃO ABAIXO SER VERDADEIRA. O VALOR DO PROMOTOR VAI SER DESCONTADO DO MÉDICO
                            // NÃO DÁ CLINICA

                            if (@$empresa_permissao[0]->promotor_medico == 't' && $_POST['promotor'] == 'SIM') {
                                // MESMAS REGRAS ABAIXO PARA O PROMOTOR ABAIXO
//                                var_dump(@$empresa_permissao[0]->promotor_medico);
//                                die;
                                if ($item->percentual_promotor == "t") {
                                    $simbolopercebtualpromotor = " %";
                                    $valorpercentualpromotor = $item->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;

                                    $percpromotor = $valor_total * ($valorpercentualpromotor / 100);
                                } else {
                                    $simbolopercebtualpromotor = "";
                                    $valorpercentualpromotor = $item->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;

                                    $percpromotor = $valorpercentualpromotor * $item->quantidade;
                                }

                                // SE FOR PERCENTUAL, ELE CALCULA O TOTAL PELO PERCENTUAL
                                if ($item->percentual_medico == "t") {
                                    $simbolopercebtual = " %";

                                    $valorpercentualmedico = $item->valor_medico/* - ((float) $item->valor_medico * ((float) $item->taxa_administracao / 100)) */;

                                    $perc = $valor_total * ($valorpercentualmedico / 100);
                                    
                                    
                                    if($empresa_permissao[0]->redutor_valor_liquido == "t"){
                                        $perc = $valor_liquido * ($valorpercentualmedico / 100);
                                    }
                                    if ($item->valor_promotor != null) {
//                                        echo '<pre>';
                                        $perc = $perc - $percpromotor;
                                    }
    
                                } else {
                                    // SE FOR VALOR, É O VALOR * A QUANTIDADE
                                    $simbolopercebtual = "";
                                    $valorpercentualmedico = $item->valor_medico/* - ((float) $item->valor_medico * ((float) $item->taxa_administracao / 100)) */;

//                                    $perc = $valorpercentualmedico;

                                    $perc = $valorpercentualmedico * $item->quantidade;
    
                                    if ($item->valor_promotor != null) {
//                                        echo '<pre>';
                                        $perc = $perc - $percpromotor;
                                    }
                                }
//                                var_dump($item->valor_promotor);
//                                var_dump($perc);
//                                var_dump($percpromotor);
//                                die;

                                $totalperc = $totalperc + $perc;
//                                if ($item->forma_pagamento1 != 1000 && $item->forma_pagamento2 != 1000 && $item->forma_pagamento3 != 1000 && $item->forma_pagamento4 != 1000) {
                                $totalgeral = $totalgeral + $valor_total_calculo;
//                                }

                                $totalpercpromotor = $totalpercpromotor + $percpromotor;
                                $totalgeralpromotor = $totalgeralpromotor + $valor_total;
                            } else {
                                // SENÃO, VAI CONTINUAR DA FORMA QUE ERA ANTES
                                if ($item->percentual_medico == "t") {
                                    $simbolopercebtual = " %";

                                    $valorpercentualmedico = $item->valor_medico/* - ((float) $item->valor_medico * ((float) $item->taxa_administracao / 100)) */;

                                    $perc = $valor_total * ($valorpercentualmedico / 100);
                                    
                                    if($empresa_permissao[0]->redutor_valor_liquido == "t"){
                                        $perc = $valor_liquido * ($valorpercentualmedico / 100);
                                    }
                                    
                                } else {
                                    $simbolopercebtual = "";
                                    $valorpercentualmedico = $item->valor_medico/* - ((float) $item->valor_medico * ((float) $item->taxa_administracao / 100)) */;

//                                    $perc = $valorpercentualmedico;
                                    $perc = $valorpercentualmedico * $item->quantidade;
                                    
    
                                   
                                }

                                $totalperc = $totalperc + $perc;
//                                if ($item->forma_pagamento1 != 1000 && $item->forma_pagamento2 != 1000 && $item->forma_pagamento3 != 1000 && $item->forma_pagamento4 != 1000) {
                                $totalgeral = $totalgeral + $valor_total_calculo;
//                                }

                                if ($item->percentual_promotor == "t") {
                                    $simbolopercebtualpromotor = " %";

                                    $valorpercentualpromotor = $item->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;

                                    $percpromotor = $valor_total * ($valorpercentualpromotor / 100);
                                } else {
                                    $simbolopercebtualpromotor = "";
                                    $valorpercentualpromotor = $item->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;

//                                    $percpromotor = $valorpercentualpromotor;
                                    $percpromotor = $valorpercentualpromotor * $item->quantidade;
                                }

                                $totalpercpromotor = $totalpercpromotor + $percpromotor;
                                $totalgeralpromotor = $totalgeralpromotor + $valor_total;
                            }

                            @$tempoRecebimento[str_replace("-", "", $item->data_producao)][$item->medico_parecer1] = array(
                                "medico_nome" => @$item->medico,
                                "valor_recebimento" => @$tempoRecebimento[str_replace("-", "", $item->data_producao)][@$item->medico_parecer1]["valor_recebimento"] + $perc,
                                "data_recebimento" => $item->data_producao
                            );
                            
                            
                            
                            ?>
                            <? if ($clinica == 'SIM') { ?>    

                            <? } ?>
                            <td style='text-align: right;'><font size="-2"><?= number_format($valorpercentualmedico, 2, ",", "") . $simbolopercebtual ?></td>
                            <td style='text-align: right;'><font size="-2"><?= number_format($perc, 2, ",", "."); ?></td>

                            <? if ($_POST['promotor'] == 'SIM') { ?>
                                <td style='text-align: right;'><font size="-2"><?= number_format($valorpercentualpromotor, 2, ",", "") . $simbolopercebtual ?></td>

                                <td style='text-align: right;'><font size="-2"><?= number_format($percpromotor, 2, ",", "."); ?></td>
                                <td style='text-align: left;'><font size="-2"><?= $item->indicacao ?></td>

                            <? }
                            ?>
                            <? if ($_POST['laboratorio'] == 'SIM') { ?>
                                <td style='text-align: right;'><font size="-2"><?= number_format($valorpercentuallaboratorio, 2, ",", "") . $simbolopercebtualpromotor ?></td>

                                <td style='text-align: right;'><font size="-2"><?= number_format($perclaboratorio, 2, ",", "."); ?></td>
                                <td style='text-align: left;'><font size="-2"><?= $item->laboratorio ?></td>

                            <? }
                            ?> 
                            <?
                            if($item->taxaadm_perc == 'SIM'){
                                $taxaAdm = ((float) $perc * ((float) $item->taxa_administracao / 100));
                            }else if($item->taxaadm_perc == 'NAO'){
                                $taxaAdm = $item->taxa_administracao;
                            }else{
                                $taxaAdm = 0;
                                $taxaAdministracao = $item->taxa_administracao;
                            }
                            
                            $taxaAdmStr = number_format($taxaAdm, 2, ',', '.');

                            if($item->taxaadm_perc == 'SIM'){
                                $taxaAdmStr = $item->taxa_administracao . " (%)";
                            }
                            if($item->taxaadm_perc == 'FIXO'){
                                $taxaAdmStr =  "FIXO";
                            }
                            ?>

                            <? if ($mostrar_taxa == 'SIM') { ?>
                                <td style='text-align: right;' width="50"><font size="-2"><?= $taxaAdmStr ?></td>
                                <?                                
                               if (@$item->valor_medico > 0) {
                                    @$taxaAdministracao += $taxaAdm;
                                }
                                ?>
                            <? } ?>
                            <? if ($clinica == 'SIM') { ?>    
                                <td><font size="-2"><?= $item->revisor; ?></td>
                            <? } ?>

                            <? if ($solicitante == 'SIM') { ?>
                                <td style='text-align: right;'><font size="-2"><?= $item->medicosolicitante; ?></td>
                            <? } ?>
                            <? if ($_POST['tipo_desconto'] == 'SIM') { ?>
                                <td style='text-align: right;'><font size="-2"><?= $tipo_desconto; ?></td>
                            <? } ?> 
                            <?
                            if ($empresa_permissao[0]->faturamento_novo == "t") {
                                ?> 
                                <!--<td style='text-align: right;'><font size="-2"><?= $item->observacao_pg_outro_dia; ?></td>-->
                                <?
                            } else {
                                
                            }
                            ?>
                        </tr>


                        <?php
                        $qtdetotal = $qtdetotal + $item->quantidade;
                        $amb_antg = $item->ambulatorio_laudo_id;
                    endforeach;

                    if ($_POST['somarcredito'] == 'SIM') {
                        $totalgeral = $totalgeral + $valor_credito;
                    }
//                    var_dump(@$empresa_permissao[0]->valor_laboratorio);
                    if ($_POST['promotor'] == 'SIM') {
//                        if (@$empresa_permissao[0]->promotor_medico == 't') {
                        $resultadototalgeral = $totalgeral - $totalperc - $totalpercpromotor;
//                            $totalperc = $totalperc - $totalpercpromotor;
//                        } else {
//                        $resultadototalgeral = $totalgeral - $totalperc - $totalpercpromotor;
//                        }
                    } else {
                        if (@$empresa_permissao[0]->valor_laboratorio == 't') {
                            $resultadototalgeral = $totalgeral - $totalperc;
                        } else {
                            $resultadototalgeral = $totalgeral - $totalperc - $totalperclaboratorio;
                        }
                    }
                    if ($empresa_permissao[0]->faturamento_novo == 't') {
                        $resultadototalgeral -= $descontoTotal;
                    }
                    ?>
                    <tr>
                        <td ><font size="-1">TOTAL</td>
                        <td  colspan="2" style='text-align: right;'><font size="-1">Nr. Procedimentos: <?= $qtdetotal; ?></td>
                        <? if ($clinica == 'SIM') { ?>
                            <? if ($_POST['somarcredito'] == 'SIM') { ?>
                                <td colspan="2" style='text-align: right;'><font size="-1">TOTAL CLINICA: <?= number_format($resultadototalgeral, 2, ",", "."); ?></td>
                                <td colspan="2" style='text-align: right;'><font size="-1">CRÉDITOS: <?= number_format($valor_credito, 2, ",", "."); ?></td>
                            <? } else { ?>
                                <td colspan="4" style='text-align: right;'><font size="-1">TOTAL CLINICA: <?= number_format($resultadototalgeral, 2, ",", "."); ?></td>
                            <? } ?>
                        <? } else { ?>
                            <td colspan="4" style='text-align: right;'><font size="-1">&nbsp;</td>
                        <? } ?>
                        <!--                            As váriaveis estão invertidas-->
                        <? if ($_POST['forma_pagamento'] == 'SIM') { ?>
                            <td colspan="2" style='text-align: right;'><font size="-1">T. CARTÃO: <?= number_format($vlrTotalDinheiro, 2, ",", "."); ?></td>
                            <td colspan="3" style='text-align: right;'><font size="-1">T. DINHEIRO: <?= number_format($vlrTotalCartao, 2, ",", "."); ?></td>
                        <? } ?>
                        <? if ($_POST['promotor'] == 'SIM') { ?>
                            <td colspan="2" style='text-align: right;'><font size="-1">TOTAL PROMOTOR: <?= number_format($totalpercpromotor, 2, ",", "."); ?></td>
                            <td colspan="2" style='text-align: right;'
                                title="Diferença entre o valor do médico e o valor do promotor."><font size="-1">DIFERENÇA: <?= number_format($totalperc - $totalpercpromotor, 2, ",", "."); ?></td>
                            <? }
                            ?>
                            <? if ($_POST['laboratorio'] == 'SIM') { ?>
                            <td colspan="2" style='text-align: right;'><font size="-1">TOTAL LABORATÓRIO: <?= number_format($totalperclaboratorio, 2, ",", "."); ?></td>
                        <? }
                        ?>
                        <td colspan="2" style='text-align: right;'><font size="-1">TOTAL MEDICO.: <?= number_format($totalperc, 2, ",", "."); ?></td>
                        <td colspan="3" style='text-align: right;'><font size="-1">TOTAL LÍQUIDO: <?= number_format($valorLiquidoTotal - $totalperc, 2, ",", "."); ?></td>
                    </tr>
                </tbody>
            </table>

            <? if ($empresa_permissao[0]->faturamento_novo == 't') { ?>
                <br>
                <br>
                <table border="1">
                    <tr>
                        <td>
                            TOTAL BRUTO 
                        </td>
                        <td>
                            <?= number_format($resultadototalgeral + $totalperc + $descontoTotal, 2, ",", "."); ?>

                        </td>
                    </tr>

                    <tr>
                        <td>
                            TOTAL DESCONTO
                        </td>
                        <td>
                            <?= number_format($descontoTotal, 2, ",", "."); ?>         
                        </td>
                    </tr>
                    <tr>
                        <td>
                            TOTAL LÍQUIDO 
                        </td>
                        <td>
                            <?= number_format($resultadototalgeral + $totalperc, 2, ",", "."); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            PRODUÇÃO MÉDICA
                        </td>
                        <td>
                            <?= number_format($totalperc, 2, ",", "."); ?> 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            SALDO
                        </td>
                        <td>
                            <?= number_format($resultadototalgeral, 2, ",", "."); ?>          
                        </td>
                    </tr>
                </table>
                <br>
                <br>
            <? } ?>
        <? endif; ?>


        <? 
        
        if (count($relatoriohomecare) > 0): ?>
            <hr>
            <table border="1">
                <thead>
                    <tr>
                        <td colspan="50"><center>PRODUÇÃO HOME CARE</center></td>
                </tr>
                <tr>  

                    <th class="tabela_header"><font size="-1">Convenio</th>
                    <th class="tabela_header"><font size="-1">Nome</th>
                    <th class="tabela_header"><font size="-1">Medico</th>
                    <th class="tabela_header"><font size="-1">Data</th>
                    <th class="tabela_header"><font size="-1">Qtde</th>
                    <th class="tabela_header" width="220px;"><font size="-1">Procedimento</th>
                    <? if ($clinica == 'SIM') { ?>
                        <th class="tabela_header" ><font size="-1">Valor Bruto</th>
                        <th class="tabela_header" ><font size="-1">ISS</th>
                        <th class="tabela_header" ><font size="-1">Valor Liquido</th>
                    <? } ?>
                    <th class="tabela_header" width="80px;"><font size="-1">Indice/Valor</th>
                    <th class="tabela_header" width="80px;"><font size="-1">Valor Medico</th>
                    <? if ($_POST['promotor'] == 'SIM') { ?>
                        <th class="tabela_header" width="80px;"><font size="-1">Indice/Valor Promotor</th>
                        <th class="tabela_header" width="80px;"><font size="-1">Valor Promotor</th>   
                        <th class="tabela_header" width="80px;"><font size="-1">Promotor</th>   
                    <? }
                    ?>

                    <? if ($solicitante == 'SIM') { ?>
                        <th class="tabela_header" width="80px;"><font size="-1">Solicitante</th>
                    <? } ?>
                </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    $valor = 0;
                    $valortotal = 0;
                    $convenio = "";
                    $y = 0;
                    $qtde = 0;
                    $qtdetotal = 0;
                    $resultado = 0;
                    $simbolopercebtual = " %";
                    $iss = 0;
                    $totalperchome = 0;
                    $totalgeralhome = 0;
                    $perchome = 0;
                    $totalgeral = 0;
    
                    $totalretorno = 0;
                    $percpromotorhome = 0;
                    $totalpercpromotor = 0;
                    $totalgeralpromotor = 0;
                    $valor_total_home_care = 0;
                    $i = 0;
                    
                    foreach ($relatoriohomecare as $item) :  
                         if ($empresa_permissao[0]->faturamento_novo == 't') {
                            $descontoForma = $this->guia->listardescontoTotal($item->agenda_exames_id); 
                            if (count($descontoForma) > 0) {
                                $descontoTotal += $descontoForma[0]->desconto;
                                $descontoAtual = $descontoForma[0]->desconto;
                            }
                         }
                         $i++;
                          $procedimentopercentual = $item->procedimento_convenio_id; 
                          $medicopercentual = $item->operador_id;
                          if ($item->classificacao == 1) {
                            $totalconsulta++;
                          }
                          if ($item->classificacao == 2) {
                             $totalretorno++;
                          } 
                          if ($empresa_permissao[0]->faturamento_novo == 't') { 
                            if($empresa_permissao[0]->producao_por_valor == 't'){  
                                if($item->valores_materias_odontologia != ''){
                                    $valor_total_home_care = $item->valor_pago - $item->valores_materias_odontologia;
                                    $title_desconto = 'Descontando o Valor de '.$item->valores_materias_odontologia.' referente aos Valores do Materias da Odontologia Valor Bruto Real '.$item->valor_pago;
                                    $cor = 'red';
                                }else{
                                    $valor_total_home_care = $item->valor_pago;
                                }

                            }else{ 
                                $valor_total_home_care = ($item->valor * $item->quantidade) - @$descontoAtual;   
                            }                
                        } else {
                            $valor_total_home_care = $item->valor_total;
                        }
                        ?>
                        <tr>
                            <td><font size="-2"><?= $item->convenio; ?></td>
                            <td><font size="-2"><?= $item->paciente; ?></td>
                            <td><font size="-2"><?= $item->medico; ?></td>
                            <td><font size="-2"><?= substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4); ?></td>
                            <td ><font size="-2"><?= $item->quantidade; ?></td>
                            <td><font size="-2"><?= $item->procedimento; ?></td>
                            <? if ($clinica == 'SIM') { ?>
                                <td style='text-align: right;'><font size="-2"><?= number_format($valor_total_home_care, 2, ",", "."); ?></td>
                                <td style='text-align: right;' width="50"><font size="-2"><?= number_format($item->iss, 2, ",", "."); ?> (%)</td>
                                <td style='text-align: right;'><font size="-2"><?= number_format(((float) $valor_total_home_care - ((float) $valor_total_home_care * ((float) $item->iss / 100))), 2, ",", "."); ?></td>
                            <? } ?>
                            <?
                            if ($item->percentual_medico == "t") {
                                $simbolopercebtual = " %"; 
                                $valorpercentualmedico = $item->valor_medico; 
                                $perc = $valor_total_home_care * ($valorpercentualmedico / 100);
                                $totalperchome = $totalperchome + $perc;
                                $totalgeralhome = $totalgeralhome + $valor_total_home_care;
                            } else {
                                $simbolopercebtual = "";
                                $valorpercentualmedico = $item->valor_medico;

                                $perchome = $valorpercentualmedico;
                                $totalperchome = $totalperchome + $perchome;
                                $totalgeralhome = $totalgeralhome + $valor_total_home_care;
                            } 
                            if ($item->percentual_promotor == "t") {
                                $simbolopercebtualpromotorhome = " %"; 
                                $valorpercentualpromotorhome = $item->valor_promotor/* - ((float) $item->valor_promotorhome * ((float) $item->taxa_administracao / 100)) */;

                                $percpromotorhome = $valor_total_home_care * ($valorpercentualpromotorhome / 100);
                            } else {
                                $simbolopercebtualpromotorhome = "";
                                $valorpercentualpromotorhome = $item->valor_promotor/* - ((float) $item->valor_promotorhome * ((float) $item->taxa_administracao / 100)) */;

                                $percpromotorhome = $valorpercentualpromotorhome;
                                $percpromotorhome = $percpromotorhome * $item->quantidade;
                            }

                            $totalpercpromotor = $totalpercpromotor + $percpromotorhome;
                            $totalgeralpromotor = $totalgeralpromotor + $valor_total_home_care;
                            ?>
                            <td style='text-align: right;'><font size="-2"><?= $valorpercentualmedico . $simbolopercebtual; ?></td>
                            <td style='text-align: right;'><font size="-2"><?= number_format($perc, 2, ",", "."); ?></td>
                            <? if ($_POST['promotor'] == 'SIM') { ?>
                                <td style='text-align: right;'><font size="-2"><?= $valorpercentualpromotorhome . $simbolopercebtualpromotorhome ?></td>
                                <td style='text-align: right;'><font size="-2"><?= number_format($percpromotorhome, 2, ",", "."); ?></td>  
                                <td style='text-align: left;'><font size="-2"><?= $item->indicacao ?></td>
                            <? }
                            ?>



                            <? if ($solicitante == 'SIM') { ?>
                                <td style='text-align: right;'><font size="-2"><?= $item->medicosolicitante; ?></td>
                            <? } ?>
                        </tr>


                        <?php
                        $qtdetotal = $qtdetotal + $item->quantidade;
                    endforeach;
                    if ($_POST['promotor'] == 'SIM') {
                        $resultadototalgeralhome = $totalgeralhome - $totalperchome - $totalpercpromotor;
                    } else {
//                        if (@$empresa_permissao[0]->valor_laboratorio == 't') {
                        $resultadototalgeralhome = $totalgeralhome - $totalperchome;
//                        }
                    }
                    ?>
                    <tr>
                        <td ><font size="-1">TOTAL</td>
                        <td style='text-align: right;'><font size="-1">Nr. Procedimentos: <?= $qtdetotal; ?></td>
                        <? if ($clinica == 'SIM') { ?>
                            <td colspan="5" style='text-align: right;'><font size="-1">TOTAL CLINICA: <?= number_format($resultadototalgeralhome, 2, ",", "."); ?></td>
                        <? } else { ?>
                            <td colspan="4" style='text-align: right;'><font size="-1">&nbsp;</td>
                        <? } ?>
                        <? if ($_POST['promotor'] == 'SIM') { ?>
                            <td colspan="3" style='text-align: right;'><font size="-1">TOTAL PROMOTOR: <?= number_format($totalpercpromotor, 2, ",", "."); ?></td>

                        <? }
                        ?>
                        <td colspan="2" style='text-align: right;'><font size="-1">TOTAL MEDICO: <?= number_format($totalperchome, 2, ",", "."); ?></td>
                    </tr>
                </tbody>
            </table>
        <? endif; ?>
        <!-- aaaaaaaaaaaaaaaaaaaaaa -->
        <? if (count($relatorioProducaoImp) > 0): ?>
            <hr>
            <table border="1">
                <thead>
                    <tr>
                        <td colspan="50"><center>PRODUÇÃO IMPORTAÇÃO</center></td>
                    </tr>
                <tr>


                    <th class="tabela_header"><font size="-1">Convenio</th>
                    <th class="tabela_header"><font size="-1">Atendimento</th>
                    <!-- <th class="tabela_header"><font size="-1">Paciente</th> -->
                    <th class="tabela_header"><font size="-1">Medico</th>
                    <th class="tabela_header"><font size="-1">Data Agend.</th>
                    <th class="tabela_header"><font size="-1">Data Receb.</th>
                    <th class="tabela_header" width="220px;"><font size="-1">Procedimento</th>
                    <th class="tabela_header"><font size="-1">Qtde</th>
                    <? if ($clinica == 'SIM') { ?>
                        <th class="tabela_header" ><font size="-1">Valor Bruto</th>
                        <th class="tabela_header" ><font size="-1">ISS</th>
                        <th class="tabela_header" ><font size="-1">Valor Liquido</th>
                    <? } ?>
                    <th class="tabela_header" width="80px;"><font size="-1">Indice/Valor</th>
                    <th class="tabela_header" width="80px;"><font size="-1">Valor Medico</th>
                   
                </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    $valor = 0;
                    $valortotal = 0;
                    $convenio = "";
                    $y = 0;
                    $qtde = 0;
                    $qtdetotal = 0;
                    $resultado = 0;
                    $simbolopercentual = " %";
                    $iss = 0;
                    $totalperchome = 0;
                    $totalgeralhome = 0;
                    $perchome = 0;
                    $totalpercImp = 0;
                    $totalgeralImp = 0;
                    $percImp = 0;

                    foreach ($relatorioProducaoImp as $item) :
                        $i++;
                        $procedimentopercentual = $item->procedimento_convenio_id;
//            $medicopercentual = $item->medico_parecer1;
                        $medicopercentual = $item->medico_id;
                        $valor_total = $item->valor_total;
                        if ($item->grupo != "RETORNO") {
                            $totalconsulta++;
                        } else {
                            $totalretorno++;
                        }
                        ?>
                        <tr>
                            <td><font size="-2"><?= $item->convenio; ?></td>
                            <? if($item->pedido != ''){?>
                                <td><font size="-2"><?= $item->pedido; ?></td>
                            <? } else {?>
                                <td><font size="-2"><?= $item->paciente_nome; ?></td>
                            <? } ?>

                            <td><font size="-2"><?= $item->medico; ?></td>
                            <td><font size="-2"><?= substr($item->data_agendamento, 8, 2) . "/" . substr($item->data_agendamento, 5, 2) . "/" . substr($item->data_agendamento, 0, 4); ?></td>
                            <td><font size="-2"><?= substr($item->data_producao, 8, 2) . "/" . substr($item->data_producao, 5, 2) . "/" . substr($item->data_producao, 0, 4); ?></td>
                            <td><font size="-2"><?= $item->procedimento; ?></td>
                            <td ><font size="-2"><?= $item->quantidade; ?></td>
                           
                            <? if ($clinica == 'SIM') { ?>
                                <td style='text-align: right;'><font size="-2"><?= number_format($valor_total, 2, ",", "."); ?></td>
                                <td style='text-align: right;' width="50"><font size="-2"><?= number_format($item->iss, 2, ",", "."); ?> (%)</td>
                                <td style='text-align: right;'><font size="-2"><?= number_format(((float) $valor_total - ((float) $valor_total * ((float) $item->iss / 100))), 2, ",", "."); ?></td>
                            <? } ?>
                            <?
                            if ($item->percentual_medico == "t") {
                                $simbolopercebtual = " %";

                                $valorpercentualmedico = $item->valor_medico;

                                $perc = $valor_total * ($valorpercentualmedico / 100);
                                $totalpercImp = $totalpercImp + $perc;
                                $totalgeralImp = $totalgeralImp + $valor_total;
                            } else {
                                $simbolopercebtual = "";
                                $valorpercentualmedico = $item->valor_medico;

                                $perc = $valorpercentualmedico;
                                $totalpercImp = $totalpercImp + $perc;
                                $totalgeralImp = $totalgeralImp + $valor_total;
                            }
                            ?>
                            <td style='text-align: right;'><font size="-2"><?= $valorpercentualmedico . $simbolopercebtual ?></td>
                            <td style='text-align: right;'><font size="-2"><?= number_format($perc, 2, ",", "."); ?></td>

                        </tr>


                        <?php
                        $qtdetotal = $qtdetotal + $item->quantidade;
                    endforeach;
                    if ($_POST['promotor'] == 'SIM') {
                        $resultadototalgeralImp = $totalgeralImp - $totalpercImp - $totalpercpromotor;
                    } else {
//                        if (@$empresa_permissao[0]->valor_laboratorio == 't') {
                        $resultadototalgeralImp = $totalgeralImp - $totalpercImp;
//                        }
                    }
                    ?>
                    <tr>
                        <td ><font size="-1">TOTAL</td>
                        <td style='text-align: right;'><font size="-1">Nr. Procedimentos: <?= $qtdetotal; ?></td>
                        <? if ($clinica == 'SIM') { ?>
                            <td colspan="5" style='text-align: right;'><font size="-1">TOTAL CLINICA: <?= number_format($resultadototalgeralImp, 2, ",", "."); ?></td>
                        <? } else { ?>
                            <td colspan="4" style='text-align: right;'><font size="-1">&nbsp;</td>
                        <? } ?>
                      
                        <td colspan="2" style='text-align: right;'><font size="-1">TOTAL MEDICO: <?= number_format($totalpercImp, 2, ",", "."); ?></td>
                    </tr>
                </tbody>
            </table>
        <? endif; ?>
        <br>
        <br>
        <?
        if (count(@$relatoriocirurgico) > 0):
            $totalprocedimentoscirurgicos = 0;
            ?>
            <br>
            <table border="1">
                <thead>
                    <tr>
                        <td colspan="50"><center>PRODUÇÃO CIRURGICA</center></td>
                </tr>
                <tr>
                    <th class="tabela_header"><font size="-1"><center>Convenio</center></th>
                <th class="tabela_header"><font size="-1"><center>Nome</center></th>
                <th class="tabela_header"><font size="-1"><center>Medico</center></th>
                <th class="tabela_header"><font size="-1"><center>Data</center></th>
                <th class="tabela_header"><font size="-1"><center>Procedimento</center></th>
                <th class="tabela_header"><font size="-1"><center>Valor Procedimento</center></th>
                <th class="tabela_header"><font size="-1"><center>Grau de Participação</center></th>
                <th class="tabela_header"><font size="-1"><center>Valor Médico</center></th>
                </tr>
                </thead>
                <tbody>
                    <?
                    $totalMedicoCirurgico = 0;
                    foreach ($relatoriocirurgico as $itens) :
                        $totalprocedimentoscirurgicos++;
                        $totalMedicoCirurgico += (float) $itens->valor_medico;
//                        $totalperc += $totalMedicoCirurgico;
                        ?>

                        <tr>
                            <!--<td><font size="-2"><?= $itens->guia_id; ?></td>-->
                            <td ><font size="-2"><?= $itens->convenio; ?></td>
                            <td><font size="-2"><?= $itens->paciente; ?></td>
                            <td><font size="-2"><?= $itens->medico; ?></td>
                            <td><font size="-2"><?= date("d/m/Y", strtotime($itens->data)); ?></td>
                            <td ><font size="-2"><?= $itens->procedimento; ?></td>
                            <td style='text-align: right;'><font size="-2"><?= number_format($itens->valor_total, 2, ",", "."); ?></td>
                            <td><font size="-2"><?= $itens->funcao; ?></td>
                            <td style='text-align: right;'><font size="-2"><?= number_format($itens->valor_medico, 2, ",", "."); ?></td>
                        </tr>

                        <?
                    endforeach;
                    $totalperc += $totalMedicoCirurgico;
                    ?>
                    <tr>
                        <td ><font size="-1">TOTAL</td>
                        <td style='text-align: right;'><font size="-1">Nr. Procedimentos: <?= $totalprocedimentoscirurgicos; ?></td>
                        <td colspan="4" style='text-align: right;'><font size="-1">&nbsp;</td>
                        <td colspan="2" style='text-align: right;'><font size="-1">TOTAL MEDICO: <?= number_format($totalMedicoCirurgico, 2, ",", "."); ?></td>
                    </tr>

                </tbody>
            </table>
            <?
        endif;

        if ($medico != 0) {
            ?> 
            <hr>
            <? if ($medico != 0 && $recibo == 'NAO') { ?> 
                <table border="1">
                    <tr>
                        <th colspan="2" width="200px;">RESUMO</th>
                    </tr>
                    <?
                    $resultado = $totalperc;
                    if (@$totalretorno > 0 || @$totalconsulta > 0) :
                        if (@$totalretorno == "") {
                            @$totalretorno = 0;
                        }
                        ?>
                        <tr>
                            <td>TOTAL CONSULTAS</td>
                            <td style='text-align: right;' width="30px;"><?= @$totalconsulta; ?></td>
                        </tr>

                        <tr>
                            <td>TOTAL RETORNO</td>
                            <td style='text-align: right;'><?= @$totalretorno; ?></td>
                        </tr>
                        <?
                    endif;
                    if (@$totalprocedimentoscirurgicos > 0):
                        ?>
                        <tr>
                            <td>TOTAL PROC. CIRURGICOS</td>
                            <td style='text-align: right;'><?= $totalprocedimentoscirurgicos; ?></td>
                        </tr>
                    <? endif; ?>
                </table>
                <?
                if (@$totalperchome != 0) {
                    $totalperc = $totalperc + $totalperchome;
                }
                if (@$totalpercImp > 0) {
                    $totalperc = $totalperc + $totalpercImp;
                }
                // var_dump($medico[0]->valor_base); 
                // die;
                $pisoMedico = $medico[0]->piso_medico;
                $irpf = 0;
                if ($totalperc >= $medico[0]->valor_base) {
                    $irpf = $totalperc * ($medico[0]->ir / 100);
                    ?>
                    <br>
                    <table border="1">
                        <tr>
                            <th colspan="2" width="200px;">RESUMO FISCAL</th>
                        </tr>
                        <tr>
                            <td>TOTAL</td>
                            <td style='text-align: right;'><?= number_format(@$totalperc, 2, ",", "."); ?></td> <!-- chamado #5934 pede para retirar -->
                        </tr>
                        <tr>
                            <td>IRPF</td>
                            <td style='text-align: right;'><?= number_format($irpf, 2, ",", "."); ?></td>
                        </tr>
                        <? if ($mostrar_taxa == 'SIM') { ?>
                            <tr>
                                <td>TAXA ADMINISTRAÇÃO</td>
                                <td style='text-align: right;'><?= number_format(@$taxaAdministracao, 2, ",", "."); ?></td>
                            </tr>
                            <?
                        }
                        $resultado = @$totalperc - @$irpf - @$taxaAdministracao;
                    } else {
                        ?>
                        <hr>
                        <table border="1">
                            <tr>
                                <th colspan="2" width="200px;">RESUMO FISCAL</th>
                            </tr>
                            <?
                        }
                        if ($totalperc > 215) {
                            $pis = $totalperc * ($medico[0]->pis / 100);
                            $csll = $totalperc * ($medico[0]->csll / 100);
                            $cofins = $totalperc * ($medico[0]->cofins / 100);
                            $resultado = $resultado - $pis - $csll - $cofins;
                            ?>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <!--                            <tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <td>TAXA ADMINISTRAÇÃO</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <td style='text-align: right;'><?= number_format($taxaAdministracao, 2, ",", "."); ?></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </tr>-->
                            <tr>
                                <td>PIS</td>
                                <td style='text-align: right;'><?= number_format($pis, 2, ",", "."); ?></td>
                            </tr>
                            <tr>
                                <td>CSLL</td>
                                <td style='text-align: right;'><?= number_format($csll, 2, ",", "."); ?></td>
                            </tr>
                            <tr>
                                <td>COFINS</td>
                                <td style='text-align: right;'><?= number_format($cofins, 2, ",", "."); ?></td>
                            </tr>
                            <?
                            $iss = $totalperc * ($medico[0]->iss / 100);
                            $resultado = $resultado - $iss;
                        }
                        if (@$iss > 0) {
                            ?>
                            <tr>
                                <td>ISS</td>
                                <td style='text-align: right;'><?= number_format($iss, 2, ",", "."); ?></td>
                            </tr>
                        <? } ?>
                        <tr>
                            <td>PISO MÉDICO</td>
                            <td style='text-align: right;'><?= number_format(@$pisoMedico, 2, ",", "."); ?></td>
                        </tr>
                        <tr>
                            <td>RESULTADO</td>
                            <?
                            if(@$pisoMedico > $resultado){
                                $resultado = $pisoMedico;
                            }
                            ?>
                            <td style='text-align: right;'><?= number_format($resultado, 2, ",", "."); ?></td>
                        </tr>
                    </table>
                <? } ?>
                <? ?>
                <? if ($medico != 0 & $revisor == 0) {
                    ?>

                    <form name="form_caixa" id="form_caixa" action="<?= base_url() ?>ambulatorio/guia/fecharmedico" method="post">
                        <input type="hidden" class="texto3" name="tipo" value="<?= $medico[0]->tipo_id; ?>" readonly/>
                        <input type="hidden" class="texto3" name="nome" value="<?= $medico[0]->credor_devedor_id; ?>" readonly/>
                        <input type="hidden" class="texto3" name="conta" value="<?= $medico[0]->conta_id; ?>" readonly/>
                        <input type="hidden" class="texto3" name="classe" value="<?= $medico[0]->classe; ?>" readonly/>
                        <input type="hidden" class="texto3" name="empresa_id" value="<?= $_POST['empresa']; ?>" readonly/>
                        <input type="hidden" class="texto3" name="operador_id" value="<?= $medico[0]->operador_id; ?>" readonly/>
                        <input type="hidden" class="texto3" name="observacao" value="<?= "Período " . substr($txtdata_inicio, 8, 2) . "/" . substr($txtdata_inicio, 5, 2) . "/" . substr($txtdata_inicio, 0, 4) . " até " . substr($txtdata_fim, 8, 2) . "/" . substr($txtdata_fim, 5, 2) . "/" . substr($txtdata_fim, 0, 4) . " médico: " . $medico[0]->operador; ?>" readonly/>
                        <input type="hidden" class="texto3" name="data" value="<?= substr($txtdata_inicio, 8, 2) . "/" . substr($txtdata_inicio, 5, 2) . "/" . substr($txtdata_inicio, 0, 4) ?>" readonly/>
                        <input type="hidden" class="texto3" name="data_fim" value="<?= substr($txtdata_fim, 8, 2) . "/" . substr($txtdata_fim, 5, 2) . "/" . substr($txtdata_fim, 0, 4) ?>" readonly/>
                        <input type="hidden" class="texto3" name="valor" value="<?= $resultado; ?>" readonly/>
                        <?
                        $j = 0;
                        if ($medico != 0 && $recibo == 'NAO') {
                            ?> 
                            <br>
                            <?
                            $empresa_id = $this->session->userdata('empresa_id');
                            $data['empresa'] = $this->guia->listarempresa($empresa_id);
                            $data_contaspagar = $data['empresa'][0]->data_contaspagar;
                            if ($data_contaspagar == 't') {
                                ?>

                                <br>
                                <label>Data Contas a Pagar</label><br>
                                <input type="text" class="texto3" name="data_escolhida" id="data_escolhida" value=""/>
                                <br>
                                <br>  
                            <? } ?>

                            <!--<br>-->
                            <?if(@$producao_paga == 't'){?>
                                </p><span style="color:red;">(PRODUÇÃO FECHADA)</span></p>
                            <?}else{?>
                                <button type="submit" name="btnEnviar">Producao medica</button>
                            <?}?>
                            
                            

                        <? } ?>
                    </form>
                    <?
                }
            }
            ?>
            <br>
            <? if ($medico != 0 && $recibo == 'NAO') { ?> 
                <div>
                    <div style="display: inline-block">
                        <? if ($_POST['producao_ambulatorial'] == 'SIM') { ?>
                            <table border="1">

                                <thead>
                                    <tr>
                                        <td colspan="50"><center>PRODUÇÃO AMBULATORIAL</center></td>
                                </tr>
                                <tr>
                                    <th class="tabela_header"><font size="-1">Medico</th>
                                    <th class="tabela_header"><font size="-1">Qtde</th>
                                    <th class="tabela_header"><font size="-1">Produ&ccedil;&atilde;o Medico</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?
                                    foreach ($relatoriogeral as $itens) :
                                        ?>

                                        <tr>
                                            <td><font size="-2"><?= $itens->medico; ?></td>
                                            <td ><font size="-2"><?= $itens->quantidade; ?></td>
                                            <td ><font size="-2"><?= number_format($itens->valor, 2, ",", "."); ?></td>
                                        </tr>

                                    <? endforeach; ?>
                                </tbody>
                            </table>
                        <? } ?>
                    </div>
                <? } ?>
                <div style="display: inline-block;margin: 5pt">
                </div>

                <? if (count($relatoriocirurgicogeral) > 0):
                    ?>
                    <div style="display: inline-block">
                        <table border="1">
                            <thead>
                                <tr>
                                    <td colspan="50"><center>PRODUÇÃO CIRURGICA</center></td>
                            </tr>
                            <tr>
                                <th class="tabela_header"><font size="-1">Medico</th>
                                <th class="tabela_header"><font size="-1">Qtde</th>
                                <th class="tabela_header"><font size="-1">Produ&ccedil;&atilde;o Medico</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?
                                foreach ($relatoriocirurgicogeral as $itens) :
                                    ?>

                                    <tr>
                                        <td><font size="-2"><?= $itens->medico; ?></td>
                                        <td ><font size="-2"><?= $itens->quantidade; ?></td>
                                        <td ><font size="-2"><?= number_format($itens->valor, 2, ",", "."); ?></td>
                                    </tr>

                                <? endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <? endif; ?>
                <?
//                echo "<pre>";
//                print_r($relatoriohomecaregeral);
                if (count($relatoriohomecaregeral) > 0):
                    ?>
                    <div style="display: inline-block">
                        <table border="1">
                            <thead>
                                <tr>
                                    <td colspan="50"><center>PRODUÇÃO HOME CARE</center></td>
                            </tr>
                            <tr>
                                <th class="tabela_header"><font size="-1">Medico</th>
                                <th class="tabela_header"><font size="-1">Qtde</th>
                                <th class="tabela_header"><font size="-1">Produ&ccedil;&atilde;o Medico</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?
                                foreach ($relatoriohomecaregeral as $itens) :
                                    ?> 
                                    <tr>
                                        <td><font size="-2"><?= $itens->medico; ?></td>
                                        <td ><font size="-2"><?= $itens->quantidade; ?></td>
                                        <td ><font size="-2"><?= number_format($itens->valor, 2, ",", "."); ?></td>
                                    </tr> 
                                <? endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <? endif; ?>
            </div>

            <hr>
            <? if ($tabela_recebimento == "SIM") { ?>
                <table border="1">
                    <tr>
                        <td colspan="3" align="center">PREVISÃO DE RECEBIMENTO</td>
                    </tr>
                    <tr>
                        <td>Médico</td>
                        <td>Valor</td>
                        <td>Data Prevista</td>
                    </tr>
                    <pre>
                        <?
                        foreach ($tempoRecebimento as $value) {
                            foreach ($value as $item) {
                                $vlr = $item['valor_recebimento'];

                                if ($vlr == 0) {
                                    continue;
                                }

                                $dt_recebimento = date("d/m/Y", strtotime($item['data_recebimento']));
                                ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <td><?= $item['medico_nome'] ?></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <td><?= number_format($vlr, 2, ",", "."); ?></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <td><?= $dt_recebimento ?></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </tr> 
                                <?
                            }
                        }
                        ?>
                                                                                                                                                                                                                                                                                                </table>
                                                                                                                                                                                                                                                                                                <hr>
            <? } ?>
                                                                                                                                                    <style>
                /*.pagebreak { page-break-before: always; }*/
                                                                                                                                                    </style>

            <?
        } else {
            ?>
                                                        <h4>N&atilde;o h&aacute; resultados para esta consulta.</h4>
            <?
        }
        ?>
                                                    
                                                    
                                                    
                                                    
                                                    

</div> <!-- Final da DIV content -->



<?
if ($empresa_permissao[0]->faturamento_novo == 't') {
    ?>

    <div>
        <? if (count($faturado_outro_dia) > 0): ?>
                    <table border="1">
                        <thead>
                            <tr>
                                <td colspan="50"><center>Procedimentos faturados em outro dia.</center></td>
                        </tr>
                        <tr>


                            <th class="tabela_header"><font size="-1">Convenio</th>
                            <th class="tabela_header"><font size="-1">Nome</th>
                            <th class="tabela_header"><font size="-1">Medico</th>
                            <th class="tabela_header" width="100px;" title="Data do agendamento. Data onde o paciente foi agendado"><font size="-1">Data Agend.</th>
                            <th class="tabela_header" width="100px;" title="Data do atendimento. Data em que foi enviado da sala de espera"><font size="-1">Data Atend.</th>
                            <th class="tabela_header" width="100px;" title="Data de recebimento. Data em que o relatorio se baseia"><font size="-1">Data Receb.</th>
                            <th class="tabela_header"><font size="-1">Qtde</th>
                            <th class="tabela_header" width="220px;"><font size="-1">Procedimento</th>
                    <? if ($clinica == 'SIM') { ?>
                                    <th class="tabela_header" ><font size="-1">Valor Bruto</th>
                                    <!--<th class="tabela_header" ><font size="-1">ISS</th>-->
                                    <th class="tabela_header" ><font size="-1">Valor Liquido</th>
                    <? } ?>
                    <? if ($_POST['forma_pagamento'] == 'SIM') { ?>
                                    <th class="tabela_header" ><font size="-1">F. Pagamento Cartão</th>
                                    <th class="tabela_header" ><font size="-1">F. Pagamento Dinheiro</th>
                    <? } ?>
                    <? if ($clinica == 'SIM') { ?>    

                    <? } ?>
                            <!--<th class="tabela_header" width="80px;"><font size="-1">Indice/Valor</th>-->
                            <!--<th class="tabela_header" width="80px;"><font size="-1">Valor Medico</th>-->
                    <? if ($_POST['promotor'] == 'SIM') { ?>
            <!--                        <th class="tabela_header" width="80px;"><font size="-1">Indice/Valor Promotor</th>
                                    <th class="tabela_header" width="80px;"><font size="-1">Valor Promotor</th>   
                                    <th class="tabela_header" width="80px;"><font size="-1">Promotor</th>   -->
                    <? } ?>
                    <? if ($_POST['laboratorio'] == 'SIM') { ?>
            <!--                        <th class="tabela_header" width="80px;"><font size="-1">Indice/Valor Laboratório</th>
                                    <th class="tabela_header" width="80px;"><font size="-1">Valor Laboratório</th>   
                                    <th class="tabela_header" width="80px;"><font size="-1">Laboratório</th>   -->
                    <? } ?>

                    <? if ($mostrar_taxa == 'SIM') { ?>
                                    <!--<th class="tabela_header" ><font size="-1">Taxa Administração</th>-->
                    <? } ?>


                    <? if ($clinica == 'SIM') { ?>    
                                    <!--<th class="tabela_header"><font size="-1">Revisor</th>-->
                    <? } ?>
         
                    <? if ($solicitante == 'SIM') { ?>
                                    <!--<th class="tabela_header" width="80px;"><font size="-1">Solicitante</th>-->
                    <? } ?>
                    <? if ($_POST['tipo_desconto'] == 'SIM') { ?>
                                    <!--<th class="tabela_header" width="80px;"><font size="-1">Desconto Especial</th>-->
                    <? } ?>
                             <th class="tabela_header" width="80px;"><font size="-1">Observção</th>
                        </tr>
                        </thead>
                        <tbody>
                    <?php
                    $dados = array();
                    $vlrTotalDinheiro = 0;
                    $vlrTotalCartao = 0;
                    $i = 0;
                    $valor = 0;
                    $valortotal = 0;
                    $convenio = "";
                    $y = 0;
                    $qtde = 0;
                    $qtdetotal = 0;
                    $resultado = 0;
                    $simbolopercebtual = " %";
                    $iss = 0;
                    $perc = 0;
                    $totalgeral = 0;
                    $percpromotor = 0;
                    $totalgeralpromotor = 0;
                    $totalpercpromotor = 0;
                    $perclaboratorio = 0;
                    $totalgerallaboratorio = 0;
                    $totalperclaboratorio = 0;
    
                    $totalretorno = 0;
                    $taxaAdministracao = 0;
                    $valor_total = 0;
                    $valor_total_calculo = 0;
                    $valor_credito = 0;
                    $producao_paga = 'f';
                    $descontoTotal = 0;
                    $valor_tcd = 0;
                    
                    foreach ($faturado_outro_dia as $item) :
                        $i++;
                        $procedimentopercentual = $item->procedimento_convenio_id;
                        $descontoAtual = 0;
//            $medicopercentual = $item->medico_parecer1;
                        $medicopercentual = $item->operador_id;
                        if ($item->grupo != "RETORNO") {
                            $totalconsulta++;
                        } else {
                            $totalretorno++;
                        }
                        $tipo_desconto = '';

                        if ($item->tipo_desconto == 'medico') {
                            $tipo_desconto = 'Desconto com Permissão do Médico';
                        }
                        if ($item->tipo_desconto == 'clinica') {
                            $tipo_desconto = 'Desconto com Permissão da Clinica';
                        }
                        if ($item->tipo_desconto == 'medico_clinica') {
                            $tipo_desconto = 'Desconto do Médico e da Clinica';
                        }

                        if ($item->producao_paga == 't') {
                            $producao_paga = 't';
                        }

                        if ($empresa_permissao[0]->faturamento_novo == 't') {
                            $descontoForma = $this->guia->listardescontoTotal($item->agenda_exames_id);
                            // var_dump($descontoForma);
                            if (count($descontoForma) > 0) {
                                $descontoTotal += $descontoForma[0]->desconto;
                                $descontoAtual = $descontoForma[0]->desconto;
                            }
                        }


                        if ($empresa_permissao[0]->faturamento_novo == 't') {
//                            $valor_total = $item->valor_pago;
                            $valor_total = $item->valor_pago;
                        } else {
                            $valor_total = $item->valor_total;
                        }

//                        $valor_total_formas = $item->valor1 + $item->valor2 + $item->valor3 + $item->valor4;
//                        $valor_total = $valor_total_formas + $item->desconto_ajuste1 + $item->desconto_ajuste2 + $item->desconto_ajuste3 + $item->desconto_ajuste4;
                        if ($item->forma_pagamento1 != 1000 && $item->forma_pagamento2 != 1000 && $item->forma_pagamento3 != 1000 && $item->forma_pagamento4 != 1000 && $item->forma_pagamento1 != 2000 && $item->forma_pagamento2 != 2000 && $item->forma_pagamento3 != 2000 && $item->forma_pagamento4 != 2000) {
                            if ($empresa_permissao[0]->faturamento_novo == 't') {
//                                $valor_total_calculo = $item->valor_pago;
                               $valor_total_calculo = $item->valor;
                            } else {
                                $valor_total_calculo = $item->valor;
                            }
                        } else {
                            if ($item->forma_pagamento1 == 1000) {
                                $valorSemCreditoTotal = $item->valor2 + $item->valor3 + $item->valor4;
                                $valor_credito = $valor_credito + $item->valor1;
                            }
                            if ($item->forma_pagamento2 == 1000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor3 + $item->valor4;
                                $valor_credito = $valor_credito + $item->valor2;
                            }
                            if ($item->forma_pagamento3 == 1000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor2 + $item->valor4;
                                $valor_credito = $valor_credito + $item->valor3;
                            }
                            if ($item->forma_pagamento4 == 1000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor2 + $item->valor3;
                                $valor_credito = $valor_credito + $item->valor4;
                            } 
                            
                            if ($item->forma_pagamento1 == 2000) {
                                $valorSemCreditoTotal = $item->valor2 + $item->valor3 + $item->valor4;
                                $valor_tcd= $valor_tcd + $item->valor1;
                            }
                            if ($item->forma_pagamento2 == 2000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor3 + $item->valor4;
                                $valor_tcd= $valor_tcd  + $item->valor2;
                            }
                            if ($item->forma_pagamento3 == 2000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor2 + $item->valor4;
                                $valor_tcd= $valor_tcd  + $item->valor3;
                            }
                            if ($item->forma_pagamento4 == 2000) {
                                $valorSemCreditoTotal = $item->valor1 + $item->valor2 + $item->valor3;
                                $valor_tcd= $valor_tcd + $item->valor4;
                            }
                            
                            
                            $valor_total_calculo = $valorSemCreditoTotal;
                        }

//                        $valor_total = $item->valor_total;
//                        var_dump($valor_total_calculo);
                        ?>
                                    <tr>
                                        <td><font size="-2"><?= $item->convenio; ?></td>
                                        <td><font size="-2"><?= $item->paciente; ?></td>
                                        <td><font size="-2"><?= $item->medico; ?></td>
                                        <td><font size="-2">
                                <?
                                $modificado = "";
                                $onclick = "";
                                if ($item->data_antiga != "") {
                                    $modificado = " ** ";
                                }

                                echo $modificado,
                                substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4),
                                ($item->sala_pendente != "f") ? " (PENDENTE)" : "",
                                $modificado;
                                ?>
                                        </td>
                                        <td ><font size="-2"><?= date('d/m/Y', strtotime($item->data_laudo)); ?></td>
                                        <td ><font size="-2">

                                <?
                                if ($empresa_permissao[0]->faturamento_novo == 't') {
                                    echo date('d/m/Y', strtotime($item->data_producao));
                                } else {
                                    ?>
                                    <?= date('d/m/Y', strtotime($item->data_producao)); ?>
                                    <?
                                }
                                ?>


                                        </td>
                                        <td ><font size="-2"><?= $item->quantidade; ?></td>

                                        <td><font size="-2"><?= $item->procedimento; ?></td>
                            <? if ($clinica == 'SIM') { ?>

                                <? if ($empresa_permissao[0]->faturamento_novo == 't') {
                                    ?>
                                <td style='text-align: right;'><font size="-2"><?= number_format($item->valor_pago, 2, ",", "."); ?></td>
                                <? } else {
                                    ?>
                                <td style='text-align: right;'><font size="-2"><?= number_format(@$valor_total, 2, ",", "."); ?></td>
                                    <?
                                }
                                ?>

                                                <!--<td style='text-align: right;' width="50"><font size="-2"><?= number_format($item->iss, 2, ",", "."); ?> (%)</td>-->
                                <?
//                                $valorLiqMed = ((float) $valor_total - ((float) $valor_total * ((float) $item->iss / 100)) - ((float) $valor_total * ((float) $item->taxa_administracao / 100))); 
                                ?>
                                                <td style='text-align: right;'><font size="-2"><?= number_format(((float) $valor_total - ((float) $valor_total * ((float) $item->iss / 100))), 2, ",", "."); ?></td>
                                <?
                            }
                            if ($_POST['forma_pagamento'] == 'SIM') {
                                $vlrDinheiro = 0;
                                $vlrCartao = 0;
                                if ($item->forma_pagamento1 != 1000 && $item->forma_pagamento2 != 1000 && $item->forma_pagamento3 != 1000 && $item->forma_pagamento4 != 1000 && $item->forma_pagamento1 != 2000 && $item->forma_pagamento2 != 2000 && $item->forma_pagamento3 != 2000 && $item->forma_pagamento4 != 2000) {


                                    if ($item->cartao1 != 'f') {
                                        $vlrDinheiro += $item->valor1;
                                    } else {
                                        $vlrCartao += $item->valor1;
                                    }

                                    if ($item->cartao2 != 'f') {
                                        $vlrDinheiro += $item->valor2;
                                    } else {
                                        $vlrCartao += $item->valor2;
                                    }

                                    if ($item->cartao3 != 'f') {
                                        $vlrDinheiro += $item->valor3;
                                    } else {
                                        $vlrCartao += $item->valor3;
                                    }

                                    if ($item->cartao4 != 'f') {
                                        $vlrDinheiro += $item->valor4;
                                    } else {
                                        $vlrCartao += $item->valor4;
                                    }
                                }

                                $vlrTotalDinheiro += $vlrDinheiro;
                                $vlrTotalCartao += $vlrCartao;
                                ?>
                                                <td style='text-align: right;'><font size="-2"><?= number_format($vlrDinheiro, 2, ",", "."); ?></td>
                                                <td style='text-align: right;'><font size="-2"><?= number_format($vlrCartao, 2, ",", "."); ?></td>
                                <?
                            }

                            // DESCONTANDO O VALOR DO LABORATORIO
                            if ($_POST['laboratorio'] == 'SIM') {


                                if ($item->percentual_laboratorio == "t") {
                                    $simbolopercebtuallaboratorio = " %";

                                    $valorpercentuallaboratorio = $item->valor_laboratorio/* - ((float) $item->valor_laboratorio * ((float) $item->taxa_administracao / 100)) */;

                                    $perclaboratorio = $valor_total * ($valorpercentuallaboratorio / 100);
//                                    var_dump(@$empresa_permissao[0]->valor_laboratorio); die;
                                } else {
                                    $simbolopercebtuallaboratorio = "";
                                    $valorpercentuallaboratorio = $item->valor_laboratorio/* - ((float) $item->valor_laboratorio * ((float) $item->taxa_administracao / 100)) */;

//                                    $perclaboratorio = $valorpercentuallaboratorio;
                                    $perclaboratorio = $valorpercentuallaboratorio * $item->quantidade;
                                }
                                if (@$empresa_permissao[0]->valor_laboratorio == 't') {
                                    $valor_total = $valor_total - $perclaboratorio;
                                    $valor_total_calculo = $valor_total_calculo - $perclaboratorio;
                                }
                                $totalperclaboratorio = $totalperclaboratorio + $perclaboratorio;
                                $totalgerallaboratorio = $totalgerallaboratorio + $valor_total;
                            }
                            // EM CASO DE A CONDIÇÃO ABAIXO SER VERDADEIRA. O VALOR DO PROMOTOR VAI SER DESCONTADO DO MÉDICO
                            // NÃO DÁ CLINICA

                            if (@$empresa_permissao[0]->promotor_medico == 't' && $_POST['promotor'] == 'SIM') {
                                // MESMAS REGRAS ABAIXO PARA O PROMOTOR ABAIXO
//                                var_dump(@$empresa_permissao[0]->promotor_medico);
//                                die;
                                if ($item->percentual_promotor == "t") {
                                    $simbolopercebtualpromotor = " %";
                                    $valorpercentualpromotor = $item->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;

                                    $percpromotor = $valor_total * ($valorpercentualpromotor / 100);
                                } else {
                                    $simbolopercebtualpromotor = "";
                                    $valorpercentualpromotor = $item->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;

                                    $percpromotor = $valorpercentualpromotor * $item->quantidade;
                                }

                                // SE FOR PERCENTUAL, ELE CALCULA O TOTAL PELO PERCENTUAL
                                if ($item->percentual_medico == "t") {
                                    $simbolopercebtual = " %";

                                    $valorpercentualmedico = $item->valor_medico/* - ((float) $item->valor_medico * ((float) $item->taxa_administracao / 100)) */;

                                    $perc = $valor_total * ($valorpercentualmedico / 100);
                                    if ($item->valor_promotor != null) {
//                                        echo '<pre>';
                                        $perc = $perc - $percpromotor;
                                    }
                                } else {
                                    // SE FOR VALOR, É O VALOR * A QUANTIDADE
                                    $simbolopercebtual = "";
                                    $valorpercentualmedico = $item->valor_medico/* - ((float) $item->valor_medico * ((float) $item->taxa_administracao / 100)) */;

//                                    $perc = $valorpercentualmedico;

                                    $perc = $valorpercentualmedico * $item->quantidade;
                                    if ($item->valor_promotor != null) {
//                                        echo '<pre>';
                                        $perc = $perc - $percpromotor;
                                    }
                                }
//                                var_dump($item->valor_promotor);
//                                var_dump($perc);
//                                var_dump($percpromotor);
//                                die;

                                $totalperc = $totalperc + $perc;
//                                if ($item->forma_pagamento1 != 1000 && $item->forma_pagamento2 != 1000 && $item->forma_pagamento3 != 1000 && $item->forma_pagamento4 != 1000) {
                                $totalgeral = $totalgeral + $valor_total_calculo;
//                                }

                                $totalpercpromotor = $totalpercpromotor + $percpromotor;
                                $totalgeralpromotor = $totalgeralpromotor + $valor_total;
                            } else {
                                // SENÃO, VAI CONTINUAR DA FORMA QUE ERA ANTES
                                if ($item->percentual_medico == "t") {
                                    $simbolopercebtual = " %";

                                    $valorpercentualmedico = $item->valor_medico/* - ((float) $item->valor_medico * ((float) $item->taxa_administracao / 100)) */;

                                    $perc = $valor_total * ($valorpercentualmedico / 100);
                                } else {
                                    $simbolopercebtual = "";
                                    $valorpercentualmedico = $item->valor_medico/* - ((float) $item->valor_medico * ((float) $item->taxa_administracao / 100)) */;

//                                    $perc = $valorpercentualmedico;
                                    $perc = $valorpercentualmedico * $item->quantidade;
                                }

                                $totalperc = $totalperc + $perc;
//                                if ($item->forma_pagamento1 != 1000 && $item->forma_pagamento2 != 1000 && $item->forma_pagamento3 != 1000 && $item->forma_pagamento4 != 1000) {
                                $totalgeral = $totalgeral + $valor_total_calculo;
//                                }

                                if ($item->percentual_promotor == "t") {
                                    $simbolopercebtualpromotor = " %";

                                    $valorpercentualpromotor = $item->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;

                                    $percpromotor = $valor_total * ($valorpercentualpromotor / 100);
                                } else {
                                    $simbolopercebtualpromotor = "";
                                    $valorpercentualpromotor = $item->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;

//                                    $percpromotor = $valorpercentualpromotor;
                                    $percpromotor = $valorpercentualpromotor * $item->quantidade;
                                }

                                $totalpercpromotor = $totalpercpromotor + $percpromotor;
                                $totalgeralpromotor = $totalgeralpromotor + $valor_total;
                            }

                            @$tempoRecebimento[str_replace("-", "", $item->data_producao)][$item->medico_parecer1] = array(
                                "medico_nome" => @$item->medico,
                                "valor_recebimento" => @$tempoRecebimento[str_replace("-", "", $item->data_producao)][@$item->medico_parecer1]["valor_recebimento"] + $perc,
                                "data_recebimento" => $item->data_producao
                            );
                            ?>
                            <? if ($clinica == 'SIM') { ?>    

                            <? } ?>
                                        <!--<td style='text-align: right;'><font size="-2"><?= number_format($valorpercentualmedico, 2, ",", "") . $simbolopercebtual ?></td>-->
                                        <!--<td style='text-align: right;'><font size="-2"><?= number_format($perc, 2, ",", "."); ?></td>-->

                            <? if ($_POST['promotor'] == 'SIM') { ?>
                                                <!--<td style='text-align: right;'><font size="-2"><?= number_format($valorpercentualpromotor, 2, ",", "") . $simbolopercebtual ?></td>-->

                                                <!--<td style='text-align: right;'><font size="-2"><?= number_format($percpromotor, 2, ",", "."); ?></td>-->
                                                <!--<td style='text-align: left;'><font size="-2"><?= $item->indicacao ?></td>-->

                            <? }
                            ?>
                            <? if ($_POST['laboratorio'] == 'SIM') { ?>
                                                <!--<td style='text-align: right;'><font size="-2"><?= number_format($valorpercentuallaboratorio, 2, ",", "") . $simbolopercebtualpromotor ?></td>-->

                                                <!--<td style='text-align: right;'><font size="-2"><?= number_format($perclaboratorio, 2, ",", "."); ?></td>-->
                                                <!--<td style='text-align: left;'><font size="-2"><?= $item->laboratorio ?></td>-->

                            <? }
                            ?> 
                            <?
                                if($item->taxaadm_perc == 'SIM'){
                                    $taxaAdm = ((float) $perc * ((float) $item->taxa_administracao / 100));
                                }else if($item->taxaadm_perc == 'NAO'){
                                    $taxaAdm = $item->taxa_administracao;
                                }else{
                                    $taxaAdm = 0;
                                    $taxaAdministracao = $item->taxa_administracao;
                                }
                                
                                $taxaAdmStr = number_format($taxaAdm, 2, ',', '.');

                                if($item->taxaadm_perc == 'SIM'){
                                    $taxaAdmStr = $item->taxa_administracao . " (%)";
                                }
                                if($item->taxaadm_perc == 'FIXO'){
                                    $taxaAdmStr =  "FIXO";
                                }
                            ?>
                            <? if ($mostrar_taxa == 'SIM') { ?>
                                                <!--<td style='text-align: right;' width="50"><font size="-2"><?= number_format($item->taxa_administracao, 2, ",", "."); ?> (%)</td>-->
                                <?$taxaAdministracao += $taxaAdm; ?>
                            <? } ?>
                            <? if ($clinica == 'SIM') { ?>    
                                                <!--<td><font size="-2"><?= $item->revisor; ?></td>-->
                            <? } ?>

                            <? if ($solicitante == 'SIM') { ?>
                                                <!--<td style='text-align: right;'><font size="-2"><?= $item->medicosolicitante; ?></td>-->
                            <? } ?>
                            <? if ($_POST['tipo_desconto'] == 'SIM') { ?>
                                                <!--<td style='text-align: right;'><font size="-2"><?= $tipo_desconto; ?></td>-->
                            <? } ?> 
                            <?
                            if ($empresa_permissao[0]->faturamento_novo == "t") {
                                ?> 
                                                <td style='text-align: right;'><font size="-2"><?= $item->observacao_pg_outro_dia; ?></td>
                                    <?
                                } else {
                                    
                                }
                                ?>
                                            
                                         
                                    </tr>


                        <?php
                        $qtdetotal = $qtdetotal + $item->quantidade;
                    endforeach;

//        if ($_POST['somarcredito'] == 'SIM') {
//            $totalgeral = $totalgeral + $valor_credito;
//        }
////                    var_dump(@$empresa_permissao[0]->valor_laboratorio);
//        if ($_POST['promotor'] == 'SIM') {
////                        if (@$empresa_permissao[0]->promotor_medico == 't') {
//            $resultadototalgeral = $totalgeral - $totalperc - $totalpercpromotor;
////                            $totalperc = $totalperc - $totalpercpromotor;
////                        } else {
////                        $resultadototalgeral = $totalgeral - $totalperc - $totalpercpromotor;
////                        }
//        } else {
//            if (@$empresa_permissao[0]->valor_laboratorio == 't') {
//                $resultadototalgeral = $totalgeral - $totalperc;
//            } else {
//                $resultadototalgeral = $totalgeral - $totalperc - $totalperclaboratorio;
//            }
//        }
//        if ($empresa_permissao[0]->faturamento_novo == 't') {
//            $resultadototalgeral -= $descontoTotal;
//        }
                    ?>
                            
                        </tbody>
                    </table>
             
        <? endif; ?>
       
    <? } ?>
    <br>
        <br>
        <? if ($medico != 0 && $recibo == 'SIM') { ?>
                                                                                    <div>
                                                                                            <div>
                                                                                                <p style="text-align: center;font-size: 14pt">
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                       <strong></strong> <span style="color:red;"><?= ($producao_paga == 't') ? '(MÉDICO JÁ FOI PAGO)' : ''; ?></span></p>
                        <?
           $valor = number_format($totalperc, 2, ",", ".");
           $valoreditado = str_replace(",", "", str_replace(".", "", $valor));
           $extenso = GExtenso::moeda($valoreditado);
                        ?>
                                                                                               <p style="text-align: center;">EU   <u><b><?= $medico[0]->operador ?></b></u>, RECEBI DA CLÍNICA,</p>
                                                                                                <p style="text-align: center;">  A QUANTIA DE R$ <?=number_format($totalperc, 2, ",", "."); ?> (<?= strtoupper($extenso) ?>)

                                                                                                <p style="text-align: center;">REFERENTE AOS ATENDIMENTOS 
                                                                                                    CLÍNICOS DO PERÍODO DE <?= substr($txtdata_inicio, 8, 2) . "/" . substr($txtdata_inicio, 5, 2) . "/" . substr($txtdata_inicio, 0, 4); ?> a <?= substr($txtdata_fim, 8, 2) . "/" . substr($txtdata_fim, 5, 2) . "/" . substr($txtdata_fim, 0, 4); ?> </p>
                                                                                                <p><?//=$empresamunicipio[0]->municipio ?> </p>
                                                                                                <p style="text-align: center"><?= $empresamunicipio[0]->municipio ?>, <?= $empresamunicipio[0]->estado ?>
                                                                                                
                        <?= date("d") . " de " . $MES . " de " . date("Y"); ?> -

                        <?= date("H:i") ?>
                                                                                                </p>
                                                                                                <p><center><font size = 4><b>DECLARA&Ccedil;&Atilde;O</b></font></center></p>
                                                                                                <br>


                                                                                                <h4><center>______________________________________________________________</center></h4>
                                                                                               <h4><center>Assinatura do Profissional</center></h4>
                                                                                                <h4><center>Carimbo</center></h4>
                                                                                               <br>
                                                                                                <br>
                                                                                               <p style="text-align: center"><b>AVISO:</b> CARO PROFISSIONAL, INFORMAMOS QUE QUALQUER RECLAMAÇÃO DAREMOS UM 
                                                                                                    PRAZO DE 05(CINCO DIAS) A CONTAR DA DATA DE RECEBIMENTO PARA REINVIDICAR SEUS 
                                                                                                    DIREITOS. A CLINICA NÃO RECEBERÁ CONTESTAÇÃO SOB HIPÓTESE ALGUMA FORA DO PRAZO DETERMINADO ACIMA
                                                                                                </p>
                                                                                            </div>
                                                                                    </div>
            <? } ?>
    <br><br><br><br><br><br><br><br>
     
</div>

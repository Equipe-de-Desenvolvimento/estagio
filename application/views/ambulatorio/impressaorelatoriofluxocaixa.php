<?
// echo '<pre>';
// print_r($relatorio_saldo_saida);
// die;
?>


<meta charset="UTF-8">
<div class="content"> <!-- Inicio da DIV content -->    
   <?
    $MES = '';
    $MES_ant = '';

   switch ($_POST['mes']) {
    case 01 : $MES = 'Janeiro';
        break;
    case 02 : $MES = 'Fevereiro';
        break;
    case 03 : $MES = 'Mar&ccedil;o';
        break;
    case 04 : $MES = 'Abril';
        break;
    case 05 : $MES = 'Maio';
        break;
    case 06 : $MES = 'Junho';
        break;
    case 07 : $MES = 'Julho';
        break;
    case 08 : $MES = 'Agosto';
        break;
    case 09 : $MES = 'Setembro';
        break;
    case 10 : $MES = 'Outubro';
        break;
    case 11 : $MES = 'Novembro';
        break;
    case 12 : $MES = 'Dezembro';
        break;
      
    }

   switch ($_POST['mes'] - 1) {
    case 1 : $MES_ant = 'Janeiro';
        break;
    case 2 : $MES_ant = 'Fevereiro';
        break;
    case 3 : $MES_ant = 'Mar&ccedil;o';
        break;
    case 4 : $MES_ant = 'Abril';
        break;
    case 5 : $MES_ant = 'Maio';
        break;
    case 6 : $MES_ant = 'Junho';
        break;
    case 7 : $MES_ant = 'Julho';
        break;
    case 8 : $MES_ant = 'Agosto';
        break;
    case 9 : $MES_ant = 'Setembro';
        break;
    case 10 : $MES_ant = 'Outubro';
        break;
    case 11 : $MES_ant = 'Novembro';
        break;
    case 12 : $MES_ant = 'Dezembro';
        break;
      
}
   
   ?>

    <?
    $ano = date("Y");
    if($_POST['tipoPesquisa'] == 'MENSAL'){
        
        // $MES = '';
        $periodo = "$MES de $ano";
    }else if($_POST['tipoPesquisa'] == 'ANUAL'){
        $periodo = "" . $_POST['ano'];
    }else{
    $data_inicio = $_POST['txtdata_inicio'];
    $data_fim = $_POST['txtdata_fim'];

    $periodo = $data_inicio.' - '.$data_fim;
    }
    
    ?>

    <style>
        .left{
            text-align: left;
        }
        .right{
            text-align: right;
        }
    
    </style>
    <h4>EMPRESA: <?=($_POST['empresa'] > 0) ? $empresa[0]->nome : 'TODAS';?></h4>
    <h4>FLUXO DE CAIXA DE MENSAL</h4>
    <h4>PERIODO: <?=$periodo?></h4>
    <hr>
    <?
    if (count($relatorio_entrada) > 0 || count($relatorio_saida) > 0) {
        ?>
        <table border="1">
            <thead>
                <tr>
                    <th class="tabela_header" colspan="1">ITENS</th>
                    <th class="tabela_header" colspan="1"> $ </th>
                    <th class="tabela_header" colspan="1">% NO GRUPO</th>
                    <th class="tabela_header" colspan="1">% TOTAL</th>
                </tr>
            </thead>
            <tr>
                <th class="tabela_header left" bgcolor="#C0C0C0" colspan="8">1. ENTRADAS</th>
                
            </tr>
            <?
            $contador = 0;
            $i = 0;
            $tipo_atual = '&nbsp;';
            $valor_totalEntrada = 0;
            $valor_tipo = 0;
            $percentual = 0;

            ?>


            <?
            foreach ($relatorio_entrada as $key => $item) {
                $valor_totalEntrada += $item->valor;
                if($item->valor_tipo > 0){
                    $percentual = round((100 * $item->valor)/$item->valor_tipo, 2);
                }else{
                    $percentual = 0;
                }

                if($item->relatorio_total > 0){
                    $percentual_tipo = round((100 * $item->valor_tipo)/$item->relatorio_total, 2);
                    $percentualNoTotal = round((100 * $item->valor)/$item->relatorio_total, 2);
                }else{
                    $percentual_tipo = 0;
                    $percentualNoTotal = 0;
                }
               
                ?>
                <?if($item->tipo != $tipo_atual){?>

                    <tr>
                        <th class="tabela_header left" bgcolor="#C0C0C0" colspan="1">
                            <?=$item->tipo?> 
                        </th>
                        <th class="tabela_header right" bgcolor="#C0C0C0" colspan="1">
                        <?=number_format($item->valor_tipo, 2, ",", ".");?>
                        </th>
                        <th class="tabela_header right" bgcolor="#C0C0C0" colspan="1">
                          100%  
                        </th>
                        <th class="tabela_header right" bgcolor="#C0C0C0" colspan="1">
                          <?=str_replace('.', ',', $percentual_tipo)?> %
                        </th>
                    </tr>
                    
                <?}
                $i++;
                $tipo_atual = $item->tipo;
                ?>
                <tr>
                    <td>
                        <?=$item->classe?> 
                    </td>
                    <td class="right">
                        <?=number_format($item->valor, 2, ",", ".");?>
                    </td>
                    <td class="right">
                        <?=str_replace('.', ',', $percentual);?>%
                    </td>
                    <td class="right">
                        <?=str_replace('.', ',', $percentualNoTotal);?>%
                    </td>
                </tr>

                <?if((count($relatorio_entrada)) == $i){?>
                    <tr>
                        <td colspan="1" bgcolor="#C0C0C0"><b>SOMA DAS ENTRADAS</b></td>
                        <td colspan="" bgcolor="#C0C0C0" class="right"><b><?= number_format($item->relatorio_total, 2, ",", "."); ?></b></td>
                        <td colspan="1" bgcolor="#C0C0C0" class="right"><b>100%</b></td>
                        
                        <td colspan="" bgcolor="#C0C0C0" class="right"><b>100%</b></td>
                    </tr>
                <?}?>
                

                
            <?}?>
            <tr>
                <th class="tabela_header left"  colspan="8">&nbsp;</th>
                
            </tr>
            <tr>
                <th class="tabela_header left" bgcolor="#C0C0C0" colspan="8">2. SAIDAS</th>
                
            </tr>
            <?
            $valor_totalSaida = 0;
            $i = 0;
            foreach ($relatorio_saida as $key => $item) {
                $valor_totalSaida += $item->valor;
                if($item->valor_tipo > 0){
                    $percentual = round((100 * $item->valor)/$item->valor_tipo, 2);
                }else{
                    $percentual = 0;
                }

                if($item->relatorio_total > 0){
                    $percentual_tipo = round((100 * $item->valor_tipo)/$item->relatorio_total, 2);
                    $percentualNoTotal = round((100 * $item->valor)/$item->relatorio_total, 2);
                }else{
                    $percentual_tipo = 0;
                    $percentualNoTotal = 0;
                }
               
                ?>
                <?if($item->tipo != $tipo_atual){?>

                    <tr>
                        <th class="tabela_header left" bgcolor="#C0C0C0" colspan="1">
                            <?=$item->tipo?> 
                        </th>
                        <th class="tabela_header right" bgcolor="#C0C0C0" colspan="1">
                        <?=number_format($item->valor_tipo, 2, ",", ".");?>
                        </th>
                        <th class="tabela_header right" bgcolor="#C0C0C0" colspan="1">
                          100%  
                        </th>
                        <th class="tabela_header right" bgcolor="#C0C0C0" colspan="1">
                          <?=str_replace('.', ',', $percentual_tipo)?> %
                        </th>
                    </tr>
                    
                <?}
                $i++;
                $tipo_atual = $item->tipo;
                ?>
                <tr>
                    <td>
                        <?=$item->classe?> 
                    </td>
                    <td class="right">
                        <?=number_format($item->valor, 2, ",", ".");?>
                    </td>
                    <td class="right">
                        <?=str_replace('.', ',', $percentual);?>%
                    </td>
                    <td class="right">
                        <?=str_replace('.', ',', $percentualNoTotal);?>%
                    </td>
                </tr>

                <?if((count($relatorio_saida)) == $i){?>
                    
                <?}?>
                

                
            <?}?>
            <tr>
                <td colspan="1" bgcolor="#C0C0C0"><b>SOMA DAS ENTRADAS</b></td>
                <td colspan="1" bgcolor="#C0C0C0" class="right" ><b><?= number_format($valor_totalEntrada, 2, ",", "."); ?></b></td>
                <td colspan="2" bgcolor="#C0C0C0"><b></b></td>  
            </tr>
            <tr>
                <td colspan="1" bgcolor="#C0C0C0"><b>SOMA DAS SAIDAS</b></td>
                <td colspan="1" bgcolor="#C0C0C0" class="right" ><b><?= number_format($valor_totalSaida, 2, ",", "."); ?></b></td>
                <td colspan="2" bgcolor="#C0C0C0"><b></b></td>    
            </tr>
            <?
            $_POST['ano'] = $_POST['ano'] - 1;
            $_POST['mes'] = $_POST['mes'] - 1;
            if($_POST['tipoPesquisa'] == 'MENSAL'){
                $anterior = "({$MES_ant})";
            }else{
                $anterior = "({$_POST['ano']})";
            }
            
            ?>
            <!-- <tr>
                <td colspan="1" bgcolor="#C0C0C0"><b>SALDO ANTERIOR <? $anterior?></b></td>
                <td colspan="1" bgcolor="#C0C0C0" class="right" ><b><? //number_format($saldo_antigo, 2, ",", "."); ?></b></td>
                <td colspan="2" bgcolor="#C0C0C0"><b></b></td>    
            </tr> -->
            <tr>
                <td colspan="1" ><b>
                <? if($_POST['tipoPesquisa'] == 'MENSAL'){
                        echo 'TOTAL SALDO DO MÊS';
                    }else if($_POST['tipoPesquisa'] == 'ANUAL'){
                        echo 'TOTAL SALDO DO ANO';
                    }else{
                        echo 'TOTAL SALDO DO PERIODO';
                    }?>
                </b></td>
                <td colspan="1" bgcolor="#C0C0C0" class="right" ><b><?= number_format($valor_totalEntrada - $valor_totalSaida, 2, ",", "."); ?></b></td>
                <td colspan="2" ><b></b></td>    
            </tr>
            <?
            //$sup_def = $saldo_antigo + ($valor_totalEntrada - $valor_totalSaida);
            
            ?>
            <!-- <tr>
                <td colspan="1" ><b>4. <?=($sup_def > 0)? 'SUPERAVIT' : 'DÉFICIT' ?></b></td>
                <td colspan="1" bgcolor="#C0C0C0" class="right" ><b><?= number_format($sup_def, 2, ",", "."); ?></b></td>
                <td colspan="2" ><b></b></td>    
            </tr>
             -->
            
           
        </table>

        <h4>Obs: Transfer&ecirc;ncias n&atilde;o s&atilde;o mostradas nesse relatório </h4>


        <hr>
        <h3>Tabelas de Saldos (Até o Mês / Ano selecionado) </h3>
           
            <table border="1">
            <tr>
                <th bgcolor="#C0C0C0" class="right">Entradas</th>
                <td><?=number_format($relatorio_saldo_entrada[0]->relatorio_total, 2, ",", ".")?> $</td>
                <td> <a onClick="MostrarEntradas()"> <img src="<?=base_url()?>img/insert_table_on.gif"></a>   </td>
            </tr>
            <tr>
                <th bgcolor="#C0C0C0" class="right">Saidas</th>
                <td><?=number_format($relatorio_saldo_saida[0]->relatorio_total, 2, ",", ".")?> $</td>
                <td> <a onClick="MostrarSaidas()"> <img src="<?=base_url()?>img/insert_table_on.gif"></a>   </td>
            </tr>
            <tr>
           <? $totalsaldo = $relatorio_saldo_entrada[0]->relatorio_total - $relatorio_saldo_saida[0]->relatorio_total;?>
                <th bgcolor="#C0C0C0" class="right" colspan="3">Total Saldo Geral: <?=number_format($totalsaldo, 2, ",", ".")?> $</th>
            </tr>
            </table>
            <h4>O Total Saldo Geral é equivalente a todo os Saldos dos Meses anteriores</h4>

            <br>
            <table border="1" id="saldo_entradas" style="display: none;">
            <thead>
                <tr>
                    <th colspan="4" align="center">Entradas</th>
                </tr>
                <tr>
                    <th class="tabela_header" colspan="1">ITENS</th>
                    <th class="tabela_header" colspan="1"> $ </th>
                    <th class="tabela_header" colspan="1">% NO GRUPO</th>
                    <th class="tabela_header" colspan="1">% TOTAL</th>
                </tr>
            </thead>
            <?
                                    $i = 0;
                        foreach ($relatorio_saldo_entrada as $key => $item) {
                            $valor_totalEntrada += $item->valor;
                            if($item->valor_tipo > 0){
                                $percentual = round((100 * $item->valor)/$item->valor_tipo, 2);
                            }else{
                                $percentual = 0;
                            }
            
                            if($item->relatorio_total > 0){
                                $percentual_tipo = round((100 * $item->valor_tipo)/$item->relatorio_total, 2);
                                $percentualNoTotal = round((100 * $item->valor)/$item->relatorio_total, 2);
                            }else{
                                $percentual_tipo = 0;
                                $percentualNoTotal = 0;
                            }
                           
                            ?>
                            <?if($item->tipo != $tipo_atual){?>
            
                                <tr>
                                    <th class="tabela_header left" bgcolor="#C0C0C0" colspan="1">
                                        <?=$item->tipo?> 
                                    </th>
                                    <th class="tabela_header right" bgcolor="#C0C0C0" colspan="1">
                                    <?=number_format($item->valor_tipo, 2, ",", ".");?>
                                    </th>
                                    <th class="tabela_header right" bgcolor="#C0C0C0" colspan="1">
                                      100%  
                                    </th>
                                    <th class="tabela_header right" bgcolor="#C0C0C0" colspan="1">
                                      <?=str_replace('.', ',', $percentual_tipo)?> %
                                    </th>
                                </tr>
                                
                            <?}
                            $i++;
                            $tipo_atual = $item->tipo;
                            ?>
                            <tr>
                                <td>
                                    <?=$item->classe?> 
                                </td>
                                <td class="right">
                                    <?=number_format($item->valor, 2, ",", ".");?>
                                </td>
                                <td class="right">
                                    <?=str_replace('.', ',', $percentual);?>%
                                </td>
                                <td class="right">
                                    <?=str_replace('.', ',', $percentualNoTotal);?>%
                                </td>
                            </tr>
            
                            <?if((count($relatorio_saldo_entrada)) == $i){?>
                                <tr>
                                    <td colspan="1" bgcolor="#C0C0C0"><b>SOMA DAS ENTRADAS</b></td>
                                    <td colspan="" bgcolor="#C0C0C0" class="right"><b><?= number_format($item->relatorio_total, 2, ",", "."); ?></b></td>
                                    <td colspan="1" bgcolor="#C0C0C0" class="right"><b>100%</b></td>
                                    
                                    <td colspan="" bgcolor="#C0C0C0" class="right"><b>100%</b></td>
                                </tr>
                            <?}?>
                            
            
                            
                        <?}?>
                        </table>


                        <table border="1" id="saldo_saidas" style="display: none;">
            <thead>
                <tr>
                    <th colspan="4" align="center">Saidas</th>
                </tr>
                <tr>
                    <th class="tabela_header" colspan="1">ITENS</th>
                    <th class="tabela_header" colspan="1"> $ </th>
                    <th class="tabela_header" colspan="1">% NO GRUPO</th>
                    <th class="tabela_header" colspan="1">% TOTAL</th>
                </tr>
            </thead>
            <?
                                    $i = 0;
                        foreach ($relatorio_saldo_saida as $key => $item) {
                            $valor_totalEntrada += $item->valor;
                            if($item->valor_tipo > 0){
                                $percentual = round((100 * $item->valor)/$item->valor_tipo, 2);
                            }else{
                                $percentual = 0;
                            }
            
                            if($item->relatorio_total > 0){
                                $percentual_tipo = round((100 * $item->valor_tipo)/$item->relatorio_total, 2);
                                $percentualNoTotal = round((100 * $item->valor)/$item->relatorio_total, 2);
                            }else{
                                $percentual_tipo = 0;
                                $percentualNoTotal = 0;
                            }
                           
                            ?>
                            <?if($item->tipo != $tipo_atual){?>
            
                                <tr>
                                    <th class="tabela_header left" bgcolor="#C0C0C0" colspan="1">
                                        <?=$item->tipo?> 
                                    </th>
                                    <th class="tabela_header right" bgcolor="#C0C0C0" colspan="1">
                                    <?=number_format($item->valor_tipo, 2, ",", ".");?>
                                    </th>
                                    <th class="tabela_header right" bgcolor="#C0C0C0" colspan="1">
                                      100%  
                                    </th>
                                    <th class="tabela_header right" bgcolor="#C0C0C0" colspan="1">
                                      <?=str_replace('.', ',', $percentual_tipo)?> %
                                    </th>
                                </tr>
                                
                            <?}
                            $i++;
                            $tipo_atual = $item->tipo;
                            ?>
                            <tr>
                                <td>
                                    <?=$item->classe?> 
                                </td>
                                <td class="right">
                                    <?=number_format($item->valor, 2, ",", ".");?>
                                </td>
                                <td class="right">
                                    <?=str_replace('.', ',', $percentual);?>%
                                </td>
                                <td class="right">
                                    <?=str_replace('.', ',', $percentualNoTotal);?>%
                                </td>
                            </tr>
            
                            <?if((count($relatorio_saldo_saida)) == $i){?>
                                <tr>
                                    <td colspan="1" bgcolor="#C0C0C0"><b>SOMA DAS SAIDAS</b></td>
                                    <td colspan="" bgcolor="#C0C0C0" class="right"><b><?= number_format($item->relatorio_total, 2, ",", "."); ?></b></td>
                                    <td colspan="1" bgcolor="#C0C0C0" class="right"><b>100%</b></td>
                                    
                                    <td colspan="" bgcolor="#C0C0C0" class="right"><b>100%</b></td>
                                </tr>
                            <?}?>
                            
            
                            
                        <?}?>
                        </table>
        <?
    } else {
        ?>
        <h4>N&atilde;o h&aacute; resultados para esta consulta.</h4>
        <?
    }
    ?>

</div> <!-- Final da DIV content -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript">



    $(function () {
        $("#accordion").accordion();
    });

    function MostrarEntradas(){
        document.getElementById('saldo_entradas').style.display = 'block';
        document.getElementById('saldo_saidas').style.display = 'none';
      }

      function MostrarSaidas(){
        document.getElementById('saldo_entradas').style.display = 'none';
        document.getElementById('saldo_saidas').style.display = 'block';
      }

</script>

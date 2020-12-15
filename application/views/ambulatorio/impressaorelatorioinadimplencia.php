<div class="content"> <!-- Inicio da DIV content -->
    <meta charset="utf8"/>
    <?
    $tipoempresa = "";
    $credito = 0;
    ?>
    <table>
        <thead>

            <? if (count($empresa) > 0) { ?>
                <tr>
                    <th style='text-align: left; font-family: serif; font-size: 12pt;' colspan="5"><?= $empresa[0]->razao_social; ?></th>
                </tr>
                <?
                $tipoempresa = $empresa[0]->razao_social;
            } else {
                ?>
                <tr>
                    <th style='text-align: left; font-family: serif; font-size: 12pt;' colspan="5">TODAS AS CLINICAS</th>
                </tr>
                <?
                $tipoempresa = 'TODAS';
            }
            ?>
            <tr>
                <th style='text-align: left; font-family: serif; font-size: 12pt;' colspan="5">RESUMO GERAL</th>
            </tr>
            <tr>
                <th style='width:10pt;border:solid windowtext 1.0pt;
                    border-bottom:none;mso-border-top-alt:none;border-left:
                    none;border-right:none;' colspan="5">&nbsp;</th>
            </tr>
            <tr>
                <th style='text-align: left; font-family: serif; font-size: 12pt;' colspan="5">EMPRESA: <?= $tipoempresa ?></th>
            </tr>
            <tr>
                <th style='text-align: left; font-family: serif; font-size: 12pt;' colspan="5">PERIODO:

                    <?
                    if ($txtdata_inicio == "1969-12-31") {
                        echo $txtdata_inicio = "##/##/####";
                    } else {
                        echo str_replace("-", "/", date("d-m-Y", strtotime($txtdata_inicio)));
                    }
                    ?>
                    ate 
                    <?
                    if ($txtdata_fim == "1969-12-31") {
                        echo $txtdata_fim = "##/##/####";
                    } else {
                        echo str_replace("-", "/", date("d-m-Y", strtotime($txtdata_fim)));
                    }
                    ?>


                </th>
            </tr>
            <? if ($txtNome != '') { ?>
                <tr>
                    <th style='text-align: left; font-family: serif; font-size: 12pt;' colspan="4">PACIENTE: <?= $txtNome ?></th>
                </tr>
            <? } ?>
            <tr>
                <th style='width:10pt;border:solid windowtext 1.0pt;
                    border-bottom:none;mso-border-top-alt:none;border-left:
                    none;border-right:none;' colspan="5">&nbsp;</th>
            </tr>
            <tr>
                <th style='text-align: left;' width="320px;"><font size="-1">Paciente</th>
                <th style='text-align: right;'width="420px;"><font size="-1">Procedimento (Inadimplência)</th>
                <th style='text-align: right;'width="220px;"><font size="-1">Valor Inadimplência</th>
                <th style='text-align: right;'width="220px;"><font size="-1">Data do Cadastro (Inadimplência)</th>
                 <th style='text-align: right;'width="420px;"><font size="-1">Observação</th>
            </tr> 
            <?
            $i = 0;
            if (count($inadimplencias) > 0) {
                foreach ($inadimplencias as $item) {
                    $arrayobservacao = '';
                    $observacaoextra = '';
                    if($item->agenda_exames_id != '' && $item->agenda_exames_id != null){
                        $arrayobservacao = $this->guia->pegarobservacaorelatoriocaixa($item->agenda_exames_id);
                        
                        foreach($arrayobservacao as $value){
                            $observacaoextra .= $value->observacao.'<br>';
                        }
                    }
                    
                    
                    ?>
                    <tr>
                        <td ><font size="-1"><?= $item->paciente ?></td>
                        <td style='text-align: right;'><font size="-1"><?= $item->procedimento ?></td>
                        <td style='text-align: right;'><font size="-1"><?= "R$ " . number_format($item->valor, 2, ',', '.') ?></td>
                        <td style='text-align: right;'><font size="-1"><?= date("d/m/Y", strtotime($item->data_cadastro)) ?></td>
                         <td style='text-align: right;'><font size="-1"><?=  $observacaoextra.''.@$item->observacaoinadimplencia; ?></td>
                    </tr>
                    <tr>
                        <td colspan="5"> <hr></td>
                    </tr>
                    <?
                    $i++;
                    $credito = $credito + $item->valor;
                }
            }
            ?>
            <tr>
                <td colspan="3" style='text-align: left;'width="120px;"><font size="-1"><b>TOTAL DE INADIMPLÊNCIA</b></td>
                <td style='text-align: right;'width="120px;"><font size="-1"><b><?= $i ?></b></td>
            </tr> 
            <tr>
                <td colspan="3" style='text-align: left;'width="120px;"><font size="-1"><b>VALOR TOTAL DE INADIMPLÊNCIA</b></td>
                <td style='text-align: right;'width="120px;"><font size="-1"><b><?= "R$ " . number_format($credito, 2, ',', '.') ?></b></td>
            </tr> 






    </table>
    <br>


</div> <!-- Final da DIV content -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript">



    $(function () {
        $("#accordion").accordion();
    });

</script>
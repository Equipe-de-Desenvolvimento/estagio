<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
        <?
        $tipoempresa = "";
        $perfil_id = $this->session->userdata('perfil_id');
//        var_dump($convenios[0]);die;
        ?>

        <table>
            <thead>

                <? if (count($empresa) > 0) { ?>
                    <tr>
                        <th style='text-align: left; font-family: serif; font-size: 12pt;' colspan="7"><?= $empresa[0]->razao_social; ?></th>
                    </tr>
                    <?
                    $tipoempresa = $empresa[0]->razao_social;
                } else {
                    ?>
                    <tr>
                        <th style='text-align: left; font-family: serif; font-size: 12pt;' colspan="7">TODAS AS CLINICAS</th>
                    </tr>
                    <?
                    $tipoempresa = 'TODAS';
                }
                ?>
                <tr>
                    <th style='text-align: left; font-family: serif; font-size: 12pt;' colspan="7">FATURAMENTO POR PER&Iacute;ODO DE COMPET&Ecirc;NCIA</th>
                </tr>
                <tr>
                    <th style='width:10pt;border:solid windowtext 1.0pt;
                        border-bottom:none;mso-border-top-alt:none;border-left:
                        none;border-right:none;' colspan="9">&nbsp;</th>
                </tr>
                <tr>
                    <th style='text-align: left; font-family: serif; font-size: 12pt;' colspan="7">EMPRESA: <?= $tipoempresa ?></th>
                </tr>

                <? if ($convenio == "0") { ?>
                    <tr>
                        <th style='text-align: left; font-family: serif; font-size: 12pt;' colspan="7">CONVENIO:TODOS OS CONVENIOS</th>
                    </tr>
                <? } elseif ($convenio == "-1") { ?>
                    <tr>
                        <th style='text-align: left; font-family: serif; font-size: 12pt;' colspan="7">CONVENIO:PARTICULARES</th>
                    </tr>
                <? } elseif ($convenio == "") { ?>
                    <tr>
                        <th style='text-align: left; font-family: serif; font-size: 12pt;' colspan="7">CONVENIO: CONVENIOS</th>
                    </tr>
                <? } else { ?>
                    <tr>
                        <th style='text-align: left; font-family: serif; font-size: 12pt;' colspan="7">CONVENIO: <?= $convenios[0]->nome; ?></th>
                    </tr>
                <? } ?>
                <tr>
                    <th style='text-align: left; font-family: serif; font-size: 12pt;' colspan="7">ESPECIALIDADE: <?= ($_POST['grupo'] != '0') ? $_POST['grupo'] : 'TODOS'; ?></th>
                </tr>
                <?
                // Pequena gambiarra pra colocar acento no Não
                if ($_POST['situacao'] == 'NAO PAGO') {
                    $_POST['situacao'] = 'NÃO PAGO';
                }
                ?>
                <tr>
                    <th style='text-align: left; font-family: serif; font-size: 12pt;' colspan="7">SITUAÇÃO: <?= ($_POST['situacao'] != '') ? $_POST['situacao'] : 'TODOS'; ?></th>
                </tr>

                <tr>
                    <th style='text-align: left; font-family: serif; font-size: 12pt;' colspan="7">PERIODO: <?= str_replace("-", "/", date("d-m-Y", strtotime($txtdata_inicio))); ?> ate <?= str_replace("-", "/", date("d-m-Y", strtotime($txtdata_fim))); ?></th>
                </tr>
                <tr>
                    <th style='width:10pt;border:solid windowtext 1.0pt;
                        border-bottom:none;mso-border-top-alt:none;border-left:
                        none;border-right:none;' colspan="9">&nbsp;</th>
                </tr>
            </thead>
        </table>
        <?
        $financeiro = 'f';
        $valortotal = 0;
        $faturado = 0;
        $pendente = 0;
        $guia = "";
        $valortotal_faturado = 0;
        $valortotal_naofaturado = 0;
        $total = count($listar);
        if (count($listar) > 0) {
            ?>
            <table>
                <thead>
                    <tr>
                        <th width="60px;">Guia</th>
                        <th width="90px;">Autoriza&ccedil;&atilde;o</th>
                        <th width="130px;">Procedimento</th>
                        <th width="90px;"><div style="margin-left:8pt;">Convenio</div></th>
                        <th width="60px;">Codigo</th>
                        <th width="90px;">Medico</th>
                        <th width="130px;">Data Atendi</th>
                        <th width="130px;">Data Fatura</th>
                        <th width="110px;">Nome</th>
                        <th width="180px;">Obs.</th>
                        <th width="60px;">Valor Fatur.</th>
                        <th width="60px;">Valor Proc.</th>

                        <th colspan="3"><center>A&ccedil;&otilde;es</center></th>
                </tr>
                <tr>
                    <th style='width:10pt;border:solid windowtext 1.0pt;
                        border-bottom:none;mso-border-top-alt:none;border-left:
                        none;border-right:none;' colspan="9">&nbsp;</th>
                </tr>
                </thead> 


                <tbody>


                    <?php
                    @$contar_faturado = 0;

                    @$contar_pagos = 0;


                    foreach ($listar as $item) {

                        $array_totos_procediemnto = $item->agenda_exames_id;

                        $valortotal = $valortotal + $item->valor_mais_quantidade;

                        if ($item->faturado == 't') {
                            $valortotal_faturado = $valortotal_faturado + $item->valortotal;
                        } else {
                            $valortotal_naofaturado = ($valortotal - $valortotal_faturado);
                        }

                        $guia = $item->ambulatorio_guia_id;
                        if ($item->financeiro == 't') {
                            $financeiro = 't';
                        }
                        ?>
                        <tr>
                            <td ><a onmouseover="style = 'color:red;cursor: pointer;'" onmouseout="style = 'color:black;'"style="" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/impressaoguiaconsultaspsadt/<?= $item->ambulatorio_guia_id; ?>');">
                                    <?= $item->ambulatorio_guia_id ?>
                                </a></td>
                            <td ><?= $item->autorizacao; ?></td>
                            <td ><a style="cursor: pointer; color: blue;" onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/faturamentodetalhes/" . $item->agenda_exames_id; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=800,height=700');"><?= substr($item->procedimento, 0, 16) . " " . $item->numero_sessao; ?></a></td>
                            <td ><div style="margin-left:8pt;"><?= $item->nome; ?></div></td>
                            <td ><?= $item->codigo; ?></td>
                            <td >
                                <? if (count($item->medico) > 0) { ?>
                                    <a style="text-decoration: none; color: black;" title="<? echo $item->medico; ?>" href="#"><font color="c60000"><? echo substr($item->medico, 0, 10); ?>(...)</a>
                                    <?
                                } else {
                                    echo $item->medico;
                                }
                                ?>
                            </td>
                            <td ><?= substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4); ?></td>
                            <td ><?= substr($item->data_faturar, 8, 2) . "/" . substr($item->data_faturar, 5, 2) . "/" . substr($item->data_faturar, 0, 4); ?></td>
                            <? if ($item->faturado == "t") { ?>
                                <td>
                                    <font color="green"><? echo $item->paciente; ?>

                                </td>
                                <?
                            } else {
                                ?>
                                <td>
                                    <font color="c60000"><? echo $item->paciente; ?>

                                </td>
                            <? } ?>

                            <td >
                                <div class="observacao">
                                    <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/exame/alterarobservacaofaturar/" . $item->agenda_exames_id; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,\n\width=500,height=230');">
                                        =><?= $item->observacao_faturamento; ?>
                                    </a>
                                </div>
                            </td>
                            <?
                            if ($item->numero_sessao > 1 && $item->dinheiro == 't') {
                                $item->valor1 = 0;
                                $item->valor2 = 0;
                                $item->valor3 = 0;
                                $item->valor4 = 0;
                                $item->valor = 0;
                            }
                            if ($item->faturado == "t") {
                                ?>
                                <td <? if ($item->ajuste_cbhpm == 't' && $item->valor != $item->valor_total) { ?>style="color: blue;" title="Ajustado" <? } ?>>
                                    <?
                                    if ($item->valor1 != 0 || $item->valor2 != 0 || $item->valor3 != 0 || $item->valor4 != 0) {
                                        $valortotgeral = $item->valor1 + $item->valor2 + $item->valor3 + $item->valor4;
                                        echo number_format($valortotgeral, 2, ',', '.');
                                    } else { 
                                        if ($item->numero_sessao > 1 && $item->dinheiro == 't') {
                                            $item->valor1 = 0;
                                            $item->valor2 = 0;
                                            $item->valor3 = 0;
                                            $item->valor4 = 0;
                                            $item->valor = 0;
                                            $item->valor_total = 0;
                                        }   
                                            echo number_format($item->valor_total, 2, ',', '.');
                                    }
                                    ?>
                                </td>  
                            <? } else { ?>
                                
                                    <td <? if ($item->ajuste_cbhpm == 't' && $item->valor != $item->valor_total) { ?>style="color: blue;" title="Ajustado" <? } ?>>
                                        <?                                        
                                        if ($item->numero_sessao > 1 && $item->dinheiro == 't') {
                                            $item->valor1 = 0;
                                            $item->valor2 = 0;
                                            $item->valor3 = 0;
                                            $item->valor4 = 0;
                                            $item->valor = 0;
                                            $item->valor_total = 0;
                                        }                                        
 
                                            echo number_format($item->valor_total, 2, ',', '.');
 
                                        ?>   

                                    </td>
                                
                                
                            <? } ?>
                            <td ><?= number_format($item->valor, 2, ",", "."); ?></td>
                            <?
                            if ($item->faturado != "t") {
                                $faturado = 1;
                                ?>
                                <td width="40px;"><div class="bt_link">
            <? if ($perfil_id != 10) { ?>
                                            <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/faturarconvenio/" . $item->agenda_exames_id; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=600,height=250');">Faturar
                                            </a>
                                        <? } else { ?>
                                            Faturar  
            <? } ?>
                                    </div>
                                </td>
        <? } else { ?>
                                <td>Faturado&nbsp;</td>

            <? $contar_faturado++; ?>



                                <td width="40px;"><div class="bt_link">
            <? if ($perfil_id != 10) { ?>


                                            <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/desfazerfaturadoconvenio/" . $item->agenda_exames_id; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=600,height=250');">Desfazer
                                            </a>                                          
            <? } ?>
                                    </div>
                                </td>
                            <? }
                            ?>



                            <td width="110px;">
                                <div class="bt_link" style="width: 100pt" <?if($item->data_realizacao == NULL){?> title="Paciente na Sala de Espera" <?}?> >
        <? if ($perfil_id != 10 && $item->data_realizacao != NULL) { ?>
                                        <a style="width: 100pt" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/faturarguia/<?= $item->ambulatorio_guia_id ?>/<?= $item->paciente_id ?>');" >
                                            Faturar guia
                                        </a>
                                    <? } else {?>
                                        Faturar guia
        <? } ?>
                                </div>
                            </td>

                            <td width="40px;"><div class="bt_link">
                                    <? if ($item->faturado == "t") { ?>

                                        <? if ($item->situacao_faturamento == "") { ?>
                <? if ($perfil_id != 10) { ?>

                                                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/faturarconveniostatus/" . $item->agenda_exames_id; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=600,height=250');">
                                                    Situação
                                                </a>

                                            <? } else { ?>
                                                Situação
                                            <? }
                                            ?>
                                        <? } ?>
            <? if ($item->situacao_faturamento == "GLOSADO") { ?>       
                                            <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/faturarconveniostatus/" . $item->agenda_exames_id; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=600,height=250');">
                                                Glosado
                                            </a>
                                        <? } ?> 
            <? if ($item->situacao_faturamento == "NAO PAGO") { ?>       
                                            <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/faturarconveniostatus/" . $item->agenda_exames_id; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=600,height=250');">
                                                Não Pago
                                            </a>
                                        <? } ?> 
                                        <?
                                        if ($item->situacao_faturamento == "PAGO") {
                                            @$contar_pagos++;
                                            ?>        
                                            Pago
                                        <? } ?>

                                    <? } else { ?>   
                                        Situação
                                    <? }
                                    ?>
                                </div>
                            </td>


                        </tr>

                    </tbody>


                    <?php
                }
                ?>
















            </table>   
            <table>

                <tfoot>
                    <tr>
                        <th colspan="2" >
                            Registros: <?php echo $total; ?>
                        </th>
                        <th colspan="2" >
                            Valor Total: <?php echo number_format($valortotal, 2, ',', '.'); ?>
                        </th>
                        <th colspan="3" >
                            Valor Total Faturado: <?php echo number_format($valortotal_faturado, 2, ',', '.'); ?>
                        </th>
                        <th colspan="4" >
                            Valor Total Não Faturado: <?php echo number_format($valortotal_naofaturado, 2, ',', '.'); ?>
                        </th>
                        <? if ($_POST['convenio'] != '') { ?>                        
                            <? $valor = $this->convenio->listarvalorcontrato($_POST['convenio']); ?>
                            <?
                            if ($valor[0]->valor_contrato > 0) {
                                $valor_contrato = $valor[0]->valor_contrato;
                            } else {
                                $valor_contrato = -1;
                            }
                        }
                        ?>
                        <? if ($financeiro == 't') { ?>
                            <td width="40px;" style="color:red;">Faturamento Fechado</td>
    <? } elseif ($faturado == 0 && $convenios != 0 && $_POST['grupo'] == '0') { ?>
                    <form name="form_caixa" id="form_caixa" action="<?= base_url() ?>ambulatorio/exame/fecharfinanceiro/<?= $valor_contrato ?>" method="post">
                        <input type="hidden" class="texto3" name="dinheiro" value="<?= number_format($valortotal, 2, ',', '.'); ?>" readonly/>
                        <input type="hidden" class="texto3" name="relacao" value="<?= $convenios[0]->credor_devedor_id; ?>"/>
                        <input type="hidden" class="texto3" name="conta" value="<?= $convenios[0]->conta_id; ?>"/>
                        <input type="hidden" class="texto3" name="data1" value="<?= $txtdata_inicio; ?>"/>
                        <input type="hidden" class="texto3" name="data2" value="<?= $txtdata_fim; ?>"/>
                        <input type="hidden" class="texto3" name="convenio" value="<?= $convenio; ?>"/>
                        <input type="hidden" class="texto3" name="empresa" value="<?= $_POST['empresa']; ?>"/>
                        <th colspan="3" align="center"><center>
                            <button type="submit" name="btnEnviar">Financeiro</button></center></th>
                    </form>
                <? } else { ?>
        <? if ($_POST['grupo'] != '0') { ?>
                        <th colspan="3" >RETIRE O FILTRO DE GRUPO
                        </th>
        <? } else { ?>
                        <th colspan="3" >PENDENTE DE FATURAMENTO
                        </th>
        <? } ?>



    <? } ?>
                </tr>
                </tfoot>

            </table>
            <?
        }
        ?>
        <table>
            <? if (count($listarinternacao) > 0) {
                ?>

                <thead>
                    <tr>
                        <!--<th width="60px;">Numero de Internação</th>-->
                        <th>Procedimento</th>
                        <th><div style="margin-left:8pt;">Convenio</div></th>
                        <th>Codigo</th>
                        <th>Nome</th>
                        <th>Data Internação</th>
                        <th width="50px;" style="text-align: center">Dias</th>
                        <!--<th width="180px;">Obs.</th>-->
                        <th>Valor Fatur.</th>

                        <th colspan="3"><center>A&ccedil;&otilde;es</center></th>
                </tr>
                <tr>
                    <th style='width:10pt;border:solid windowtext 1.0pt;
                        border-bottom:none;mso-border-top-alt:none;border-left:
                        none;border-right:none;' colspan="10">&nbsp;</th>
                </tr>
                <tr><th colspan="10" class="destaque">Procedimentos Internação</th></tr>
                </thead>
                <tbody>
                    <?php
                    $valortotal_faturado_int = 0;
                    $valortotal_int = 0;
                    $valortotal_naofaturado_int = 0;
                    foreach ($listarinternacao as $item) {
                        $total++;
                        $valortotal_faturado_int = $valortotal_faturado_int + $item->valor_faturado_sum;
                        $valortotal_naofaturado_int = $valortotal_naofaturado_int + $item->valor_naofaturado_sum;

                        if ((int) @$item->contador_sum != (int) @$item->contador_faturado_sum || @$item->contador_sum == 0) {
                            $faturado = 1;
                        }
                        if ($item->contador_financeiro > 0) {
                            $financeiro = 't';
                        }
                        ?>
                        <tr>
                            <!--<td ><?= $item->internacao_id; ?></td>-->
                            <td ><a style="cursor: pointer; color: blue;"><?= substr($item->procedimento, 0, 16); ?></a></td>
                            <td ><div style="margin-left:8pt;"><?= $item->convenio; ?></div></td>
                            <td ><?= $item->codigo; ?></td>
        <? if ((int) @$item->contador_sum == (int) @$item->contador_faturado_sum && @$item->contador_sum > 0) { ?>
                                <td>
                                    <font color="green"><? echo $item->paciente; ?>

                                </td>
                                <?
                            } else {
                                ?>
                                <td>
                                    <font color="c60000"><? echo $item->paciente; ?>

                                </td>
        <? } ?>
                            <td ><?= substr($item->data_internacao, 8, 2) . "/" . substr($item->data_internacao, 5, 2) . "/" . substr($item->data_internacao, 0, 4); ?></td>
                            <td style="text-align: center"><?= @$item->contador_sum; ?></td>

                                                                                                                                                                                                                                    <!--                                <td >
                                                                                                                                                                                                                                                                    <div class="observacao">
                                                                                                                                                                                                                                                                        <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/exame/alterarobservacaofaturaramentomanual/" . $item->ambulatorio_guia_id; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,\n\width=500,height=230');">
                                                                                                                                                                                                                                                                            =><?= $item->observacoes; ?>
                                                                                                                                                                                                                                                                        </a>
                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                </td>-->
                            <td ><?= number_format(@$item->valor_total_sum, 2, ",", "."); ?></td>
        <? if ((int) @$item->contador_sum == (int) @$item->contador_faturado_sum && @$item->contador_sum > 0) { ?>



                                <td>Faturado</td>



        <? } else { ?>


                                <td>Pendente</td>

        <? } ?>
                            <td>

                            </td>
                            <td>
        <? if ($item->contador_financeiro == 0) { ?>
                                    <div class="bt_link_new">
                                        <a  onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/procedimentosinternacao/<?= $item->internacao_id ?>/<?= $item->paciente_id ?>');" >Procedimentos</a>
                                    </div>
        <? } ?>
                            </td>
                        </tr>

                    </tbody>
                    <?php
                    $valortotal_int += (float) @$item->valor_total_sum;
                }
                
                ?>
                <table>

                    <tfoot>
                        <tr>
                            <th colspan="2" >
                                Registros: <?php echo $total; ?>
                            </th>
                            <th colspan="2" >
                                Valor Total: <?php echo number_format($valortotal_int, 2, ',', '.'); ?>
                            </th>
                            <th colspan="3" >
                                Valor Total Faturado: <?php echo number_format($valortotal_faturado_int, 2, ',', '.'); ?>
                            </th>
                            <th colspan="4" >
                                Valor Total Não Faturado: <?php echo number_format($valortotal_naofaturado_int, 2, ',', '.'); ?>
                            </th>
                            <? if ($_POST['convenio'] != '') { ?>                        
                            <? $valor = $this->convenio->listarvalorcontrato($_POST['convenio']); ?>
                            <?
                            if ($valor[0]->valor_contrato > 0) {
                                $valor_contrato = $valor[0]->valor_contrato;
                            } else {
                                $valor_contrato = -1;
                            }
                        }

                        ?>
                            <? if ($financeiro == 't') { ?>
                                <td width="40px;" style="color:red;">Faturamento Fechado</td>
    <? } elseif ($faturado == 0 && $convenios != 0 && $_POST['grupo'] == '0') { ?>


                        <form name="form_caixa" id="form_caixa" action="<?= base_url() ?>ambulatorio/exame/fecharfinanceirointernacao/<?= $valor_contrato ?>" method="post">

                            <input type="hidden" class="texto3" name="dinheiro" value="<?= number_format($valortotal_int, 2, ',', '.'); ?>" readonly/>
                            <input type="hidden" class="texto3" name="relacao" value="<?= $convenios[0]->credor_devedor_id; ?>"/>
                            <input type="hidden" class="texto3" name="conta" value="<?= $convenios[0]->conta_id; ?>"/>
                            <input type="hidden" class="texto3" name="data1" value="<?= $txtdata_inicio; ?>"/>
                            <input type="hidden" class="texto3" name="data2" value="<?= $txtdata_fim; ?>"/>
                            <input type="hidden" class="texto3" name="convenio" value="<?= $convenio; ?>"/>
                            <input type="hidden" class="texto3" name="empresa" value="<?= $_POST['empresa']; ?>"/>
                            <th colspan="3" align="center"><center>
                                <button type="submit" name="btnEnviar">Financeiro</button></center></th>
                        </form>
                    <? } else { ?>
        <? if ($_POST['grupo'] != '0') { ?>
                            <th colspan="3" >RETIRE O FILTRO DE GRUPO
                            </th>
        <? } elseif ($convenios == 0) { ?>
                            <th colspan="3" >É NECESSÁRIO ESCOLHER UM CONVÊNIO NO FILTRO
                            </th>

        <? } else { ?>
                            <th colspan="3" >PENDENTE DE FATURAMENTO
                            </th>
                        <? } ?>                        
    <? } ?>

                    </tr>
                    </tfoot>


<? } ?>
            </table>

            <table>
            <? if (count(@$listarcirurgico) > 0) {
                ?>

                <thead>
                    <tr>
                        <!--<th width="60px;">Numero de Internação</th>-->
                        <th>Procedimento</th>
                        <th><div style="margin-left:8pt;">Convenio</div></th>
                        <th>Codigo</th>
                        <th>Nome</th>
                        <th>Data</th>
                        <!--<th width="180px;">Obs.</th>-->
                        <th>Valor</th>
                        <th>Valor Fatur.</th>

                        <th colspan="3"><center>A&ccedil;&otilde;es</center></th>
                </tr>
                <tr>
                    <th style='width:10pt;border:solid windowtext 1.0pt;
                        border-bottom:none;mso-border-top-alt:none;border-left:
                        none;border-right:none;' colspan="10">&nbsp;</th>
                </tr>
                <tr><th colspan="10" class="destaque">Procedimentos Cirurgicos</th></tr>
                </thead>
                <tbody>
                    <?php
                    $valortotal_faturado_int = 0;
                    $valortotal_int = 0;
                    $valortotal_naofaturado_int = 0;
                    foreach ($listarcirurgico as $item) {
                        // echo '<pre>';
                        // print_r($listarcirurgico);
                        // die;

                        $total++;
                        
                        if($item->faturado == 't'){
                            $valortotal_faturado_int = $valortotal_faturado_int + $item->valor_faturado;
                        }else{
                            $valortotal_naofaturado_int = $valortotal_naofaturado_int + $item->valor;
                        }
                         if ($item->contador_financeiro > 0) {
                             $financeiro = 't';
                         }
                         
       
                        ?>
                        <tr>
                            <td ><a style="cursor: pointer; color: blue;"><?= substr($item->procedimento, 0, 16); ?></a></td>
                            <td ><div style="margin-left:8pt;"><?= $item->convenio; ?></div></td>
                            <td ><?= $item->codigo; ?></td>
                            
        <? if ($item->faturado == 't') { ?>
                                <td>
                                    <font color="green"><? echo $item->paciente; ?>

                                </td>
                                <?
                            } else {
                                ?>
                                <td>
                                    <font color="c60000"><? echo $item->paciente; ?>

                                </td>
        <? } ?>
                            <td ><?= substr($item->data_cadastro, 8, 2) . "/" . substr($item->data_cadastro, 5, 2) . "/" . substr($item->data_cadastro, 0, 4); ?></td>

                                                                                                                                                                                                                                    <!--                                <td >
                                                                                                                                                                                                                                                                    <div class="observacao">
                                                                                                                                                                                                                                                                        <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/exame/alterarobservacaofaturaramentomanual/" . $item->ambulatorio_guia_id; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,\n\width=500,height=230');">
                                                                                                                                                                                                                                                                            =><?= $item->observacoes; ?>
                                                                                                                                                                                                                                                                        </a>
                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                </td>-->
                            <td ><?= number_format(@$item->valor, 2, ",", "."); ?></td>
                 <? if ($item->faturado == 't') { ?>



                <td><?= number_format(@$item->valor_faturado, 2, ",", "."); ?></td>



                <? } else { ?>


                    <td><?= number_format(@$item->valor, 2, ",", "."); ?></td>

                    <? } ?>

        <? if ($item->faturado == 't') { ?>



                                <td>Faturado</td>



        <? } else { ?>


                                <td>Pendente</td>

        <? } if($item->faturado == 't'){
            if($financeiro == 't'){
                ?>
                            <td align="center">
                                    <div class="bt_link_new">
                                        <a title="O Faturamento já foi enviado ao Financeiro, não é possivel alterar">Financeiro Fechado</a>
                                    </div>
                            </td> 
                <?
            }else{?>
            <td align="center">
                                    <div class="bt_link_new">
                                        <a  onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/desfazerconveniocirurgico/<?= @$item->solicitacao_cirurgia_procedimento_id?>/<?= @$item->agenda_exames_id?>/<?= @$item->solicitacao_cirurgia_id?>');" >Desfazer</a>
                                    </div>
                            </td>          
        <?} } else {?>
            <td align="center">
                                    <div class="bt_link_new">
                                        <a  onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/faturarconveniocirurgico/<?= @$item->solicitacao_cirurgia_procedimento_id ?>', '', 'height=230, width=600, left='+(window.innerWidth-600)/2+', top='+(window.innerHeight-230)/2);" >Faturar</a>
                                    </div>
                            </td>
        <?}?>

                            <!-- <td>
                                    <div class="bt_link_new">
                                        <a  onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/procedimentosinternacao/<?= @$item->internacao_id ?>/<?= $item->paciente_id ?>');" >Procedimentos</a>
                                    </div>

                            </td> -->
                        </tr>

                    </tbody>
                    <?php
                    $valortotal_int += (float) @$item->valor;
                }
                
                ?>
                <table>

                    <tfoot>
                        <tr>
                            <th colspan="2" >
                                Registros: <?php echo $total; ?>
                            </th>
                            <th colspan="2" >
                                Valor Total: <?php echo number_format($valortotal_int, 2, ',', '.'); ?>
                            </th>
                            <th colspan="3" >
                                Valor Total Faturado: <?php echo number_format($valortotal_faturado_int, 2, ',', '.'); ?>
                            </th>
                            <th colspan="4" >
                                Valor Total Não Faturado: <?php echo number_format($valortotal_naofaturado_int, 2, ',', '.'); ?>
                            </th>
                            <? if ($financeiro == 't') { ?>
                                <td width="40px;" style="color:red;">Faturamento Fechado</td>
    <? } elseif ($valortotal_naofaturado_int == 0 && $convenios != 0 && $_POST['grupo'] == '0') { ?>


                        <form name="form_caixa" id="form_caixa" action="<?= base_url() ?>ambulatorio/exame/fecharfinanceirocirurgico/<?=$valortotal_faturado_int ?>" method="post">

                            <input type="hidden" class="texto3" name="dinheiro" value="<?= number_format($valortotal_int, 2, ',', '.'); ?>" readonly/>
                            <input type="hidden" class="texto3" name="relacao" value="<?= $convenios[0]->credor_devedor_id; ?>"/>
                            <input type="hidden" class="texto3" name="conta" value="<?= $convenios[0]->conta_id; ?>"/>
                            <input type="hidden" class="texto3" name="data1" value="<?= $txtdata_inicio; ?>"/>
                            <input type="hidden" class="texto3" name="data2" value="<?= $txtdata_fim; ?>"/>
                            <input type="hidden" class="texto3" name="convenio" value="<?= $convenio; ?>"/>
                            <input type="hidden" class="texto3" name="empresa" value="<?= $_POST['empresa']; ?>"/>
                            <th colspan="3" align="center"><center>
                                <button type="submit" name="btnEnviar">Financeiro</button></center></th>
                        </form>
                    <? } else { ?>
        <? if ($_POST['grupo'] != '0') { ?>
                            <th colspan="3" >RETIRE O FILTRO DE GRUPO
                            </th>
        <? } elseif ($convenios == 0) { ?>
                            <th colspan="3" >É NECESSÁRIO ESCOLHER UM CONVÊNIO NO FILTRO
                            </th>

        <? } else { ?>
                            <th colspan="3" >PENDENTE DE FATURAMENTO
                            </th>
                        <? } ?>                        
    <? } ?>

                    </tr>
                    </tfoot>


<? } ?>
            </table>

            <br><br>


            <table align="left" style="width: 100px;">
                    <? if ($_POST['convenio'] != '') { ?>
                    <tr height="60px">
    <? $valor = $this->convenio->listarvalorcontrato($_POST['convenio']); ?>
                        <td width="300px;" align="Right" colspan="3"><b>Contrato de Valor Fixo: <?= number_format($valor[0]->valor_contrato, 2, ',', '.'); ?></b></td>
                    </tr>
                <? } ?>
                <? // echo '<pre>'; var_dump($valor_contrato[0]->valor_contrato);die; ?>
<? if ($convenio > 0) { ?>
                    <tr height="60px">
                        <th width="100px">

                       <? if (count(@$listarcirurgico) > 0) { ?>
                        <form name="form_caixa" id="form_caixa" action="<?= base_url() ?>ambulatorio/exame/faturartodosfaturamentocirurgico" method="post" target="_blank">
                            <?php
                                foreach ($listarcirurgico as $item) {

                            ?>
                         <input type="hidden" class="texto3" name="agenda_exames_id_array[]" value="<?= $item->agenda_exames_id; ?>"/>
                         <input type="hidden" class="texto3" name="solicitacao_cirurgia_array[]" value="<?= $item->solicitacao_cirurgia_id; ?>"/>
                            <?php
                                                            }
                            ?>

                            <div class="bt_link">
                                <!-- <a href="#"> -->
                            <button style="width:100%;height:100%" onclick="javascript: return confirm('Deseja realmente faturar todos?');" style="text-decoration: none;" type="submit" >
                             Faturar Todos
                            </button>
                                 <!-- </a> -->
                            </div>  
                            </form>
                       <? } else { ?>

                            <form name="form_caixa" id="form_caixa" action="<?= base_url() ?>ambulatorio/exame/faturartodosfaturamento" method="post" target="_blank">

                                <input type="hidden" class="texto3" name="data1" value="<?= $txtdata_inicio; ?>"/>
                                <input type="hidden" class="texto3" name="data2" value="<?= $txtdata_fim; ?>"/>
                                <input type="hidden" class="texto3" name="convenio" value="<?= $convenio; ?>"/>
                                <input type="hidden" class="texto3" name="empresa" value="<?= $_POST['empresa']; ?>"/>
                                <?php
                                foreach ($listar as $item) {
                                    ?>
                                    <input type="hidden" class="texto3" name="paciente_id_array[]" value="<?= $item->paciente_id; ?>"/>
                                    <?php
                                }
                                ?>

                                <div class="bt_link">
                                    <!-- <a href="#"> -->
                                    <button style="width:100%;height:100%" onclick="javascript: return confirm('Deseja realmente faturar todos?');" style="text-decoration: none;" type="submit" >
                                        Faturar Todos
                                    </button>
                                    <!-- </a> -->
                                </div>  
                            </form>
                            <? } ?>
                        </th>
                    </tr>
<? } ?>

            </table>
            <br><br>
            <table border="1">
                <tr>
                    <td bgcolor="c60000" width="4px;"></td>
                    <td width="40px;">&nbsp;Em Aberto</td>
                    <td bgcolor="green" width="4px;"></td>
                    <td width="40px;">&nbsp;Faturado</td>
                </tr>
            </table>
    </div>

    <?
    if ((@$total == @$contar_faturado && @$contar_faturado != 0)) {
        ?>

        <br>


        <?
        $empresa_id = $this->session->userdata('empresa_id');

        $data['retorno_header'] = $this->exame->permisoesempresa($empresa_id);

        if ($data['retorno_header'][0]->pagar_todos == 't') {






//  
// CASO AMBRAM UM CHAMADO DIZENDO QUE SE TIVER TODOS PAGOS NÃO PRECISA MOSTRAR "Pagar todos" a logica  :)                          
//                                                      
//      if (@$total == @$contar_faturado && @$contar_faturado != 0 && @$contar_pagos == @$total && @$contar_pagos !=0) {
//                                
//                            }else{  }
//              
//                                
            ?>


            <form method="post" action="<?= base_url() ?>ambulatorio/exame/pagartodosprocedimentos">  
                <input type="hidden" class="texto3" name="data1" value="<?= $txtdata_inicio; ?>"/>
                <input type="hidden" class="texto3" name="data2" value="<?= $txtdata_fim; ?>"/>
                <input type="hidden" class="texto3" name="convenio" value="<?= $convenio; ?>"/>
                <input type="hidden" class="texto3" name="empresa" value="<?= $_POST['empresa']; ?>"/>
                <button style="height:30px;" type="submit" value="" > <font >Pagar todos </font></button>   
            </form>




            <?
        } else {
            
        }
        ?>



        <?
    } else {
        
    }
    ?>







</div> <!-- Final da DIV content -->
<style>
    .observacao{
        max-height: 50pt;
        max-width: 170px;

        word-wrap: break-word;
        overflow-y: auto;
    }
    .destaque{
        font-size: 20px;
        font-style: italic;
        font-style: bold;
    }
</style>
<script type="text/javascript" src="<?= base_url() ?>js/scripts.js" ></script>
<script type="text/javascript">


</script>

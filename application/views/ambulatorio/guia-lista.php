<?php
//echo "<pre>";
//print_r($exames);
?>
<div class="content ficha_ceatox">
    <div class="bt_link_new">
        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/novo/<?= $paciente['0']->paciente_id ?>');">
            Nova guia
        </a>
    </div>
    <?
    $empresa_id = $this->session->userdata('empresa_id');
    $empresapermissoes = $this->guia->listarempresapermissoes($empresa_id);

    $limitar_acesso = $empresapermissoes[0]->limitar_acesso;
    $operador_id = $this->session->userdata('operador_id');
    $empresa = $this->session->userdata('empresa');
   
    $perfil_id = $this->session->userdata('perfil_id');
    $botao_faturar_guia = $this->session->userdata('botao_faturar_guia');
    $botao_faturar_proc = $this->session->userdata('botao_faturar_proc');

    ?>
    <div>
        <form name="form_guia" id="form_guia" action="<?= base_url() ?>ambulatorio/guia/gravarprocedimentos" method="post">
            <fieldset>
                <legend>Dados do Paciente</legend>
                <div>
                    <label>Nome</label>                      
                    <input type="text" id="txtNome" name="nome"  class="texto09" value="<?= $paciente['0']->nome; ?>" readonly/>
                </div>
                <div>
                    <label>Sexo</label>
                    <select name="sexo" id="txtSexo" class="size2">
                        <option value="M" <?
                        if ($paciente['0']->sexo == "M"):echo 'selected';
                        endif;
                        ?>>Masculino</option>
                        <option value="F" <?
                        if ($paciente['0']->sexo == "F"):echo 'selected';
                        endif;
                        ?>>Feminino</option>
                        <option value="O" <?
                        if ($paciente['0']->sexo == "O"):echo 'selected';
                        endif;
                        ?>>Outros</option>
                    </select>
                </div>

                <div>
                    <label>Nascimento</label>
                    <input type="text" name="nascimento" id="txtNascimento" class="texto02" alt="date" value="<?php echo substr($paciente['0']->nascimento, 8, 2) . '/' . substr($paciente['0']->nascimento, 5, 2) . '/' . substr($paciente['0']->nascimento, 0, 4); ?>" onblur="retornaIdade()" readonly/>
                </div>

                <div>
                    <label>Idade</label>
                    <?
                    if ($paciente['0']->nascimento != '') {
                        $data_atual = date('Y-m-d');
                        $data1 = new DateTime($data_atual);
                        $data2 = new DateTime($paciente[0]->nascimento);

                        $intervalo = $data1->diff($data2);
                        ?>
                        <input type="text" name="idade" id="idade" class="texto02" readonly value="<?= $intervalo->y ?> ano(s)"/>
                    <? } else { ?>
                        <input type="text" name="nascimento" id="txtNascimento" class="texto01" readonly/>
                    <? } ?>
                </div>

                <div>
                    <label>Nome da M&atilde;e</label>
                    <input type="text" name="nome_mae" id="txtNomeMae" class="texto08" value="<?= $paciente['0']->nome_mae; ?>" readonly/>
                </div>
            </fieldset>
        </form>

        <fieldset>
            <?
            foreach ($guia as $test) :
                $guia_id = $test->ambulatorio_guia_id;
                $cancelado = 0;
                $empresa = 0;
                if ($test->empresa_id == $empresa_id || @$empresapermissoes[0]->empresas_unicas == 't') {
//                    echo'<pre>';var_dump($guia);die;
                    ?>
                    <table >
                        <thead>
                            <tr>
                                <th class="tabela_header">Guia: 
                                    <a onmouseover="style = 'color:red;cursor: pointer;'" onmouseout="style = 'color:white;'"style="" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/impressaoguiaconsultaspsadt/<?= $test->ambulatorio_guia_id; ?>');">
                                        <?= $test->ambulatorio_guia_id ?>
                                    </a>
                                </th>

                                <th class="tabela_header">
                                <div class="bt_link">
                                <a  onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/log/<?= $guia_id ?>', '', 'height=230, width=600, left='+(window.innerWidth-600)/2+', top='+(window.innerHeight-230)/2);" >LOG</a>
                                </div>
                                </th>

                                <?
                                if ($perfil_id != 12) {
                                    if ($empresapermissoes[0]->ajuste_pagamento_procedimento != 't') {
                                        if ($botao_faturar_guia == 't') {
                                            ?>
                                            <th class="tabela_header">
                                                <div class="bt_link">
                                                    <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/faturarguia/" . $guia_id; ?> ', '_blank', 'width=1000,height=600');">F. Guia</a>
                                                </div>
                                            </th> 
                                            <?
                                        }
                                        if ($botao_faturar_proc == 't') {
                                            ?>
                                            <? if ($empresapermissoes[0]->faturamento_novo == 'f') { ?>
                                                <th class="tabela_header">
                                                    <div class="bt_link">
                                                        <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/faturarprocedimentos/" . $guia_id; ?> ', '_blank', 'width=1000,height=600');">F. Proc</a>
                                                    </div>
                                                </th>  
                                            <? } else { ?>
                                                <th class="tabela_header">
                                                    <div class="bt_link">
                                                        <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/faturarprocedimentosmodelo2/" . $guia_id; ?> ', '_blank', 'width=1000,height=600');">F. Proc</a>
                                                    </div>
                                                </th>
                                            <? } ?>
                                            <?
                                        }
                                    } else { 
                                        ?>
                                        <th class="tabela_header">
                                            <div class="bt_link">
                                                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/faturarprocedimentospersonalizados/" . $guia_id; ?> ', '_blank', 'width=1000,height=600');">F. Proc</a>
                                            </div>
                                        </th>  
                                        <?
                                    }
                                    
                                }
                                ?>

                                <th class="tabela_header"><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/anexarimagem/" . $guia_id; ?> ', '_blank', 'width=800,height=600');">Arquivos

                                        </a></div></th>
                                <th class="tabela_header"><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/guiadeclaracao/" . $guia_id; ?> ', '_blank', 'width=950,height=700');">Declara&ccedil;&atilde;o

                                        </a></div></th>
                                <?if($empresapermissoes[0]->valores_recepcao == 't' || $operador_id == 1 ){
                                 if ($limitar_acesso == 't') { ?>        
                                    <? if ($perfil_id != 2) { ?>
                                        <th class="tabela_header"><div class="bt_link">
                                                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/guiavalor/" . $guia_id; ?> ', '_blank', 'width=800,height=600');">Valor

                                                </a></div></th>

                                    <? } ?>
                                <? } else { ?>
                                    <? if ($perfil_id != 11 && $perfil_id != 2) { ?>
                                        <th class="tabela_header"><div class="bt_link">
                                                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/guiavalor/" . $guia_id; ?> ', '_blank', 'width=800,height=600');">Valor

                                                </a></div></th>

                                    <? } ?>
                                <? } 
                                
                                    }?>
                                
                                <? if ($empresapermissoes[0]->nota_fiscal_sp == 't') { ?>
                                <th class="tabela_header"><div class="bt_link">
                                                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/xmlprefeituradesp/" . $guia_id; ?> ', 'width=800,height=600');">Nota F. Sp
                                                </a></div>
                                </th>
                                <? } ?>
                                
                                <th class="tabela_header"><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/guiaobservacao/" . $guia_id; ?> ', '_blank', 'width=800,height=600');">Observa&ccedil;&atilde;o

                                        </a></div></th>

                                <th class="tabela_header"><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/guiaconvenio/" . $guia_id; ?> ', '_blank', 'width=800,height=250');">N. Guia

                                        </a></div></th>
                                <th class="tabela_header"><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/exame/faturarguiamatmed/" . $guia_id . '/' . $paciente['0']->paciente_id; ?> ', '_blank');">Mat/Med

                                        </a></div></th>

                                        <th class="tabela_header"><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/materialodontologia/" . $guia_id; ?> ', '_blank', 'width=300,height=200');">Mat. Odontologia

                                        </a></div></th>
                                <? if ($empresapermissoes[0]->endereco_integracao_lab != '') { ?>
                                    <th class="tabela_header">
                                        <div class="bt_link">
                                            <a target="_blank" href='<?= base_url() . "ambulatorio/guia/enviarExamesLabLuz/" . $guia_id . '/' . $paciente['0']->paciente_id; ?>'>
                                                Importar Lab
                                            </a>
                                        </div>
                                    </th>
                                    <th class="tabela_header" colspan="4"> <!-- colpan = "6" -->
                                        <div class="bt_link">
                                            <a target="_blank" href='<?= base_url() . "ambulatorio/guia/resultadoExamesLabLuz/" . $guia_id . '/' . $paciente['0']->paciente_id; ?>'>
                                                Resultado Lab
                                            </a>
                                        </div>
                                    </th>
                                    <th class="tabela_header" colspan="1"> <!-- colpan = "6" -->
                                        <div class="bt_link">
                                            <a target="_blank" href='<?= base_url() . "ambulatorio/guia/etiquetaExamesLab/" . $guia_id . '/' . $paciente['0']->paciente_id; ?>'>
                                                Etiqueta
                                            </a>
                                        </div>
                                    </th>
                                    <!-- <th class="tabela_header" colspan="5"></th>   -->
                                <? } else { ?>
                                    <th class="tabela_header" style="width: 100px;"></th>
                                    <th class="tabela_header" colspan="7"></th>  
                                <? }
                                ?>

                                <!--IMPORTAÇÃO Diagnósticos do Brasil-->
        <!--                                                            <th class="tabela_header">
                      <div class="bt_link">
                                        <a target="_blank" href='<?= base_url() . "ambulatorio/guia/enviarExamesDigBra/" . $guia_id . '/' . $paciente['0']->paciente_id; ?>'>
                                        Importar DB
                                        </a>
                                    </div>
                           </th>     -->


                            </tr>
                        </thead>
                        <tbody>
                            <?
                        } else {
                            $empresa ++;
                        }
                        $estilo_linha = "tabela_content01";
                        foreach ($exames as $item) :
//                            echo '<pre>';var_dump($item);die;
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";


                            if ($test->ambulatorio_guia_id == $item->guia_id) {
                                $cancelado++;
                                if ($item->empresa_id == $empresa_id || @$empresapermissoes[0]->empresas_unicas == 't') {
                                    ?>
                                    <tr>
                                        <td class="<?php echo $estilo_linha; ?>" width="100px;"><a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/impressaoguiaconsultaconvenio/<?= $paciente['0']->paciente_id; ?>/<?= $item->guia_id; ?>/<?= $item->agenda_exames_id ?>');"><?= $item->procedimento ?></a></td>
                                        <?
                                        if (isset($item->data_antiga)) {
                                            $data_alterada = 'alterada';
                                        } else {
                                            $data_alterada = '';
                                        }
                                        ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="50px;">
                                            <table cellspacing="5">
                                                <tr>
                                                    <td class="<?php echo $estilo_linha; ?>"></td>
                                                    <td class="<?php echo $estilo_linha; ?>">
                                                        <?= $item->convenio ?>
                                                    </td>
                                                    <td class="<?php echo $estilo_linha; ?>"> 
                                                        <?= substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4); ?><br/><?= $data_alterada ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="<?php echo $estilo_linha; ?>" width="50px;">
                                            <table cellspacing="5">
                                                <tr>                                                     
                                                    <td class="<?php echo $estilo_linha; ?>">
                                                        <?= $item->inicio ?>
                                                    </td>
                                                    <?php
                                                    if (@$empresapermissoes[0]->empresas_unicas == 't') {
                                                        ?>
                                                        <td class="<?php echo $estilo_linha; ?>" width="20px;">
                                                            <b> <?= @$item->empresa; ?>  </b>
                                                        </td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                            <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/escolherdeclaracao/<?= $paciente['0']->paciente_id; ?>/<?= $item->guia_id; ?>/<?= $item->agenda_exames_id ?>');">Declaracao
                                            </a>
                                        </td>

                                        <?if($empresapermissoes[0]->valores_recepcao == 't' || $operador_id == 1 ){?>
                                            <? if ($empresapermissoes[0]->valor_recibo_guia == 't') { ?>
                                                <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                                    <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/reciboounota/<?= $paciente['0']->paciente_id; ?>/<?= $item->guia_id; ?>/<?= $item->agenda_exames_id ?>');">Recibo
                                                    </a>
                                                </td> 
                                            <? } else { ?>
                                                <? if ($test->valor_guia == '') { ?>
                                                    <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                                        <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/guiavalor/" . $guia_id; ?> ', '_blank', 'width=800,height=600');"> Valor Recibo

                                                        </a>
                                                    </td>  
                                                <? } else { ?>
                                                    <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/reciboounota/<?= $paciente['0']->paciente_id; ?>/<?= $item->guia_id; ?>/<?= $item->agenda_exames_id ?>');">Recibo
                                                        </a>
                                                    </td>
                                                <? } ?> 

                                            <? } ?>
                                        <?}?> 




                                        <? if ($empresapermissoes[0]->retirar_botao_ficha == 'f') { ?>
                                            <td class="<?php echo $estilo_linha; ?>" >
                                                <? if ($empresaficha[0]->impressao_tipo == 47) { ?>
                                                    <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/impressaoficha/<?= $paciente['0']->paciente_id; ?>/<?= $item->guia_id; ?>/<?= $item->agenda_exames_id ?>');">Ficha
                                                    </a>
                                                <? } else { ?>
                                                    <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/impressaoficha/<?= $paciente['0']->paciente_id; ?>/<?= $item->guia_id; ?>/<?= $item->agenda_exames_id ?>');">Ficha
                                                    </a>
                                                    <?
                                                }
                                                $teste = $this->guia->listarfichatexto($item->agenda_exames_id);
                                                if (isset($teste[0]->agenda_exames_id)) {
                                                    ?>
                                                    <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/editarfichaxml/<?= $paciente['0']->paciente_id; ?>/<?= $item->agenda_exames_id ?>/<?= $item->agenda_exames_id ?>');"> //  Editar F. RM
                                                    </a>
                                                <? } ?>

                                            </td>
                                        <? } ?>
                                        <?
                                        $data_atual = date('Y-m-d');
                                        $data1 = new DateTime($data_atual);
                                        $data2 = new DateTime($item->data);

                                        $intervalo = $data1->diff($data2);
                                        ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="200"><?= $intervalo->days ?> dia(s)</td>
                                        <? if (isset($item->atendente) || isset($item->medicorealizou)) { ?>
                                            <td class="<?php echo $estilo_linha; ?>" style="padding: 5pt; width: auto;">
                                                <? if (isset($item->atendente)): ?>
                                                    <a style="text-decoration: none; color: black;" title="<? echo $item->atendente; ?>" href="#"><span style="font-weigth:bolder; text-decoration: underline; color: rgb(255,50,0);">Atendente:</span> <? echo substr($item->atendente, 0, 5); ?>(...)</a><br>
                                                    <?
                                                endif;
                                                if (isset($item->medicorealizou)):
                                                    ?>
                                                    <a style="text-decoration: none; color: black;" title="<? echo $item->medicorealizou; ?>" href="#"><span style="font-weigth:bolder; text-decoration: underline; color: rgb(255,50,0);">Medico:</span> <? echo substr($item->medicorealizou, 0, 5); ?>(...)</a>
                                                <? endif; ?>
                                            </td>
                                        <? } ?>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <!--                                    <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/editarfichaxml/<?= $paciente['0']->paciente_id; ?>/<?= $item->agenda_exames_id ?>');">Editar Ficha RM
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </a>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </td>-->
                                        <? if ($item->grupo != 'MEDICAMENTO' && $item->grupo != 'MATERIAL') { ?>

                                            <? if ($empresapermissoes[0]->retirar_botao_ficha == 'f') { ?>
                                                <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                                    <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/impressaofichaconvenio/<?= $paciente['0']->paciente_id; ?>/<?= $item->guia_id; ?>/<?= $item->agenda_exames_id ?>');">Ficha-convenio
                                                    </a>
                                                </td>
                                            <? } ?>
                                            <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                                <a href="<?= base_url() ?>ambulatorio/guia/impressaoetiiqueta/<?= $paciente['0']->paciente_id; ?>/<?= $item->guia_id; ?>/<?= $item->agenda_exames_id ?>">Etiqueta</a></div>
                                            </td>
                                            <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                                <a href="<?= base_url() ?>ambulatorio/guia/impressaoetiquetaunica/<?= $paciente['0']->paciente_id; ?>/<?= $item->guia_id; ?>/<?= $item->agenda_exames_id ?>">Etiq. unica
                                                </a>
                                            </td>
                                            <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                                <a href="<?= base_url() ?>ambulatorio/guia/editarexame/<?= $paciente['0']->paciente_id; ?>/<?= $item->guia_id; ?>/<?= $item->agenda_exames_id ?>">Editar
                                                </a>
                                            </td>
                                                

                                            <? 
                                            if ($perfil_id == 1 || $perfil_id == 6 || $perfil_id == 20) { ?>
                                                <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                                    <a href="<?= base_url() ?>ambulatorio/guia/valorexame/<?= $paciente['0']->paciente_id; ?>/<?= $item->guia_id; ?>/<?= $item->agenda_exames_id ?>">valor
                                                    </a>
                                                </td>

                                                <?
                                            }
                                        
                                            ?>
                                            <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                                <a href="#" onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/guiaconvenioexame/$guia_id/$item->agenda_exames_id"; ?> ', '_blank', 'width=800,height=250');">N. Guia

                                                </a>
                                            </td>


                                            <? if (($item->faturado == "f" || $perfil_id == 1 || $perfil_id == 13 || $perfil_id == 5 || ($perfil_id == 18 || $perfil_id == 20)) && ($item->dinheiro == "t")) { ?>
                                                <? if ($perfil_id != 12) { ?>
                                                    <? if ($empresapermissoes[0]->ajuste_pagamento_procedimento != 't') { ?>
                                                        <? if ($empresapermissoes[0]->faturamento_novo == 't') { ?>
                                                            <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                                                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/faturarmodelo2/" . $item->agenda_exames_id; ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->guia_id ?>', '_blank', 'width=1000,height=600');">Faturar</a>
                                                            </td>
                                                        <? } else { ?>
                                                            <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                                                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/faturar/" . $item->agenda_exames_id; ?>/<?= $item->procedimento_tuss_id ?>', '_blank', 'width=1000,height=600');">Faturar</a>
                                                            </td>
                                                        <? } ?> 

                                                    <? } else { ?>
                                                        <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                                            <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/faturarpersonalizado/" . $item->agenda_exames_id; ?>/<?= $item->procedimento_tuss_id ?>', '_blank', 'width=1000,height=600');">Faturar</a>
                                                        </td>
                                                    <? } ?>
                                                <? } ?>
                                            <? } else { ?>
                                                <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                                </td>
                                            <? } ?>
                                        <? } else {
                                            ?>

                                            <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                                <a href="#" onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/guiaconvenioexame/$guia_id/$item->agenda_exames_id"; ?> ', '_blank', 'width=800,height=250');">N. Guia

                                                </a>
                                            </td>
                                            <td colspan="5" class="<?php echo $estilo_linha; ?>" width="30px;">
                                                <a  onclick="javascript:window.open('<?= base_url() . "ambulatorio/exame/matmedcancelamento/" . $item->agenda_exames_id; ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?> ', '_blank', 'width=800,height=600');">Excluir

                                                </a>
                                            </td>
                                            <? if ($perfil_id != 12) { ?> 
                                                <?
                                                if ($empresapermissoes[0]->faturamento_novo == 't') {
                                                    ?>
                                                    <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                                        <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/faturarmodelo2/" . $item->agenda_exames_id; ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->guia_id ?>', '_blank', 'width=1000,height=600');">Faturar</a>
                                                    </td>
                                                    <?
                                                } else {
                                                    ?>
                                                    <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                                        <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/faturar/" . $item->agenda_exames_id; ?>/<?= $item->procedimento_tuss_id ?>', '_blank', 'width=1000,height=600');">Faturar</a>
                                                    </td> 
                                                    <?
                                                }
                                                ?>

                                            <? } ?>

                                        <? }
                                        ?>   
                                    </tr>


                                <? } else {
                                    ?>

                                    <?
                                }
                            }
                        endforeach;
                        ?>

                        <?
                        if ($empresa == 0) {


                            if ($cancelado == 0) {
                                ?>
                                <tr>
                                    <td colspan="6"><center><span style="color: red; font-weight: bold; font-size: 17px;">PROCEDIMENTO CANCELADO</span></center></td>
                            </tr>  

                            <?
                        }
                    }
                    ?>

                    </tbody>                                
                    <br>
                <? endforeach; ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="11">
                        </th>
                    </tr>
                </tfoot>
            </table>
        </fieldset>
    </div>


    <script type="text/javascript">



        $(function () {
            $(".competencia").accordion({autoHeight: false});
            $(".accordion").accordion({autoHeight: false, active: false});
            $(".lotacao").accordion({
                active: true,
                autoheight: false,
                clearStyle: true

            });


        });
    </script>

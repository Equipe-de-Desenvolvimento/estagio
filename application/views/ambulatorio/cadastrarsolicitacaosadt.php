<?php
//echo "<pre>";
//print_r(@$lista);
?>

<div class="content ficha_ceatox"> <!-- Inicio da DIV content -->
    <!--    <div class="bt_link_voltar">
            <a href="<?= base_url() ?>ambulatorio/guia/pesquisarsolicitacaosadt/<?= $paciente_id ?>/<?= $convenio_id ?>/<?= $solicitante_id ?>">
                Voltar
            </a> 
        </div>-->
    <div class="clear"></div>
    <form name="form_solicitacaoitens" id="form_solicitacaoitens" action="<?= base_url() ?>ambulatorio/guia/gravarprocedimentosolicitacaosadt/<?= $solicitacao_id ?>/<?= $paciente_id ?>/<?= $convenio_id ?>/<?= $solicitante_id ?>" method="post">
        <?
        $perfil_id = $this->session->userdata('perfil_id');
        ?>
        <fieldset>
            <legend>Dados da Solicitação</legend>
            <div>
                <label>Paciente</label>
                <input type="text" class="texto06" readonly value="<?= $guia[0]->paciente ?>"/> 
                <input type="hidden" class="texto06" id="exame_sala_id"  name="exame_sala_id" value="<?= @$sala[0]->exame_sala_id; ?>"/> 
                 <input type="hidden" class="texto06" id="ambulatorio_laudo_id"  name="ambulatorio_laudo_id" value="<?= @$ambulatorio_laudo_id; ?>"/> 
               
            </div>

            <div>
                <label>Médico Solicitante</label>
                <input type="text" class="texto06" readonly value="<?= $guia[0]->solicitante ?>"/> 
            </div>

            <div>
                <label>Convênio</label>
                <input type="text" id="convenio" readonly value="<?= $guia[0]->convenio ?>"/> 
            </div>

        </fieldset>
        <fieldset>
            <legend>Adicionar Procedimentos</legend>

            <fieldset>
                <legend>Procedimentos</legend>
                <input type="hidden" name="solicitacao_id" value="<?php echo $solicitacao_id; ?>"/>
                <table>
                    <tr>
                        <td>Quantidade</td>
                        <td>
                            <input type="text" name="quantidade" id="quantidade" value="1" class="texto00"/>
                        </td>
                    </tr>


                    <?
                    if (@$externo == "externo") {
                        ?> 
                        <tr>
                            <td>Procedimento</td>
                            <td>
                                <input type="text" value="<?= $externo; ?>" name="externo" hidden>
                                <input type="text" name="procedimentoescrito" required=""> 
                            </td>
                        </tr> 
                        <?
                    } else {
                        ?>

                        <tr>
                            <td>Procedimento</td>
                            <td>
                                <select name="procedimento1" id="procedimento1" class="size4 chosen-select" tabindex="1" required="">
                                    <option value="">Selecione</option>
                                    <? foreach ($lista as $item) { ?>
                                        <option value="<?= $item->procedimento_convenio_id ?>"><?= $item->procedimento ?></option>
                                    <? } ?>
                                </select>
                            </td>
                        </tr>

                        <?
                    }
                    ?>
                    <tr>
                        <td>Situação</td>
                        <td>
                            <select name="direcao" id="direcao" class="size1" tabindex="1" required="">
                                <option value="">Selecione</option>
                                <option value="1">Emergência</option>
                                <option value="2">Eletivo</option>                                     
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Justificativa</td>
                        <td>

                            <textarea name="justificativa" style="height:18px; width: 40%;"><?php
                                $ulticont = count($procedimentos_cadastrados) - 1;
                                echo @$procedimentos_cadastrados[$ulticont]->justificativa;
                                ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Diagnóstico provável</td>
                        <td>
                            <textarea name="dig_provavel" style="height:18px; width: 40%;"><?php
                                $ulticont = count($procedimentos_cadastrados) - 1;
                                echo @$procedimentos_cadastrados[$ulticont]->dig_provavel;
                                ?></textarea>
                        </td>
                    </tr>



                    <!--<tr>-->
                        <!--<td>Valor Unitario</td>-->
                        <!--<td>-->

                    <?
                    if (@$externo == "externo") {
                        ?>
                        <input type="text"  alt="decimal" name="valor1" id="valor1" class="texto02"   hidden=""/>
                        <?
                    } else {
                        ?>
                        <input readonly="" type="text" name="valor1" id="valor1" <?
                        if ($perfil_id != 1) {
                            echo 'readonly';
                        }
                        ?> class="texto01" hidden=""/>

                        <?
                    }
                    ?>


                    <!--</td>-->
                    <!--</tr>-->

                </table>
                <hr/>
                <button type="submit" name="btnEnviar">Enviar</button>
            </fieldset>

            <hr/>

            <!--<button type="submit" name="btnEnviar">Enviar</button>-->
            <!--<button type="reset" name="btnLimpar">Limpar</button>-->
        </fieldset>

        <fieldset id="cadastro"> 
            <!-- NAO REMOVA ESSE FIELDSET POIS O JAVASCRIPT IRA FUNCIONAR NELE!!! -->
        </fieldset>




    </form>
    <?
    if (count($procedimentos_cadastrados) > 0) {
        ?>
        <table id="table_agente_toxico" border="0" style="width:600px">
            <thead>

                <tr>
                    <th class="tabela_header">Procedimento</th>
                    <th class="tabela_header">Quantidade</th>
                    <th class="tabela_header">Convenio</th>
                    <th class="tabela_header">Tipo</th>
                    <th class="tabela_header">&nbsp;</th>

                </tr>
            </thead>
            <tbody>
                <?
                $estilo_linha = "tabela_content01";
                foreach ($procedimentos_cadastrados as $item) {
                    ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                    ?>

                    <tr>
                        <td class="<?php echo $estilo_linha; ?>">


                            <?
                            if ($item->procedimento == "") {
                                echo $item->procedimento_escrito;
                            } else {
                                echo $item->procedimento;
                            }
                            ?>


                        </td>
                        <td class="<?php echo $estilo_linha; ?>"><?= $item->quantidade; ?></td>
                        <td class="<?php echo $estilo_linha; ?>"><?= $item->convenio; ?></td>
                        <td class="<?php echo $estilo_linha; ?>"> 
                            <?php
                            if ($item->emergencia == "t") {
                                echo "Emergência";
                            } elseif ($item->eletivo == "t") {
                                echo "Eletivo";
                            } else {
                                
                            }
                            ?>
                        </td>
                        <td class="<?php echo $estilo_linha; ?>" width="100px;">
                <center>
                    <a href="<?= base_url() ?>ambulatorio/guia/excluirsolicitacaoprocedimentosadt/<?= $solicitacao_id ?>/<?= $item->solicitacao_sadt_procedimento_id; ?>" class="delete" target="_blank">
                    </a>
                </center>
                </td>
                </tr>


                <?
            }
        }
        ?>
        </tbody>

        <tfoot>
            <tr>
                <th class="tabela_footer" colspan="4">
                </th>
            </tr>
        </tfoot>
    </table> 
    <?php
    if (count($procedimentos_cadastrados) > 0) {
        ?>
        <br>
        <div class="bt_link_new">
            <a href="<?= base_url() ?>ambulatorio/guia/impressaosolicitacaosadt/<?= @$solicitacao_id; ?>/<?= @$sala[0]->exame_sala_id; ?>">
                Imprimir
            </a>
        </div>
        <?php
    }
    ?>


    <br><br>
    <div class="bt_link_new">
        <a href="<?= base_url() ?>ambulatorio/guia/gravarnovasolicitacaosadt/<?= $paciente_id ?>/<?= $convenio_id ?>/<?= $solicitante_id ?>/<?= @$ambulatorio_laudo_id; ?>/<?= @$sala[0]->exame_sala_id; ?>/<? echo "externo"; ?>">
            Guia Externo
        </a>
    </div>
    <fieldset>
        <legend>Histórico de solicitações antigas</legend>
        <table>
            <tr>
                <th class="tabela_header">Solicitação</th>
                <th class="tabela_header">Convênio</th>
                <th class="tabela_header">Solicitante</th>
                <th class="tabela_header">Data</th>
                <th class="tabela_header" width="70px;" colspan="3"><center>Detalhes</center></th>
            </tr>
            </thead>
            <?php
            $total = count($guiahistorico);
            if ($total > 0) {
                ?>
                <tbody>
                    <?php
                    $estilo_linha = "tabela_content01";
//                        echo "<pre>";
//                        print_r($guiahistorico);die;

                    foreach ($guiahistorico as $item) {
                        $atual = date("d/m/Y", strtotime($item->data_cadastro));
                        if ($atual == date('d/m/Y')) {
                            continue;
                        }



                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                        ?>
                        <tr>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->solicitacao_sadt_id; ?></td>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->convenio; ?></td>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->solicitante ?></td>
                            <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y H:i:s", strtotime($item->data_cadastro)); ?></td>

                            <?php
                            if ($empresapermissoes[0]->convenio_padrao == 't') {

                                if ($item->guia_externo == null || $item->guia_externo == '' || $item->guia_externo == 'f') {
                                    ?>

                                                                                                        <!--<td class="<?php echo $estilo_linha; ?>"><div class="bt_link">;-->
                                                                                                                <!--<a href="<?= base_url() ?>ambulatorio/guia/gravaratendimentosadt/<?= $item->solicitacao_sadt_id ?>/<?= $paciente['0']->paciente_id ?>/<?= $convenio_id ?>/<?= $solicitante_id ?>/externo" target="_blank">Finalizar</a>--> 
                                                                                                          <!--<a href="<?= base_url() ?>ambulatorio/guia/listarexamessolicitados/<?= $item->solicitacao_sadt_id ?>/<?= $paciente['0']->paciente_id ?>/<?= $convenio_id ?>/<?= $solicitante_id ?>/externo" target="_blank">Tela de Autorização</a>--> 

                                    <!--</div></td>-->

                                    <?
                                } else {
                                    ?>
                                <!--                                    <td class="<?php echo $estilo_linha; ?>">


                                                                    </td>-->

                                    <?
                                }
                            } else {
                                
                            }
                            ?>




                            <td class="<?php echo $estilo_linha; ?>" width="50px;" colspan="1">

                                <div class="bt_link" >


                                    <?
                                    if ($item->guia_externo == "t") {
                                        ?>
                                        <a href="<?= base_url() ?>ambulatorio/guia/cadastrarsolicitacaosadt/<?= $item->solicitacao_sadt_id ?>/<?= @$paciente_id ?>/<?= $convenio_id ?>/<?= $solicitante_id ?>/<?= @$sala[0]->exame_sala_id; ?>/externo">Cadastrar</a> 
                                        <?
                                    } else {
                                        ?>
                                        <a href="<?= base_url() ?>ambulatorio/guia/cadastrarsolicitacaosadt/<?= $item->solicitacao_sadt_id ?>/<?= @$paciente_id ?>/<?= $convenio_id ?>/<?= $solicitante_id ?>/<?= @$sala[0]->exame_sala_id; ?>">Cadastrar</a> 
                                    <? } ?>


                            </td>
                            <td class="<?php echo $estilo_linha; ?>" width="50px;" colspan="1"><div class="bt_link">
                                    <a href="<?= base_url() ?>ambulatorio/guia/impressaosolicitacaosadt/<?= $item->solicitacao_sadt_id ?>/<?= @$sala[0]->exame_sala_id; ?>">Imprimir</a></div>
                            </td>



                        </tr>
                    <? } ?>


                </tbody>
            <? } ?>
            <tfoot>
                <tr>
                    <th class="tabela_footer" colspan="8">
                        Total de registros: <?php echo $total; ?>
                    </th>
                </tr>
            </tfoot>
        </table>
    </fieldset>

</fieldset>
<!--<br>-->
<!--<table>-->
<?php
if (@$empresapermissoes[0]->convenio_padrao == 't') {
    ?>
                                    <!--<td class="<?php echo @$estilo_linha; ?>"><div class="bt_link">-->

                                <!--<a href="<?= base_url() ?>ambulatorio/guia/gravaratendimentosadt/<?= @$solicitacao_id ?>/<?= @$paciente_id ?>/<?= @$convenio_id ?>/<?= @$solicitante_id ?>/externo" target="_blank">Finalizar</a>--> 
                                 <!--<a href="<?= base_url() ?>ambulatorio/guia/listarexamessolicitados/<?= @$solicitacao_id ?>/<?= @$paciente_id ?>/<?= @$convenio_id ?>/<?= @$solicitante_id ?>/externo" target="_blank">Tela de Autorização</a>--> 

    <!--</div>-->
    <!--</td>--> 

    <td class="<?php echo @$estilo_linha; ?>">


    </td>

    <?
} else {
    
}
?>
<!--</table>-->

</div> <!-- Final da DIV content -->

<style>
    #label{
        display: inline-block;
        font-size: 15px;
    }
</style>

<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-verificaCPF.js"></script>-->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<!--<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">-->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript">

    $(document).ready(function () {



        $('#procedimento1_chosen').click(function () {
//            alert($('#procedimento1').val());
            if ($('#procedimento1').val()) {
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/procedimentovalor', {procedimento1: $('#procedimento1').val(), ajax: true}, function (j) {
                    options = "";
                    options += j[0].valortotal;
                    document.getElementById("valor1").value = options
                    $('.carregando').hide();
                });
            } else {
                $('#valor1').html('value=""');
            }
        });





    });




</script>

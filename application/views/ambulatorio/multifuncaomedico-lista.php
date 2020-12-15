<link href="<?= base_url() ?>css/ambulatorio/multifuncaomedico-lista.css?v=1" rel="stylesheet"/>
<script>
    // Fazendo a integracao
    $(function () {
        $.ajax({
            type: "GET",
            url: "<?= base_url(); ?>ambulatorio/laudo/multifuncaomedicointegracao",
            dataType: "json",
            success: function () {

            }
        });
    });
</script>
<div class="content"> <!-- Inicio da DIV content -->
    <table>
        <tr>
            <td>
                <div class="btn-group-sm">
                    <button class="btn btn-outline-default" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exametemp/mostrarlembretes', '_blank', 'toolbar=no,Location=no,menubar=no,width=600,height=700');" >
                        Lembretes
                    </button>
                    <button class="btn btn-outline-default" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exametemp/mostrarpendencias', '_blank', 'width=1600,height=700');" >
                        Ver Pendentes
                    </button>
                    <button class="btn btn-outline-default" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/agenda/medicoagenda');">
                        Bloquear Agenda
                    </button>
                </div>
            </td>
        </tr>
    </table>
    <div id="accordion">
        <h3 class="singular"><a href="#">Multifuncao Medico</a></h3>
        <div>
            <?
            $salas = $this->exame->listartodassalas();
            $empresa = $this->guia->listarempresasaladeespera();
            @$ordem_chegada = @$empresa[0]->ordem_chegada;
            @$ordenacao_situacao = @$empresa[0]->ordenacao_situacao;
            @$retirar_ordem_medico = @$empresa[0]->retirar_ordem_medico;
            @$retirar_medico_cadastro = @$empresa[0]->retirar_ordem_medico;
            $medicos = $this->operador_m->listarmedicos();
            $perfil_id = $this->session->userdata('perfil_id');
            $procedimento = $this->procedimento->listarprocedimento2();
            $empresa_id = $this->session->userdata('empresa_id');
            $data['empresa'] = $this->empresa->listarempresatoten($empresa_id);
            @$endereco = $data['empresa'][0]->endereco_toten;
            if (@$empresa[0]->campos_listaatendimentomed != '') {
                $campos_listaatendimentomed = json_decode(@$empresa[0]->campos_listaatendimentomed);
            } else {
                $campos_listaatendimentomed = array();
            }
            $data['empresa_permissao'] = $this->empresa->listarverificacaopermisao2($empresa_id);
            @$bardeira_status = @$data['empresa_permissao'][0]->bardeira_status;
            @$setores = @$data['empresa_permissao'][0]->setores;
            $bardeiras = $this->exame->listarbardeirasstatusmedico();
            $setor = $this->exame->listarsetores();
            $convenios = $this->convenio->listar()->get()->result();
            ?>

            <form method="get" action="<?= base_url() ?>ambulatorio/exame/listarmultifuncaomedico">
                <fieldset>
                    <div class="row">
                        <div class="col-lg-2">
                            <div>
                                <label>Salas</label>
                                <select name="sala" id="sala" class="form-control">
                                    <option value=""></option>
                                    <? foreach ($salas as $value) : ?>
                                        <option value="<?= $value->exame_sala_id; ?>" <?
                                        if (@$_GET['sala'] == $value->exame_sala_id):echo 'selected';
                                        endif;
                                        ?>><?php echo $value->nome; ?></option>
                                    <? endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <? if ($perfil_id != 4) { ?>
                        <div class="col-lg-3">
                            <div>
                                <label>Medico</label>
                                <select name="medico" id="medico" class="form-control">
                                    <option value=""> </option>
                                    <? foreach ($medicos as $value) : ?>
                                        <option value="<?= $value->operador_id; ?>"<?
                                        if (@$_GET['medico'] == $value->operador_id):echo 'selected';
                                        endif;
                                        ?>>
                                            <?php echo $value->nome; ?>

                                        </option>
                                    <? endforeach; ?>

                                </select>
                            </div>
                        </div>
                        <? } ?>
                        <div class="col-lg-2">
                            <div>
                                <label>Data</label>
                                <input type="date"  id="data" alt="date" name="data" class="form-control"  value="<?php echo @$_GET['data']; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Prontu&aacute;rio</label>
                                <input type="text"  id="prontuario" name="prontuario" class="form-control"  value="<?php echo @$_GET['prontuario']; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div>
                                <label>Nome </label>
                                <input type="text" name="nome" class="form-control bestupper" value="<?php echo @$_GET['nome']; ?>" />
                            </div>
                        </div>
                        <?if($empresa[0]->prontuario_antigo_pesquisar == 't'){?>
                        <div class="col-lg-2">
                            <div>
                                <label>Prontuário Antigo</label>
                                <input type="text" name="prontuario_antigo" class="texto03 bestupper" value="<?php echo @$_GET['prontuario_antigo']; ?>" />
                            </div>
                        </div>
                        <? } ?>
                        <div class="col-lg-3">
                            <div>
                                <label>Procedimento</label>
                                <select name="txtprocedimento" id="procedimento" class="chosen-select form-control">
                                    <option value="">Selecione</option>
                                    <? foreach ($procedimento as $value) : ?>
                                        <option value="<?= $value->nome; ?>"<?
                                        if (@$_GET['txtprocedimento'] == $value->nome):echo'selected';
                                        endif;
                                        ?>><?php echo $value->nome; ?></option>
                                    <? endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Cid</label>
                                <input type="text" name="txtCICPrimariolabel" id="txtCICPrimariolabel" class="form-control" value="<?php echo @$_GET['txtCICPrimariolabel']; ?>" />
                                <input type="hidden" name="txtCICPrimario" id="txtCICPrimario" value="" class="size2" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div>
                                <label>Convênio</label>
                                <select  class="chosen-select" name="convenios[]" id="convenios" multiple data-placeholder="Selecione">
                                    <option value="0" <?= @(in_array("0", $_GET['convenios'])) ? "selected":""; ?> >TODOS</option>
                                    <?php foreach($convenios as $item){?>
                                        <option value="<?= $item->convenio_id; ?>" <?= @(in_array($item->convenio_id, $_GET['convenios'])) ? "selected":""; ?> ><?= $item->nome; ?></option>
                                    <?}?>
                                </select>
                            </div>
                        </div>
                        <?if($bardeira_status == 't'){?>
                        <div class="col-lg-2">
                            <div>
                                <label>Bardeiras de Status</label>
                                <select name="bardeirastatus" id="bardeirastatus" class="size2">
                                    <option value=""></option>
                                    <? foreach ($bardeiras as $value) : ?>
                                        <option value="<?= $value->bardeira_id; ?>" <?
                                        if (@$_GET['bardeirastatus'] == $value->bardeira_id):echo 'selected';
                                        endif;
                                        ?>><?php echo $value->nome; ?></option>
                                    <? endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <? } ?>
                        <?if($setores == 't'){?>
                            <div class="col-lg-2">
                                <div>
                                    <label>Setores</label>
                                    <select name="setores" id="setores" class="size2">
                                        <option value=""></option>
                                        <? foreach ($setor as $value) : ?>
                                            <option value="<?= $value->setor_id; ?>" <?
                                            if (@$_GET['setores'] == $value->setor_id):echo 'selected';
                                            endif;
                                            ?>><?php echo $value->nome; ?></option>
                                        <? endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <? } ?>
                        <div class="col-lg-2 btnsend">
                            <button class="btn btn-outline-success" type="submit" id="enviar">Pesquisar</button>
                        </div>
                    </div>
                </fieldset>
                         <td colspan="2">

                        </td>
                        <?if($bardeira_status == 't'){?>
                        <td class="tabela_title">

                        </td>
                        <?}?>

                        <?if($setores == 't'){?>
                        <td class="tabela_title">

                        </td>
                        <?}?>
                    </tr>
            </form>

            <br>
            <table>
                <thead>
                    <tr>
                        <? if ($retirar_medico_cadastro == 'f') { ?>
                            <?if(in_array('ordem', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                <th class="tabela_header" width="100px;">Ordem</th>
                            <?}?>
                            
                            <?if(in_array('status', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                <th class="tabela_header" >Status</th>
                            <?}?>
                            
                        <? } ?>
                        <?if(in_array('nome', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header" width="250px;">Nome</th>
                        <?}?>
                        
                        <?if(in_array('idade', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header" width="100px;">Idade</th>
                        <?}?>
                       
                        <? if ($retirar_medico_cadastro == 'f') { ?>
                            <?if(in_array('espera', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                <th class="tabela_header" width="60px;">Espera</th>
                            <?}?>
                           
                        <? } ?>
                        <?if(in_array('convenio', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header" width="100px;">Convenio</th>
                        <?}?>
                       
                        <? if ($retirar_ordem_medico == 'f' && $retirar_medico_cadastro == 'f') { ?>
                            <?if(in_array('data', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                <th class="tabela_header" width="80px;">Data</th>
                            <?}?>
                            <?if(in_array('agenda', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                <th class="tabela_header" width="80px;">Agenda</th>
                            <?}?>

                            
                           
                            <?if(in_array('autorizacao', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                <th class="tabela_header" width="250px;">Autorização</th>
                            <?}?>
                            

                        <? } ?>
                        <?if(in_array('procedimento', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header" width="250px;">Procedimento</th>
                        <?}?>

                        <? if($setores == 't'){?>
                        <?if(in_array('procedimento', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header">Setor</th>
                        <?}?>
                            <?}?>

                        <? if($bardeira_status == 't'){?>
                        <?if(in_array('obs', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header">Bardeira Status</th>
                        <?}?>
                            <?}?>

                        <?if(in_array('statuslaudo', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header">Laudo</th>
                        <?}?>
                        <?if(in_array('obs', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header">Observações</th>
                        <?}?>
                        <th class="tabela_header" colspan="5"><center>A&ccedil;&otilde;es</center></th>
                </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
//                $consulta = $this->exame->listarmultifuncaomedico($_GET);
                $total = 2000000000000000000000;
                $limit = 50;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;
//                if ($total > 0) {
                ?>
                <tbody>
                    <?php
                    $lista = $this->exame->listarmultifuncao2medico($_GET, @$ordem_chegada, $ordenacao_situacao)->limit($limit, $pagina)->get()->result();
                    $estilo_linha = "tabela_content01";
                    $operador_id = $this->session->userdata('operador_id');


//                    echo "<pre>";
//                    print_r($lista);
//                    




                    foreach ($lista as $item) {

                        if($bardeira_status == 't'){
                            $verificarbardeira = $this->exame->verificarbardeirastatus($item->agenda_exames_id);
                        }

                        $laudo = false;
                        $dataFuturo = date("Y-m-d H:i:s");
                        $dataAtual = $item->data_autorizacao;
                        $date_time = new DateTime($dataAtual);
                        $diff = $date_time->diff(new DateTime($dataFuturo));
                        $teste = $diff->format('%H:%I:%S');

                        $dataFuturo2 = date("Y-m-d");
                        $dataAtual2 = $item->nascimento;
                        $date_time2 = new DateTime($dataAtual2);
                        $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
                        if ($retirar_ordem_medico == 'f') {
                            $teste2 = $diff2->format('%Ya %mm %dd');
                        } else {
                            $teste2 = $diff2->format('%Y');
                        }

                        if ($item->ordenador == 1) {
                            $ordenador = 'Normal';
                            $cor = 'blue';
                        } elseif ($item->ordenador == 2) {
                            $ordenador = 'Prioridade';
                            $cor = 'darkorange';
                        } elseif ($item->ordenador == 3) {
                            $ordenador = 'Urgência';
                            $cor = 'red';
                        } else {
                            $ordenador = $item->ordenador;
                        }


                        $verifica = 0;

                        if ($item->paciente != '') {
                            if ($item->cpf != '') {
                                $cpf = $item->cpf;
                            } else {
                                $cpf = 'null';
                            }
                            if ($item->toten_fila_id != '') {
                                $toten_fila_id = $item->toten_fila_id;
                            } else {
                                $toten_fila_id = 'null';
                            }
                            if ($item->toten_sala_id != '') {
                                $toten_sala_id = $item->toten_sala_id;
                            } else {
                                $toten_sala_id = 'null';
                            }
                            $url_enviar_ficha = "$endereco/webService/telaAtendimento/enviarFicha/$toten_fila_id/$item->paciente/$cpf/$item->medico_consulta_id/$item->medicoconsulta/$toten_sala_id/false";
                        } else {
                            $url_enviar_ficha = '';
                        }

                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                        if ($item->paciente == "" && $item->bloqueado == 't') {
                            $situacao = "Bloqueado";
                            $paciente = "Bloqueado";
                            $verifica = 5;
                        } else {
                            $paciente = "";

                            if ($item->realizada == 't' && $item->situacaoexame == 'EXECUTANDO') {
                                $situacao = "Atendendo";
                                $verifica = 2;
                            } elseif ($item->realizada == 't' && $item->situacaoexame == 'FINALIZADO') {
                                $situacao = "Finalizado";
                                $verifica = 4;
                            } elseif ($item->confirmado == 'f') {
                                $situacao = "agenda";
                                $verifica = 1;
                            } elseif ($item->situacaoexame == 'PENDENTE') {
                                $situacao = "pendente";
                                $verifica = 1;
                            } else {
//                                echo $item->situacaoexame;
                                $situacao = "espera";
                                $verifica = 3;
                            }
                        }
                        if ($item->paciente == "" && $item->bloqueado == 'f') {
                            $paciente = "vago";
                        }
                        ?>
                        <tr>



                        <? if ($retirar_medico_cadastro == 'f') { ?>
                                    <?if(in_array('ordem', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                        <td style="color: <?= @$cor ?>" class="<?php echo $estilo_linha; ?>"><?= $ordenador; ?></td>
                                    <?}?>
                                   
                                    <?if(in_array('status', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                        <? if ($verifica == 1) { ?>
                                            <td class="<?php echo $estilo_linha; ?>"><font <? ?>><b><?= $situacao; ?></b></td>
                                        <? }if ($verifica == 2) { ?>
                                            <td class="<?php echo $estilo_linha; ?>"><font color="green"><b><?= $situacao; ?></b></td>
                                        <? }if ($verifica == 3) { ?>
                                            <td class="<?php echo $estilo_linha; ?>"><font color="red"><b><?= $situacao; ?></b></td>
                                        <? }if ($verifica == 4) { ?>
                                            <td class="<?php echo $estilo_linha; ?>"><font color="blue"><b><?= $situacao; ?></b></td>
                                        <? } ?>
                                    <?}?>
                                    
                                <? } ?>
                                <?if(in_array('nome', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                    <? if ($verifica == 1) { ?>
                                        <td class="<?php echo $estilo_linha; ?>" onclick="javascript:window.open('<?= base_url() . "ambulatorio/laudo/auditorialaudo/" . $item->ambulatorio_laudo_id ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');"><?= $item->paciente; ?></td>
                                    <? }if ($verifica == 2) { ?>
                                        <td class="<?php echo $estilo_linha; ?>" onclick="javascript:window.open('<?= base_url() . "ambulatorio/laudo/auditorialaudo/" . $item->ambulatorio_laudo_id ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');"><font color="green"><b><?= $item->paciente; ?></b></td>
                                    <? }if ($verifica == 3) { ?>
                                        <td class="<?php echo $estilo_linha; ?>" onclick="javascript:window.open('<?= base_url() . "ambulatorio/laudo/auditorialaudo/" . $item->ambulatorio_laudo_id ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');"><font color="red"><b><?= $item->paciente; ?></b></td>
                                    <? }if ($verifica == 4) { ?>
                                        <td class="<?php echo $estilo_linha; ?>" onclick="javascript:window.open('<?= base_url() . "ambulatorio/laudo/auditorialaudo/" . $item->ambulatorio_laudo_id ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');"><font color="blue"><b><?= $item->paciente; ?></b></td>
                                    <? } ?>
                                <?}?>
                               
                                <?if(in_array('idade', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $teste2; ?></td>
                                <?}?>
                               
                                <?if(in_array('espera', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                    <? if ($retirar_medico_cadastro == 'f') { ?>
                                        <? if ($verifica == 4) { ?>
                                            <td class="<?php echo $estilo_linha; ?>">&nbsp;</td>
                                        <? } else { ?>
                                            <td class="<?php echo $estilo_linha; ?>"><?= $teste; ?></td>
                                        <? } ?>
                                    <? } ?>
                                <?}?>
                               
                                
                                <?if(in_array('convenio', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                    <? if ($item->convenio != '') { ?>
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->convenio; ?></td>
                                    <? } else { ?>
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->convenio_paciente; ?></td>
                                    <? } ?>
                                <?}?>
                                
                                
                                <? if ($retirar_ordem_medico == 'f' && $retirar_medico_cadastro == 'f') { ?>
                                    <?if(in_array('data', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                        <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data)) ?></td>
                                    <?}?>
                                    <?if(in_array('agenda', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                        <td class="<?php echo $estilo_linha; ?>"><?=  $item->inicio; ?></td>
                                    <?}?>
                                    <?if(in_array('autorizacao', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                        <td class="<?php echo $estilo_linha; ?>"><?
                                                if ($item->data_autorizacao != '') {
                                                    echo date("H:i:s", strtotime($item->data_autorizacao));
                                                }?>
                                        </td>
                                    <?}?>
                                    
                                    
                                    <?
                                }
                                ?>
                                <?if(in_array('procedimento', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->procedimento; ?></td>
                                <?}?>

                                <? if($setores == 't'){?>

                                <?if(in_array('procedimento', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->setore; ?></td>
                                <?}?>

                                <?}?>

                                <? if($bardeira_status == 't'){?>
                                
                                <?if(in_array('procedimento', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                    <td class="<?php echo $estilo_linha; ?>"><span class="statusbardeira" style="background-color:<?=@$verificarbardeira[0]->cor?>;"><?=@$verificarbardeira[0]->bardeira?></span></td>
                                <?}?>

                                <?}?>

                            <?if(in_array('statuslaudo', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                <? if ($item->situacaolaudo == 'FINALIZADO' || $item->situacaolaudo == 'REVISAR') { ?>
                                    <td class="<?php echo $estilo_linha; ?>"><font color="blue"><b><?= $item->situacaolaudo; ?></b></td>
                                <? } else { ?>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->situacaolaudo; ?></td>
                                <? } ?>
                            <?}?>
                               

                            

                            <?if(in_array('obs', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                <td class="<?php echo $estilo_linha; ?>">
                                    <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/alterarobservacao/<?= $item->agenda_exames_id ?>', '_blank', 'toolbar=no,Location=no,menubar=no width=500,height=400');">
                                        =><?= $item->observacoes; ?>
                                    </a>
                                </td>
                            <?}?>
                            




                            <? if ($item->situacaolaudo != '' && $item->situacaoexame != 'PENDENTE') { ?>  <td class="<?php echo $estilo_linha; ?>" width="40px;">
                                </td>
                                <?
                                if (($item->medico_parecer1 == $operador_id && $item->situacaolaudo == 'FINALIZADO') || ($item->situacaolaudo != 'FINALIZADO' && $item->situacaolaudo != '') || $operador_id == 1) {
                                    if ($item->grupo == 'ECOCARDIOGRAMA') {
                                        ?>

                                        <td class="<?php echo $estilo_linha; ?>" width="40px;">
                                            <?if(in_array('batender', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){ $laudo = true;?>
                                                <div class="bt_link">
                                                    <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarlaudoeco/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>');" >
                                                        Laudo
                                                    </a>
                                                </div>
                                            <?}?>
                                        </td>
                                        <?
                                    } else {
                                        ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="40px;">
                                            <?if(in_array('batender', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){ $laudo = true;?>
                                                <div class="bt_link">
                                                    <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarlaudo/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>');" >
                                                        Laudo
                                                    </a>
                                                </div> 
                                            <?}?>
                                        </td>
                                        <?
                                    }
                                } else {
                                    ?>
                                    <td class="<?php echo $estilo_linha; ?>" width="40px;"><font size="-2">
                                        <a>Bloqueado</a></font>
                                    </td>
                                <? }
                                ?>

                                <td class="<?php echo $estilo_linha; ?>" width="70px;">
                                    <?if(in_array('bimprimir', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                       <? if(!($data['empresa_permissao'][0]->laudo_status_f == "t" && $laudo == true &&  $situacao != "Finalizado")){?> 
                                            <div class="bt_link">
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaolaudo/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>');">
                                                    Imprimir 
                                                </a>
                                            </div> 
                                       <?}else{ ?>
                                            <td class="<?php echo $estilo_linha; ?>" width="50px;"></td>
                                       <?}?>
                                    <?}?>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;">
                                    <?if(in_array('b2via', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                     <? if(!($data['empresa_permissao'][0]->laudo_status_f == "t" && $laudo == true &&  $situacao != "Finalizado")){?> 
                                        <div class="bt_link">
                                            <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaolaudo2via/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>');">
                                                2º via
                                            </a>
                                        </div>
                                      <?}else{ ?>
                                            <td class="<?php echo $estilo_linha; ?>" width="50px;"></td>
                                       <?}?>
                                    <?}?>
                                    
                                </td>
                            <? } else { ?>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;"><font size="-2">
                                    </font>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;"><font size="-2">
                                    <a></a></font>
                                </td>
                                <? if ($item->paciente_id == '') { ?>
                                    <td class="<?php echo $estilo_linha; ?>" width="70px;">
                                        <?if(in_array('bexame', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                            <font size="-2">
                                            <div class="bt_link">
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exametemp/carregarexame/<?= $item->agenda_exames_id ?>');">Exame
                                                </a>
                                            </div>
                                            </font>
                                        <?}?>
                                        
                                    </td>   
                                <? } elseif ($item->paciente_id != '' && $item->confirmado == 'f') { ?>
                                    <td class="<?php echo $estilo_linha; ?>" width="70px;">
                                        <?if(in_array('bcancelar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                           <font size="-2">
                                                <div class="bt_link">
                                                    <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exametemp/excluirconsultatempmedico/<?= $item->agenda_exames_id ?>/<?= @$item->exames_id?>');">Cancelar
                                                    </a>
                                                </div>
                                            </font> 
                                        <?}?>
                                    </td>  
                                <? } else { ?>


                                    <? if ($verifica == 3) { ?>

                                        <?php
                                        $empresa_id = $this->session->userdata('empresa_id');
                                        $data['retorno'] = $this->empresa->listarverificacaopreco($item->agenda_exames_id);
                                        $data['retorno_header'] = $this->empresa->listarverificacaopermisao($empresa_id);
                                        $data['retorno_header2'] = $this->empresa->listarverificacaopermisao2($empresa_id);

                                        foreach ($data['retorno_header'] as $value) {
                                            $caixa = $value->caixa;
                                        }

                                        if (($data['retorno_header2'][0]->faturamento_novo != 'f' && $item->dinheiro == 't' && $caixa != 'f') || ($caixa != 'f' && $item->dinheiro == 't')) {
                                            if (!empty($data['retorno'])) {
                                                foreach ($data['retorno'] as $value) {
                                                    if ($value->valor_total > 0 || $item->faturado != 'f') {
                                                        ?>
                                                        <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="">
                                                            <? if ($endereco != '') { ?>
                                                                <?if(in_array('bchamar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                                    <div class="bt_link">
                                                                        <a onclick="chamarPaciente('<?= $url_enviar_ficha ?>', <?= $toten_fila_id ?>, <?= $item->medico_consulta_id ?>, <?= $toten_sala_id ?>);" >Chamar</a>
                                                                    </div> 
                                                                <?}?>
                                                                
                                                            <? } else { ?>
                                                                <?if(in_array('bchamar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                                    <div class="bt_link">
                                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/chamarpacientesalaespera/<?= $item->agenda_exames_id ?>');" >Chamar</a>
                                                                    </div>  
                                                                <?}?>
                                                            
                                                            <? } ?>

                                                        </td>
                                                        <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="">

                                                            <? if ($perfil_id != 11 && $perfil_id != 12 && $perfil_id != 7 && $perfil_id != 15) {
                                                                ?>
                                                                <?if(in_array('bconfirmar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                                    <div class="bt_link">
                                                                        <a target="_blank" onclick="javascript: return confirm('Deseja realmente confirmar o paciente?');" href="<?= base_url() ?>ambulatorio/exame/enviarsalaesperamedico/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->guia_id ?>/<?= $item->agenda_exames_id; ?>/<?= $item->medico_consulta_id ?>">Confirmar</a>
                                                                    </div>
                                                                <?}?>
                                                            
                                                                <?
                                                            }
                                                            ?>
                                                        </td>
                                                        <?
                                                    }
                                                }
                                            } else {

                                                if ($item->faturado != 'f') {
                                                    ?>
                                                    <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="">
                                                    </td>
                                                    <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="2">
                                                        <? if ($endereco != '') { ?>
                                                            <?if(in_array('bchamar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                                <div class="bt_link" style=" width:70px;" >
                                                                    <a onclick="chamarPaciente('<?= $url_enviar_ficha ?>', <?= $toten_fila_id ?>, <?= $item->medico_consulta_id ?>, <?= $toten_sala_id ?>);" >Chamar</a>
                                                                </div>
                                                            <?}?>
                                                            
                                                        <? } else { ?>
                                                            <?if(in_array('bchamar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                                <div class="bt_link" >
                                                                    <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/chamarpacientesalaespera/<?= $item->agenda_exames_id ?>');" >Chamar</a>
                                                                </div> 
                                                            <?}?>
                                                        <? } ?>

                                                    </td>
                                                    <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="">

                                                        <? if ($perfil_id != 11 && $perfil_id != 12 && $perfil_id != 7 && $perfil_id != 15) {
                                                            ?>
                                                            <?if(in_array('bconfirmar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                                <div class="bt_link">
                                                                    <a target="_blank" onclick="javascript: return confirm('Deseja realmente confirmar o paciente?');" href="<?= base_url() ?>ambulatorio/exame/enviarsalaesperamedico/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->guia_id ?>/<?= $item->agenda_exames_id; ?>/<?= $item->medico_consulta_id ?>">Confirmar</a>
                                                                </div>
                                                            <?}?>
                                                            
                                                            <?
                                                        }
                                                        ?>
                                                    </td>
                                                    <? if ($retirar_medico_cadastro == 'f' && ($perfil_id == 1 || $perfil_id == 4 || $perfil_id == 19)) { ?>
                                                        <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="1">
                                                            <?if(in_array('bhistorico', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                                <div class="bt_link" style=" width:70px; ">
                                                                    <a target="_blank" href="<?= base_url() ?>ambulatorio/laudo/carregaranaminesehistoricogeral/<?= $item->paciente_id ?>">Histórico</a>
                                                                </div>
                                                            <?}?>
                                                            
                                                        </td>
                                                    <? } ?>


                                                    <?
                                                }
                                            }
                                        } else {
                                            ?>
                                            <? if ($retirar_medico_cadastro == 'f' || true) { ?>
                                                <? if ($retirar_medico_cadastro == 't') { ?>
                                                    <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="2">
                                                    </td>
                                                <? } ?>

                                                <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="1">
                                                    <? if ($endereco != '') { ?>
                                                        <?if(in_array('bchamar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                            <div class="bt_link" style=" width:70px; " >
                                                                <a onclick="chamarPaciente('<?= @$url_enviar_ficha ?>', <?= @$toten_fila_id ?>, <?= $item->medico_consulta_id ?>, <?= @$toten_sala_id ?>);" >Chamar</a>
                                                            </div> 
                                                        <?}?>
                                                        
                                                    <? } else { ?>
                                                        <?if(in_array('bchamar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                            <div class="bt_link">
                                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/chamarpacientesalaespera/<?= $item->agenda_exames_id ?>');" >Chamar</a>
                                                            </div> 
                                                        <?}?>
                                                        
                                                    <? } ?>

                                                </td>
                                                <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="1">

                                                    <? if ($perfil_id != 11 && $perfil_id != 12 && $perfil_id != 7 && $perfil_id != 15) {
                                                        ?>
                                                        <?if(in_array('bconfirmar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                            <div class="bt_link" style=" width:70px; ">
                                                                <a target="_blank" onclick="javascript: return confirm('Deseja realmente confirmar o paciente?');" href="<?= base_url() ?>ambulatorio/exame/enviarsalaesperamedico/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->guia_id ?>/<?= $item->agenda_exames_id; ?>/<?= $item->medico_consulta_id ?>">Confirmar</a>
                                                            </div>
                                                        <?}?>
                                                        
                                                        <?
                                                    }
                                                    ?>
                                                </td>
                                                <? if ($retirar_medico_cadastro == 'f' && ($perfil_id == 1 || $perfil_id == 4 || $perfil_id == 19)) { ?>
                                                    <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="1">
                                                        <?if(in_array('bhistorico', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                            <div class="bt_link" style=" width:70px; ">
                                                                <a target="_blank" href="<?= base_url() ?>ambulatorio/laudo/carregaranaminesehistoricogeral/<?= $item->paciente_id ?>">Histórico</a>
                                                            </div>
                                                        <?}?>
                                                        
                                                    </td>
                                                <? } ?>
                                            <? } ?>
                                            <?
                                        }



                                        if ($item->faturado != 't' && $data['retorno_header2'][0]->faturamento_novo != 't' && $caixa != 'f' || $item->faturado != 't' && $data['retorno_header2'][0]->faturamento_novo != 'f' && $caixa != 't') {
                                            ?>
                                            <? if ($retirar_medico_cadastro == 'f') { ?>
                                                <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="3">


                                                </td>
                                            <? } ?>
                                            <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="">
                                            </td>
                                            <?
                                        }
                                        ?>


                                        <? } else { ?>

                                        <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="10"><font size="-2">
                                            <a></a></font>
                                        </td> 

                                        <? } ?>

                                <? }
                                ?>

                            <? } ?>                                           
                        </tr>

                    </tbody>
                    <?php
                }
//                }
                ?>
                <tfoot>
                    <tr>
                        <nav class="pagination justify-content-center">
                            <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            <!-- Total de registros: <?php // echo $total;             ?> -->
                        </nav>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div> <!-- Final da DIV content -->
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>-->
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>-->
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>-->
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>-->
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>-->
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>-->

<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<!--<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">-->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
 
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>
<style>
    #procedimento_chosen a { width: 130px; }
</style>
<script type="text/javascript">
                                                        // $(document).ready(function () {
//alert('teste_parada');

                                                        if ($('#especialidade').val() != '') {
                                                            $.getJSON('<?= base_url() ?>autocomplete/medicoespecialidade', {txtcbo: $('#especialidade').val(), ajax: true}, function (j) {
                                                                var options = '<option value=""></option>';
                                                                var slt = '';
                                                                for (var c = 0; c < j.length; c++) {
                                                                    if (j[0].operador_id != undefined) {
                                                                        if (j[c].operador_id == '<?= @$_GET['medico'] ?>') {
                                                                            slt = 'selected';
                                                                        }
                                                                        options += '<option value="' + j[c].operador_id + '" ' + slt + '>' + j[c].nome + '</option>';
                                                                        slt = '';
                                                                    }
                                                                }
                                                                $('#medico').html(options).show();
                                                                $('.carregando').hide();



                                                            });
                                                        }

                                                        $(function () {
                                                            $('#especialidade').change(function () {

                                                                if ($(this).val()) {

//                                                  alert('teste_parada');
                                                                    $('.carregando').show();
//                                                        alert('teste_parada');
                                                                    $.getJSON('<?= base_url() ?>autocomplete/medicoespecialidade', {txtcbo: $(this).val(), ajax: true}, function (j) {
                                                                        options = '<option value=""></option>';
                                                                        console.log(j);

                                                                        for (var c = 0; c < j.length; c++) {


                                                                            if (j[0].operador_id != undefined) {
                                                                                options += '<option value="' + j[c].operador_id + '">' + j[c].nome + '</option>';

                                                                            }
                                                                        }
                                                                        $('#medico').html(options).show();
                                                                        $('.carregando').hide();



                                                                    });
                                                                } else {
                                                                    $('.carregando').show();
//                                                        alert('teste_parada');
                                                                    $.getJSON('<?= base_url() ?>autocomplete/medicoespecialidadetodos', {txtcbo: $(this).val(), ajax: true}, function (j) {
                                                                        options = '<option value=""></option>';
                                                                        console.log(j);

                                                                        for (var c = 0; c < j.length; c++) {


                                                                            if (j[0].operador_id != undefined) {
                                                                                options += '<option value="' + j[c].operador_id + '">' + j[c].nome + '</option>';

                                                                            }
                                                                        }
                                                                        $('#medico').html(options).show();
                                                                        $('.carregando').hide();



                                                                    });

                                                                }

                                                            });
                                                        });

                                                        $(function () {
                                                            $("#txtCICPrimariolabel").autocomplete({
                                                                source: "<?= base_url() ?>index.php?c=autocomplete&m=cid1",
                                                                minLength: 3,
                                                                focus: function (event, ui) {
                                                                    $("#txtCICPrimariolabel").val(ui.item.label);
                                                                    return false;
                                                                },
                                                                select: function (event, ui) {
                                                                    $("#txtCICPrimariolabel").val(ui.item.value);
                                                                    $("#txtCICPrimario").val(ui.item.id);
                                                                    return false;
                                                                }
                                                            });
                                                        });

                                                        $(function () {
                                                            $("#data").datepicker({
                                                                autosize: true,
                                                                changeYear: true,
                                                                changeMonth: true,
                                                                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                                                                dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                                                                buttonImage: '<?= base_url() ?>img/form/date.png',
                                                                dateFormat: 'dd/mm/yy'
                                                            });
                                                        });

                                                        $(function () {
                                                            $("#accordion").accordion();

                                                            $("#procedimento").chosen({
                                                                width: '200%'
                                                            });
                                                        });

<? if (($endereco != '')) { ?>
                                                            function enviarChamadaPainel(url, toten_fila_id, medico_id, toten_sala_id) {
                                                                // alert('Teste');
                                                                $.ajax({
                                                                    type: "POST",
                                                                    data: {teste: 'teste'},
                                                                    //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                                                    url: "<?= $endereco ?>/webService/telaChamado/proximo/" + medico_id + '/ ' + toten_fila_id + '/' + toten_sala_id,
                                                                    success: function (data) {

                                                                        alert('Operação efetuada com sucesso');


                                                                    },
                                                                    error: function (data) {
                                                                        console.log(data);
                                                                        alert('Erro ao chamar paciente');
                                                                    }
                                                                });
                                                                $.ajax({
                                                                    type: "POST",
                                                                    data: {teste: 'teste'},
                                                                    //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                                                    url: "<?= $endereco ?>/webService/telaChamado/cancelar/" + toten_fila_id,
                                                                    success: function (data) {

                                                                        //                            alert('Operação efetuada com sucesso');


                                                                    },
                                                                    error: function (data) {
                                                                        console.log(data);
                                                                        //                            alert('Erro ao chamar paciente');
                                                                    }
                                                                });

                                                            }
                                                            function chamarPaciente(url, toten_fila_id, medico_id, toten_sala_id) {
                                                                //   alert(medico_id);
                                                                $.ajax({
                                                                    type: "POST",
                                                                    data: {teste: 'teste'},
                                                                    //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                                                    url: url,
                                                                    success: function (data) {
                                                                        //                console.log(data);
                                                                        //                    alert(data.id);

                                                                        $("#idChamada").val(data.id);

                                                                    },
                                                                    error: function (data) {
                                                                        console.log(data);

                                                                    }
                                                                });
                                                                setTimeout(enviarChamadaPainel, 1000, url, toten_fila_id, medico_id, toten_sala_id);

                                                            }

<? } ?>

//                                        setTimeout('delayReload()', 20000);
//                                        function delayReload()
//                                        {
//                                            if (navigator.userAgent.indexOf("MSIE") != -1) {
//                                                history.go(0);
//                                            } else {
//                                                window.location.reload();
//                                            }
//                                        }

                                                        // });
                                                        setInterval(function () {
                                                            window.location.reload();
                                                        }, 60000);


</script>

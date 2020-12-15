<link href="<?= base_url() ?>css/ambulatorio/multifuncaomedicofisioterapia-lista.css" rel="stylesheet"/>
<?
$this->db->select('o.operador_id,
                    o.profissional_agendar_o');
$this->db->from('tb_operador o');
$this->db->where('o.operador_id', $this->session->userdata('operador_id'));
$retorno_paciente = $this->db->get()->result();

$this->db->select('ep.profissional_agendar');
$this->db->from('tb_empresa_permissoes ep');
$this->db->where('ep.empresa_id', $this->session->userdata('empresa_id'));
$retorno_header = $this->db->get()->result();


$profissional_agendar = $retorno_header[0]->profissional_agendar;
$profissional_agendar_o = $retorno_paciente[0]->profissional_agendar_o;


$data['permissao'] = $this->empresa->listarverificacaopermisao2($this->session->userdata('empresa_id'));
?>
<div class="content"> <!-- Inicio da DIV content -->
    <table>
        <tr>
            <? if ($profissional_agendar == 't' && $profissional_agendar_o == 't') { ?>
                <td>
                    <div class="bt_link_new">
                        <button class="bnt btn-outline-default btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exametemp/novopacientefisioterapiaencaixemedico');">
                            Encaixar Especialidade
                        </button>
                    </div>
                </td>
            <? } ?>
            <td>
                <div class="bt_link_new">
                    <button class="btn btn-outline-default btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exametemp/mostrarlembretes', '_blank', 'toolbar=no,Location=no,menubar=no,width=600,height=700');" >
                        Lembretes
                    </button>
                </div>
            </td>
            <td>
                <div class="bt_link_new">
                    <a class="btn btn-outline-default btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exametemp/mostrarpendencias', '_blank', 'width=1600,height=700');" >
                        Ver Pendentes
                    </a>
                </div>
            </td>

        </tr>
    </table>
    <div id="accordion">
        <h3 class="singular">Multifuncao Especialidade</h3>
        <div>
            <?
            $salas = $this->exame->listartodassalas();
            $empresa = $this->guia->listarempresasaladeespera();
            @$ordem_chegada = @$empresa[0]->ordem_chegada;
            @$ordenacao_situacao = @$empresa[0]->ordenacao_situacao; 
            $medicos = $this->operador_m->listarmedicos();
            $perfil_id = $this->session->userdata('perfil_id');
            $empresa_id = $this->session->userdata('empresa_id');
            $data['empresa'] = $this->empresa->listarempresatoten($empresa_id);
            @$endereco = $data['empresa'][0]->endereco_toten;
            if (@$empresa[0]->campos_listaatendimentomed != '') {
                $campos_listaatendimentomed = json_decode(@$empresa[0]->campos_listaatendimentomed);
            } else {
                $campos_listaatendimentomed = array();
            }
            $convenios = $this->convenio->listar()->get()->result();
            ?>
            <form method="get" action="<?= base_url() ?>ambulatorio/exame/listarmultifuncaomedicofisioterapia">
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
                                <label>Médico</label>
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
                                <label>Situação</label>
                                <select name="situacao" id="situacao" class="form-control">
                                    <option value=""></option>
                                    <option value="BLOQUEADO" <?
                                    if (@$_GET['situacao'] == "BLOQUEADO") {
                                        echo 'selected';
                                    }
                                    ?>>BLOQUEADO</option>
                                    <option value="FALTOU" <?
                                    if (@$_GET['situacao'] == "FALTOU") {
                                        echo 'selected';
                                    }
                                    ?>>FALTOU</option>
                                    <option value="OK" <?
                                    if (@$_GET['situacao'] == "OK") {
                                        echo 'selected';
                                    }
                                    ?>>OCUPADO</option>
                                    <option value="LIVRE" <?
                                    if (@$_GET['situacao'] == "LIVRE") {
                                        echo 'selected';
                                    }
                                    ?>>VAGO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Data</label>
                                <input type="date"  id="data" alt="date" name="data" class="form-control"  value="<?php echo @$_GET['data']; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div>
                                <label>Nome</label>
                                <input type="text" name="nome" class="form-control bestupper" value="<?php echo @$_GET['nome']; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Procedimento</label>
                                <input type="text" name="txtprocedimento" class="form-control texto03 bestupper" value="<?php echo @$_GET['txtprocedimento']; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Cid</label>
                                <input type="text" name="txtCICPrimariolabel" id="txtCICPrimariolabel" class="form-control texto03" value="<?php echo @$_GET['txtCICPrimariolabel']; ?>" />
                                <input type="hidden" name="txtCICPrimario" id="txtCICPrimario" value="" class="form-control size2" />
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Convênio</label>
                                <select  style="width:100%" class="chosen-select form-control" name="convenios[]" id="convenios" multiple data-placeholder="Selecione">
                                    <option value="0" <?= @(in_array("0", $_GET['convenios'])) ? "selected":""; ?> >TODOS</option>
                                    <?php foreach($convenios as $item){?>
                                        <option value="<?= $item->convenio_id; ?>" <?= @(in_array($item->convenio_id, $_GET['convenios'])) ? "selected":""; ?> ><?= $item->nome; ?></option>
                                    <?}?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 btnsend">
                            <div>
                                <button class="btn btn-outline-success btn-sm" type="submit" id="enviar">Pesquisar</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>

            <br>
            <table>
                <thead>
                    <tr>
                        <?if(in_array('status', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header" >Status</th>
                        <?}?>
                            
                        <?if(in_array('nome', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header" width="250px;">Nome</th>
                        <?}?>
                        <th class="tabela_header" width="100px;"></th>
                        

                        <?if(in_array('telefone', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header" width="250px;">Telefone</th>
                        <?}?>
                        
                        <?if(in_array('idade', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header" width="100px;">Idade</th>
                        <?}?>
                       
                       
                        <?if(in_array('espera', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header" width="60px;">Espera</th>
                        <?}?>
                           
                        
                        <?if(in_array('convenio', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header" width="100px;">Convenio</th>
                        <?}?>
                       
                       
                        <?if(in_array('agenda', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header" width="80px;">Agenda</th>
                        <?}?>
                        
                        <?if(in_array('autorizacao', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header" width="250px;">Autorização</th>
                        <?}?>
                            
                       
                        <?if(in_array('procedimento', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header" width="250px;">Procedimento</th>
                        <?}?>
                        <?if(in_array('obs', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header">OBS</th>
                        <?}?>
                        <th class="tabela_header" colspan="5"><center>A&ccedil;&otilde;es</center></th>
                </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->exame->listarmultifuncaofisioterapia($_GET);
                $total = $consulta->count_all_results();
                $limit = 100;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;
                // echo "<pre>";
                // print_r($item); die;
                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $lista = $this->exame->listarmultifuncao2fisioterapia($_GET, $ordenacao_situacao)->limit($limit, $pagina)->get()->result();

                        $estilo_linha = "tabela_content01";
                        $operador_id = $this->session->userdata('operador_id');
                        foreach ($lista as $item) {

                            if($item->agenda_exames_nome_id == ''){
                                $item->agenda_exames_nome_id = 'null';
                               }


                            $dataFuturo = date("Y-m-d H:i:s");
                            $dataAtual = $item->data_autorizacao;
                            $date_time = new DateTime($dataAtual);
                            $diff = $date_time->diff(new DateTime($dataFuturo));
                            $teste = $diff->format('%H:%I:%S');

                            $dataFuturo2 = date("Y-m-d");
                            $dataAtual2 = $item->nascimento;
                            $date_time2 = new DateTime($dataAtual2);
                            $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
                            // if($retirar_ordem_medico == 'f'){
                                $teste2 = $diff2->format('%Ya %mm %dd');
                            // }else{
                            //     $teste2 = $diff2->format('%Y');
                            // }

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
                            if (($item->realizada == 't' && $item->situacaolaudo != 'FINALIZADO') && $item->situacaoexame != "PENDENTE") {
                                $situacao = "Aguardando";
                                $verifica = 2;
                            } elseif ($item->realizada == 't' && $item->situacaolaudo == 'FINALIZADO') {
                                $situacao = "Finalizado";
                                $verifica = 4;
                            } elseif ($item->confirmado == 'f') {
                                $situacao = "agenda";
                                $verifica = 1;
                            } elseif ($item->situacaoexame == 'PENDENTE') {
                                $situacao = "pendente";
                                $verifica = 1;
                            } else {
                                $situacao = "espera";
                                $verifica = 3;
                            }
                            ?>
                            <tr>
                                <?if(in_array('status', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                    <? if ($verifica == 1) { ?>
                                        <td class="<?php echo $estilo_linha; ?>"><?= $situacao; ?></td>
                                    <? }if ($verifica == 2) { ?>
                                        <td class="<?php echo $estilo_linha; ?>"><font color="green"><b><?= $situacao; ?></b></td>
                                    <? }if ($verifica == 3) { ?>
                                        <td class="<?php echo $estilo_linha; ?>"><font color="red"><b><?= $situacao; ?></b></td>
                                    <? }if ($verifica == 4) { ?>
                                        <td class="<?php echo $estilo_linha; ?>"><font color="blue"><b><?= $situacao; ?></b></td>
                                    <? } ?>
                                <?}?>
                                
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
                        <style>
                            .vermelho{
                                color: red;
                            }
                        </style>
                        <td class="<?php echo $estilo_linha; ?>"><?
                            if ($item->encaixe == 't') {
                                if ($item->paciente == '') {
                                    echo '<span class="vermelho">Encaixe H.</span>';
                                } else {
                                    echo '<span class="vermelho">Encaixe</span>';
                                }
                            }
                            ?>
                        </td>
                        <?if(in_array('telefone', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->celular; ?></td>
                        <?}?>
                        <?if(in_array('idade', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <td class="<?php echo $estilo_linha; ?>"><?= $teste2; ?></td>
                        <?}?>
                        <?if(in_array('espera', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <? if ($verifica == 4) { ?>
                                <td class="<?php echo $estilo_linha; ?>">&nbsp;</td>
                            <? } else { ?>
                                <td class="<?php echo $estilo_linha; ?>"><?= $teste; ?></td>
                            <? } ?>
                            
                        <?}?>

                       <?if(in_array('convenio', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <? if ($item->convenio != '') { ?>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->convenio; ?></td>
                            <? } else { ?>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->convenio_paciente; ?></td>
                            <? } ?>
                        <?}?>
                        
                        <?if(in_array('agenda', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data)) . " " . $item->inicio; ?></td>
                        <?}?>
                        <?if(in_array('autorizacao', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <td class="<?php echo $estilo_linha; ?>"><?
                                    if ($item->data_autorizacao != '') {
                                        echo date("H:i:s", strtotime($item->data_autorizacao));
                                    }?>
                            </td>
                        <?}?>
                            
                        <?if(in_array('procedimento', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->procedimento; ?></td>
                        <?}?>
                        
                        <?if(in_array('obs', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <td class="<?php echo $estilo_linha; ?>">
                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/alterarobservacao/<?= $item->agenda_exames_id ?>', '_blank', 'toolbar=no,Location=no,menubar=no width=500,height=400');">
                                    =><?= $item->observacoes; ?>
                                </a>
                            </td>
                        <?}?>
                        <? if ($item->confirmado == 't' && $item->situacaoexame != 'PENDENTE') { ?>
                            <td class="<?php echo $estilo_linha; ?>" width="70px;">
                            </td>
                            <?
                            if (($item->medico_parecer1 == $operador_id && $item->situacao == 'FINALIZADO') || $item->situacao != 'FINALIZADO' && $item->realizada == 't' || $operador_id == 3) {
                                ?>
                                <td class="<?php echo $estilo_linha; ?>" width="40px;">
                                    <?if(in_array('batender', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                        <div class="bt_link">
                                            <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregaranaminese/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>');" >
                                                Atender
                                            </a>
                                        </div>
                                    <?}?>
                                </td>
                            <? } else { ?>
                <!--                                <td class="<?php echo $estilo_linha; ?>" width="40px;"><font size="-2">
                                    <a>Bloqueado</a></font>
                                </td>-->
                            <? } ?>

                        <?php } if (($item->medico_parecer1 == $operador_id && $item->situacao == 'FINALIZADO') || $item->situacao != 'FINALIZADO' && $item->realizada == 't' || $operador_id == 3) { ?>
                            <td class="<?php echo $estilo_linha; ?>" width="70px;">
                                <?if(in_array('barquivos', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                    <div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/anexarimagem/<?= $item->ambulatorio_laudo_id ?>');">
                                            Arquivos
                                        </a>
                                    </div>
                                <?}?>
                            </td>


                                    <!--                                    <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                           <a href="<?= base_url() ?>ambulatorio/laudo/chamarpaciente/<?= $item->ambulatorio_laudo_id ?> ">
                                           Chamar</a></div>
                                    </td>-->
                            <? if ((($operador_id == 1 || $perfil_id == 1) && $item->realizada == 't') || ( $perfil_id == 20 && $data['permissao'][0]->gerente_cancelar_atendimento == 't' && $item->realizada == 't')) { ?>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;">
                                    <? if ($item->encaixe == 't') { ?>
                                        <?if(in_array('bcancelar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                            <div class="bt_link">
                                                <a onclick="javascript: return confirm('Deseja realmente cancelar o encaixe?\n\nObs: Irá excluir também o horário');" href="<?= base_url() ?>ambulatorio/exametemp/examecancelamentoencaixe/<?= $item->agenda_exames_id; ?>/<?= $item->exame_id ?>">
                                                    Cancelar
                                                </a>
                                            </div> 
                                        <?}?> 

                                    <? } else { ?>
                                        <?if(in_array('bcancelar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                             <div class="bt_link">
                                                <a href="<?= base_url() ?>ambulatorio/exame/examecancelamento/<?= $item->exame_id ?>/<?= $item->agenda_exames_nome_id ?> /<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/false ">
                                                    Cancelar
                                                </a>
                                            </div> 
                                        <?}?>
                                       
                                    <? } ?>
                                </td>
                            <? } elseif ((($operador_id == 1 || $perfil_id == 1) && $item->realizada == 'f') || ( $perfil_id == 20 && $data['permissao'][0]->gerente_cancelar_atendimento == 't' && $item->realizada == 't')) { ?>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;">
                                    <? if ($item->encaixe == 't') {
                                        ?> 
                                        <?if(in_array('bcancelar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                            <div class="bt_link">
                                                <a onclick="javascript: return confirm('Deseja realmente cancelar o encaixe?\n\nObs: Irá excluir também o horário');" href="<?= base_url() ?>ambulatorio/exametemp/examecancelamentoencaixe/<?= $item->agenda_exames_id; ?>/<?= $item->exame_id ?>">
                                                    Cancelar
                                                </a>
                                            </div> 
                                        <?}?> 
 
                                    <? } else { ?>
                                        <?if(in_array('bcancelar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                             <div class="bt_link">
                                                <a href="<?= base_url() ?>ambulatorio/exame/esperacancelamento/<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?> ">
                                                    Cancelar
                                                </a>
                                            </div> 
                                        <?}?>
                                        
                                    <?php } ?>

                                </td>
                            <? } ?>

                        <? } else { ?>
                            <td class="<?php echo $estilo_linha; ?>" width="70px;">
                                <font size="-2"><a></a></font>
                            </td>
                            <td class="<?php echo $estilo_linha; ?>" width="70px;"><font size="-2">
                                <a></a></font>
                            </td>

                            <? if ($item->paciente_id == "" && $item->bloqueado == 'f') { ?>
                                                                                                                                <!--                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><font size="-2">
                                                                                                                                                                            <a></a></font>
                                                                                                                                                                        </td>-->
                                <td class="<?php echo $estilo_linha; ?>" width="60px;">
                                    <? if ($profissional_agendar == 't' && $profissional_agendar_o == 't') { ?>
                                        <?if(in_array('bbloquear', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                            <div class="bt_link">
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/bloquear/<?= $item->agenda_exames_id ?>/<?= $item->inicio; ?> ', 'toolbar=no,Location=no,menubar=no,width=500,height=200');">Bloquear
                                                </a>
                                            </div>
                                        <?}?>
                                        
                                    <? } ?>
                                </td>


                                <td class="<?php echo $estilo_linha; ?>" width="60px;">
                                    <? if ($profissional_agendar == 't' && $profissional_agendar_o == 't') { ?>
                                        <?if(in_array('bconsulta', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                            <div class="bt_link">
                                                <a style="cursor: pointer;" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exametemp/carregarfisioterapiatempmedico/<?= $item->agenda_exames_id ?>');">Consultas
                                                </a>
                                            </div>
                                        <?}?>
                                        
                                    <? } ?>
                                </td>


                            <? } elseif ($item->bloqueado == 't') { ?>
                                <td class="<?php echo $estilo_linha; ?>" width="60px;"> Bloqueado</td>
                                <td class="<?php echo $estilo_linha; ?>" width="60px;">
                                    <? if ($profissional_agendar == 't' && $profissional_agendar_o == 't') { ?>
                                        <?if(in_array('bbloquear', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                            <div class="bt_link">
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/desbloquear/<?= $item->agenda_exames_id ?>/<?= $item->inicio; ?> ', 'toolbar=no,Location=no,menubar=no,width=500,height=200');">Desbloq.
                                                </a>
                                            </div>
                                        <?}?>
                                        
                                    <? } ?>
                                </td>
                            <? } else { ?>
                                <? if ($verifica == 3) { ?>
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
                                        <?if(in_array('bconfirmar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                            <div class="bt_link">
                                                <a target="_blank" onclick="javascript: return confirm('Deseja realmente confirmar o paciente?');" href="<?= base_url() ?>ambulatorio/exame/enviarsalaesperamedicoespecialidade/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->guia_id ?>/<?= $item->agenda_exames_id; ?>/<?= $item->medico_consulta_id ?>">Confirmar</a>
                                            </div>
                                        <?}?>
                                        
                                    </td>
                                <? } else { ?>
                                    <td colspan="2" class="<?php echo $estilo_linha; ?>" width="70px;"><font size="-2">
                                        <a></a></font>
                                    </td>
                                <?
                                }
                                if (($perfil_id == 4) || $perfil_id == 1 || ($perfil_id == 20 && $data['permissao'][0]->gerente_cancelar_atendimento == 't')) {
                                    if ($item->encaixe == 't') {
                                        ?>      <td  class="<?php echo $estilo_linha; ?>" width="70px;"><font size="-2">
                        <? if (($profissional_agendar == 't' && $profissional_agendar_o == 't')) { ?>
                                                
                                            <?if(in_array('bcancelar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                <div class="bt_link">
                                                    <a onclick="javascript: return confirm('Deseja realmente cancelar o encaixe?\n\nObs: Irá excluir também o horário');" href="<?= base_url() ?>ambulatorio/exametemp/examecancelamentoencaixe/<?= $item->agenda_exames_id; ?>/<?= $item->exame_id ?>">
                                                        Cancelar</a>
                                                </div> 
                                            <?}?>
                                        <? } ?>
                                        </td>
                                        <? } elseif (($operador_id == 1 || $perfil_id == 1 || $perfil_id == 4) && $verifica != 3) { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="70px;"><font size="-2">
                        <? if (($profissional_agendar == 't' && $profissional_agendar_o == 't')) { ?>
                                                
                                                <?if(in_array('bcancelar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                     <div class="bt_link">
                                                        <a onclick="javascript: return confirm('Deseja realmente cancelar esse horario?');" href="<?= base_url() ?>ambulatorio/exametemp/excluirfisioterapiatempmultifuncaomedico/<?= $item->agenda_exames_id; ?>">
                                                            Cancelar</a>
                                                    </div> 
                                                <?}?>
                                            </td>
                                        <? } ?>
                                    <? } ?>

                                <? } ?>

                            <? } ?>
        <? } ?>
                        </tr>

                        </tbody>
                        <?php
                    }
                }
                ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="16">
<?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total; ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div> <!-- Final da DIV content -->
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>-->
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>-->
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>-->
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/scripts_alerta.js" ></script>

<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<!--<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">-->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
 
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>
<script type="text/javascript">
                            $(document).ready(function () {
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
                                });

//                                        setTimeout('delayReload()', 20000);
//                                        function delayReload()
//                                        {
//                                            if (navigator.userAgent.indexOf("MSIE") != -1) {
//                                                history.go(0);
//                                            } else {
//                                                window.location.reload();
//                                            }
//                                        }

                            });
<? if (($endereco != '')) { ?>
                                function chamarPaciente(url, toten_fila_id, medico_id, toten_sala_id) {
                                    //   alert(url);
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
                                            //                alert('DEU MERDA');
                                        }
                                    });


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

<? } ?>

                            setInterval(function () {
                                window.location.reload();
                            }, 60000);

</script>
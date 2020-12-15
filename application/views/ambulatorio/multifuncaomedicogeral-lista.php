<?
$empresa_id = $this->session->userdata('empresa_id');
$data['empresa'] = $this->empresa->listarempresatoten($empresa_id);
@$endereco = $data['empresa'][0]->endereco_toten;
$data['permissao'] = $this->empresa->listarverificacaopermisao2($this->session->userdata('empresa_id'));
$tarefas = $this->exametemp->buscartarefas();
@$bardeira_status = @$data['permissao'][0]->bardeira_status;
@$setores = @$data['permissao'][0]->setores;
$bardeiras = $this->exame->listarbardeirasstatusmedico();
$setor = $this->exame->listarsetores();

?>
<style>
.statusbardeira{
    /* background-color:#000; */
    /* color:#fff; */
    display:inline-block;
    padding: 5px 10px;
    padding-left:15px;
    padding-right:15px;
    text-align:center;
    border-radius:2px;
    }
</style>
<div class="content"> <!-- Inicio da DIV content -->
    <table>
        <tr> 
            <td style="width:35%;">  
                <div class="bt_link_new">
                    <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/agenda/medicoagendageral');">
                        Bloquear Agenda
                    </a>
                </div>
            </td>

            <?php if (@$data['permissao'][0]->tarefa_medico == "t") {
                ?>
                <td>
                    <?php
//                echo count($tarefas);
//                print_r($tarefas);
                    if (@count($tarefas) > 1) {
                        @$s = "s";
                        @$ser = "serem";
                    } else {
                        @$s = "";

                        @$ser = "ser";
                    }

                    if (@count($tarefas) > 0) {
                        ?>

                        <div id="piscar" style="color:black">
                            <?php
                            echo "Dr(a). " . @$tarefas[0]->medico . " Você Possui " . @count($tarefas) . " tarefa" . @$s . " pendente" . @$s . " para " . @$ser . " resolvida" . @$s;
                            ?>
                        </div>
                    <?php } ?>
                </td>
            <?php } ?>

        </tr>
    </table>
    <div id="accordion">
        <h3 class="singular"><a href="#">Multifuncao Medico Geral</a></h3>

        <div>

            <?
            $operador_id = $this->session->userdata('operador_id');
            $salas = $this->exame->listartodassalas();
            $empresa = $this->guia->listarempresasaladeespera();
            $atendimento_3 = $empresa[0]->atendimento_medico_3;
            // echo '<pre>';
            // print_r($empresa);
            // die;
            $operador = $this->operador_m->listaroperadoratendimento($operador_id);
            @$ordem_chegada = @$empresa[0]->ordem_chegada;
            @$ordenacao_situacao = @$empresa[0]->ordenacao_situacao;
            @$atendimento_medico = @$empresa[0]->atendimento_medico;
            @$retirar_ordem_medico = @$empresa[0]->retirar_ordem_medico;
            @$retirar_medico_cadastro = @$operador[0]->atendimento_medico;
            @$imprimir_medico = @$empresa[0]->imprimir_medico;
            @$bloquear_botao = @$empresa[0]->bloquear_botao;
            @$historico_completo = @$empresa[0]->historico_completo;
            $medicos = $this->operador_m->listarmedicos();
            $perfil_id = $this->session->userdata('perfil_id');
            if (@$empresa[0]->campos_listaatendimentomed != '') {
                $campos_listaatendimentomed = json_decode(@$empresa[0]->campos_listaatendimentomed);
            } else {
                $campos_listaatendimentomed = array();
            }
            
            $convenios = $this->convenio->listar()->get()->result();
           
            ?>

            <table>
                <thead>
                <form method="get" action="<?= base_url() ?>ambulatorio/exame/listarmultifuncaomedicogeral">

                    <tr>
                        <th class="tabela_title">Salas</th>
                        <? if ($perfil_id != 4) { ?>
                            <th class="tabela_title">Medico</th>
                        <? } ?>
                        <th class="tabela_title">Situação</th>
                        <th colspan="1" class="tabela_title">Status</th>
                        <th class="tabela_title">Data</th>
                        <th colspan="1" class="tabela_title">Nome</th>
                        <? if ($empresa[0]->prontuario_antigo_pesquisar == 't') { ?>
                            <th colspan="1" class="tabela_title">Prontuário Antigo</th>
                        <? } else { ?>
                            <th colspan="1" class="tabela_title">Procedimento</th>
                            <th colspan="1" class="tabela_title">Cid</th>
                        <? } ?>

                    </tr>
                    <tr>
                        <th class="tabela_title">
                            <select name="sala" id="sala" class="size2">
                                <option value=""></option>
                                <? foreach ($salas as $value) : ?>
                                    <option value="<?= $value->exame_sala_id; ?>" <?
                                if (@$_GET['sala'] == $value->exame_sala_id):echo 'selected';
                                endif;
                                    ?>><?php echo $value->nome; ?></option>
                                        <? endforeach; ?>
                            </select>
                        </th>
                        <? if ($perfil_id != 4) { ?>

                            <th class="tabela_title">
                                <select name="medico" id="medico" class="size2">
                                    <option value=""> </option>
                                    <? foreach ($medicos as $value) : ?>
                                        <option value="<?= $value->operador_id; ?>" <?
                                if (@$_GET['medico'] == $value->operador_id):echo 'selected';
                                endif;
                                        ?>><?php echo $value->nome; ?></option>
                                            <? endforeach; ?>
                                </select>
                            </th>
                        <? } ?>

                        <th class="tabela_title">
                            <select name="situacao" id="situacao" class="size1">
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
                        </th>
                        <th class="tabela_title">
                            <select name="status" id="status" class="size1">
                                <option value=""></option>
                                <? if ($retirar_medico_cadastro == 'f') { ?>
                                    <option value="AGENDADO" <?
                                    if (@$_GET['status'] == "AGENDADO") {
                                        echo 'selected';
                                    }
                                    ?>>AGENDADO</option>
                                        <? } ?>

                                <? if ($retirar_ordem_medico == 'f' && $retirar_medico_cadastro == 'f') { ?>
                                    <option value="AGUARDANDO" <?
                                    if (@$_GET['status'] == "AGUARDANDO") {
                                        echo 'selected';
                                    }
                                    ?>>AGUARDANDO</option>
                                        <? } ?>
                                        <? if ($retirar_ordem_medico == 'f' && $retirar_medico_cadastro == 'f') { ?>
                                    <option value="ATENDIDO" <?
                                        if (@$_GET['status'] == "ATENDIDO") {
                                            echo 'selected';
                                        }
                                            ?>>ATENDIDO</option>

                                    <option value="ESPERA" <?
                                if (@$_GET['status'] == "ESPERA") {
                                    echo 'selected';
                                }
                                            ?>>ESPERA</option>
                                        <? } else { ?>
                                    <option value="ESPERANDO" <?
                                        if (@$_GET['status'] == "ESPERANDO") {
                                            echo 'selected';
                                        }
                                            ?>>ESPERANDO</option>
                                    <option value="FINALIZADO" <?
                                if (@$_GET['status'] == "FINALIZADO") {
                                    echo 'selected';
                                }
                                            ?>>FINALIZADO</option>


                                <? } ?>

                            </select>
                        </th>
                        <th class="tabela_title">
                            <input type="text"  id="data" alt="date" name="data" class="size1"  value="<?php echo @$_GET['data']; ?>" />
                        </th>
                        <th colspan="1" class="tabela_title">
                            <input type="text" name="nome" class="texto03 bestupper" value="<?php echo @$_GET['nome']; ?>" />

                        </th>
                        <? if ($empresa[0]->prontuario_antigo_pesquisar == 't') { ?>
                            <th colspan="1" class="tabela_title">
                                <input type="text" name="prontuario_antigo" class="texto03 bestupper" value="<?php echo @$_GET['prontuario_antigo']; ?>" />

                            </th> 
                        <? } else { ?>
                            <th colspan="1" class="tabela_title">
                                <select name="txtprocedimento" id="procedimento" class="size2" tabindex="1">
                                    <option value="">Selecione</option>
                                    <? foreach ($procedimento as $value) : ?>
                                        <option value="<?= $value->nome; ?>"<?
                                if (@$_GET['txtprocedimento'] == $value->nome):echo'selected';
                                endif;
                                        ?>><?php echo $value->nome; ?></option>
                                            <? endforeach; ?>
                                </select>
                            </th>
                            <th colspan="1" class="tabela_title">
                                <input type="text" name="txtCICPrimariolabel" id="txtCICPrimariolabel" class="texto03" value="<?php echo @$_GET['txtCICPrimariolabel']; ?>" />
                                <input type="hidden" name="txtCICPrimario" id="txtCICPrimario" value="" class="size2" />
                            </th>
                        <? } ?>
                        <th colspan="3" class="tabela_title">
                            <button type="submit" id="enviar">Pesquisar</button>
                        </th> 
                    </tr>
                     <tr class="tabela_title">
                        <td class="tabela_title" colspan="2">Convênio</td>

                                <?if($bardeira_status == 't'){?>
                        <td class="tabela_title">Bardeiras de Status</td>
                                <?}?>

                                <?if($setores == 't'){?>
                        <td class="tabela_title">Setores</td>
                                <?}?>
                     </tr>
                     <tr>
                         <td colspan="2">
                            <select  style="width:100%" class="chosen-select" name="convenios[]" id="convenios" multiple data-placeholder="Selecione">
                                    <option value="0" <?= @(in_array("0", $_GET['convenios'])) ? "selected":""; ?> >TODOS</option>
                                 <?php foreach($convenios as $item){?>
                                    <option value="<?= $item->convenio_id; ?>" <?= @(in_array($item->convenio_id, $_GET['convenios'])) ? "selected":""; ?> ><?= $item->nome; ?></option>
                                 <?}?>
                             </select>
                        </td>
                        <?if($bardeira_status == 't'){?>
                        <td class="tabela_title">
                            <select name="bardeirastatus" id="bardeirastatus" class="size2">
                                <option value=""></option>
                                <? foreach ($bardeiras as $value) : ?>
                                    <option value="<?= $value->bardeira_id; ?>" <?
                                if (@$_GET['bardeirastatus'] == $value->bardeira_id):echo 'selected';
                                endif;
                                    ?>><?php echo $value->nome; ?></option>
                                        <? endforeach; ?>
                            </select>
                        </td>
                        <?}?>

                        <?if($setores == 't'){?>
                        <td class="tabela_title">
                            <select name="setores" id="setores" class="size2">
                                <option value=""></option>
                                <? foreach ($setor as $value) : ?>
                                    <option value="<?= $value->setor_id; ?>" <?
                                if (@$_GET['setores'] == $value->setor_id):echo 'selected';
                                endif;
                                    ?>><?php echo $value->nome; ?></option>
                                        <? endforeach; ?>
                            </select>
                        </td>
                        <?}?>
                    </tr> 
                </form>
                </thead>
            </table>
            <?
            $listas = $this->exame->listarmultifuncao2geral($_GET, $ordem_chegada, $ordenacao_situacao, $historico_completo)->get()->result();
            // echo "<pre>";
            // print_r($listas);
            // die;
            $aguardando = 0;
            $espera = 0;
            $finalizado = 0;
            $agenda = 0;
            foreach ($listas as $item) {
                if ($item->realizada == 't' && $item->situacaolaudo != 'FINALIZADO') {
                    $aguardando = $aguardando + 1;
                } elseif ($item->realizada == 't' && $item->situacaolaudo == 'FINALIZADO') {
                    $finalizado = $finalizado + 1;
                } elseif ($item->confirmado == 'f') {
                    $agenda = $agenda + 1;
                } else {
                    $espera = $espera + 1;
                }
            }
            ?>
            <table>
                <thead>
                    <tr><th width="1100px;">&nbsp;</th><th class="tabela_header">Aguardando</th><th class="tabela_header"><?= $aguardando; ?></th></tr>
                    <tr><th>&nbsp;</th><th class="tabela_header">Espera</th><th class="tabela_header"><?= $espera; ?></th></tr>
                    <tr><th>&nbsp;</th><th class="tabela_header">Agendado</th><th class="tabela_header"><?= $agenda; ?></th></tr>
                    <tr><th>&nbsp;</th><th class="tabela_header">Atendido</th><th class="tabela_header"><?= $finalizado; ?></th></tr>
                    <tr><th>&nbsp;</th><th ></th><th ></th></tr>
                </thead>
            </table>

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
                        <?if(in_array('procedimento', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header">Bardeira Status</th>
                        <?}?>
                            <?}?>

                        <?if(in_array('obs', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                            <th class="tabela_header">OBS</th>
                        <?}?>
                        <th class="tabela_header" colspan="8"><center>A&ccedil;&otilde;es</center></th>
                </tr>
                </thead>
                <?php
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;
                $limit = 100;
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $lista = $this->exame->listarmultifuncao2geral($_GET, $ordem_chegada, $ordenacao_situacao, $historico_completo)->limit($limit, $pagina)->get()->result();

// echo "<pre>";
// print_r($lista);
// die;

                $total = count($listas);
//
//              echo "<pre>";
//         print_r($lista); die;
                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
//                        $lista = $this->exame->listarmultifuncao2geral($_GET, $ordem_chegada)->limit($limit, $pagina)->get()->result();
//                        echo '<pre>';
//                        print_r($lista);
                        $estilo_linha = "tabela_content01";
                        $operador_id = $this->session->userdata('operador_id');
                        foreach ($lista as $item) {

                            if($bardeira_status == 't'){
                                $verificarbardeira = $this->exame->verificarbardeirastatus($item->agenda_exames_id);
                        }



                           $laudo = false;

//                        var_dump($item->status); die;
                            $dataFuturo2 = date("Y-m-d");
                            $dataAtual2 = $item->nascimento;
                            $date_time2 = new DateTime($dataAtual2);
                            $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
                            if ($retirar_ordem_medico == 'f' && $retirar_medico_cadastro == 'f') {
                                $teste2 = $diff2->format('%Ya %mm %dd');
                            } else {
                                $teste2 = $diff2->format('%Y');
                            }


                            $dataFuturo = date("Y-m-d H:i:s");
                            $dataAtual = $item->data_autorizacao;
                            $date_time = new DateTime($dataAtual);
                            $diff = $date_time->diff(new DateTime($dataFuturo));
                            $teste = $diff->format('%H:%I:%S');

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
//                            var_dump($endereco);
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            if ($item->realizada == 't' && $item->situacaolaudo != 'FINALIZADO') {
                                $situacao = "Atendendo";
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
//                                echo $item->situacaoexame;
                                $situacao = "espera";
                                $verifica = 3;
                            }
//                            var_dump($ordenador);die;
                            ?>
                            <tr class="<?php echo $estilo_linha; ?>">
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
                               

                                <?if(in_array('obs', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                    <td class="<?php echo $estilo_linha; ?>" title="<?= $item->observacoes; ?>"><?= substr($item->observacoes, 0, 15).' ...'; ?></td>
                                <?}?>
                                
        <!--                                        <td class="<?php echo $estilo_linha; ?>" width="70px;"> <div class="bt_link">                                 
                                        <a href="<?= base_url() ?>ambulatorio/exame/anexarimagem/">
                                            Chamar
                                        </a></div>
                                </td>-->
                                <? if ($item->realizada == 't') {

                                    if($historico_completo == 't'){
                                        $historico_completo_variavel = $item->medico_consulta_id == $operador_id || $perfil_id != 4;
                                    }else{
                                        $historico_completo_variavel = true;
                                    }
                                    
                                    if($historico_completo_variavel){ 
                                        
                                        ?>
                                    <?
                                    if (($item->medico_parecer1 == $operador_id && $item->situacaolaudo == 'FINALIZADO') || ($item->realizada == 't' && $item->situacaolaudo != 'FINALIZADO') || $operador_id == 1 || $data['permissao'][0]->atender_todos == "t") {
                                        if ($item->tipo == 'EXAME') {
                                            if ($item->tipo == 'ECOCARDIOGRAMA') {
                                                ?>
                                                <? if ($retirar_medico_cadastro == 't') { ?>
                                                    <td class="<?php echo $estilo_linha; ?>"  colspan="3">
                                                    </td>
                                                <? } ?>
                                                
                                                <td class="<?php echo $estilo_linha; ?>" width="40px;">
                                                    <?if(in_array('batender', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0 ){  $laudo = true; ?>
                                                       
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
                                                <? if ($retirar_medico_cadastro == 't') { ?>
                                                    <td class="<?php echo $estilo_linha; ?>"  colspan="3">
                                                    </td>
                                                <? } ?>
                                                <td  class="<?php echo $estilo_linha; ?>" width="40px;">
                                                    <?if(in_array('batender', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){  $laudo = true; ?>
                                                        <div class="bt_link">
                                                            <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarlaudo/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>');" >
                                                                Laudo
                                                            </a>
                                                        </div> 
                                                    <?}?>
                                                   
                                                </td>



                                                <?
                                            }
                                        } elseif ($item->tipo == 'CONSULTA') {
                                            ?>        

                                            <? if ($atendimento_medico == "t") { ?>
                                                <? if ($retirar_medico_cadastro == 't') { ?>
                                                    <td class="<?php echo $estilo_linha; ?>"  colspan="4">
                                                    </td>
                                                <? } ?>
                                                <?if($atendimento_3 == 't' && $item->situacaolaudo == 'FINALIZADO' && $operador_id != 1 && $data['permissao'][0]->atender_todos != "t"){?>

                                                <?}else{?>
                                                <td class="<?php echo $estilo_linha; ?>" width="40px;">
                                                    <? if (($perfil_id != 11 && $perfil_id != 12 && $perfil_id != 7 && $perfil_id != 15) || $data['permissao'][0]->atender_todos == "t") {
                                                        ?>
                                                        <?if(in_array('batender', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                            <div class="bt_link">
                                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregaranaminese/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>');" >
                                                                    Atender
                                                                </a>
                                                            </div>
                                                        <?}?>
                                                        
                                                    <? } ?>
                                                </td>
                                                <?}?>
                                                <?if($atendimento_3 == 't' && $item->situacaolaudo == 'FINALIZADO'){?>
                                                <td class="<?php echo $estilo_linha; ?>" width="40px;">
                                                    <? if ($perfil_id != 11 && $perfil_id != 12 && $perfil_id != 7 && $perfil_id != 15) {
                                                        ?>
                                                        <?if(in_array('batender', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                            <div class="bt_link">
                                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregaranaminese/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/NULL/TRUE');" >
                                                                    Prontuário
                                                                </a>
                                                            </div>
                                                        <?}?>
                                                        
                                                    <? } ?>
                                                </td>
                                                        <?}?>

                                            <? } else { ?>
                                                <? if ($retirar_medico_cadastro == 't') { ?>
                                                    <td class="<?php echo $estilo_linha; ?>"  colspan="4">
                                                    </td>
                                                <? } ?>
                                                <td class="<?php echo $estilo_linha; ?>" width="40px;">
                                                    <? if (($perfil_id != 11 && $perfil_id != 12 && $perfil_id != 7 && $perfil_id != 15 ) || $data['permissao'][0]->atender_todos == "t") {
                                                        ?>
                                                        <?if(in_array('batender', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                            <div class="bt_link">
                                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregaranaminese/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>');" >
                                                                    Atender  
                                                                </a>
                                                            </div>
                                                        <?}?>
                                                        
                                                    <? } ?>
                                                </td>

                                            <? } ?>
                                            <?
                                            if ($verifica != 1) {
                                                if ($perfil_id != 11 && $perfil_id != 12 && $perfil_id != 7 && $perfil_id != 15) {
                                                    ?>

                                                <? } else { ?>

                                                <? } ?>

                                                <?
                                            }
                                        } else {
                                            ?>
                                            <? if ($atendimento_medico == "t") { ?>
                                                <? if ($retirar_medico_cadastro == 't') { ?>
                                                    <td class="<?php echo $estilo_linha; ?>"  colspan="4">
                                                    </td>
                                                <? } ?>
                                                <td class="<?php echo $estilo_linha; ?>" width="40px;">
                                                    <? if (($perfil_id != 11 && $perfil_id != 12 && $perfil_id != 7 && $perfil_id != 15) || $data['permissao'][0]->atender_todos == "t") {
                                                        ?>
                                                        <?if(in_array('batender', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                            <div class="bt_link">
                                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregaranaminese/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>');" >
                                                                    Atender
                                                                </a>
                                                            </div>
                                                        <?}?>
                                                       
                                                    <? } ?>
                                                </td>

                                            <? } else { ?>
                                                <? if ($retirar_medico_cadastro == 't') { ?>
                                                    <td class="<?php echo $estilo_linha; ?>"  colspan="4">
                                                    </td>
                                                <? } ?>
                                                <td class="<?php echo $estilo_linha; ?>" width="40px;">
                                                    <? if (($perfil_id != 11 && $perfil_id != 12 && $perfil_id != 7 && $perfil_id != 15) || $data['permissao'][0]->atender_todos == "t") {
                                                        ?>
                                                        <?if(in_array('batender', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                            <div class="bt_link">
                                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregaranaminese/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>');" >
                                                                    Atender
                                                                </a>
                                                            </div>
                                                        <?}?>
                                                        
                                                    <? } ?>
                                                </td>

                                            <? } ?> 
                                            <?
                                        }
                                    } else {
                                        ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="40px;"><font size="-2">
                                            <a>Bloqueado</a></font>
                                        </td>
                                    <? } ?>
                                    <?
                                    $liberarPerfil = true;
                                    if ($imprimir_medico == 't') {
                                        if ($retirar_medico_cadastro == 'f' && $perfil_id == 1 || $perfil_id == 4 || $perfil_id == 10 || $perfil_id == 19) {
                                            $liberarPerfil = true;
                                        } else {
                                            $liberarPerfil = false;
                                        }
                                    } else {
                                        $liberarPerfil = true;
                                    }
                                    ?>
                                    <? if ($retirar_medico_cadastro == 'f') { 
                                         
                                        ?>
                                    
                                        <td class="<?php echo $estilo_linha; ?>" width="70px;">
                                            <? if ($bloquear_botao == 'f' && $liberarPerfil) { ?>
                                                <?if(in_array('bimprimir', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                    <? if($atendimento_3 == 't' && $item->tipo == 'CONSULTA'){?>
                                                        <div class="bt_link">
                                                               <a onclick="abrirImpressaoTudo('<?=$item->ambulatorio_laudo_id?>')">
                                                                   Imprimir
                                                               </a>
                                                           </div>
                                                    <?}elseif(!($data['permissao'][0]->laudo_status_f == "t" && $laudo == true &&  $situacao != "Finalizado")){?>
                                                           <div class="bt_link">
                                                               <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaolaudo/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>');">
                                                                   Imprimir
                                                               </a>
                                                           </div> 
                                                    <?}else{ ?>
                                                        <td class="<?php echo $estilo_linha; ?>" width="50px;"></td>
                                                     <?}?>
                                                <?}?>
                                               
                                            <? } ?>
                                        </td>
                                    <? } ?>
                                    <? if ($retirar_medico_cadastro == 'f') { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="70px;">
                                            <?
                                            if ($verifica != 1) {
                                                //                                                
                                                ?>
                                                <?if(in_array('barquivos', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                    <div class="bt_link">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/anexarimagem/<?= $item->ambulatorio_laudo_id ?>');">
                                                            Arquivos
                                                        </a>
                                                    </div>
                                                <?}?>
                                                
                                            <? } ?>
                                        </td>
                                    <? } ?>
                                    <? if (($retirar_medico_cadastro == 'f' && $perfil_id == 1 ) || ($perfil_id == 20 && $data['permissao'][0]->gerente_cancelar_atendimento == 't')) { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="70px;">
                                        <?if(in_array('bcancelar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                            <div class="bt_link">
                                                <?
                                                if ($item->exames_id == "" && $item->sala_id == "") {
                                                    ?>

                                                    <?php if ($item->encaixe == "t") { ?>
                                                        <!-- <a target="_blank" href="<?= base_url() ?>ambulatorio/exame/examecancelamentogeral2/<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/true"> -->
                                                        <a onclick="javascript:window.open('<?= base_url(); ?>ambulatorio/exame/examecancelamentogeral/<?= $item->exames_id ?>/<?= $item->sala_id ?>/<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/true', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                                                            Cancelar
                                                        </a> 
                                                    <?php } else { ?>
                                                       <!-- <a target="_blank" href="<?= base_url() ?>ambulatorio/exame/examecancelamentogeral2/<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/false"> -->
                                                       <a onclick="javascript:window.open('<?= base_url(); ?>ambulatorio/exame/examecancelamentogeral/<?= $item->exames_id ?>/<?= $item->sala_id ?>/<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/false', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                                                            Cancelar
                                                        </a> 
                                                    <?php } ?> 

                                                    <?
                                                } elseif ($item->sala_id == "") {
                                                    ?>
                                                    <?php if ($item->encaixe == "t") { ?>
                                                     <!-- <a target="_blank" href="<?= base_url() ?>ambulatorio/exame/examecancelamentogeral3/<?= $item->exames_id ?>/<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/true "> -->
                                                     <a onclick="javascript:window.open('<?= base_url(); ?>ambulatorio/exame/examecancelamentogeral/<?= $item->exames_id ?>/<?= $item->sala_id ?>/<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/true', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                                                            Cancelar
                                                        </a>     
                                                    <?php } else { ?> 

                                                       <!-- <a target="_blank" href="<?= base_url() ?>ambulatorio/exame/examecancelamentogeral3/<?= $item->exames_id ?>/<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/false "> -->
                                                       <a onclick="javascript:window.open('<?= base_url(); ?>ambulatorio/exame/examecancelamentogeral/<?= $item->exames_id ?>/<?= $item->sala_id ?>/<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/false', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                                                            Cancelar
                                                        </a>   
                                                    <?php } ?>

                                                    <?
                                                } else {
                                                    ?>
                                                    <?php if ($item->encaixe == "t") { ?>
                                                        <!-- <a target="_blank" href="<?= base_url() ?>ambulatorio/exame/examecancelamentogeral/<?= $item->exames_id ?>/<?= $item->sala_id ?>/<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/true "> -->
                                                        <a onclick="javascript:window.open('<?= base_url(); ?>ambulatorio/exame/examecancelamentogeral/<?= $item->exames_id ?>/<?= $item->sala_id ?>/<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/true', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                                                            Cancelar
                                                        </a> 
                                                    <?php } else { ?> 
                                                        <!-- <a target="_blank" href="<?= base_url() ?>ambulatorio/exame/examecancelamentogeral/<?= $item->exames_id ?>/<?= $item->sala_id ?>/<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/false "> -->
                                                        <a onclick="javascript:window.open('<?= base_url(); ?>ambulatorio/exame/examecancelamentogeral/<?= $item->exames_id ?>/<?= $item->sala_id ?>/<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/false', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                                                            Cancelar
                                                        </a> 
                                                    <?php } ?>
                                                <? } ?> 

                                                </div>            
                                        <?}?>
                                    </td>
                                    <? } else { ?>
                                        <? if ($retirar_medico_cadastro == 'f') { ?>
                                            <td class="<?php echo $estilo_linha; ?>" width="70px;"><font size="-2"><a></a></font></td>
                                        <? } ?>
                                    <? } ?>


                                                                                                                                                                                                                                                                                                                                                                                                <!--                                                                        <td class="<?php echo $estilo_linha; ?>" width="70px;"><font size="-2">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <a></a></font>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                </td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                <td class="<?php echo $estilo_linha; ?>" width="70px;"><font size="-2">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <a></a></font>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                </td>-->


                                                                                                                                                                                                                                                                                                                                                                                                                                    <!--                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/anexarimagem/<?= $item->ambulatorio_laudo_id ?>');">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        Arquivos</a></div>-->
                                <? } if ($retirar_medico_cadastro == 'f' && ($perfil_id == 1 || $perfil_id == 4 || $perfil_id == 19)) { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="1">
                                            <?if(in_array('bhistorico', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                <div class="bt_link" style=" width:70px; ">
                                                    <a target="_blank" href="<?= base_url() ?>ambulatorio/laudo/carregaranaminesehistoricogeral/<?= $item->paciente_id ?>">Histórico</a>
                                                </div>
                                            <?}?>
                                        </td>
                                    <? } } else {
                                    ?>
                                    <!--</td>-->


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
                                                        <?if(in_array('bchamar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
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
                                                                <?}?>
                                                        <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="">

                                                            <? if ($perfil_id != 11 && $perfil_id != 12 && $perfil_id != 7 && $perfil_id != 15) {
                                                                ?>
                                                                <?if(in_array('bconfirmar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                                    <div class="bt_link">
                                                                        <a  onclick="confirmarPaciente('<?= base_url() ?>ambulatorio/exame/enviarsalaesperamedico/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->guia_id ?>/<?= $item->agenda_exames_id; ?>/<?= $item->medico_consulta_id ?>');" >Confirmar</a>
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
                                                    <?if(in_array('bchamar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                    <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="2">


                                                        <? if ($endereco != '') { ?>
                                                            <?if(in_array('bchamar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                                <div class="bt_link" style=" width:70px;" >
                                                                    <a target="_blank" onclick="chamarPaciente('<?= $url_enviar_ficha ?>', <?= $toten_fila_id ?>, <?= $item->medico_consulta_id ?>, <?= $toten_sala_id ?>);" >Chamar</a>
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
                                                            <?}?>
                                                    <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="">

                                                        <? if ($perfil_id != 11 && $perfil_id != 12 && $perfil_id != 7 && $perfil_id != 15) {
                                                            ?>
                                                            <?if(in_array('bconfirmar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                                <div class="bt_link">
                                                                    <a  onclick="confirmarPaciente('<?= base_url() ?>ambulatorio/exame/enviarsalaesperamedico/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->guia_id ?>/<?= $item->agenda_exames_id; ?>/<?= $item->medico_consulta_id ?>');" >Confirmar</a>
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
                                                <?if(in_array('bchamar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
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
                                                        <?}?>
                                                <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="1">

                                                    <? if ($perfil_id != 11 && $perfil_id != 12 && $perfil_id != 7 && $perfil_id != 15) {
                                                        ?>
                                                        <?if(in_array('bconfirmar', $campos_listaatendimentomed) && count($campos_listaatendimentomed) > 0){?>
                                                            <div class="bt_link" style=" width:70px; ">
                                                                <a  onclick="confirmarPaciente('<?= base_url() ?>ambulatorio/exame/enviarsalaesperamedico/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->guia_id ?>/<?= $item->agenda_exames_id; ?>/<?= $item->medico_consulta_id ?>');" >Confirmar</a>
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

                                        <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="9"><font size="-2">
                                            <a></a></font>
                                        </td> 

                                    <? } ?>
                                                                                                

                                    <?
                                }
                            }
                            ?>
                        <? } ?>
                    </tr>

                </tbody>
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
<script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>    

<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css"> 
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>
<script type="text/javascript">

function abrirImpressaoTudo(ambulatorio_laudo_id){
    window.open('<?= base_url() ?>ambulatorio/laudo/impressaolaudotudonovo_imprimir/'+ambulatorio_laudo_id);
    window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceitatodosnovo_imprimir/'+ambulatorio_laudo_id);
    window.open('<?= base_url() ?>ambulatorio/laudo/imprimirReceitaEspecialTodosnovo_imprimir/'+ambulatorio_laudo_id);
}


                                                                function confirmarPaciente(url){
                                                                   if(confirm('Deseja realmente confirmar o paciente?')){
                                                                    window.open(url);
                                                                   } 
                                                                }
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

//              alert('teste_parada');
                                                                                $('.carregando').show();
//                    alert('teste_parada');
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
//                    alert('teste_parada');
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

                                                                    
                                                                    

                                                                });
                                                                window.setTimeout(function(){
                                                                location.reload();
                                                                }, 60000); 
                                                                // 60000 = 1 minuto

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

$.getJSON('<?= base_url() ?>appPacienteAPI/confirmarpagamentoautomaticogerencianet', {plano: 'teste', ajax: true}, function (j) {
//           alert(j);
//                    
});
</script>

<script>

    function pisca(item) {

        var ob = document.getElementById(item);

        if (ob.style.color == "red") {

            ob.style.color = "black";

        } else {

            ob.style.color = "red";

        }

    }



</script>




<script>

    t = setInterval("pisca('piscar')", 500)

</script>

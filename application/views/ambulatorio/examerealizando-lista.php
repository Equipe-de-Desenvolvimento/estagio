<?
$empresa_id = $this->session->userdata('empresa_id');
$data['empresa'] = $this->empresa->listarempresatoten($empresa_id);
@$endereco = $data['empresa'][0]->endereco_toten;
?> 
<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3 class="singular"><a href="#">Manter Atendimentos</a></h3>
        <div>
            <?
            $salas = $this->exame->listartodassalas();
            $empresa_p = $this->guia->listarempresapermissoes();
            ?>
            <table>
                <thead>
                    <tr>
                        <th  colspan="2" class="tabela_title">Salas</th>
                        <th class="tabela_title">Nome</th>
                    </tr>
                    <tr>

                <form method="get" action="<?= base_url() ?>ambulatorio/exame/listarexamerealizando">
                    <th colspan="2" class="tabela_title">
                        <select name="sala" id="sala" class="size2">
                            <option value="">TODAS</option>
                            <? foreach ($salas as $value) : ?>
                                <option value="<?= $value->exame_sala_id; ?>" <?
                                if (@$_GET['sala'] == $value->exame_sala_id):echo 'selected';
                                endif;
                                ?>><?php echo $value->nome; ?></option>
                                    <? endforeach; ?>
                        </select>
                    </th>
                    <th colspan="4" class="tabela_title">
                        <input type="text" name="nome" class="texto09" value="<?php echo @$_GET['nome']; ?>" />

                    </th>
                    <th colspan="2" class="tabela_title">
                        <button type="submit" id="enviar">Pesquisar</button>
                    </th>
                </form>
                </tr>
                <tr>
                    <th class="tabela_header">Pedido</th>
                    <th class="tabela_header">Nome</th>
                    <th class="tabela_header">Agenda</th>
                    <th class="tabela_header">Tempo</th>
                    <th class="tabela_header">Sala</th>
                    <th class="tabela_header">Tecnico</th>
                    <th class="tabela_header">Procedimento</th>
                    <th class="tabela_header" colspan="10"><center>A&ccedil;&otilde;es</center></th>
                </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->exame->listarexames($_GET);
                $total = $consulta->count_all_results();
                $limit = $limite_paginacao;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $perfil_id = $this->session->userdata('perfil_id');
                        $lista = $this->exame->listarexames($_GET)->orderby('e.data_cadastro')->limit($limit, $pagina)->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            $dataFuturo = date("Y-m-d H:i:s");
                            $dataAtual = $item->data_cadastro;

                            $date_time = new DateTime($dataAtual);
                            $diff = $date_time->diff(new DateTime($dataFuturo));
                            $teste = $diff->format('%H:%I:%S');

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

                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->guia_id; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/examepacientedetalhes/<?= $item->paciente_id; ?>/<?= $item->procedimento_tuss_id; ?>/<?= $item->guia_id; ?>/<?= $item->agenda_exames_id; ?>', 'toolbar=no,Location=no,menubar=no,width=500,height=200');"><?= $item->paciente; ?></a></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->inicio; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $teste; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->sala; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->tecnico; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->procedimento; ?></td>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;"> 
                                    <div class="bt_link" style="width: 85px">                                 
                                        <a style="width: 85px" href="<?= base_url() ?>ambulatorio/exame/anexarimagem/<?= $item->exames_id ?>/<?= $item->sala_id ?>">
                                            Atendimento
                                        </a>
                                    </div>
                                </td>
                                <? if ($empresa_p[0]->tecnica_enviar == 't' || ($perfil_id != 15 && $perfil_id != 7)) { ?>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                        <a href="<?= base_url() ?>ambulatorio/exame/finalizarexame/<?= $item->exames_id ?>/<?= $item->sala_id ?> ">
                                            Finalizar
                                        </a></div>
                                </td>
                                <? } ?>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                        <a href="<?= base_url() ?>ambulatorio/exame/finalizarexametodos/<?= $item->sala_id ?>/<?= $item->guia_id; ?>/<?= $item->grupo; ?> ">
                                            Todos
                                        </a></div>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                        <a href="<?= base_url() ?>ambulatorio/exame/pendenteexame/<?= $item->exames_id ?>/<?= $item->sala_id ?> ">
                                            Pendente
                                        </a></div>
                                </td>
                                <td>
                                    <? if ($empresa_p[0]->tecnica_enviar == 't' || ($perfil_id != 15 && $perfil_id != 7)) { ?>


                                    
                                    <? if ($endereco != '') { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="70px;">
                                            <div class="bt_link">
                                                <a onclick="chamarPaciente('<?= $url_enviar_ficha ?>', <?= $toten_fila_id ?>, <?= $item->medico_consulta_id ?>, <?= $toten_sala_id ?>);" >Chamar</a>
                                            </div>
                                            <!--                                        impressaolaudo -->
                                        </td>
                                    <?}else{?>
                                        <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                            <a href="<?= base_url() ?>ambulatorio/laudo/chamarpaciente2/<?= $item->ambulatorio_laudo_id ?> ">
                                                Chamar</a></div>
                                        <!--                                        impressaolaudo -->
                                        </td>
                                    <?}?>
                                    
                                <? } ?>

                                <?
                                if ($perfil_id == 1) {
                                    if ($item->agrupador_pacote_id == '') {
                                        ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                                <a href="<?= base_url() ?>ambulatorio/exame/examecancelamento/<?= $item->exames_id ?>/<?= $item->sala_id ?> /<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?> " target="_blank">
                                                    Cancelar
                                                </a></div>
                                        </td>
                                    <? } else { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link_new">
                                                <a target="_blank" href="<?= base_url() ?>ambulatorio/exame/pacotecancelamento/<?= $item->sala_id ?>/<?= $item->guia_id ?>/<?= $item->paciente_id ?>/<?= $item->agrupador_pacote_id ?> "  target="_blank">
                                                    Cancelar Pacote
                                                </a></div>
                                        </td>
                                        <?
                                    }
                                }
                                ?>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                        <a href="<?= base_url() ?>ambulatorio/exame/voltarexame/<?= $item->exames_id ?>/<?= $item->sala_id ?>/<?= $item->agenda_exames_id ?> ">
                                            Voltar
                                        </a></div>
                                </td>
        <!--                                <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                        <a href="<?= base_url() ?>ambulatorio/exame/estoqueguia/<?= $item->exames_id ?>/<?= $item->sala_id ?>/<?= $item->agenda_exames_id ?> ">
                                            estoque
                                        </a></div>
                                </td>-->
                            </tr>

                        </tbody>
                        <?php
                    }
                }
                ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="17">
                            <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total; ?>
                            <span style="margin-left: 15px; color: white; font-weight: bolder;"> Limite: </span>
                            <select style="width: 57px">
                                <option onclick="javascript:window.location.href = ('<?= base_url() ?>ambulatorio/exame/listarexamerealizando/25');" <?
                                if ($limit == 25) {
                                    echo "selected";
                                }
                                ?>>25 </option>
                                <option onclick="javascript:window.location.href = ('<?= base_url() ?>ambulatorio/exame/listarexamerealizando/50');" <?
                                if ($limit == 50) {
                                    echo "selected";
                                }
                                ?>>50 </option>
                                <option onclick="javascript:window.location.href = ('<?= base_url() ?>ambulatorio/exame/listarexamerealizando/100');" <?
                                if ($limit == 100) {
                                    echo "selected";
                                }
                                ?>> 100 </option>
                                <option onclick="javascript:window.location.href = ('<?= base_url() ?>ambulatorio/exame/listarexamerealizando/todos');" <?
                                if ($limit == "todos") {
                                    echo "selected";
                                }
                                ?>> Todos </option>
                            </select>
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
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
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

</script>

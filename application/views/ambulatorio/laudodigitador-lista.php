<link href="<?= base_url() ?>css/ambulatorio/laudodigitador-lista.css" rel="stylesheet"/>
<div class="content"> <!-- Inicio da DIV content -->
    <?
    $salas = $this->exame->listartodassalas();
    $setor = $this->guia->listarsetores();
    $medicos = $this->operador_m->listarmedicos();
    $empresa_id = $this->session->userdata('empresa_id');
    $data['empresa'] = $this->guia->listarempresa($empresa_id);
    $medico_laudodigitador = $data['empresa'][0]->medico_laudodigitador;
    $data['permissao'] = $this->empresa->listarverificacaopermisao2($this->session->userdata('empresa_id'));
    $convenios = $this->convenio->listar()->get()->result();
    $empresa = $data['permissao'];
    $entrega_laudos = $empresa[0]->entrega_laudos;
    $setores = $empresa[0]->setores;
    ?>
    <div id="accordion">
        <h3 class="singular">Manter Laudo Digitador</h3>
        <div>
            <form name="laudo_lista" id="laudo_lista" method="get" action="<?= base_url() ?>ambulatorio/laudo/pesquisardigitador">
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
                        <div class="col-lg-3">
                            <div>
                                <label>Médico</label>
                                <select name="medico" id="medico" class="form-control">
                                    <option value=""></option>
                                    <? foreach ($medicos as $value) : ?>
                                        <option value="<?= $value->operador_id; ?>" <?
                                        if (@$_GET['medico'] == $value->operador_id):echo 'selected';
                                        endif;
                                        ?>><?php echo $value->nome; ?></option>
                                    <? endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Status</label>
                                <select name="situacao" id="situacao" class="form-control">
                                    <option value='' ></option>
                                    <option value='AGUARDANDO' <? if (@$_GET['situacao'] == 'AGUARDANDO') echo 'selected'; ?>>AGUARDANDO</option>
                                    <option value='DIGITANDO' <? if (@$_GET['situacao'] == 'DIGITANDO') echo 'selected'; ?>>DIGITANDO</option>
                                    <option value='FINALIZADO' <? if (@$_GET['situacao'] == 'FINALIZADO') echo 'selected'; ?>>FINALIZADO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Data</label>
                                <input type="date"  id="data" name="data" class="form-control texto03"  value="<?php echo @$_GET['data']; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Exame</label>
                                <input type="text"  id="exame_id" name="exame_id" class="form-control"  value="<?php echo @$_GET['exame_id']; ?>" />
                            </div>
                            <?if($empresa[0]->prontuario_antigo_pesquisar == 't'){?>
                            <div>
                                <label>Prontuário Antigo</label>
                                <input type="text" name="prontuario_antigo" class="texto03 bestupper" value="<?php echo @$_GET['prontuario_antigo']; ?>" />
                            </div>
                            <?}?>

                        </div>
                        <div class="col-lg-3">
                            <div>
                                <label>Prontuario</label>
                                <input type="text"  id="paciente_id" name="paciente_id" class="form-control"  value="<?php echo @$_GET['paciente_id']; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div>
                                <label>Nome</label>
                                <input type="text" name="nome" class="form-control" value="<?php echo @$_GET['nome']; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Convênio</label>
                                <select  style="width:100%" class="chosen-select texto04" name="convenios[]" id="convenios" multiple data-placeholder="Selecione">
                                    <option value="0" <?= @(in_array("0", $_GET['convenios'])) ? "selected":""; ?> >TODOS</option>
                                    <?php foreach($convenios as $item){?>
                                        <option value="<?= $item->convenio_id; ?>" <?= @(in_array($item->convenio_id, $_GET['convenios'])) ? "selected":""; ?> ><?= $item->nome; ?></option>
                                    <?}?>
                                </select>
                            </div>
                        </div>
                        <? if($setores == 't'){?>
                        <div class="col-lg-2">
                            <div>
                                <label>Setores</label>
                                <select name="setores" id="setores" class="size1">
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
                        <?}?>
                        <div class="col-lg-2">
                            <button class="btn btn-outline-success btnsend" type="submit" id="enviar">Pesquisar</button>
                        </div>

                    </div>
                </fieldset>
            </form>


            <br>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th class="tabela_header" width="300px;">Nome</th>
                        <th class="tabela_header" width="30px;">Idade</th>
                        <th class="tabela_header" width="30px;">Data</th>
                        <th class="tabela_header" width="130px;">M&eacute;dico</th>
                        <th class="tabela_header">Status</th>
                        <th class="tabela_header" width="300px;">Procedimento</th>
                                                <?if($setores == 't'){?>
                        <th class="tabela_header" width="300px;">Setor</th>
                                                    <?}?>
                        <!--<th class="tabela_header">M&eacute;dico Revisor</th>
                        <th class="tabela_header">Status Revisor</th>-->
                        <th class="tabela_header" colspan="8" width="140px;"><center>A&ccedil;&otilde;es</center></th>
                </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->laudo->listardigitador($_GET, $medico_laudodigitador);
                $total = $consulta->count_all_results();
                $limit = 10;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $lista = $this->laudo->listar2digitador($_GET, $medico_laudodigitador)->limit($limit, $pagina)->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            $dataFuturo = date("Y-m-d H:i:s");
                            $dataAtual = $item->data_cadastro;
                            $operador_id = $this->session->userdata('operador_id');
                            $perfil_id = $this->session->userdata('perfil_id');
                            $date_time = new DateTime($dataAtual);
                            $diff = $date_time->diff(new DateTime($dataFuturo));
                            $teste = $diff->format('%d');

                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>" onclick="javascript:window.open('<?= base_url() . "ambulatorio/laudo/auditorialaudo/" . $item->ambulatorio_laudo_id ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');"><?= $item->paciente; ?></td>
                                <td class="<?php echo $estilo_linha; ?>" width="30px;"><?= $item->idade; ?></td> 

                                <td class="<?php echo $estilo_linha; ?>" width="30px;"><?= substr($item->data_cadastro, 8, 2) . "/" . substr($item->data_cadastro, 5, 2) . "/" . substr($item->data_cadastro, 0, 4); ?></td>
  
                                <td class="<?php echo $estilo_linha; ?>" width="130px;"><?= substr($item->medico, 0, 18); ?></td>
                                <? if ($item->situacao != 'FINALIZADO') { ?>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->situacao; ?></td>
                                <? } else { ?>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->situacao . '<br>' . substr($item->data_atualizacao, 8, 2) . '/' . substr($item->data_atualizacao, 5, 2) . '/' . substr($item->data_atualizacao, 0, 4) . '<br>' . substr($item->data_atualizacao, 10, 8); ?></td>
                                <? } ?>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->procedimento; ?></td>
                                <? if($setores == 't'){?>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->setore; ?></td>
                                <?}?>
                                <!--<td class="<?php echo $estilo_linha; ?>"><?= $item->medicorevisor; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->situacao_revisor; ?></td>-->
                                <? if($entrega_laudos != 't' || $operador_id == 1 || $perfil_id == 1 || $perfil_id == 4){ ?>
                                <? if (($item->medico_parecer1 == $operador_id && $item->situacao == 'FINALIZADO') || $item->situacao != 'FINALIZADO' || $operador_id == 1 || $perfil_id == 1) { ?>

                                    <td class="<?php echo $estilo_linha; ?>" width="40px;"><div class="bt_link">
                                            <a class="btn btn-outline-primary btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarlaudodigitador/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>');" >
                                                Laudo</a></div>
                                    </td>
                                    <td class="<?php echo $estilo_linha; ?>" width="40px;"><div class="bt_link">
                                            <a class="btn btn-outline-primary btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/todoslaudo/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->guia_id ?>');" >
                                                TODOS</a></div>
                                    </td> 
                                <? } else { ?>
                                    <td class="<?php echo $estilo_linha; ?>" width="40px;"><font size="-2">
                                        <a class="btn btn-outline-primary btn-sm">Bloqueado</a>
                                    </td>
                                <? } 
                                }
                                ?>
                                <!--                                    <td class="<?php echo $estilo_linha; ?>" width="70px;">
                                    <a href="<?= base_url() ?>ambulatorio/laudo/carregarrevisao/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>">
                                        Revis&atilde;o</a>
                                </td>-->
                                <? if($entrega_laudos != 't' || $item->situacao == "FINALIZADO" || $operador_id == 1 || $perfil_id == 1 || $perfil_id == 4){ ?>
                               <? if(!($data['permissao'][0]->laudo_status_f == "t" &&  $item->situacao != 'FINALIZADO')){?> 
                                <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                        <a class="btn btn-outline-primary btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaolaudo/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>');">
                                            Imprimir</a></div>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                        <a class="btn btn-outline-primary btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoimagem/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>');">
                                            imagem</a></div>
                                </td>
                               <?php }else{?>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;"></td>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;"></td>
                               <?php }
                               }?>

                                <? if($entrega_laudos != 't' || $operador_id == 1 || $perfil_id == 1 || $perfil_id == 4){ ?>
                                <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                    <a class="btn btn-outline-warning btn-sm" href="<?= base_url() ?>ambulatorio/guia/impressaoetiiqueta/<?= $item->paciente_id; ?>/<?= $item->guia_id; ?>/<?= $item->agenda_exames_id ?>">Etiqueta</a></div>
                                </td>
                                <? if ($perfil_id == 1 || $operador_id == 4582 || ($perfil_id == 20 && $data['permissao'][0]->gerente_cancelar_atendimento == 't')) { ?>
                                    <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                            <a class="btn btn-outline-danger btn-sm" href="<?= base_url() ?>ambulatorio/exame/examecancelamento/<?= $item->exames_id ?>/<?= $item->sala_id ?> /<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?> " target="_blank">
                                                Cancelar
                                            </a></div>
                                    </td>
                                <? } ?>

                                <? if (((($item->medico_parecer1 == $operador_id && $item->situacao == 'FINALIZADO') || $item->situacao != 'FINALIZADO' || $operador_id == 1)) && @$_GET['exame_id'] != "") { ?>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $("body").keypress(function (event) {
                                        if (event.keyCode == 120)   // se a tecla apertada for 13 (enter)
                                        {
                                            window.open("<?= base_url() ?>ambulatorio/laudo/carregarlaudodigitador/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>"); // abre uma janela
                                                        }
                                                    });
                                                });

                            </script>

                        <? } ?>

                        <? if ($item->recebido == 'f') { ?>
                            <td class="<?php echo $estilo_linha; ?>" width="50px;"><a class="btn btn-outline-info btn-sm" href="<?= base_url() ?>ambulatorio/guia/recebidoresultado/<?= $item->paciente_id; ?>/<?= $item->agenda_exames_id ?>">ENTREGAR
                                </a></td>
                        <? } else {
                            ?>
                            <td class="<?php echo $estilo_linha; ?>" width="50px;"><?= $item->operadorrecebido . " - " . substr($item->data_recebido, 8, 2) . "/" . substr($item->data_recebido, 5, 2) . "/" . substr($item->data_recebido, 0, 4) ?>
                            </td>
                            <?
                        }
                        if ($item->entregue == "") {
                            ?>
                            <td class="<?php echo $estilo_linha; ?>" width="100px;"><a class="btn btn-outline-success btn-sm" onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/entregaexame/$item->paciente_id/$item->agenda_exames_id"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=600,height=400');">
                                    ENTREGUE
                                </a></td>
                        <? } else { ?>
                            <td class="<?php echo $estilo_linha; ?>" width="50px;"><center><a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/vizualizarobservacao/$item->agenda_exames_id"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=600,height=400');"> SIM <br/> <?= substr($item->data_entregue, 8, 2) . "/" . substr($item->data_entregue, 5, 2) . "/" . substr($item->data_entregue, 0, 4) ?></a></center>
                            </td>
                        <? } ?></tr>

                        </tbody>
                        <?php
                        }
                    }
                }
                ?>
                <tfoot>
                    <tr class="text-center pag">
                        <th class="tabela_footer pagination-container" colspan="14">
                            <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total; ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

</div> <!-- Final da DIV content -->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css"> 
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>  
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>
<script type="text/javascript">

    document.laudo_lista.exame_id.focus()

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

</script>

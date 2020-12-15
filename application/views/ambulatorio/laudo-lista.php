<link href="<?= base_url() ?>css/ambulatorio/laudo-lista.css?v=3" rel="stylesheet"/>
<div class="content"> <!-- Inicio da DIV content -->
    <?
    $perfil_id = $this->session->userdata('perfil_id');
    $salas = $this->exame->listartodassalas();
    $setor = $this->guia->listarsetores();
    $medicos = $this->operador_m->listarmedicos();
    $especialidade = $this->exame->listarespecialidade();
    $data['empresa_permissao'] = $this->empresa->listarverificacaopermisao2($this->session->userdata('empresa_id'));
    $empresa = $data['empresa_permissao'];
    $entrega_laudos = $empresa[0]->entrega_laudos;
    $setores = $empresa[0]->setores;
    $convenios = $this->convenio->listar()->get()->result();
    ?>
    
    <table>
        <tr>

            <td>  
                <div class="bt_link_new">
                    <button class="btn btn-outline-primary btn-round btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/agenda/medicoagenda');">
                        Bloquear Agenda
                    </button>
                </div>
            </td>
        </tr>
    </table>
    <div id="accordion">
        <h3 class="singular"><a href="#">Manter Laudo</a></h3>
        <div>
            <form method="get" action="<?= base_url() ?>ambulatorio/laudo/pesquisar">
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
                        <div class="col-lg-2">
                            <div>
                                <label>Especialidade</label>
                                <select name="especialidade" id="especialidade" class="form-control">
                                    <option value=""></option>
                                    <? foreach ($especialidade as $value) : ?>
                                        <option value="<?= $value->cbo_ocupacao_id; ?>" <?
                                        if (@$_GET['especialidade'] == $value->cbo_ocupacao_id):echo 'selected';
                                        endif;
                                        ?>><?php echo $value->descricao; ?></option>
                                    <? endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div>
                                <label>Medico</label>
                                <select name="medico" id="medico" class="form-control">
                                    <option value=""> </option>
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
                                <select name="situacao" id="situacao" class="form-control" >
                                    <option value='' ></option>
                                    <option value='AGUARDANDO' >AGUARDANDO</option>
                                    <option value='DIGITANDO' >DIGITANDO</option>
                                    <option value='FINALIZADO' >FINALIZADO</option>
                                    <option value='REVISAR' >REVISAR</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Data</label>
                                <input type="date" class="form-control"  value="<?php echo @$_GET['data']; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div>
                                <label>Nome</label>
                                <input type="text" name="nome" class="form-control texto09" value="<?php echo @$_GET['nome']; ?>" />
                            </div>

                            <div>
                                <?if($empresa[0]->prontuario_antigo_pesquisar == 't'){?>
                                <label>Prontuário Antigo</label>
                                <input type="text" name="prontuario_antigo" class="texto03 bestupper" value="<?php echo @$_GET['prontuario_antigo']; ?>" />
                                <? } ?>
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

                        <div class="col-lg-2 btnsend">
                                <button class="btn btn-outline-success" type="submit" id="enviar">Pesquisar</button>

                        </div>
                    </div>
                </fieldset>
            </form>
            <br>
        <div class="table-responsive">
            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr>
                        <th class="tabela_header" width="300px;">Nome</th>
                        <th class="tabela_header" width="30px;">Idade</th>
                        <th class="tabela_header" width="30px;">Data</th>
                        <th class="tabela_header" width="130px;">M&eacute;dico</th>
                        <th class="tabela_header">Status</th>
                        <th class="tabela_header" width="300px;">Procedimento</th>
                        <?if($setores == 't'){?>
                        <td class="tabela_header">Setor</td>
                                <?}?>
<!--                            <th class="tabela_header">M&eacute;dico Revisor</th>
                        <th class="tabela_header">Status Revisor</th>-->
                        <th class="tabela_header" colspan="5" width="140px;"><center>A&ccedil;&otilde;es</center></th>
                </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->laudo->listar($_GET);
                $total = $consulta->count_all_results();
                $limit = 10;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $lista = $this->laudo->listar2($_GET)->limit($limit, $pagina)->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            $dataFuturo = date("Y-m-d H:i:s");
                            $dataAtual = $item->data_cadastro;
                            $operador_id = $this->session->userdata('operador_id');
                            $perfil_id = $this->session->userdata('perfil_id');
                            $date_time = new DateTime($dataAtual);
                            $diff = $date_time->diff(new DateTime($dataFuturo));
                            $teste = $diff->format('%d');

                            $ano_atual = date("Y");
                            $ano_nascimento = substr($item->nascimento, 0, 4);
                            $idade = $ano_atual - $ano_nascimento;

                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="tbdata <?php echo $estilo_linha; ?>"><?= $item->paciente; ?></td>
                                <td class="tbdata <?php echo $estilo_linha; ?>" width="30px;"><?= $idade; ?></td>

                                <?
                                if (isset($item->data_antiga)) {
                                    $data_alterada = 'alterada';
                                } else {
                                    $data_alterada = '';
                                }
                                ?>

                                <td class="tbdata <?php echo $estilo_linha; ?>" width="30px;"><a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/alterardata/<?= $item->ambulatorio_laudo_id ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=600,height=250');">

                                        <?
                                        echo substr($item->data_cadastro, 8, 2) . "/" . substr($item->data_cadastro, 5, 2) . "/" . substr($item->data_cadastro, 0, 4);
                                        ?> 

                                        <br/><?= $data_alterada ?></a></td>

                                <td class="tbdata <?php echo $estilo_linha; ?>" width="130px;"><?= substr($item->medico, 0, 18); ?></td>
                                <td class="tbdata <?php echo $estilo_linha; ?>"><?= $item->situacao; ?></td>
                                <td class="tbdata <?php echo $estilo_linha; ?>"><?= $item->procedimento; ?></td>

                                <? if($setores == 't'){?>
                                    <td class="tbdata <?php echo $estilo_linha; ?>"><?= $item->setore; ?></td>
                                <?}?>

        <!--                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->medicorevisor; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->situacao_revisor; ?></td>-->
                                <?
                                if($entrega_laudos != 't' || $operador_id == 1 || $perfil_id == 1 || $perfil_id == 4){
                                if (($item->medico_parecer1 == $operador_id && $item->situacao == 'FINALIZADO') || $item->situacao != 'FINALIZADO' || $operador_id == 1 || $perfil_id == 1) {
                                    if ($item->grupo == 'ECOCARDIOGRAMA' && false) {
                                        ?>
                                        <td class="tbdata <?php echo $estilo_linha; ?>" width="40px;"><div class="bt_link">
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarlaudoeco/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>');" >
                                                    Laudo</a></div>
                                        </td>
                                        <?
                                    } else {
                                        ?>
                                        <td class="tbdata <?php echo $estilo_linha; ?>" width="40px;"><div class="btn btn-outline-primary btn-sm">
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarlaudo/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>');" >
                                                    Laudo</a></div>
                                        </td>
                                        <?
                                    }
                                } else {
                                    ?>
                                    <td class="tbdata <?php echo $estilo_linha; ?>" width="40px;"><font size="-2">
                                        <a>Bloqueado</a></font>
                                    </td>
                                <? }
                                }
                                ?>
        <!--                                    <td class="<?php echo $estilo_linha; ?>" width="70px;">
        <a href="<?= base_url() ?>ambulatorio/laudo/carregarrevisao/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>">
        Revis&atilde;o</a>
        </td>-->
        <? if($entrega_laudos != 't' || $item->situacao == "FINALIZADO" || $operador_id == 1 || $perfil_id == 1 || $perfil_id == 4){ ?>
 <? if(!($data['empresa_permissao'][0]->laudo_status_f == "t" &&  $item->situacao != "FINALIZADO")){?>
                                <td class="tbdata <?php echo $estilo_linha; ?>" width="70px;"><div class="btn btn-outline-default btn-sm">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaolaudo/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>');">
                                            Imprimir</a></div>
                                </td>
                                <td class="tbdata <?php echo $estilo_linha; ?>" width="70px;"><div class="btn btn-outline-success btn-sm">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoimagem/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>');">
                                            Imagem</a></div>
                                </td>
 <?}else{?>
                                <td ></td>
                                <td class="tbdata <?php echo $estilo_linha; ?>" width="70px;">
 <?php }
 }?>

 <? if($entrega_laudos != 't' || $operador_id == 1 || $perfil_id == 1 || $perfil_id == 4){?>
                                <td class="tbdata"><div class="btn btn-outline-danger btn-sm">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/impressaoetiiqueta/<?= $item->paciente_id ?>/<?= $item->guia_id; ?>/<?= $item->agenda_exames_id ?>');">
                                            Etiqueta</a></div>
                                </td>
                                <td class="tbdata <?php echo $estilo_linha; ?>" width="70px;"><div class="btn btn-outline-warning btn-sm">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/listarxml/<?= $item->convenio ?>/<?= $item->paciente_id ?>');">
                                            XML</a></div>
                                </td>
                                <? } ?>
                            </tr>

                        </tbody>
                        <?php
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

    setInterval(function () {
        window.location.reload();
    }, 180000);
</script>

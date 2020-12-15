<link href="<?= base_url() ?>css/ambulatorio/medicoconsulta-lista.css" rel="stylesheet"/>
<div id="page-wrapper"> <!-- Inicio da DIV content -->
    <?
    $salas = $this->exame->listartodassalas();
    $medicos = $this->operador_m->listarmedicos();
    $especialidade = $this->exame->listarespecialidade();
    $perfil_id = $this->session->userdata('perfil_id');
    ?>
    <!-- <div class="row"> -->
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="table-responsive " id="pesquisar">
                <form method="get" action="<?= base_url() ?>ambulatorio/exame/listarmultifuncaomedicoconsulta">
                    <table width="100%" class="table " id="dataTables-example">
                        <div class="container">
                            <!--<th>Situação</th>-->
                            <!--<th>Medico</th>-->
                            <div class="date">
                                <h6>Data</h6>
                                <!-- </div> --><br>
                                <!-- <div class="name"> -->
                                <h6>Nome</h6>
                                <!-- </div> --><br>
                                <!-- <div class="action"> -->
                                <h6>Ações</h6>
                            </div>
                            <!--<td>

                                                                    <select name="situacao" id="situacao" class="form-control texto06">
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

                                                                </td>-->

                            <!--<td>
                                                                    <select name="medico" id="medico" class="form-control texto06">
                                                                        <option value=""> </option>
                                                                <? foreach ($medicos as $value) : ?>
                                                                                                <option value="<?= $value->operador_id; ?>"<?
                                if (@$_GET['medico'] == $value->operador_id):echo 'selected';
                                endif;
                                ?>>

                                                                    <?php echo $value->nome . ' - CRM: ' . $value->conselho; ?>


                                                                                                </option>
                                                                <? endforeach; ?>

                                                                    </select>
                                                                </td>-->
                            <div class="idate">
                                <input type="text"  id="data" alt="date" name="data" class="form-control texto02"  value="<?php echo @$_GET['data']; ?>" />
                                <!-- </div> --><br>
                                <!-- <div class="iname"> -->
                                <input type="text" name="nome" class="form-control texto06" value="<?php echo @$_GET['nome']; ?>"/>
                                <!-- </div> --><br>
                                <!-- <div class="iaction"> -->
                                <button type="submit" class="btn btn-default  btn-danger btn-sm" name="enviar"><i class="fa fa-search fa-1x"></i></button>
                            </div>
                            <div class="calendar">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </table>
                </form>
                <style>

                    .desbloq{
                        width: 150pt;
                    }

                </style>

            </div>
            <div class="panel-heading ">
                Atendimento Médico
            </div>
            <a class="btn btn-outline-danger btn-round btn-sm" href="<?php echo base_url() ?>ambulatorio/exametemp/novopacienteconsultaencaixe" target="_blank">
                <i class="fa fa-plus fa-w"></i> Encaixar
            </a>
            <a class="btn btn-outline-danger btn-round btn-sm" href="<?php echo base_url() ?>cadastros/pacientes/novo" target="_blank">
                <i class="fa fa-plus fa-w"></i> Novo Cadastro
            </a>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!--<div class="table-responsive">-->
                <?
                $listas = $this->exame->listarmultifuncao2consulta($_GET)->get()->result();
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
                <!--<table class="table">
                            <thead>
                                <tr><td class="tabela_header">Aguardando</td><td class="tabela_header"><?= $aguardando; ?></td></tr>
                                <tr><td class="tabela_header">Espera</td><td class="tabela_header"><?= $espera; ?></td></tr>
                                <tr><td class="tabela_header">Agendado</td><td class="tabela_header"><?= $agenda; ?></td></tr>
                                <tr><td class="tabela_header">Atendido</td><td class="tabela_header"><?= $finalizado; ?></td></tr>
                            </thead>
                        </table>
                    </div>-->
                <div class="table-responsive">

                    <table class="table ">
                        <thead>
                        <tr>
                            <th>Ordenador</th>
                            <th>Situação</th>
                            <th>Horário</th>
                            <th>Paciente</th>
                            <th>Data</th>
                            <th>Procedimento</th>
                            <th>OBS</th>
                            <th class="tabela_acoes">Ações</th>
                        </tr>
                        </thead>
                        <?php
                        $url = $this->utilitario->build_query_params(current_url(), $_GET);
                        $consulta = $this->exame->listarmultifuncaoconsulta($_GET);
                        $total = $consulta->count_all_results();
                        $limit = 20;
                        isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;
                        if ($total > 0) {
                            ?>

                            <?php
                            $lista = $this->exame->listarmultifuncao2consulta1($_GET)->limit($limit, $pagina)->get()->result();
                            $estilo_linha = "tabela_content01";
                            $operador_id = $this->session->userdata('operador_id');

                            // echo "<pre>";
                            // print_r($lista);
                            // die;
                            foreach ($lista as $item) {

                                $dataFuturo = date("Y-m-d H:i:s");
                                $dataAtual = $item->data_autorizacao;
                                $date_time = new DateTime($dataAtual);
                                $diff = $date_time->diff(new DateTime($dataFuturo));
                                $teste = $diff->format('%H:%I:%S');

                                $verifica = 0;
                                //echo $item->realizada;
                                ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                                if ($item->realizada == 't' && $item->situacaolaudo == 'FINALIZADO') {
                                    $situacao = "Finalizado";
                                    $verifica = 4;

                                }
                                elseif ($item->realizada == 't' && $item->situacaolaudo == 'PENDENCIA'){
                                    $situacao = "Pendente";
                                    $verifica = 2;

                                }
                                elseif ($item->realizada == 't' && $item->situacaolaudo == 'RECONVOCACAO'){
                                    $situacao = "Reconvocação";
                                    $verifica = 2;

                                }

                                elseif ($item->realizada == 't' && $item->situacaoexame == 'FINALIZADO') {
                                    $situacao = "Atendendo";
                                    $verifica = 3;
                                }
                                elseif ($item->realizada == 't' && $item->situacaolaudo != 'FINALIZADO') {
                                    $situacao = "Aguardando";
                                    $verifica = 2;
                                }
                                elseif ($item->confirmado == 'f') {
                                    $situacao = "Agenda";
                                    $verifica = 1;
                                } else {
                                    $situacao = "Aguardando";
                                    $verifica = 3;
                                }

                                if ($item->ordenador == '1') {
                                    $classificacao = "<b class='normal'>Normal</b>";
                                } else if ($item->ordenador == '2') {
                                    $classificacao = "<b class='prioridade'>Prioridade</b>";
                                } elseif ($item->ordenador == '3') {
                                    $classificacao = "<b class='urgencia'>Urgência</b>";
                                } else {
                                    $classificacao = "Normal";
                                }
                                ?>

                                <? if ($verifica == 1) { ?>
                                    <tr class="success2">
                                        <td><?= $classificacao ?></td>
                                        <td><?= $situacao ?></td>
                                        <td><?= $item->inicio ?></td>
                                        <td><?= $item->paciente. "-" .$item->situacaolaudo ?></td>
                                        <td><?= date("d/m/Y", strtotime($item->data)) ?></td>
                                        <td><?= $item->convenio. " - " .$item->procedimento; ?></td>
                                        <td><?= $item->observacoes; ?></td>
                                        <td class="tabela_acoes desbloq">
                                            <? if ($item->situacaolaudo != '') { ?>

                                                <? if (($item->medico_parecer1 == $operador_id && $item->situacaolaudo == 'FINALIZADO') || ($item->realizada == 't' && $item->situacaolaudo != 'FINALIZADO') || $operador_id == 1 && ($item->tipo != 'EXAME')) { ?>
                                                    <a class="btn btn-danger btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregaranaminese/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>');" >
                                                        Atender <i class="fa fa-stethoscope" aria-hidden="true"></i>
                                                    </a>
                                                <? } else { ?>
                                                    <button class="btn  btn-primary btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarlaudo/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->tipo?>');">
                                                        Laudo  <i class="fa fa-stethoscope" aria-hidden="true"></i>
                                                    </button>
                                                <? } ?>
                                                <a class="btn  btn-primary btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/anexarimagem/<?= $item->ambulatorio_laudo_id ?>');">
                                                    Arquivos <i class="fa fa-file-archive-o" aria-hidden="true"></i></a>

                                            <? } else { ?>
                                                <p>
                                                    <? if ($item->paciente_id != '') { ?>


                                                        <? if (date("d/m/Y") == date("d/m/Y", strtotime($item->data)) && $item->confirmado == 'f') { ?>
                                                            <a class="btn btn-info btn-sm" onclick="javascript:window.open('<?= base_url() ?>cadastros/pacientes/procedimentoautorizarconsulta/<?= $item->paciente_id ?>');">Autorizar <i class="fa fa-check" aria-hidden="true"></i>

                                                            </a>

                                                        <? } ?>
                                                        <button class="btn  btn-primary btn-sm" disabled="">
                                                            Atender <i class="fa fa-stethoscope" aria-hidden="true"></i>

                                                        </button>
                                                    <? } else { ?>

                                                        <? if ($item->bloqueado == 'f') { ?>
                                                            <a class="btn btn-success btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exametemp/carregarconsultatempmedico/<?= $item->agenda_exames_id ?>');">Agendar <i class="fa fa-list-alt" aria-hidden="true"></i>

                                                            </a>
                                                            <a class="btn btn-success btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/bloquear/<?= $item->agenda_exames_id ?>/<?= $item->inicio; ?>');">Bloquear <i class="fa fa-lock" aria-hidden="true"></i>
                                                            </a>
                                                        <? } else { ?>
                                                            <a class="btn btn-success btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/desbloquear/<?= $item->agenda_exames_id ?>/<?= $item->inicio; ?> ', 'toolbar=no,Location=no,menubar=no,width=500,height=200');">Desbloq. <i class="fa fa-unlock" aria-hidden="true"></i>
                                                            </a>
                                                        <? }
                                                        ?>

                                                    <? } ?>

                                                </p>
                                            <? } ?>
                                        </td>
                                    </tr>
                                <? } elseif ($verifica == 2) { ?>
                                    <tr class="alert alert-aguardando">
                                        <td><?= $classificacao ?></td>
                                        <td><?= $situacao ?></td>
                                        <td><?= $item->inicio ?></td>
                                        <td><?= $item->paciente. "-" .$item->situacaolaudo ?></td>
                                        <td><?= date("d/m/Y", strtotime($item->data)) ?></td>
                                        <td><?= $item->convenio. " - " .$item->procedimento; ?></td>
                                        <td><?= $item->observacoes; ?></td>
                                        <td class="tabela_acoes desbloq">
                                            <? if ($item->situacaolaudo != '') { ?>

                                                <? if (($item->medico_parecer1 == $operador_id && $item->situacaolaudo == 'FINALIZADO') || ($item->realizada == 't' && $item->situacaolaudo != 'FINALIZADO') && $item->tipo != 'EXAME') { ?>
                                                    <a class="btn  btn-primary btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregaranaminese/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>');" >
                                                        Atender <i class="fa fa-stethoscope" aria-hidden="true"></i></a>
                                                <? } else { ?>
                                                    <button class="btn  btn-primary btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarlaudo/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->tipo?>');">
                                                        Laudo &nbsp&nbsp&nbsp   <i class="fa fa-stethoscope" aria-hidden="true"></i>
                                                    </button>
                                                <? } ?>
                                                <a class="btn  btn-success btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/anexarimagem/<?= $item->ambulatorio_laudo_id ?>');">
                                                    Arquivos <i class="fa fa-file-archive-o" aria-hidden="true"></i></a>

                                            <? } else { ?>
                                                <button class="btn  btn--primary btn-sm" disabled="">
                                                    Atender <i class="fa fa-stethoscope" aria-hidden="true"></i>
                                                </button>
                                                <button class="btn  btn-success btn-sm" disabled="">
                                                    Arquivos <i class="fa fa-file-archive-o" aria-hidden="true"></i>
                                                </button>
                                            <? } ?>
                                        </td>
                                    </tr>

                                <? } elseif ($verifica == 3) { ?>
                                    <tr class="alert alert-aguardando">
                                        <td><?= $classificacao ?></td>
                                        <td><?= $situacao ?></td>
                                        <td><?= $item->inicio ?></td>
                                        <td><?= $item->paciente. "-" .$item->situacaolaudo ?></td>
                                        <td><?= date("d/m/Y", strtotime($item->data)) ?></td>
                                        <td><?= $item->convenio. " - " .$item->procedimento; ?></td>
                                        <td><?= $item->observacoes; ?></td>
                                        <td class="tabela_acoes desbloq">
                                            <? if ($item->situacaolaudo != '') { ?>

                                                <? if ((($item->medico_parecer1 == $operador_id && $item->situacaolaudo == 'FINALIZADO') || ($item->realizada == 't' && $item->situacaolaudo != 'FINALIZADO') || ($item->realizada == 'f' && $item->situacaolaudo == 'AGUARDANDO')) && $item->tipo != 'EXAME') { ?>
                                                    <a class="btn  btn-primary btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregaranaminese/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>');" >
                                                        Atender <i class="fa fa-stethoscope" aria-hidden="true"></i></a>
                                                <? } else {?>
                                                    <button class="btn btn-primary btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarlaudo/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->tipo?>');">
                                                        Laudo &nbsp&nbsp&nbsp <i class="fa fa-stethoscope" aria-hidden="true"></i>
                                                    </button>
                                                <? } ?>
                                                <a class="btn btn-success btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/anexarimagem/<?= $item->ambulatorio_laudo_id ?>');">
                                                    Arquivos <i class="fa fa-file-archive-o" aria-hidden="true"></i></a>

                                            <? } else { ?>
                                                <button class="btn  btn-primary btn-sm" disabled="">
                                                    Atender  <i class="fa fa-stethoscope" aria-hidden="true"></i>
                                                </button>
                                                <button class="btn  btn-success btn-sm" disabled="">
                                                    Arquivos <i class="fa fa-file-archive-o" aria-hidden="true"></i>

                                                </button>
                                            <? } ?>
                                        </td>
                                    </tr>
                                <? } elseif ($verifica == 4) { ?>
                                    <tr class="finalizado">
                                        <td><?= $classificacao ?></td>
                                        <td><?= $situacao ?></td>
                                        <td><?= $item->inicio ?></td>
                                        <td><?= $item->paciente. "-" .$item->situacaolaudo ?></td>
                                        <td><?= date("d/m/Y", strtotime($item->data)) ?></td>
                                        <td><?= $item->convenio. " - " .$item->procedimento; ?></td>
                                        <td><?= $item->observacoes; ?></td>
                                        <td class="tabela_acoes desbloq">
                                            <? if ($item->situacaolaudo != '') { ?>

                                                <? if (($item->medico_parecer1 == $operador_id && $item->situacaolaudo == 'FINALIZADO') || ($item->realizada == 't' && $item->situacaolaudo != 'FINALIZADO') || $item->tipo != 'EXAME') { ?>
                                                    <a class="btn  btn-primary btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregaranaminese/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>');" >
                                                        Atender <i class="fa fa-stethoscope" aria-hidden="true"></i></a>
                                                <? } else { ?>
                                                    <button class="btn btn-primary btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarlaudo/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->tipo?>');">
                                                        Laudo &nbsp&nbsp&nbsp <i class="fa fa-stethoscope" aria-hidden="true"></i>
                                                    </button>
                                                <? } ?>
                                                <a class="btn btn-success btn-sm" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/anexarimagem/<?= $item->ambulatorio_laudo_id ?>');">
                                                    Arquivos <i class="fa fa-file-archive-o" aria-hidden="true"></i></a>

                                            <? } else { ?>
                                                <button class="btn btn-primary btn-sm" disabled="">
                                                    Atender <i class="fa fa-stethoscope" aria-hidden="true"></i>
                                                </button>
                                                <button class="btn btn-succes btn-sm" disabled="">
                                                    Arquivos <i class="fa fa-file-archive-o" aria-hidden="true"></i>
                                                </button>

                                            <? } ?>
                                        </td>
                                    </tr>
                                <? } ?>

                                <?php
                            }
                        }
                        ?>
                        <tr>
                            <th class="tabela_footer  btn-info" colspan="9">
                                <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>

                            </th>
                        </tr>
                        <tr>
                            <th class="tabela_footer  btn-info" colspan="9">

                                Total de registros: <?php echo $total; ?>
                            </th>
                        </tr>

                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</div>


</div> <!-- Final da DIV content -->
<!-- <script src="<?= base_url() ?>bootstrap/fullcalendar/main.js"></script>
    <script src="<?= base_url() ?>bootstrap/fullcalendar/main.css"></script>
    <link href="<?= base_url() ?>bootstrap/fullcalendar-scheduler/main.css" rel='stylesheet'/>
    <script src="<?= base_url() ?>bootstrap/fullcalendar-scheduler/main.js"></script>
    <script src="<?= base_url() ?>bootstrap/fullcalendar/locales/pt-br.js"></script>
    <script src="<?= base_url() ?>node_modules/@fullcalendar/daygrid/main.js"></script>
    <link href="<?= base_url() ?>node_modules/@fullcalendar/daygrid/main.css" rel='stylesheet'/> -->
<!-- <script src="<?= base_url() ?>node_modules/moment/moment.js"></script> -->
<!-- <script src='https://cdn.jsdelivr.net/npm/moment@2.27.0/min/moment.min.js'></script> -->

<script type="text/javascript">

    // document.addEventListener('DOMContentLoaded', function() {
    //         var calendarEl = document.getElementById('calendar');
    //         var calendar = new FullCalendar.Calendar(calendarEl, {
    //           initialView: 'dayGridMonth'
    //         });
    //         calendar.render();
    //       });
    $(document).ready(function () {
        //alert('teste_parada');
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

//                                                    setTimeout('delayReload()', 20000);
//                                                    function delayReload()
//                                                    {
//                                                        if (navigator.userAgent.indexOf("MSIE") != -1) {
//                                                            history.go(0);
//                                                        } else {
//                                                            window.location.reload();
//                                                        }
//                                                    }

    });

    setInterval(function () {
        window.location.reload();
    }, 180000);


    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar : {
            left: 'prev,next',
            center: 'title',
            right: 'dayGridMonth'
        },
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        initialView: 'dayGridMonth',
        selectable: true,
        selectionInfo:'allDay',


        dayCellContent: function (selectionInfo, cell) {
            // console.log(date);
            if ($('#data').val() == '') {
                var data_escolhida = moment(new Date()).format('DD/MM/YYYY');

            } else {
                var data_escolhida = $('#data').val();
            }
            // console.log(data_escolhida);
            var today = moment(new Date()).format('DD/MM/YYYY');
            var check =moment(Object.values(selectionInfo)[0]).format('DD/MM/YYYY');
            // console.log(today);
            //            alert(data_escolhida);
            //            var today = $.fullCalendar.formatDate(new Date(), 'yyyy-MM-dd');

            if (data_escolhida == check && data_escolhida != today) {
                backgroundColor: {
                    'red'
                }

                //  info.dayEl.style.backgroundColor = 'red';
            }
            // console.log('PASSOU');
        },
        dateClick: function (selectionInfo) {
            var data = selectionInfo.dateStr;
            // info.dayEl.style.backgroundColor = 'red';
            window.open('<?= base_url() ?>ambulatorio/exame/listarmultifuncaomedicoconsulta?data=&nome=' + moment(data).format('DD%2FMM%2FYYYY') + '&nome=', '_self');
            // console.log(info);


        },
        showNonCurrentDates: false,
        defaultDate: '',
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        editable: false,
        eventLimit: true, // allow "more" link when too many events

        eventSources: [

            {
                url: '<?= base_url() ?>autocomplete/listarhorarioscalendario2',
                type: 'POST',
                data: {
                    medico: $('#medico').val(),
                    // especialidade: $('#especialidade').val()
                },
                error: function () {
                    alert('there was an error while fetching events!');
                }

            }


        ]



    });
    calendar.render();

</script>

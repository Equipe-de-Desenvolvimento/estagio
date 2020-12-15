<link href="<?= base_url() ?>css/ambulatorio/revisor-lista.css?" rel="stylesheet"/>
<div class="content"> <!-- Inicio da DIV content -->
    <?
    $operador_id = $this->session->userdata('operador_id');
    $salas = $this->exame->listartodassalas();
    $medicos = $this->operador_m->listarmedicos();
    ?>
    <div id="accordion">
        <h3 class="singular">Manter Laudo Revisor</h3>
        <div>
            <form method="get" action="<?= base_url() ?>ambulatorio/laudo/pesquisarrevisor">
                <fieldset>
                    <div class="row">
                        <div class="col-lg-2">
                            <div>
                                <label>Salas</label>
                                <select name="sala" id="sala" class="form-control">
                                    <option value=""></option>
                                    <? foreach ($salas as $value) : ?>
                                        <option value="<?= $value->exame_sala_id; ?>"><?php echo $value->nome; ?></option>
                                    <? endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Médico</label>
                                <select name="medicorevisor" id="medicorevisor" class="form-control">
                                    <option value=""></option>
                                    <? foreach ($medicos as $value) : ?>
                                        <option value="<?= $value->operador_id; ?>" ><?php echo $value->nome; ?></option>
                                    <? endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Status</label>
                                <select name="situacaorevisor" id="situacaorevisor" class="form-control" >
                                    <option value='' ></option>
                                    <option value='AGUARDANDO' >AGUARDANDO</option>
                                    <option value='DIGITANDO' >DIGITANDO</option>
                                    <option value='FINALIZADO' >FINALIZADO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Data</label>
                                <input type="date"  id="data" name="data" class="form-control"  value="<?php echo @$_GET['data']; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Prontuário</label>
                                <input type="text"  id="prontuario" name="prontuario" class="form-control"  value="<?php echo @$_GET['prontuario']; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div>
                                <label>Nome</label>
                                <input type="text" name="nome" class="form-control text05" value="<?php echo @$_GET['nome']; ?>" />
                            </div>
                        </div>
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
                            <th class="tabela_header" width="200px;">Nome</th>
                            <th class="tabela_header" width="30px;">Idade</th>
                            <th class="tabela_header" width="30px;">Dias</th>
                            <th class="tabela_header" width="200px;">M&eacute;dico Revisor</th>
                            <th class="tabela_header">Status Revisor</th>
                            <th class="tabela_header" width="350px;">Procedimento</th>
                            <th class="tabela_header" colspan="4" width="140px;"><center>A&ccedil;&otilde;es</center></th>
                    </tr>
                    </thead>
                    <?php
                    $url = $this->utilitario->build_query_params(current_url(), $_GET);
                    $consulta = $this->laudo->listarrevisor($_GET);
                    $total = $consulta->count_all_results();
                    $limit = 10;
                    isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                    if ($total > 0) {
                        ?>
                        <tbody>
                            <?php
                            $lista = $this->laudo->listar2revisor($_GET)->limit($limit, $pagina)->get()->result();
                            $estilo_linha = "tabela_content01";
                            foreach ($lista as $item) {
                                $dataFuturo = date("Y-m-d H:i:s");
                                $dataAtual = $item->data_cadastro;

                                $date_time = new DateTime($dataAtual);
                                $diff = $date_time->diff(new DateTime($dataFuturo));
                                $teste = $diff->format('%d');

                                ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                                ?>
                                <tr>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->paciente; ?></td>
                                    <td class="<?php echo $estilo_linha; ?>" width="30px;"><?= $item->idade; ?></td>
                                    <td class="<?php echo $estilo_linha; ?>" width="30px;"><?= $teste; ?></td>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->medicorevisor; ?></td>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->situacao_revisor; ?></td>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->procedimento; ?></td>

                                    <? if (($item->medico_parecer2 == $operador_id) || $operador_id == 1) { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                                <a class="btn btn-outline-primary btn-sm" href="<?= base_url() ?>ambulatorio/laudo/carregarrevisao/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>">
                                                    Revis&atilde;o</a></div>
                                        </td>
                                    <? } else { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="40px;"><font size="-2">
                                            <a class="btn btn-outline-secondary btn-sm">Bloqueado</a></font>
                                        </td>
                                    <? }
                                    ?>



                                    <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                            <a class="btn btn-outline-success btn-sm" href="<?= base_url() ?>ambulatorio/laudo/impressaolaudo/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>">
                                                Imprimir</a></div>
                                    </td>
                                    <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                            <a class="btn btn-outline-info btn-sm" href="<?= base_url() ?>ambulatorio/laudo/impressaoimagem/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>">
                                                imagem</a></div>
                                    </td>
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
<script type="text/javascript">

    $(function() {
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


    $(function() {
        $("#accordion").accordion();
    });

</script>

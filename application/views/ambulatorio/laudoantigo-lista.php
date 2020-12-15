<link href="<?= base_url() ?>css/ambulatorio/laudoantigo-lista.css?" rel="stylesheet"/>
<div class="content"> <!-- Inicio da DIV content -->
    <?
    $salas = $this->exame->listartodassalas();
    $medicos = $this->operador_m->listarmedicos();
    ?>
    <div id="accordion">
        <h3 class="singular"><a href="#">Laudos antigos</a></h3>
        <div>
            <form method="get" action="<?= base_url() ?>ambulatorio/laudo/pesquisarlaudoantigo">
                <fieldset>
                    <div class="row">
                        <div class="col-lg-3">
                            <div>
                                <label>Médico</label>
                                <select name="medico" id="medico" class="form-control">
                                    <option value=""></option>
                                    <? foreach ($medicos as $value) : ?>
                                        <option value="<?= $value->operador_id; ?>"><?php echo $value->nome; ?></option>
                                    <? endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Data</label>
                                <input type="date"  id="data" name="data" class="form-control"  value="<?php echo @$_GET['data']; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div>
                                <label>Nome</label>
                                <input type="text" name="nome" class="form-control texto07" value="<?php echo @$_GET['nome']; ?>"/>
                            </div>
                        </div>
                        <div class="col-lg-2 btnsend">
                            <div>
                                <button class="btn btn-outline-success" type="submit" id="enviar">Pesquisar</button>
                            </div>
                        </div>

                    </div>
                </fieldset>
            </form>
<br>
            <table>
                <thead>
                    <tr>
                        <th class="tabela_header" width="300px;">Nome</th>
                        <th class="tabela_header" width="30px;">Data</th>
                        <th class="tabela_header" width="130px;">M&eacute;dico</th>
                        <th class="tabela_header" width="300px;">Procedimento</th>
<!--                            <th class="tabela_header">M&eacute;dico Revisor</th>
                        <th class="tabela_header">Status Revisor</th>-->
                        <th class="tabela_header" colspan="4" width="140px;"><center>A&ccedil;&otilde;es</center></th>
                </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->laudo->listarlaudoantigo($_GET);
                $total = $consulta->count_all_results();
                $limit = 10;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $lista = $this->laudo->listarlaudoantigo2($_GET)->limit($limit, $pagina)->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {


                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->nomedopaciente; ?></td>
                                <td class="<?php echo $estilo_linha; ?>" width="30px;"><?= substr($item->emissao, 8, 2) . "/" . substr($item->emissao, 5, 2) . "/" . substr($item->emissao, 0, 4); ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->nomemedicolaudo; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->nomeexame; ?></td>
                               <!-- <td class="<?php echo $estilo_linha; ?>" width="40px;"><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarlaudoantigo/<?= $item->id ?>');" >
                                            Laudo</a></div>
                                </td> -->

                                <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaolaudoantigo/<?= $item->id ?>');">
                                            Imprimir</a></div>
                                </td>
<!--                                <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoimagem/<?= $item->id ?>');">
                                            imagem</a></div>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                            <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/impressaoetiiqueta/<?= $item->id ?>');">
                                            Etiqueta</a></div>
                                </td>-->
                            </tr>

                        </tbody>
                        <?php
                    }
                }
                ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="12">
                            <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total; ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function() {
        $( "#data" ).datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
    });


    $(function() {
        $( "#accordion" ).accordion();
    });

</script>

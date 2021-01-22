
<div class="content"> <!-- Inicio da DIV content -->
    <table>
        <tr>
            <td>
                
            </td>
            
<!--            <td>
                <div class="bt_link_new">
                    <a href="<?php echo base_url() ?>ambulatorio/procedimentoplano/carregarprocedimentoplanoexcluirgrupo">
                        Excluir Proc. Grupo
                    </a>
                </div>
            </td>-->
        </tr>
    </table>
    <div id="">
        <div class="alert alert-primary"><a>Log de Alterações</a></div>
            <div class="table-responsive">
                <div class="panel-body">
                    <thead>
                        <tr>
                            <th colspan="7" class="tabela_title">
                                <form method="get" action="<?= base_url() ?>cadastros/convenio/pesquisarlogs">
                                    <input type="text" name="nome" class="texto10 bestupper" value="<?php echo @$_GET['nome']; ?>" />
                                    <button class="btn btn-outline-default btn-round btn-sm" type="submit" id="enviar">Pesquisar</button>
                                </form>
                            </th>
                            
                        </tr>
                        <table  class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <tr>
                                <th class="tabela_header">Convênio</th>
                                <th class="tabela_header">Operador Alteração</th>
                                <th class="tabela_header">Data Alteração</th>
                                <th class="tabela_header">Campo</th>
                                <th class="tabela_header" style="max-width: 200px;">Informação Antiga</th>
                                <th class="tabela_header" style="max-width: 200px">Informação Nova</th>
                            </tr>
                    </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->convenio->listarconveniolog($_GET);
                $total = $consulta->count_all_results();
                $limit = 10;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    ?>
                    <body>
                        <?php
                        $lista = $this->convenio->listarconveniolog($_GET)->limit($limit, $pagina)->orderby('data_cadastro desc')->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"width="200px"><?= $item->nome; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"width="200px"><?= $item->operador; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"width="200px"><?= date("d/m/Y H:i:s", strtotime($item->data_cadastro)); ?></td>
                                <td class="<?php echo $estilo_linha; ?>"width="200px"><?= $item->alteracao; ?></td>
                                <td style="max-width: 200px;" class="<?php echo $estilo_linha; ?>"><?= $item->informacao_antiga; ?></td>
                                <td style="max-width: 200px;" class="<?php echo $estilo_linha; ?>"><?= $item->informacao_nova; ?></td>
                                
                            </tr>

                        </body>
                        <?php
                    }
                }
                ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="12">
                            <div class="pagination">
                                <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                                Total de registros: <?php echo $total; ?>
                            </div>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <a href="<?= base_url() ?>cadastros/convenio">
        <button type="button" id="btnVoltar" class="btn btn-secondary btn-sm">Voltar</button>
    </a>

</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });

</script>

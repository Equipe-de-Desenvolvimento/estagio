<div class="panel-body"> <!-- Inicio da DIV content -->
    <table>
        <tr>
            <td>
                <div class="bt_link_new">
                    <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/convenio/carregar/0">
                        Novo Convênio
                    </a>
                </div>
            </td>
            <td>&nbsp;&nbsp;</td>
            <td>
                <div class="bt_link_new">
                    <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/convenio/pesquisarlogs">
                        Convênio Logs
                    </a>
                </div>
            </td>

        </tr>
    </table>
    <br>
    <?
    $empresa = $this->convenio->listarempresa();
    
    ?>

    <div class="alert alert-primary"><b>Manter Convênio</b></div>

    <form method="get" action="<?= base_url() ?>cadastros/convenio/pesquisar">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="">Nome</label>
                                        <input type="text" name="nome" class="form-control" value="<?php echo @$_GET['nome']; ?>" />
                                    </div>

                                    <div class="col-lg-4">
                                        <br>
                                        <button class="btn btn-outline-default btn-round btn-sm" type="submit" id="enviar">Pesquisar</button>
                                    </div>
                                </div>
                    </form>
                    <br>

            <table  class="table table-striped table-bordered table-hover" id="dataTables-example">
                <tr>
                    <th class="tabela_header">Nome</th>
                    <th colspan="4" class="tabela_header"><center>Detalhes</center></th>
                </tr>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->convenio->listar($_GET);
                $total = $consulta->count_all_results();
                $limit = 10;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    
                    ?>
                    <tbody>
                        <?php
                        
                        $lista = $this->convenio->listar($_GET)->limit($limit, $pagina)->orderby('nome')->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->nome; ?></td>

                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                        <a class="btn btn-outline-default btn-round btn-sm" href="<?= base_url() ?>cadastros/convenio/carregar/<?= $item->convenio_id ?>">
                                            Editar
                                        </a>
                                    </div>
                                </td>

                                <td class="<?php echo $estilo_linha; ?>" width="120px;"><div class="bt_link" style="width: 70px;">
                                        <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/convenio/empresaconvenio/<?= $item->convenio_id ?>">
                                            Instituição
                                        </a>
                                    </div>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                        <a class="btn btn-outline-default btn-round btn-sm" onclick="javascript: return confirm('Deseja realmente excluir o convenio?\n\nObs: Irá excluir também os procedimentos associados ao convenio  ');" href="<?= base_url() ?>cadastros/convenio/excluir/<?= $item->convenio_id ?>">
                                            
                                            Excluir
                                        </a>
                                    </div>
                                </td>


                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link" style="">
                                <a class="btn btn-outline-default btn-round btn-sm"  onclick="javascript:window.open('<?= base_url() ?>cadastros/convenio/log/<?= @$item->convenio_id ?>', '', 'height=230, width=600, left='+(window.innerWidth-600)/2+', top='+(window.innerHeight-230)/2);" >LOG</a>
                                </td>
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

</div> <!-- Final da DIV content -->
<script type="text/javascript">


</script>

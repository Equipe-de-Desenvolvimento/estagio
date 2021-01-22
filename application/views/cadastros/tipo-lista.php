
<div class="panel-body"> <!-- Inicio da DIV content -->
        <a class="btn btn-outline-default btn-round btn-sm"  href="<?php echo base_url() ?>cadastros/tipo/carregartipo/0">
            Novo Tipo Entrada/saida
        </a>
        <br> <br>
    
        <div class="alert alert-primary"><b>Manter Tipo Entrada/saida</b></div>

        <form method="get" action="<?= base_url() ?>cadastros/tipo/pesquisar">
                <div class="row">
                    <div class="col-lg-4">
                        <label for="">Nome</label>
                        <input type="text" name="nome" class="form-control" value="<?php echo @$_GET['nome']; ?>" />
                    </div>
                    <div class="col-lg-4"><br>
                        <button class="btn btn-outline-default btn-round btn-sm" type="submit" id="enviar">Pesquisar</button>
                    </div>
                </div>
        </form>

        <br>


            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="tabela_header">Nome</th>
                        <th class="tabela_header" width="70px;" colspan="2"><center>Detalhes</center></th>
                </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->tipo->listar($_GET);
                $total = $consulta->count_all_results();
                $limit = 10;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $lista = $this->tipo->listar($_GET)->limit($limit, $pagina)->orderby("descricao")->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->descricao; ?></td>
                                <?
                                $perfil_id = $this->session->userdata('perfil_id');
                                ?>
                                <? if ($perfil_id != 10) { ?>
                                    <td>                                  
                                        <a class="btn btn-outline-default btn-round btn-sm" href="<?= base_url() ?>cadastros/tipo/carregartipo/<?= $item->tipo_entradas_saida_id ?>">Editar</a>
                                    </td>
                                    <td>                                  
                                        <a class="btn btn-outline-default btn-round btn-sm" onclick="javascript: return confirm('Deseja realmente exlcuir esse Tipo?');" href="<?= base_url() ?>cadastros/tipo/excluir/<?= $item->tipo_entradas_saida_id ?>">Excluir</a>
                                    </td>
                                <? } else { ?>
                                    <td>                                  
                                        <a class="btn btn-outline-default btn-round btn-sm">Editar</a>
                                    </td>
                                    <td >                                  
                                        <a class="btn btn-outline-default btn-round btn-sm">Excluir</a>
                                    </td>

                                <? } ?>

                            </tr>

                        </tbody>
                        <?php
                    }
                }
                ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="4">
                            <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total; ?>
                        </th>
                    </tr>
                </tfoot>
            </table>

</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });

</script>

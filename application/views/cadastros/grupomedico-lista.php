
<div class="content"> <!-- Inicio da DIV content -->
    <div class="bt_link_new">
        <a href="<?php echo base_url() ?>cadastros/grupomedico/carregargrupomedico/0">
            Novo Grupo Profissional
        </a>
    </div>
    <div id="accordion">
        <h3 class="singular"><a href="#">Grupo Profissionais</a></h3>
        <div>
            <table>
                <thead>
                    <tr>
                        <th colspan="5" class="tabela_title">
                            <form method="get" action="<?= base_url() ?>cadastros/grupomedico/pesquisar">
                                <input type="text" name="nome" class="texto10 bestupper" value="<?php echo @$_GET['nome']; ?>" />
                                <button type="submit" id="enviar">Pesquisar</button>
                            </form>
                        </th>
                    </tr>
                    <tr>
                        <th class="tabela_header">Nome</th>
                        <th class="tabela_header" colspan="2"><center>Detalhes</center></th>
                    </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->grupomedico->listar($_GET);
                $total = $consulta->count_all_results();
                $limit = 10;
                $perfil_id = $this->session->userdata('perfil_id');
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $lista = $this->grupomedico->listar($_GET)->limit($limit, $pagina)->orderby("nome")->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->nome; ?></td>

                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                    <a href="<?= base_url() ?>cadastros/grupomedico/carregargrupomedicoadicionar/<?= $item->operador_grupo_id ?>">Editar</a></div>
                                </td>
                                <?php if($perfil_id != 18 && $perfil_id != 20){ ?>
                                    <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                        <a href="<?= base_url() ?>cadastros/grupomedico/excluir/<?= $item->operador_grupo_id ?>">Excluir</a></div>
                                    </td>
                                <?php }?>
                            </tr>

                        </tbody>
                        <?php
                    }
                }
                ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="6">
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

    $(function () {
        $("#accordion").accordion();
    });

</script>

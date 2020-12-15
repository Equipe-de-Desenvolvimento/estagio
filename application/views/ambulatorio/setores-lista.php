
<div class="content"> <!-- Inicio da DIV content -->
    <div class="bt_link_new">
        <a href="<?php echo base_url() ?>ambulatorio/sala/carregarsetores/0">
            Novo Setores
        </a>
    </div>
    <div id="accordion">
        <h3 class="singular"><a href="#">Manter Setores</a></h3>
        <div>
            <table>
                <thead>
                    <tr>
                        <th colspan="8" class="tabela_title">
                            <form method="get" action="<?= base_url() ?>ambulatorio/sala/pesquisarsetores">
                                <input type="text" name="nome" class="texto10 bestupper" value="<?php echo @$_GET['nome']; ?>" />
                                <button type="submit" id="enviar">Pesquisar</button>
                            </form>
                        </th>
                    </tr>
                    <tr>
                        <th class="tabela_header">Nome</th>
                        <th class="tabela_header" colspan="8">Detalhes</th>
                    </tr>
                </thead>
                <?php
                 $perfil_id = $this->session->userdata('perfil_id');
                    $url      = $this->utilitario->build_query_params(current_url(), $_GET);
                    $consulta = $this->sala->listarsetores($_GET);
                    $total    = $consulta->count_all_results();
                    $limit    = 10;
                    isset ($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                    if ($total > 0) {
                ?>
                <tbody>
                    <?php
                        $lista = $this->sala->listarsetores($_GET)->limit($limit, $pagina)->orderby("nome")->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                     ?>
                        <tr>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->nome; ?></td>

                            <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                <a href="<?= base_url() ?>ambulatorio/sala/carregarsetores/<?= $item->setor_id ?>">Editar</a></div>
                            </td>

                            <?php if($perfil_id != 18 && $perfil_id != 20){ ?>
                            <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                <a href="<?= base_url() ?>ambulatorio/sala/excluirsetores/<?= $item->setor_id ?>">Excluir</a></div>
                            </td>
                            <?}?>
                        </tr>
                        </tbody>
                        <?php
                                }
                            }
                        ?>
                        <tfoot>
                            <tr>
                                <th class="tabela_footer" colspan="7">
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
        $( "#accordion" ).accordion();
    });

</script>

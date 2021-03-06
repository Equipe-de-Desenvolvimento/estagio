<div class="content"> <!-- Inicio da DIV content -->
    <? $perfil_id = $this->session->userdata('perfil_id'); ?>
    <div class="bt_link_new">
        <a href="<?= base_url() ?>ambulatorio/exame/novoacompanhamentodocumento/0" >
            Novo Acompanhamento
        </a>
    </div>
    <div id="accordion">
        <h3 class="singular"><a href="#">Manter Acompanhamento</a></h3>
        <div>
            <table>
                <thead>
                    <tr>
                        <th colspan="5" class="tabela_title">
                            Lista de Acompanhamentos
                            <form method="get" action="<?= base_url() ?>ambulatorio/exame/acompanhamentodocumento">
                                <input type="text" name="nome" class="size10" value="<?php echo @$_GET['nome']; ?>" />
                                <button type="submit" id="enviar">Pesquisar</button>
                            </form>
                        </th>
                    </tr>
                    <tr>
                        <th class="tabela_header">Nome</th>
                        <th class="tabela_header" colspan="2">&nbsp;</th>
                    </tr>
                </thead>
                <?php
                $perfil_id = $this->session->userdata('perfil_id'); 
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->exame->listaracompanhamentoquest($_GET);
                $total = $consulta->count_all_results();
                $limit = 50;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $lista = $this->exame->listaracompanhamentoquest($_GET)->limit($limit, $pagina)->orderby("nome")->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->nome; ?></td>
                                <td class="<?php echo $estilo_linha; ?>" width="140px;">
                                    <a href="<?= base_url() ?>upload/acompquest/<?= $item->acompanhamento_quest_id ?>/<?= $item->caminho_documento ?>">Arquivo</a>
                                </td>
  
                           <?php if($perfil_id != 18 && $perfil_id != 20){?>
                                <td class="<?php echo $estilo_linha; ?>" width="140px;">
                                    <a  style="cursor: pointer;" onclick="javascript: return confirm('Deseja realmente excluir o item? ');"
                                        href="<?= base_url() ?>ambulatorio/exame/excluiracompquest/<?= $item->acompanhamento_quest_id ?>">Excluir</a>
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
                        <th class="tabela_footer" colspan="9">
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

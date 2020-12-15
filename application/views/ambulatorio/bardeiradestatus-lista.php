<? $permissoes = $this->guia->listarempresapermissoes(); ?>
<style>
.statusbardeira{
    /* background-color:#000; */
    /* color:#fff; */
    display:inline-block;
    padding: 5px 10px;
    padding-left:15px;
    padding-right:15px;
    text-align:center;
    border-radius:2px;
    }

/* .w3-badge{border-radius:2px} */

</style>
<div class="content"> <!-- Inicio da DIV content -->

    <div id="accordion">
        <h3 class="singular"><a href="#">Manter Bardeiras de Status</a></h3>
        <div>

            <form id="form_menuitens" action="<?= base_url() ?>ambulatorio/procedimentoplano/excluirmedicopercentual" method="post" target="_blank">
                
                <table>
                    <thead>
                        <tr>

                            <th class="tabela_header">Status </th>
                            <td class="tabela_header">Cor</td>
                            <th class="tabela_header" >Detalhes</th>
                        </tr>
                    </thead>
                    <?php
                    $url = $this->utilitario->build_query_params(current_url(), $_GET);
                    $consulta = $this->exame->listarbardeirasstatus($_GET);
                    $total = $consulta->count_all_results();
                    $limit = 10;
                    isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                    if ($total > 0) {
                        ?>
                        <tbody>
                            <?php
                            $lista = $this->exame->listarbardeirasstatus($_GET)->orderby('bardeira_id')->limit($limit, $pagina)->get()->result();
                            $estilo_linha = "tabela_content01";
                            foreach ($lista as $item) {
                                ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                                ?>
                                <tr>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->nome; ?></td>
                                    
                                    <td class="<?php echo $estilo_linha; ?>"><span class="statusbardeira" style="background-color:<?=$item->cor?>; color:<?=$item->cor?>;">------</span></td>
                                        <td class="<?php echo $estilo_linha; ?>">
                                            <a target="_blank" href="<?= base_url() ?>ambulatorio/exame/conveniobardeirasstatus/<?= $item->bardeira_id; ?>">Editar</a>  
                                        </td>                                        
                                </tr>

                            </tbody>
                            <?php
                        }
                    }
                    ?>
                    <tfoot>
                        <tr>
                            <th class="tabela_footer" colspan="3">
                                <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                                Total de registros: <?php echo $total; ?>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </div>

</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });

    $(function () {
        $('#selecionaTodos').change(function () {
            if ($(this).is(":checked")) {
                $("input[id='percentual']").attr("checked", "checked");

            } else {
                $("input[id='percentual']").attr("checked", false);
            }
        });
    });
</script>


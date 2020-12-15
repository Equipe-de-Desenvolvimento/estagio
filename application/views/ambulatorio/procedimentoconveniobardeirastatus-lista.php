
<div class="content"> <!-- Inicio da DIV content -->
    <div class="bt_link_voltar">
        <a href="<?= base_url() ?>ambulatorio/exame/conveniobardeirasstatus/<?=$bardeira_id?>">
            Voltar
        </a>
    </div>

    <div id="accordion">
        <h3 class="singular"><a href="#">Manter Bardeiras de Status</a></h3>
        <div>
            <table>
                <thead>
                    <tr>
                        <th colspan="5" class="tabela_title">
                    </tr>
                <form method="get" action="<?= base_url() ?>ambulatorio/exame/procedimentoconveniobardeirastatus/<?=$bardeira_id?>/<?=$convenio_id?>">
                    <tr>
                        <th class="tabela_title" >Grupo</th>                  
                        <th class="tabela_title">Procedimento</th>                   
                    </tr>
                    <tr>
                        <th class="tabela_title">
                            <select name="grupo" id="grupo" class="size2">
                                <option value="">Selecione</option>
                                <? foreach ($grupo as $value) : ?>
                                    <option value="<?= $value->nome; ?>"
                                        <? if (@$_GET['grupo'] == $value->nome) echo 'selected'?>>
                                    <?= $value->nome; ?>
                                    </option>
                                <? endforeach; ?>

                            </select>
                            <!--<input type="text" name="" class="texto04" value="<?php echo @$_GET['grupo']; ?>" />-->

                        </th>
                        <th class="tabela_title">
                            <input type="text" name="procedimento" id="procedimento" class="texto05" value="<?php echo @$_GET['procedimento']; ?>" />
                        </th>
                        <th class="tabela_title">
                            <button type="submit" id="enviar">Pesquisar</button>
                        </th>
                    </tr>
                </form>
                </th>
                </tr>
                </thead>
            </table>
            <form id="form_menuitens" action="<?= base_url() ?>ambulatorio/exame/excluirbardeirastatus" method="post" target="_blank">
                <div id="marcarTodos" style="float: right">
                    <input type="checkbox" name="selecionaTodos" id="selecionaTodos">
                    Todos
                </div>
                <table>
                    <thead>
                        <tr>
                            <th class="tabela_header">Procedimento</th>
                            <th class="tabela_header">Grupo</th>
                            <td class="tabela_header" width="120px;"></td>
                            <th class="tabela_header">Convenio</th>
                            <td class="tabela_header" width="120px;"></td>
                            <th class="tabela_header">Bardeira</th>
                            <td class="tabela_header" width="120px;"></td>
                            <th class="tabela_header" colspan="" style="text-align: center">Excluir?</th>
                        </tr>
                    </thead>
                    <?php
                    $url = $this->utilitario->build_query_params(current_url(), $_GET);
                    $consulta = $this->exame->listarprocedimentoconveniobardeirastatus($bardeira_id, $convenio_id);
                    $total = $consulta->count_all_results();

                    $limit = 10;
                    $procedimentos;
                    isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                    if ($total > 0) {
                        ?>
                        <tbody>
                            <?php
                            $lista = $this->exame->listarprocedimentoconveniobardeirastatus($bardeira_id, $convenio_id)->orderby('c.nome')->limit($limit, $pagina)->get()->result();
                            $estilo_linha = "tabela_content01";
                            foreach ($lista as $item) {
                                ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                                ?>
                                <tr>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->procedimento; ?></td>                               
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->grupo; ?></td> 
                                    <td class="<?php echo $estilo_linha; ?>" width="100px;"></td>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->convenio; ?></td>
                                    <td class="<?php echo $estilo_linha; ?>" width="100px;"></td>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->bardeira; ?></td>
                                    <td class="<?php echo $estilo_linha; ?>" width="100px;"></td>
                                    <td class="<?php echo $estilo_linha; ?>" style="text-align: center">

                                        <input type="checkbox" id="percentual" name="percentual[<?= $item->procedimento_bardeira_status_convenio_id; ?>]"/>
                                        <input type="checkbox" style="display:none;" id="percentual2" name="percentual2[<?= $item->procedimento_bardeira_status_id; ?>]"/>
                                    </td>
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
                            <th class="tabela_footer" colspan="2">
                                <button type="submit" style="text-align: center; font-weight: bold">Excluir</button>
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
    
    $(function() {
        $("#procedimento").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=listarprocedimentoautocomplete",
            minLength: 3,
            focus: function( event, ui ) {
                $( "#procedimento" ).val( ui.item.label );
                return false;
            },
            select: function( event, ui ) {
                $( "#procedimento" ).val( ui.item.value );
                return false;
            }
        });
    });
    
     $(function () {
        $('#selecionaTodos').change(function () {
            if ($(this).is(":checked")) {
                $("input[id='percentual']").attr("checked", "checked");
                $("input[id='percentual2']").attr("checked", "checked");

            } else {
                $("input[id='percentual']").attr("checked", false);
                $("input[id='percentual2']").attr("checked", false);
            }
        });
    });

</script>

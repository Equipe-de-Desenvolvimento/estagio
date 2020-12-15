
<div class="content"> <!-- Inicio da DIV content -->
    <div class="bt_link_new">
        <a href="<?php echo base_url() ?>ambulatorio/exame/carregargastooperador/0/ABERTO">
            Novo Gasto
        </a>
    </div>
    <div id="accordion">
        <h3 class="singular"><a href="#">Manter Gastos Operadores</a></h3>
        <div>
        <form method="get" action="<?= base_url() ?>ambulatorio/exame/mantergastos">
        
            <?
            $perfil_id = $this->session->userdata('perfil_id');
            $operadores = $this->exame->listaroperadores();
            ?>
                            <table>
                                <tr>
                                <?if($perfil_id == 1){?>
                                    <th class="tabela_title">Operadores</th>
                                <?}?>
                                    <th class="tabela_title">Status</th>
                                    <th class="tabela_title">Data</th>
                                </tr>

                                <tr>
                                <?if($perfil_id == 1){?>
                                    <td>
                                    <select name="operador">
                                    <option value="">Selecione</option>
                                    <? foreach ($operadores as $operador){?>
                                        <option value="<?=$operador->operador_id?>"
                                        <?if(@$_GET['operador'] == $operador->operador_id) echo 'selected'?>> <?=$operador->nome?> </option>
                                    <? } ?>
                                    </select>
                                    </td>
                                <?}?>

                                    <td>
                                    <select name="status">
                                    <option value="">Selecione</option>
                                    <option value="ABERTO" <?if(@$_GET['status'] == 'ABERTO') echo 'selected'?>>Abertos</option>
                                    <option value="FINALIZADO" <?if(@$_GET['status'] == 'FINALIZADO') echo 'selected'?>>Finalizados</option>
                                    </select>
                                    </td>

                                    <td><input type="text" name="data_gasto" id="data_gasto" class="texto" value="<?php echo @$_GET['data_gasto']; ?>" /></td>

                                    <td><button type="submit" id="enviar">Pesquisar</button></td>
                                </tr>
                            
                            </table>
                            </form>
            <table>
                <thead>
                    <tr>
                        <th class="tabela_header">Nome</th>
                        <th class="tabela_header">Preço</th>
                        <th class="tabela_header">Data</th>
                        <th class="tabela_header">Horário</th>
                        <th class="tabela_header">observacao</th>
                        <th class="tabela_header">Operador</th>
                        <th class="tabela_header" colspan="4">Detalhes</th>
                    </tr>
                </thead>
                <?php
                    $url      = $this->utilitario->build_query_params(current_url(), $_GET);
                    $consulta = $this->exame->listarmantergastos($_GET);
                    $total    = $consulta->count_all_results();
                    $limit    = 10;
                    isset ($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                    if ($total > 0) {
                ?>
                <tbody>
                    <?php
                        $lista = $this->exame->listarmantergastos($_GET)->limit($limit, $pagina)->orderby("data_gasto","desc")->orderby("nome")->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            $data_gasto = date("d/m/Y", strtotime(str_replace('-', '/', $item->data_gasto)));
                     ?>
                        <tr>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->nome; ?></td>

                            <td class="<?php echo $estilo_linha; ?>"><?= $item->preco; ?></td>

                            <td class="<?php echo $estilo_linha; ?>"><?= $data_gasto; ?></td>

                            <td class="<?php echo $estilo_linha; ?>"><?= $item->horario_inicial.' - '.$item->horario_final; ?></td>

                            <td class="<?php echo $estilo_linha; ?>"><?= $item->observacao; ?></td>

                            <td class="<?php echo $estilo_linha; ?>"><?= $item->operador; ?></td>


                            <td class="<?php echo $estilo_linha; ?>" width="60px;">
                                <? if($item->status != 'FINALIZADO'){?>
                                    <div class="bt_link">
                                <a href="<?= base_url() ?>ambulatorio/exame/carregargastooperador/<?= $item->manter_gasto_id ?>/FINALIZAR">Finalizar</a></div>
                                <?}else{?>
                                    FINALIZADO
                                <?}?>
                            </td> 

                                
                            <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                            <? if($item->status != 'FINALIZADO'){?>
                                <div class="bt_link">
                                <a href="<?= base_url() ?>ambulatorio/exame/carregargastooperador/<?= $item->manter_gasto_id ?>/ABERTO">Editar</a>
                            <?}?>
                            </div>
                            </td> 

                            <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                <div class="bt_link">
                                <a href="<?= base_url() ?>ambulatorio/exame/anexararquivogastooperador/<?= $item->manter_gasto_id ?>">Anexo</a>
                            </div>
                            </td>

                            <? 
                            $operador_id = $this->session->userdata('operador_id');
                            if($operador_id == 1){?>
                            <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                <div class="bt_link">
                                <a href="<?= base_url() ?>ambulatorio/exame/excluirgastooperador/<?= $item->manter_gasto_id ?>">Excluir</a>
                            </div>
                            </td>  
                            <? } ?>

                            <?}?>
                        </tr>
                        </tbody>
                        <?php
                                }
                            
                        ?>
                        <tfoot>
                            <tr>
                                <th class="tabela_footer" colspan="10">
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

    $(function () {
        $("#data_gasto").datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
    });

</script>

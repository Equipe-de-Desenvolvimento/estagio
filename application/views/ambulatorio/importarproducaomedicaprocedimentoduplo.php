<div class="content  "> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3><a href="#">Procedimentos Duplicados Importação Produção</a></h3>
        <div >
        <form method="post" action="<?= base_url() ?>ambulatorio/exame/gravarprocedimentoproducaoduplo">
            <table >
                    <thead>
                        <tr>
                            <th class="tabela_header">Médico</th>
                            <th class="tabela_header">Convênio</th>
                            <th class="tabela_header">Código</th>
                            <th class="tabela_header">Procedimento</th>
                        </tr>
                    </thead>
                <?if(count($producao) > 0){?>
                    <?$estilo_linha = "tabela_content01";?>
                    <? foreach ($producao as $key => $value) {
                    ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";   
                    $procedimentos = $this->procedimento->listarprocedimentoconveniocodigo($value->convenio_id, $value->codigo);
                    ?>
                    
                    <tr>
                        <td class="<?php echo $estilo_linha; ?>">
                            <input type="hidden" name="producao_id[<?=$key?>]" value="<?=$value->procedimento_importacao_producao_id?>">
                            <input type="hidden" name="convenio_id[<?=$key?>]" value="<?=$value->convenio_id?>">
                            <input type="hidden" name="medico_id[<?=$key?>]" value="<?=$value->medico_id?>">
                            <?php echo $value->medico; ?>
                        </td>
                        <td class="<?php echo $estilo_linha; ?>"><?php echo $value->convenio; ?></td>
                        <td class="<?php echo $estilo_linha; ?>"><?php echo $value->codigo; ?></td>
                        <td class="<?php echo $estilo_linha; ?>">
                            <select name="procedimento[<?=$key?>]" id="procedimento<?=$key?>" class="size2">
                                <option value="">Selecione</option>
                                <?foreach ($procedimentos as $key => $item) {?>
                                    <option value="<?=$item->procedimento_convenio_id?>"><?=$item->procedimento?></option>
                                <?}?>
                            </select>
                        </td>
                    </tr>

                    <?}?>
                <?}?>
            </table>
            <br>
            <br>
            <button type="submit">Enviar</button>
        </form>
        
        </div>

    </div> <!-- Final da DIV content -->
    
</div> <!-- Final da DIV content -->
<script type="text/javascript">
    $(function () {
        $("#accordion").accordion();
    });
</script>

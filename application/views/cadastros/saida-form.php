<div class="panel-body"> <!-- Inicio da DIV content -->
    
    <?
    $permissoes = $this->caixa->listarpermissoesempresa();
    ?>
   
        <div class="alert alert-primary"><b>Saida</b></div>
        <div>
            <form name="form_saida" id="form_saida" action="<?= base_url() ?>cadastros/caixa/gravarsaida" enctype="multipart/form-data" method="post">
                <div class="row">
                    <div class="col-lg-2">
                        <label>Valor *</label>
                        <input type="text" name="valor"  id="valor" alt="decimal" class="form-control" value="<?= @$obj->_valor; ?>"/>
                        <input type="hidden" id="saida_id" class="texto_id" name="saida_id" value="<?= @$obj->_saida_id; ?>" />
                        <input type="hidden" id="parametros" name="parametros" value="<?= @$parametros; ?>" />
                    </div>
                    <div class="col-lg-2">
                        <label>Data*</label>
                        <input type="text" name="inicio" id="inicio" class="form-control" alt="date" value="<?= substr(@$obj->_data, 8, 2) . '/' . substr(@$obj->_data, 5, 2) . '/' . substr(@$obj->_data, 0, 4);  ?>" required=""/>
                    </div>
                    <div class="col-lg-3">
                        <label>Pagar a:</label>
                        <input type="hidden" id="devedor" class="texto_id" name="devedor" value="<?= @$obj->_devedor; ?>" />
                        <input type="text" id="devedorlabel" class="form-control" name="devedorlabel" value="<?= @$obj->_razao_social; ?>" required=""/>
                        <a class="btn btn-outline-default btn-sm" target="_blank" href="<?= base_url() ?>cadastros/fornecedor">
                            Manter Credor/Devedor
                        </a>
                    </div>
                    <div class="col-lg-3">
                        <label>Empresa*</label>
                        <select name="empresa_id" id="empresa_id" class="form-control">
                            <option value="">Selecione</option>
                            <? foreach ($empresas as $value) : ?>
                                <option value="<?= $value->empresa_id; ?>" <?if($empresa_id == $value->empresa_id) echo 'selected'?>>
                                    <?php echo $value->nome; ?>
                                </option>
                            <? endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label>Tipo</label>
                        <select name="tipo" id="tipo" class="form-control">
                            <option value="">Selecione</option>
                            <? foreach ($tipo as $value) { ?>
                                <option value="<?= $value->tipo_entradas_saida_id; ?>"
                                <? if ($value->descricao == @$obj->_tipo):echo'selected';
                                endif;
                                ?>><?php echo $value->descricao; ?></option>
                            <? } ?>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label>Classe *</label>
                        <select name="classe" id="classe" class="form-control"  <?=($permissoes[0]->financ_4n == 'f')? 'required' : ''?>>
                            <option value="">Selecione</option>
                            <? foreach ($classe as $value) { ?>
                                <option value="<?= $value->descricao; ?>"
                                        <? if ($value->descricao == @$obj->_classe):echo'selected';
                                        endif;
                                        ?>><?php echo $value->descricao; ?></option>
                            <? } ?>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label>Forma *</label>
                        <select name="conta" id="conta" class="form-control" required="">
                            <option value="">Selecione</option>
                            <? foreach ($conta as $value) { ?>
                                <option value="<?= $value->forma_entradas_saida_id; ?>"<?
                                        if (@$obj->_forma == $value->forma_entradas_saida_id):echo'selected';
                                        endif;
                                        ?>><?php echo $value->descricao; ?></option>
                            <? } ?>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Observa&ccedil;&atilde;o</label>
                        <textarea  class="form-control" name="Observacao" id="Observacao"><?= @$obj->_observacao; ?></textarea><br/>
                    </div>
                </div>
                <?if(!@$obj->_saida_id > 0){?>
                        <label for='selecao-arquivo'>Arquivos: <input type="file" multiple="" name="arquivos[]"/></label>
                        
                    <?}?>
                    <br>
                <div class="btn-group">
                    <button class="btn btn-outline-default btn-sm"  type="submit" name="btnEnviar">Enviar</button>
                    <button class="btn btn-outline-default btn-sm" type="reset" name="btnLimpar">Limpar</button>
                </div>
                
                    
               
            </form>
        </div>
    
</div> <!-- Final da DIV content -->
<script type="text/javascript">


    $(function () {
        $('#tipo').change(function () {
            if ($(this).val()) {
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/classeportiposaidalista', {nome: $(this).val(), ajax: true}, function (j) {
                    options = '<option value=""></option>';
                    for (var c = 0; c < j.length; c++) {
                        options += '<option value="' + j[c].classe + '">' + j[c].classe + '</option>';
                    }
                    $('#classe').html(options).show();
                    $('.carregando').hide();
                });
            } else {
                $('#classe').html('<option value="">TODOS</option>');
            }
        });
    });

    $(function () {
        $("#devedorlabel").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=credordevedor",
            minLength: 1,
            focus: function (event, ui) {
                $("#devedorlabel").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#devedorlabel").val(ui.item.value);
                $("#devedor").val(ui.item.id);
                return false;
            }
        });
    });

    $(function () {
        $("#accordion").accordion();
    });

    $(function () {
        $("#inicio").datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
    });

    $(document).ready(function () {
        jQuery('#form_saida').validate({
            rules: {
                valor: {
                    required: true
                },
                devedor: {
                    required: true
                },
                classe: {
                    required: true
                },
                conta: {
                    required: true
                },
                inicio: {
                    required: true
                }
            },
            messages: {
                valor: {
                    required: "*"
                },
                devedor: {
                    required: "*"
                },
                classe: {
                    required: "*"
                },
                conta: {
                    required: "*"
                },
                inicio: {
                    required: "*"
                }
            }
        });
    });


</script>
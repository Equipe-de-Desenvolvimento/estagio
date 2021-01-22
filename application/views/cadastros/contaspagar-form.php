<div class="panel-body"> <!-- Inicio da DIV content -->
    
    <?
    $permissoes = $this->caixa->listarpermissoesempresa();
    ?>
    
        <div class="alert alert-primary"><b>Contas a pagar</b></div>
        <div>
            <form name="form_contaspagar" id="form_contaspagar" action="<?= base_url() ?>cadastros/contaspagar/gravar" enctype="multipart/form-data" method="post">
                <div class="row">
                    <div class="col-lg-2">
                        <label>Valor *</label>
                        <input type="hidden" id="financeiro_contaspagar_id" class="texto_id" name="financeiro_contaspagar_id" value="<?= @$obj->_financeiro_contaspagar_id; ?>" />
                        <input type="hidden" id="parametros" name="parametros" value="<?= @$parametros; ?>" />
                        <input type="text" name="valor" id="valor" alt="decimal" class="form-control" value="<?= @$obj->_valor; ?>"/>
                    </div>
                    <div class="col-lg-2">
                        <label>Data*</label>
                        <input type="text" name="inicio" id="inicio" class="form-control" alt="date" value="<?= substr(@$obj->_data, 8, 2) . '/' . substr(@$obj->_data, 5, 2) . '/' . substr(@$obj->_data, 0, 4);  ?>" required=""/>
                    </div>
                    <div class="col-lg-2">
                        <label>Data de Pagamento</label>
                        <input type="text" name="data_pagamento" id="data_pagamento" class="form-control" alt="date" value="<?= substr(@$obj->_data_pagamento, 8, 2) . '/' . substr(@$obj->_data_pagamento, 5, 2) . '/' . substr(@$obj->_data_pagamento, 0, 4);  ?>"/>
                    </div>
                    <div class="col-lg-3">
                        <label>Pagar a:</label>
                        <input type="hidden" id="credor" class="texto_id" name="credor" value="<?= @$obj->_credor; ?>"/>
                        <input type="text" id="credorlabel" class="form-control" name="credorlabel" value="<?= @$obj->_razao_social; ?>"  required=""/>
                        <a class="btn btn-outline-default btn-sm" target="_blank" href="<?= base_url() ?>cadastros/fornecedor">
                            Manter Credor/Devedor
                        </a>
                    </div>
                    <div class="col-lg-2">
                        <label>Tipo numero</label>
                        <input type="text" name="tiponumero" id="tiponumero" class="form-control" value="<?= @$obj->_tipo_numero; ?>"/>
                    </div>
                    <div class="col-lg-2">
                        <label>Empresa*</label>
                        <select name="empresa_id" id="empresa_id" class="form-control">
                            <option value="">Selecione</option>
                            <? foreach ($empresas as $value) : ?>
                                <option value="<?= $value->empresa_id; ?>" <?if($empresa_id == $value->empresa_id || @$obj->_empresa_id == $value->empresa_id) echo 'selected'?>>
                                    <?php echo $value->nome; ?>
                                </option>
                            <? endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label>Tipo</label>
                        <select name="tipo" id="tipo" class="form-control" required="">
                            <option value="">Selecione</option>
                            <? foreach ($tipo as $value) : ?>
                                <option value="<?= $value->tipo_entradas_saida_id; ?>"                            <?
                                if ($value->descricao == @$obj->_tipo):echo'selected';
                                endif;
                                ?>
                                        ><?php echo $value->descricao; ?></option>
                                    <? endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label>Classe</label>
                        <select name="classe" id="classe" class="form-control" <?=($permissoes[0]->financ_4n == 'f')? 'required' : ''?>>
                            <option value="">Selecione</option>
                            <? foreach ($classe as $value) : ?>
                                <option value="<?= $value->descricao; ?>"
                                <?
                                if ($value->descricao == @$obj->_classe):echo'selected';
                                endif;
                                ?>
                                        ><?php echo $value->descricao; ?></option>
                                    <? endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label>Conta *</label>
                        <select name="conta" id="conta" class="form-control" required="">
                            <option value="">Selecione</option>
                            <? foreach ($conta as $value) : ?>
                                <option value="<?= $value->forma_entradas_saida_id; ?>"<?
                                if (@$obj->_conta_id == $value->forma_entradas_saida_id):echo'selected';
                                endif;
                                ?>><?php echo $value->descricao; ?></option>
                                    <? endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-lg-2">
                        <label>Repetir </label>
                        <input type="number"  name="repitir" id="repetir" alt="integer" class="form-control" value="<?= @$obj->_numero_parcela; ?>"/> nos proximos meses
                    </div>
                    <div class="col-lg-2">
                        <label>Observa&ccedil;&atilde;o</label>
                        <textarea cols="70" rows="3" name="Observacao" id="Observacao" ><?= @$obj->_observacao; ?></textarea><br/>
                    </div>
                </div>
                <?if(!@$obj->_financeiro_contaspagar_id > 0){?>
                        <label for='selecao-arquivo'>Arquivos:</label>
                        <input type="file" multiple="" name="arquivos[]"/>
                    <?}?>
                <br>
                <div class="btn-group">
                    <button class="btn btn-outline-default btn-sm" type="submit" name="btnEnviar">Enviar</button>
                    <button class="btn btn-outline-default btn-sm" type="reset" name="btnLimpar">Limpar</button>
                </div>
                
            </form>
        </div>
   
</div> <!-- Final da DIV content -->
<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript">
    
    $(function () {
        $("#credorlabel").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=credordevedor",
            minLength: 1,
            focus: function (event, ui) {
                $("#credorlabel").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#credorlabel").val(ui.item.value);
                $("#credor").val(ui.item.id);
                return false;
            }
        });
    });


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
   
    function mostrarParcelasRepetir() {
        var repetir_str = parseInt($('#repetir').val());
        var inicio_str = $('#inicio').val();
        if(repetir_str > 0 && inicio_str != ''){
            $.getJSON('<?= base_url() ?>cadastros/contaspagar/repetirDiaDePagamento', {repetir: repetir_str, inicio: inicio_str, ajax: true}, function (j) {
                // console.log(j);
                var tr = '';
                $('#tableParcelas').html('');
                for (var c = 0; c < j.length; c++) {
                    tr += '<tr><td style="width: 100px;">'+ (c + 1) +' Parcela</td><td>'+j[c]+'</td></tr>';
                }
                $('#tableParcelas').html(tr);
            });
        }
    }
        
  

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


    $(function () {
        $("#data_pagamento").datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
    });

    $(function () {
        $("#accordion").accordion();
    });



    $(document).ready(function () {
        jQuery('#form_contaspagar').validate({
            rules: {
                valor: {
                    required: true
                },
                credor: {
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
                credor: {
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
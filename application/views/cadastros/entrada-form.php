<div class="panel-body"> <!-- Inicio da DIV content -->

    <?
    $empresa_id = $this->session->userdata('empresa_id');
    $this->db->select('ep.financ_4n');
    $this->db->from('tb_empresa_permissoes ep');
    $this->db->where('ep.empresa_id', $empresa_id);
    $retorno = $this->db->get()->result();
    ?>
        <div class="alert alert-primary"><b>Entrada</b></div>

            <form name="form_entrada" id="form_entrada" action="<?= base_url() ?>cadastros/caixa/gravarentrada" enctype="multipart/form-data" method="post">

                <div class="row">
                    <div class="col-lg-2">
                        <div>
                            <label>Valor *</label>
                            <input type="text" name="valor" id="valor" alt="decimal" class="form-control"/>
                            <input type="hidden" id="parametros" name="parametros" value="<?= @$parametros; ?>" />
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div>
                            <label>Data*</label>
                            <input type="text" name="inicio" id="inicio" class="form-control" value="<?= substr(@$obj->_data, 8, 2) . '/' . substr(@$obj->_data, 5, 2) . '/' . substr(@$obj->_data, 0, 4); ?>" alt="date" required=""/>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div>
                            <label>Receber de:</label>
                            <input type="hidden" id="devedor" class="texto_id" name="devedor" value="<?= @$obj->_devedor; ?>" />
                            <input type="text" id="devedorlabel" class="form-control" name="devedorlabel" value="<?= @$obj->_razao_social; ?>" required=""/>
                            <a class="btn btn-outline-default btn-sm" target="_blank" href="<?= base_url() ?>cadastros/fornecedor">
                                Manter Credor/Devedor
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div>
                            <label>Empresa*</label>
                            <select name="empresa_id" id="empresa_id" class="form-control">
                                <option value="">Selecione</option>
                                    <? foreach ($empresas as $value) : ?>
                                <option value="<?= $value->empresa_id; ?>" <? if ($empresa_id == $value->empresa_id) echo 'selected' ?>>
                                <?php echo $value->nome; ?>
                                </option>
                                <? endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <? if($retorno[0]->financ_4n == 't'){ ?>
                    <div class="col-lg-2">
                        <div>
                            <label>Nível 1 </label>
                            <select name="nivel1" id="nivel1" class="form-control">
                            <option value="">Selecione</option> 
                                <? foreach ($nivel1 as $value) : ?>
                                <option value="<?= $value->nivel1_id; ?>"><?php echo $value->descricao; ?></option>
                            <? endforeach; ?>
                        </select>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div>
                            <label>Nível 2 </label>
                            <select name="nivel2" id="nivel2" class="form-control">
                            <option value="">Selecione</option> 
                            <? foreach ($nivel2 as $value) : ?>
                                <option value="<?= $value->nivel2_id; ?>"><?php echo $value->descricao; ?></option>
                            <? endforeach; ?>
                        </select>
                        </div>
                    </div>

                    <?}?>


                    <div class="col-lg-2">
                        <div>
                            <label>Tipo </label>
                            <select name="tipo" id="tipo" class="form-control" required="">
                            <option value="">Selecione</option>
                            <? foreach ($tipo as $value) : ?>
                                <option value="<?= $value->tipo_entradas_saida_id; ?>"><?php echo $value->descricao; ?></option>
                            <? endforeach; ?>
                        </select>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div>
                            <label>Classe *</label>
                            <select name="classe" id="classe" class="form-control" <?=($retorno[0]->financ_4n == 'f')? 'required' : ''?>>
                            <option value="">Selecione</option> 
                            <? foreach ($classe as $value) : ?>
                                <option value="<?= $value->descricao; ?>"><?php echo $value->descricao; ?></option>
                            <? endforeach; ?>
                        </select>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div>
                            <label>Conta *</label>
                            <select name="conta" class="form-control" required="">
                                <option value="">Selecione</option>
                                <? foreach ($conta as $value) : ?>
                                <option value="<?= $value->forma_entradas_saida_id; ?>"><?php echo $value->descricao; ?></option>
                            <? endforeach; ?>
                        </select>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div>
                            <label>Observação</label>
                            <dd class="dd_texto">
                                <textarea class="form-control" name="Observacao" id="Observacao"></textarea><br/>
                            </dd>
                        </div>
                    </div>
                </div>

                <label for='selecao-arquivo'>Arquivos:  <input  type="file" multiple="" name="arquivos[]"/> </label>
                <br>
                <div class="btn-group">
                    <button class="btn btn-outline-default btn-round btn-sm" type="submit" name="btnEnviar">Enviar</button>
                    <button class="btn btn-outline-default btn-round btn-sm" type="reset" name="btnLimpar">Limpar</button>
                </div>
        

                
            </form>

</div> <!-- Final da DIV content -->

<script type="text/javascript">

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
        $('#tipo').change(function () {
            if ($(this).val()) {
//                console.log($(this).val());
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
        $('#nivel1').change(function () {
//            alert($(this).val());
            if ($(this).val()) {
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/nivel2pornivel1saidalista', {nome: $(this).val(), ajax: true}, function (j) {
//                    alert('teste');
                    options = '<option value=""></option>';
                    for (var c = 0; c < j.length; c++) {
                        options += '<option value="' + j[c].nivel2_id + '">' + j[c].nivel2 + '</option>';
                        console.log(options);
                    }
                    $('#nivel2').html(options).show();
                    $('.carregando').hide();
                });
            } else {
                $('#nivel2').html('<option value="">TODOS</option>');
            }
        });
    });
    
    $(function () {
        $('#nivel2').change(function () {
//            alert($(this).val());
            if ($(this).val()) {
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/tipopornivel2saidalista', {nome: $(this).val(), ajax: true}, function (j) {
//                    alert('teste');
                    options = '<option value=""></option>';
                    for (var c = 0; c < j.length; c++) {
                        options += '<option value="' + j[c].tipo_entradas_saida_id + '">' + j[c].tipo + '</option>';
                        console.log(options);
                    }
                    $('#tipo').html(options).show();
                    $('.carregando').hide();
                });
            } else {
                $('#tipo').html('<option value="">TODOS</option>');
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



    $(document).ready(function () {
        jQuery('#form_entrada').validate({
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
<div class="content"> <!-- Inicio da DIV content -->
    <!--    <div class="bt_link_voltar">
            <a href="<?= base_url() ?>ponto/horariostipo">
                Voltar
            </a>
    
        </div>-->
    <?
    $empresa_id = $this->session->userdata('empresa_id');
    $this->db->select('ep.financ_4n');
    $this->db->from('tb_empresa_permissoes ep');
    $this->db->where('ep.empresa_id', $empresa_id);
    $retorno = $this->db->get()->result();
    ?>
    <div id="accordion">
        <h3 class="singular"><a href="#">Entrada</a></h3>
        <div>
            <form name="form_entrada" id="form_entrada" action="<?= base_url() ?>cadastros/caixa/gravarentrada" enctype="multipart/form-data" method="post">

                <dl class="dl_desconto_lista">
                    <dt>
                        <label>Valor *</label>
                    </dt>
                    <dd>
                        <input type="text" name="valor" alt="decimal" class="texto04"/>
                        <input type="hidden" id="parametros" name="parametros" value="<?= @$parametros; ?>" />
                    </dd>
                    <dt>
                        <label>Data*</label>
                    </dt>
                    <dd>
                        <input type="text" name="inicio" id="inicio" class="texto04" value="<?= substr(@$obj->_data, 8, 2) . '/' . substr(@$obj->_data, 5, 2) . '/' . substr(@$obj->_data, 0, 4); ?>" alt="date" required=""/>
                    </dd>
                    <dt>
                        <label>Receber de:</label>
                    </dt>
                    <dd>
                        <input type="hidden" id="devedor" class="texto_id" name="devedor" value="<?= @$obj->_devedor; ?>" />
                        <input type="text" id="devedorlabel" class="texto09" name="devedorlabel" value="<?= @$obj->_razao_social; ?>" required=""/>
                        <a target="_blank" href="<?= base_url() ?>cadastros/fornecedor">
                            Manter Credor/Devedor
                        </a>
                    </dd>
                    <dt>
                        <label>Empresa*</label>
                    </dt>
                    <dd>
                        <select name="empresa_id" id="empresa_id" class="size4">
                            <option value="">Selecione</option>
<? foreach ($empresas as $value) : ?>
                                <option value="<?= $value->empresa_id; ?>" <? if ($empresa_id == $value->empresa_id) echo 'selected' ?>>
                                <?php echo $value->nome; ?>
                                </option>
                                <? endforeach; ?>
                        </select>
                    </dd>
                                        <?if($retorno[0]->financ_4n == 't'){?>
                    <dt>
                        <label>Nível 1 </label>
                    </dt>
                    <dd>
                        <select name="nivel1" id="nivel1" class="size4">
                            <option value="">Selecione</option> 
<? foreach ($nivel1 as $value) : ?>
                                <option value="<?= $value->nivel1_id; ?>"><?php echo $value->descricao; ?></option>
                            <? endforeach; ?>
                        </select>
                    </dd>
                    <dt>
                        <label>Nível 2 </label>
                    </dt>
                    <dd>
                        <select name="nivel2" id="nivel2" class="size4">
                            <option value="">Selecione</option> 
<? foreach ($nivel2 as $value) : ?>
                                <option value="<?= $value->nivel2_id; ?>"><?php echo $value->descricao; ?></option>
                            <? endforeach; ?>
                        </select>
                    </dd>
                    <? } ?>
                    <dt>
                        <label>Tipo </label>
                    </dt>
                    <dd>
                        <select name="tipo" id="tipo" class="size4" required="">
                            <option value="">Selecione</option>
<? foreach ($tipo as $value) : ?>
                                <option value="<?= $value->tipo_entradas_saida_id; ?>"><?php echo $value->descricao; ?></option>
                            <? endforeach; ?>
                        </select>
                    </dd>
                    <dt>
                        <label>Classe *</label>
                    </dt>
                    <dd>
                        <select name="classe" id="classe" class="size4" <?=($retorno[0]->financ_4n == 'f')? 'required' : ''?>>
                            <option value="">Selecione</option> 
<? foreach ($classe as $value) : ?>
                                <option value="<?= $value->descricao; ?>"><?php echo $value->descricao; ?></option>
                            <? endforeach; ?>
                        </select>
                    </dd>

                    <dt>
                        <label>Conta *</label>
                    </dt>
                    <dd>
                        <select name="conta" id="conta" class="size4" required="">
                            <option value="">Selecione</option>
<? foreach ($conta as $value) : ?>
                                <option value="<?= $value->forma_entradas_saida_id; ?>"><?php echo $value->descricao; ?></option>
                            <? endforeach; ?>
                        </select>
                    </dd>
                    <dt>
                        <label>Observa&ccedil;&atilde;o</label>
                    </dt>
                    <dd class="dd_texto">
                        <textarea cols="70" rows="3" name="Observacao" id="Observacao"></textarea><br/>
                    </dd>
                </dl>    

                <hr/>
                <button type="submit" name="btnEnviar">Enviar</button>
                <button type="reset" name="btnLimpar">Limpar</button>
                <?//if($_POST[''] ){?>
                    <label for='selecao-arquivo'>Arquivos:</label>
                    <input type="file" multiple="" name="arquivos[]"/>
                <?//}?>
                
            </form>
        </div>
    </div>
</div> <!-- Final da DIV content -->
<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
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

<div class="panel-body"> <!-- Inicio da DIV content -->

        <div class="alert alert-primary"><b>Saida</b></div>

            <form name="form_emprestimo" id="form_emprestimo" action="<?= base_url() ?>cadastros/caixa/gravartransferencia" method="post">
                <?
                $empresas = $this->exame->listarempresas();
                $empresa_atual = $this->session->userdata('empresa_id');
                ?>

                <div class="row">
                    <div class="col-lg-2">
                        <label>Valor *</label>
                        <input type="text" name="valor" id="valor" alt="decimal" class="form-control" required=""/>

                    </div>

                    <div class="col-lg-2">
                        <label>Data*</label>
                        <input type="text" name="inicio" id="inicio" class="form-control" required/>
                    </div>

                    <div class="col-lg-2">
                        <label>Empresa Saída</label>
                        <select name="empresa" id="empresa" class="form-control">
                            <? foreach ($empresas as $value) : ?>
                                <option <?
                                if ($empresa_atual == $value->empresa_id) {
                                    echo 'selected';
                                }
                                ?> value="<?= $value->empresa_id; ?>"><?php echo $value->nome; ?></option>
                            <? endforeach; ?>
                        </select>
                    </div>


                    <div class="col-lg-2">
                        <label>Conta Saida</label>
                        <select name="conta" id="conta" class="form-control" required>
                            <? foreach ($conta as $value) : ?>
                                <option value="<?= $value->forma_entradas_saida_id; ?>"><?php echo $value->descricao; ?></option>
                        <? endforeach; ?>
                        </select>
                    </div>


                    <div class="col-lg-2">
                        <label>Empresa Entrada</label>
                        <select name="empresaentrada" id="empresaentrada" class="form-control">
                            <? foreach ($empresas as $value) : ?>
                                <option <?
                                    if ($empresa_atual == $value->empresa_id) {
                                        echo 'selected';
                                    }
                                    ?> value="<?= $value->empresa_id; ?>"><?php echo $value->nome; ?></option>
                        <? endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-2">
                        <label>Conta Entrada</label>
                        <select name="contaentrada" id="contaentrada" class="form-control" required>
                        <? foreach ($conta as $item) : ?>
                        <option value="<?= $item->forma_entradas_saida_id; ?>"><?php echo $item->descricao; ?></option>
                        <? endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label>Observa&ccedil;&atilde;o</label>
                    '   <textarea name="Observacao" class="form-control" id="Observacao"></textarea><br/>

                    </div>
                </div>

                <hr/>
                <button class="btn btn-outline-default btn-sm" type="submit" name="btnEnviar">enviar</button>
                <button class="btn btn-outline-default btn-sm" type="reset" name="btnLimpar">Limpar</button>
            </form>
        </div>
    </div>
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
            dateFormat: 'dd/mm/yy',
            maxDate: 'date'
        });
    });

    $(function () {
        $('#empresa').change(function () {
//                                            if ($(this).val()) {
            $('.carregando').show();
            $.getJSON('<?= base_url() ?>autocomplete/contaporempresa', {empresa: $(this).val(), ajax: true}, function (j) {
                options = '<option value=""></option>';
                for (var c = 0; c < j.length; c++) {
                    options += '<option value="' + j[c].forma_entradas_saida_id + '">' + j[c].descricao + '</option>';
                }
                $('#conta').html(options).show();
                $('.carregando').hide();
            });
//                                            } else {
//                                                $('#nome_classe').html('<option value="">TODOS</option>');
//                                            }
        });
    });

    $(function () {
        $('#empresaentrada').change(function () {
//                                            if ($(this).val()) {
            $('.carregando').show();
            $.getJSON('<?= base_url() ?>autocomplete/contaporempresa', {empresa: $(this).val(), ajax: true}, function (j) {
                options = '<option value=""></option>';
                for (var c = 0; c < j.length; c++) {
                    options += '<option value="' + j[c].forma_entradas_saida_id + '">' + j[c].descricao + '</option>';
                }
                $('#contaentrada').html(options).show();
                $('.carregando').hide();
            });
//                                            } else {
//                                                $('#nome_classe').html('<option value="">TODOS</option>');
//                                            }
        });
    });

    $(function () {
        $("#accordion").accordion();
    });




</script>
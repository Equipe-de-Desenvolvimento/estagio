<div class="panel-body"> <!-- Inicio da DIV content -->
        <div class="alert alert-primary"><b>Cadastro de Conta</b></div>
            <form name="form_forma" id="form_sala" action="<?= base_url() ?>cadastros/forma/gravar" method="post">

                    <div class="row">
                        <div class="col-lg-3">
                            <label>Nome</label>
                            <input type="hidden" name="txtcadastrosformaid" class="texto10" value="<?= @$obj->_forma_entradas_saida_id; ?>" />
                            <input type="text" name="txtNome" class="form-control" value="<?= @$obj->_descricao; ?>" />
                        </div>

                        <div class="col-lg-2">
                            <label>Agencia</label>
                            <input type="text" name="txtagencia" class="form-control" value="<?= @$obj->_agencia; ?>" />
                        </div>

                        <div class="col-lg-2">
                            <label>Conta</label>
                            <input type="text" name="txtconta" class="form-control" value="<?= @$obj->_conta; ?>" />
                        </div>

                        <div class="col-lg-3">
                            <label>Empresa</label>
                            <select name="empresa" id="empresa" class="form-control" required>
                            <option value="">Selecione</option>
                            <? foreach ($empresa as $item) { ?>
                                <option value="<?= $item->empresa_id ?>" <?
                                if (@$obj->_empresa_id == $item->empresa_id) {
                                    echo 'selected';
                                }
                                ?>><?= $item->nome ?></option>
                                    <? } ?>
                            </select>
                        </div>
                    </div>
                    
                <hr/>
                <button class="btn btn-outline-default btn-round btn-sm" type="submit" name="btnEnviar">Enviar</button>
                <button class="btn btn-outline-default btn-round btn-sm" type="reset" name="btnLimpar">Limpar</button>
                <button class="btn btn-outline-default btn-round btn-sm" type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
                <a href="<?= base_url() ?>cadastros/contasreceber/">
            </form>
        </div>
    </div>
</div> <!-- Final da DIV content -->

<script type="text/javascript">
    $('#btnVoltar').click(function () {
        $(location).attr('href', '<?= base_url(); ?>ponto/cargo');
    });

    $(function () {
        $("#accordion").accordion();
    });


    $(document).ready(function () {
        jQuery('#form_forma').validate({
            rules: {
                txtNome: {
                    required: true,
                    minlength: 3
                },
                txtagencia: {
                    required: true
                },
                txtconta: {
                    required: true
                }
            },
            messages: {
                txtNome: {
                    required: "*",
                    minlength: "!"
                },
                txtagencia: {
                    required: "*"
                },
                txtconta: {
                    required: "*"
                }
            }
        });
    });

</script>
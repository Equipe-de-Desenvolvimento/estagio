<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3 class="singular"><a href="#">Cadastro de Credor/Devedor</a></h3>

        <div>
          <form name="form_fornecedor" id="form_fornecedor" action="<?= base_url() ?>cadastros/fornecedor/gravar" method="post">
                
            <dl class="dl_desconto_lista">
                <div class="col-lg-2">
               
                    <dt>
                        <label>Raz&atilde;o social*</label>
                    </dt>
                    <div class="col-lg-4">
                    <dd>
                        <input type="hidden" name="txtcadastrosfornecedorid" class="texto07" value="<?= @$obj->_financeiro_credor_devedor_id; ?>" />
                        <input type="text" name="txtrazaosocial" class="texto07" value="<?= @$obj->_razao_social; ?>" />
                    </dd>
                    <dt>
                        <label>CNPJ*</label>
                    </dt>
                    <dd>
                        <input type="text" id="txtCnpj" name="txtCNPJ" maxlength="11" alt="cnpj" class="texto07" value="<?= @$obj->_cnpj; ?>" />
                    </dd>
                    <dt>
                        <label>CPF*</label>
                    </dt>
                    
                    <dd>
                        <input type="text" id="txtCpf" name="txtCPF" maxlength="11" alt="cpf" class="texto07" value="<?= @$obj->_cpf; ?>" />
                    </dd>
                    </div>
                </div>    
                    
                <div class="">    
                    <div class="col-lg-3">
                    <dt>
                        <label>Tipo Pessoa</label>
                    </dt>
                    <dd>
                        <select name="tipo_pessoa" id="tipo_pessoa" class="size4" required="">

                            <option value="">Selecione</option>
                            <option <?=(@$obj->_tipo_pessoa == 'pessoa_f')? 'selected':'' ; ?> value="pessoa_f">Pessoa Física</option>
                            <option <?=(@$obj->_tipo_pessoa == 'pessoa_j')? 'selected':'' ; ?> value="pessoa_j">Pessoa Jurídica</option>

                        </select>
                    </dd>
                    <dt>
                        <label>Endere&ccedil;o*</label>
                    </dt>
                    <dd>
                        <input type="text" id="txtendereco" class="texto07" name="endereco" value="<?= @$obj->_logradouro; ?>" />
                    </dd>
                    <dt>
                        <label>N&uacute;mero</label>
                    </dt>
                    <dd>
                        <input type="text" id="txtNumero" class="texto07" name="numero" value="<?= @$obj->_numero; ?>" />
                    </dd>
                    </div>
                </div>
                <div class="col-lg-3">
                    <dt>
                        <label>Bairro</label>
                    </dt>
                    <dd>
                        <input type="text" id="txtBairro" class="texto07" name="bairro" value="<?= @$obj->_bairro; ?>" />
                    </dd>
                    <dt>
                        <label>Complemento</label>
                    </dt>
                    <dd>
                        <input type="text" id="txtComplemento" class="texto10" name="complemento" value="<?= @$obj->_complemento; ?>" />
                    </dd>
                    <dt>
                        <label>Telefone</label>
                    </dt>
                    <dd>
                        <input type="text" id="txtTelefone" class="texto07" name="telefone" alt="phone" value="<?= @$obj->_telefone; ?>" />
                    </dd>
                </div>    
                    
                <div class="col-lg-2">
                    <div class="form-group">
                    <dt>
                        <label>Celular</label>
                    </dt>
                    <dd>
                        <input type="text" id="txtCelular" class="texto07" name="celular" alt="phone" value="<?= @$obj->_celular; ?>" />
                    </dd>
                    <dt>
                        <label>Email</label>
                    </dt>
                    <dd>
                        <input type="text" id="txtEmail" alt="email" class="texto07" name="email" value="<?= @$obj->_email; ?>" />
                    </dd>
                    <dt>
                        <label>Município</label>
                    </dt>
                    <dd>
                        <input type="hidden" id="txtCidadeID" class="texto_id" name="municipio_id" value="<?= @$obj->_municipio_id; ?>" readonly="true" />
                        <input type="text" id="txtCidade" class="texto04" name="txtCidade" value="<?= @$obj->_nome; ?>" />
                    </dd>
                    </div>
                </div>
                    
                <div>
                   
                    <dd class="dd_texto">
                        <label>Observa&ccedil;&atilde;o</label>
                        <textarea cols="70" rows="3" name="observacao" id="observacao"><?= @$obj->_observacao; ?></textarea><br/>
                    </dd>
                </div>
                </dl>   
                <hr/>
                <button type="submit" name="btnEnviar">Enviar</button>
                <button type="reset" name="btnLimpar">Limpar</button>
                <button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
            </form>
        </div>
    </div>
</div> <!-- Final da DIV content -->

<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">
    
    
    jQuery("#txtTelefone")
            .mask("(99) 9999-9999?9")
            .focusout(function (event) {
                var target, phone, element;
                target = (event.currentTarget) ? event.currentTarget : event.srcElement;
                phone = target.value.replace(/\D/g, '');
                element = $(target);
                element.unmask();
                if (phone.length > 10) {
                    element.mask("(99) 99999-999?9");
                } else {
                    element.mask("(99) 9999-9999?9");
                }
            });

                jQuery("#txtCelular")
            .mask("(99) 9999-9999?9")
            .focusout(function (event) {
                var target, phone, element;
                target = (event.currentTarget) ? event.currentTarget : event.srcElement;
                phone = target.value.replace(/\D/g, '');
                element = $(target);
                element.unmask();
                if (phone.length > 10) {
                    element.mask("(99) 99999-999?9");
                } else {
                    element.mask("(99) 9999-9999?9");
                }
            });

    $('#btnVoltar').click(function () {
        $(location).attr('href', '<?= base_url(); ?>cadastros/fornecedor');
    });
    $(function () {
        $("#txtCidade").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=cidade",
            minLength: 3,
            focus: function (event, ui) {
                $("#txtCidade").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#txtCidade").val(ui.item.value);
                $("#txtCidadeID").val(ui.item.id);
                return false;
            }
        });
    });


    $(function () {
        $("#accordion").accordion();
    });

    $(document).ready(function () {
        jQuery('#form_fornecedor').validate({
            rules: {
                txtrazaosocial: {
                    required: true,
                    minlength: 3
                },
                endereco: {
                    required: true
                },
                cep: {
                    required: true
                },
                cns: {
                    maxLength: 15
                }, rg: {
                    maxLength: 20
                }

            },
            messages: {
                txtrazaosocial: {
                    required: "*",
                    minlength: "*"
                },
                endereco: {
                    required: "*"
                },
                cep: {
                    required: "*"
                },
                cns: {
                    required: "Tamanho m&acute;ximo do campo CNS é de 15 caracteres"
                },
                rg: {
                    maxlength: "Tamanho m&acute;ximo do campo RG é de 20 caracteres"
                }
            }
        });
    });

</script>
<div class="panel-body"> <!-- Inicio da DIV content -->
    <div id="">
    <div class="alert alert-primary">Cadastro de Credor/Devedor</div>
            <div class="panel-body infodados">

        <div>
            <form name="form_fornecedor" id="form_fornecedor" action="<?= base_url() ?>cadastros/fornecedor/gravar" method="post">
                <!--<dl class="dl_desconto_lista">-->
                    <div class="row">
                        <div class="col-lg-3">
                            <div>  
                                <label>Raz&atilde;o social</label>
                                    <input type="hidden" name="txtcadastrosfornecedorid" value="<?= @$obj->_financeiro_credor_devedor_id; ?>" />
                                    <input type="text" placeholder="Nome do Credor/Devedor" class="form-control" name="txtrazaosocial" value="<?= @$obj->_razao_social; ?>" />
                            </div>
                            <div>
                                <label>Complemento</label>
                                <input type="text" id="txtComplemento" class="form-control" placeholder="complemento" alt="email" name="complemento" value="<?= @$obj->_complemento; ?>" />
                            </div>
                            <div>
                                <label>Observa&ccedil;&atilde;o</label>
                                <textarea cols="70" rows="3" class="form-control" name="observacao" placeholder="Observações sobre o Credor/Devedor" id="observacao"><?= @$obj->_observacao; ?></textarea><br/>
                            </div>
                        </div>              
        
                        <div class="col-lg-2">
                            <div>
                                    <label>CNPJ</label>
                                    <input type="text" id="txtCnpj" class="form-control" placeholder="CNPJ" name="txtCNPJ" maxlength="11" alt="cnpj" value="<?= @$obj->_cnpj; ?>" />
                            </div>
                            <div>
                                    <label>CPF</label>
                                    <input type="text" id="txtCpf" class="form-control" placeholder="ex:000.000.000-00" name="txtCPF" maxlength="11" alt="cpf" value="<?= @$obj->_cpf; ?>" />
                            </div>
                            <div>
                                <label>Endere&ccedil;o</label>
                                <input type="text" class="form-control" placeholder="Endereço do Credor/Devedor" id="txtendereco" name="endereco" value="<?= @$obj->_logradouro; ?>" />
                            </div>
                            <div>
                                <label>Município</label>
                                <input type="hidden" id="txtCidadeID" class="form-control" name="municipio_id" value="<?= @$obj->_municipio_id; ?>" readonly="true" />
                                <input type="text" id="txtCidade" class="form-control"  placeholder="Município" name="txtCidade" value="<?= @$obj->_nome; ?>" />
                            </div>
                        </div>    
                      
                        <div class="col-lg-2">
                            <div>
                                <label>Tipo Pessoa</label>
                                    <select name="tipo_pessoa" class="form-control" id="tipo_pessoa" class="size6" required="">
                                        <option value="">Selecione</option>
                                        <option <?=(@$obj->_tipo_pessoa == 'pessoa_f')? 'selected':'' ; ?> value="pessoa_f">Pessoa Física</option>
                                        <option <?=(@$obj->_tipo_pessoa == 'pessoa_j')? 'selected':'' ; ?> value="pessoa_j">Pessoa Jurídica</option>
                                    </select>
                            </div>
                            <div>
                                <label>N&uacute;mero</label>
                                <input type="text" class="form-control" id="txtNumero" placeholder="(00)00000-0000" name="numero" value="<?= @$obj->_numero; ?>" />
                            </div>
                            <div>
                                <label>Bairro</label>
                                <input type="text" class="form-control" id="txtBairro" placeholder="Bairro do Credor/Devedor" name="bairro" value="<?= @$obj->_bairro; ?>" />
                            </div>
                            <div>
                                <label>Telefone</label>
                                <input type="text" class="form-control" id="txtTelefone" placeholder="(00)00000-0000" name="telefone" alt="phone" value="<?= @$obj->_telefone; ?>" />
                            </div>
                        </div>   
                
                        <div class="col-lg-2">
                            <div>
                                <label>Celular</label>
                                <input type="text" id="txtCelular" class="form-control" placeholder="(00)00000-0000" name="celular" alt="phone" value="<?= @$obj->_celular; ?>" />
                            </div>
                            <div>
                                <label>Email</label>
                                <input type="text" id="txtEmail" class="form-control" placeholder="Email Principal" alt="email" name="email" value="<?= @$obj->_email; ?>" />
                            </div>
                        </div>
                    </div>
                 
                        <div>
                            <button type="submit"  class="btn btn-success btn-sm" name="btnEnviar">Enviar</button>
                            <button type="reset"  class="btn btn-warning btn-sm" name="btnLimpar">Limpar</button>
                            <button type="button" id="btnVoltar" class="btn btn-secondary btn-sm" name="btnVoltar">Voltar</button>
                        </div>  
                 
                </fieldset>
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
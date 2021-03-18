 <div class="panel-body"> <!-- Inicio da DIV content -->
    <style>
        #accordion dl dt{
            min-width:300pt;
        }
    </style>  
          <div class="alert alert-primary"><b>Cadastro de Empresa</b></div>
            <form name="form_empresa" id="form_empresa" action="<?= base_url() ?>ambulatorio/empresa/gravar" method="post">
                <dl class="dl_desconto_lista">
                      <div class="row">
                        <div class="col-lg-4">
                            <label>Raz&atilde;o social</label>
                            <input type="hidden" name="txtempresaid" class="texto08" value="<?= @$obj->_empresa_id; ?>" /> 
                        </div>
                      </div>
                    
                    <dd>
                        <input type="text" name="txtrazaosocial" id="txtrazaosocial" class="texto08 form-control" value="<?= @$obj->_razao_social; ?>" />
                    </dd> 
                   
                    <dt>
                        <label>CNPJ</label>
                    </dt>
                    <dd>
                        <input type="text" id="cnpj" name="txtCNPJ" alt="cnpj" class="texto03 form-control" value="<?= @$obj->_cnpj; ?>" />
                    </dd>  
                
                    <dt>
                        <label>Endere&ccedil;o</label>
                    </dt>
                    <dd>
                        <input type="text" id="txtendereco" class="texto08 form-control" name="endereco" value="<?= @$obj->_logradouro; ?>" />
                    </dd>  
                    <dt>
                        <label>Telefone para contato relativo ao estágio</label>
                    </dt>
                    <dd> 
                        <input type="text" id="txtTelefone" class="texto08 form-control" name="telefone" value="<?= @$obj->_telefone; ?>" />
                    </dd> 
                    <dt>
                        <label>Email institucional para contatos relativos ao estágio</label>
                    </dt>
                    <dd>
                        <input type="text" id="email_institucional" class="texto08 form-control" name="email_institucional" value="<?= @$obj->_email_institucional; ?>" />
                    </dd> 
                    <dt>
                        <label>Representante da Unidade Concedente ao estágio</label>
                    </dt>
                    <dd>
                        <input type="text" id="representante_unidade" class="texto08 form-control" name="representante_unidade" value="<?= @$obj->_representante_unidade; ?>" />
                    </dd> 
                    <dt>
                        <label>Cargo do Representante da Unidade Concedente</label>
                    </dt>
                    <dd>
                         <input type="hidden" id="txtcboID" class="texto_id" name="txtcboID" value="<?= @$obj->_cbo_ocupacao_id; ?>" readonly="true" />
                         <input type="text" id="txtcbo" class="form-control" name="txtcbo" value="<?= @$obj->_cbo_nome; ?>" />
                    </dd> 
                    
                    </dl>    
                    <hr/>
                    <button type="submit" name="btnEnviar">Enviar</button>
                    <!--<button type="reset" name="btnLimpar">Limpar</button>-->
                    <!--<button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>-->
              </form>
                            
                           
             </div> <!-- Final da DIV content -->

                            <link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
                            <script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
                            <script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
                            <script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
                            <script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
                            <link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
                            <!--<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">-->
                            <link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
                            <script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
                            <!--<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
                            <script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>

                            <script type="text/javascript"> 
                             $("#cnpj").mask("99.999.999/9999-99");
                            $(function () {
                                   $("#txtcbo").autocomplete({
                                       source: "<?= base_url() ?>index.php?c=autocomplete&m=cboprofissionais",
                                       minLength: 3,
                                       focus: function (event, ui) {
                                           $("#txtcbo").val(ui.item.label);
                                           return false;
                                       },
                                       select: function (event, ui) {
                                           $("#txtcbo").val(ui.item.value);
                                           $("#txtcboID").val(ui.item.id);
                                           return false;
                                       }
                                   });
                               });


                                //    $('#btnVoltar').click(function () {
                                //        $(location).attr('href', '<?= base_url(); ?>ponto/cargo');
                                //    });

                                $(function () {
                                    $("#accordion").accordion();
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
                                            $("#codigoibge").val(ui.item.codigo_ibge);
                                            return false;
                                        }
                                    });
                                });

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

                            </script>

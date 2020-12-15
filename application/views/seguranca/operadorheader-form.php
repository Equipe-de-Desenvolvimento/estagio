<div id="page-wrapper"> <!-- Inicio da DIV content -->
    <div class="alert alert-success">
        Cadastro de Operador
<!--        <a href="<?= base_url() ?>seguranca/operador">
            Voltar
        </a>-->
    </div>

    <!--<h3 class="singular"><a href="#"></a></h3>-->
    <div >
        <form name="form_operador" id="form_operador" action="<?= base_url() ?>seguranca/operador/gravar" method="post" style="margin-bottom: 50px;">
            <div class="panel panel-default" style="display: none;">
                <div class="alert alert-info">
                    Dados do Profissional
                </div>

                <div class="panel-body">
                    <!--<legend></legend>-->
                    <div class="row">

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Nome *</label>                      
                                <input type ="hidden" name ="operador_id" value ="<?= @$obj->_operador_id; ?>" id ="txtoperadorId" >
                                <input type="text" id="txtNome" name="nome"  class="form-control" value="<?= @$obj->_nome; ?>" required="true"/>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Sexo *</label>


                                <select name="sexo" id="txtSexo" class="form-control">
                                    <option value="">Selecione</option>
                                    <option value="M" <?
                                    if (@$obj->_sexo == "M"):echo 'selected';
                                    endif;
                                    ?>>Masculino</option>
                                    <option value="F" <?
                                    if (@$obj->_sexo == "F"):echo 'selected';
                                    endif;
                                    ?>>Feminino</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Nascimento</label>


                                <input type="text" name="nascimento" id="txtNascimento" class="form-control" alt="date" value="<?php echo substr(@$obj->_nascimento, 8, 2) . '/' . substr(@$obj->_nascimento, 5, 2) . '/' . substr(@$obj->_nascimento, 0, 4); ?>" onblur="retornaIdade()"/>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Conselho</label>
                                <input type="text" id="txtconselho" name="conselho"  class="form-control" value="<?= @$obj->_conselho; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>CPF *</label>


                                <input type="text" name="cpf" id ="txtCpf" maxlength="11" alt="cpf" class="form-control" value="<?= @$obj->_cpf; ?>" required />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Ocupa&ccedil;&atilde;o</label>
                                <input type="hidden" id="txtcboID" class="texto_id" name="txtcboID" value="<?= @$obj->_cbo_ocupacao_id; ?>" readonly="true" />
                                <input type="text" id="txtcbo" class="form-control eac-square" name="txtcbo" value="<?= @$obj->_cbo_nome; ?>" />


                                <?php
                                if (@$obj->_consulta == "t") {
                                    ?>
                                    <input type="checkbox" name="txtconsulta" checked ="true"/> Realiza Consulta
                                    <?php
                                } else {
                                    ?>
                                    <input type="checkbox" name="txtconsulta"  /> Realiza Consulta
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default " style="display: none;">
                <div class="alert alert-info">
                    Domicilio
                </div>
                <div class="panel-body">
                    <div class="row">

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>CEP</label>

                                <input type="text" id="cep" class="form-control texto03 eac-square" name="cep"  value="<?= @$obj->_cep; ?>" />
                                <input type="hidden" id="ibge" class="form-control texto02" name="ibge" />

                            </div>


                            <div class="form-group">
                                <label>Telefone</label>
                                <?
                                if (@$obj->_telefone != '' && strlen(@$obj->_telefone) > 3) {

                                    if (preg_match('/\(/', @$obj->_telefone)) {
                                        $telefone = @$obj->_telefone;
                                    } else {
                                        $telefone = "(" . substr(@$obj->_telefone, 0, 2) . ")" . substr(@$obj->_telefone, 2, strlen(@$obj->_telefone) - 2);
                                    }
                                } else {
                                    $telefone = '';
                                }
                                if (@$obj->_celular != '' && strlen(@$obj->_celular) > 3) {
                                    if (preg_match('/\(/', @$obj->_celular)) {
                                        $celular = @$obj->_celular;
                                    } else {
                                        $celular = "(" . substr(@$obj->_celular, 0, 2) . ")" . substr(@$obj->_celular, 2, strlen(@$obj->_celular) - 2);
                                    }
                                } else {
                                    $celular = '';
                                }
                                ?>

                                <input type="text" id="txtTelefone" class="form-control texto04" name="telefone"  value="<?= @$telefone; ?>" />
                            </div>
                            <div class="form-group">
                                <label>Celular*</label>
                                <input type="text" id="txtCelular" class="form-control texto04" name="celular" value="<?= @$celular; ?>" required=""/>
                            </div>


                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Endere&ccedil;o</label>
                                <input type="text" id="rua" class="form-control texto10" name="endereco" value="<?= @$obj->_endereco; ?>" />
                            </div>

                            <div class="form-group">
                                <label>Bairro</label>


                                <input type="text" id="bairro" class="form-control texto10" name="bairro" value="<?= @$obj->_bairro; ?>" />
                            </div>



                        </div>
                        <div class="col-lg-3">

                            <div class="form-group">
                                <label>N&uacute;mero</label>


                                <input type="text" id="txtNumero" class="form-control texto04" name="numero" value="<?= @$obj->_numero; ?>" />
                            </div>

                            <div class="form-group">
                                <label>Município</label>


                                <input type="hidden" id="txtCidadeID" class="texto_id" name="municipio_id" value="<?= @$obj->_cidade; ?>" readonly="true" />
                                <input type="text" id="txtCidade" class="form-control texto04 eac-square" name="txtCidade" value="<?= @$obj->_cidade_nome; ?>" />
                            </div>


                        </div>
                        <div class="col-lg-3">

                            <div class="form-group">
                                <label>Complemento</label>


                                <input type="text" id="txtComplemento" class="form-control texto08" name="complemento" value="<?= @$obj->_complemento; ?>" />
                            </div>



                        </div>

                    </div>



                </div>
            </div>
            <div class="panel panel-default">
                <div class="alert alert-info">
                    Acesso
                </div>
                <div class="panel-body">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Nome usu&aacute;rio *</label>

                            <input type="text" id="txtUsuario" name="txtUsuario"  class="form-control" value="<?= @$obj->_usuario; ?>" required="true"/>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Senha: *</label>
                            <input type="password" name="txtSenha" id="txtSenha" class="form-control" value="" <? if (@$obj->_senha == null) {
                                    ?>
                                       required="true"
                                   <? } ?> />

                            <!--                    <label>Confirme a Senha: *</label>
                                                <input type="password" name="verificador" id="txtSenha" class="texto04" value="" onblur="confirmaSenha(this)"/>-->
                        </div>
                    </div>
                    <div class="col-lg-4" style="display: none;">
                        <div class="form-group">
                            <label>Tipo perfil *</label>

                            <select name="txtPerfil" id="txtPerfil" class="form-control" required="true">
                                <option value="">Selecione</option>
                                <?
                                foreach ($listarPerfil as $item) :
                                    if ($this->session->userdata('perfil_id') == 1) {
                                        ?>
                                        <option value="<?= $item->perfil_id; ?>"<?
                                        if (@$obj->_perfil_id == $item->perfil_id):echo 'selected';
                                        endif;
                                        ?>>
                                            <?= $item->nome; ?></option>
                                        <?
                                    } else {
                                        if (!($item->perfil_id == 1)) {
                                            ?>
                                            <option value="<?= $item->perfil_id; ?>"<?
                                            if (@$obj->_perfil_id == $item->perfil_id):echo 'selected';
                                            endif;
                                            ?>><?= $item->nome; ?></option>
                                                    <?
                                                }
                                            }
                                            ?>
                                        <? endforeach; ?>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="panel panel-default" style="display: none;">
                <div class="alert alert-info">
                    Financeiro
                </div>
                <div class="panel-body">
                    <!--<legend></legend>-->
                    <div class="row">


                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Criar Credor</label>
                                <input class="checkbox" type="checkbox" name="criarcredor"/>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">



                                <label>Credor / Devedor</label>


                                <select name="credor_devedor" id="credor_devedor" class="form-control" >
                                    <option value='' >Selecione</option>
                                    <?php
                                    $credor_devedor = $this->convenio->listarcredordevedor();
                                    foreach ($credor_devedor as $item) {
                                        ?>

                                        <option   value =<?php echo $item->financeiro_credor_devedor_id; ?> <?
                                        if (@$obj->_credor_devedor_id == $item->financeiro_credor_devedor_id):echo 'selected';
                                        endif;
                                        ?>><?php echo $item->razao_social; ?></option>
                                                  <?php
                                              }
                                              ?> 
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Conta</label>


                                <select name="conta" id="conta" class="form-control" >
                                    <option value='' >Selecione</option>
                                    <?php
                                    $conta = $this->forma->listarforma();
                                    foreach ($conta as $item) {
                                        ?>

                                        <option   value =<?php echo $item->forma_entradas_saida_id; ?> <?
                                        if (@$obj->_conta_id == $item->forma_entradas_saida_id):echo 'selected';
                                        endif;
                                        ?>><?php echo $item->descricao; ?></option>
                                                  <?php
                                              }
                                              ?> 
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Tipo</label>


                                <select name="tipo" id="tipo" class="form-control">
                                    <option value='' >Selecione</option>
                                    <?php
                                    $tipo = $this->tipo->listartipo();

                                    foreach ($tipo as $item) {
                                        ?>

                                        <option   value = "<?= $item->descricao; ?>" <?
                                        if (@$obj->_tipo_id == $item->descricao):echo 'selected';
                                        endif;
                                        ?>><?php echo $item->descricao; ?></option>
                                                  <?php
                                              }
                                              ?> 
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Classe</label>


                                <select name="classe" id="classe" class="form-control">
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
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>IR</label>
                                <input type="text" id="ir" class="form-control" name="ir" alt="decimal" value="<?= @$obj->_ir; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>PIS</label>
                                <input type="text" id="pis" class="form-control" name="pis" alt="decimal" value="<?= @$obj->_pis; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>COFINS</label>
                                <input type="text" id="cofins" class="form-control" name="cofins" alt="decimal" value="<?= @$obj->_cofins; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>CSLL</label>
                                <input type="text" id="csll" class="form-control" name="csll" alt="decimal" value="<?= @$obj->_csll; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ISS</label>
                                <input type="text" id="iss" class="form-control" name="iss" alt="decimal" value="<?= @$obj->_iss; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Valor Base para Imposto</label>
                                <input type="text" id="valor_base" class="form-control" name="valor_base" alt="decimal" value="<?= @$obj->_valor_base; ?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default" style="display: none;">
                <div class="alert alert-info">
                    Carimbo
                </div>
                <div class="panel-body">
                    <div class="row">


                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Carimbo</label>
                                <textarea name="carimbo" id="carimbo" rows="5" cols="30"  ><?= @$obj->_carimbo; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <!--                <div>
                                        <label>Mini-Curriculo</label>
                                        <textarea name="curriculo" id="curriculo" rows="5" cols="50"  ><?= @$obj->_curriculo; ?></textarea>
                                    </div>-->


                </div>


            </div>
            <div class="panel panel-default">
                <div class="alert alert-info">
                    Ações
                </div>
                <div class="panel-body">
                   
                    <!--                <div>
                                        <label>Mini-Curriculo</label>
                                        <textarea name="curriculo" id="curriculo" rows="5" cols="50"  ><?= @$obj->_curriculo; ?></textarea>
                                    </div>-->

                    <div class="row">
                        <div class="col-lg-3">
                            <p>
                                <button class="btn btn-outline btn-success btn-sm" type="submit" name="btnEnviar"><i class="fa fa-floppy-o" aria-hidden="true"></i> Enviar</button>

                                <button class="btn btn-outline btn-danger btn-sm" type="reset" name="btnLimpar">Limpar</button>
                                <!--<button class="btn btn-outline btn-success btn-sm" type="button" id="btnVoltar" name="btnVoltar">Voltar</button>-->

                            </p>
                        </div>
                    </div>
                </div>


            </div>
            <!--<fieldset style="dislpay:block">-->

            <!--</div>-->
        </form>
    </div>

</div> <!-- Final da DIV content -->

<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script>
                                    function mascaraTelefone(campo) {

                                        function trata(valor, isOnBlur) {

                                            valor = valor.replace(/\D/g, "");
                                            valor = valor.replace(/^(\d{2})(\d)/g, "($1)$2");

                                            if (isOnBlur) {

                                                valor = valor.replace(/(\d)(\d{4})$/, "$1-$2");
                                            } else {

                                                valor = valor.replace(/(\d)(\d{3})$/, "$1-$2");
                                            }
                                            return valor;
                                        }

                                        campo.onkeypress = function (evt) {

                                            var code = (window.event) ? window.event.keyCode : evt.which;
                                            var valor = this.value

                                            if (code > 57 || (code < 48 && code != 8 && code != 0)) {
                                                return false;
                                            } else {
                                                this.value = trata(valor, false);
                                            }
                                        }

                                        campo.onblur = function () {

                                            var valor = this.value;
                                            if (valor.length < 13) {
                                                this.value = ""
                                            } else {
                                                this.value = trata(this.value, true);
                                            }
                                        }

                                        campo.maxLength = 14;
                                    }


</script>
<script type="text/javascript">
    mascaraTelefone(form_operador.txtTelefone);
    mascaraTelefone(form_operador.txtCelular);


    // NOVOS AUTOCOMPLETES.
    // A primeira coisa a definir é o nome da variável pra jogar no Jquery mais tarde
    // Url é a função que vai trazer o JSON.
    // getValue é onde se define o nome do campo que você quer que apareça na lista
    // Exemplo do getValue. Na função abaixo do CBOprofissionais, o Hamilton definiu value como o valor do CBO dos profissionais
    // Daí dentro da função list você define que match está enable, pra que ele possa verificar pelo texto que o cara digita
    // OnSelectItem event é a função responsável por jogar o ID que você quer no campo Hidden
    // getSelectedItemData(). Depois do ponto você coloca o campo que vai ser jogado no Hidden
    // Daí embaixo tem o Jquery padrão pra jogar o ID no campo Hidden

    var profissionais = {
        url: "<?= base_url() ?>index.php?c=autocomplete&m=cboprofissionais",
        getValue: "value",
        list: {
            onSelectItemEvent: function () {
                var value = $("#txtcbo").getSelectedItemData().id;

                $("#txtcboID").val(value).trigger("change");
            },
            match: {
                enabled: true
            },
            showAnimation: {
                type: "fade", //normal|slide|fade
                time: 200,
                callback: function () {}
            },
            hideAnimation: {
                type: "slide", //normal|slide|fade
                time: 200,
                callback: function () {}
            },
            maxNumberOfElements: 10,

        },
        theme: "bootstrap"
    };

    $("#txtcbo").easyAutocomplete(profissionais);
    // FINAL DO AUTOCOMPLETE NOVO. DEFINE AQUI O ID DO CAMPO ATRIBUIDO E A VARIVEL
    // 
    // 
    //


    // NOVOS AUTOCOMPLETES.
    // A primeira coisa a definir é o nome da variável pra jogar no Jquery mais tarde
    // Url é a função que vai trazer o JSON.
    // getValue é onde se define o nome do campo que você quer que apareça na lista
    // Exemplo do getValue. Na função abaixo do CBOprofissionais, o Hamilton definiu value como o valor do CBO dos profissionais
    // Daí dentro da função list você define que match está enable, pra que ele possa verificar pelo texto que o cara digita
    // OnSelectItem event é a função responsável por jogar o ID que você quer no campo Hidden
    // getSelectedItemData(). Depois do ponto você coloca o campo que vai ser jogado no Hidden
    // Daí embaixo tem o Jquery padrão pra jogar o ID no campo Hidden

    var cidade = {
        url: "<?= base_url() ?>index.php?c=autocomplete&m=cidade",
        getValue: "value",
        list: {
            onSelectItemEvent: function () {
                var value = $("#txtCidade").getSelectedItemData().id;

                $("#txtCidadeID").val(value).trigger("change");
            },
            match: {
                enabled: true
            },
            showAnimation: {
                type: "fade", //normal|slide|fade
                time: 200,
                callback: function () {}
            },
            hideAnimation: {
                type: "slide", //normal|slide|fade
                time: 200,
                callback: function () {}
            },
            maxNumberOfElements: 20,
        },
        theme: "bootstrap"
    };

    $("#txtCidade").easyAutocomplete(cidade);
    // FINAL DO AUTOCOMPLETE NOVO. DEFINE AQUI O ID DO CAMPO ATRIBUIDO E A VARIVEL


    $(function () {
        $('#tipo').change(function () {
            if ($(this).val()) {
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/classeportiposaidalistadescricao', {nome: $(this).val(), ajax: true}, function (j) {
                    options = '<option value=""></option>';
                    for (var c = 0; c < j.length; c++) {
                        options += '<option value="' + j[c].classe + '">' + j[c].classe + '</option>';
                    }
                    $('#classe').html(options).show();
                    $('.carregando').hide();
                });
            } else {
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/classeportiposaidalistadescricaotodos', {nome: $(this).val(), ajax: true}, function (j) {
                    options = '<option value=""></option>';
                    for (var c = 0; c < j.length; c++) {
                        options += '<option value="' + j[c].classe + '">' + j[c].classe + '</option>';
                    }
                    $('#classe').html(options).show();
                    $('.carregando').hide();
                });
            }
        });
    });

    $('#cep').mask('99999-999');

    $(document).ready(function () {

        function limpa_formulário_cep() {
            // Limpa valores do formulário de cep.
//            $("#rua").val("");
//            $("#bairro").val("");
//            $("#txtCidade").val("");
//            $("#uf").val("");
//            $("#ibge").val("");
        }

        //Quando o campo cep perde o foco.
        $("#cep").blur(function () {

            //Nova variável "cep" somente com dígitos.
            var cep = $(this).val().replace(/\D/g, '');

            //Verifica se campo cep possui valor informado.
            if (cep != "") {

                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;

                //Valida o formato do CEP.
                if (validacep.test(cep)) {

                    //Preenche os campos com "..." enquanto consulta webservice.
//                    $("#rua").val("Aguarde...");
//                    $("#bairro").val("Aguarde...");
//                    $("#txtCidade").val("Aguarde...");
//                    $("#uf").val("Aguarde...");

                    //Consulta o webservice viacep.com.br/
                    $.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                        if (!("erro" in dados)) {
                            //Atualiza os campos com os valores da consulta.
                            $("#rua").val(dados.logradouro);
                            $("#bairro").val(dados.bairro);
                            $("#txtCidade").val(dados.localidade);
                            $("#uf").val(dados.uf);
                            $("#ibge").val(dados.ibge);
                            $.getJSON('<?= base_url() ?>autocomplete/cidadeibge', {ibge: dados.ibge}, function (j) {
                                $("#txtCidade").val(j[0].value);
                                $("#txtCidadeID").val(j[0].id);

                            });

                        } //end if.
                        else {
                            //CEP pesquisado não foi encontrado.
                            limpa_formulário_cep();
//                            alert("CEP não encontrado.");

                            swal({
                                title: "Correios informa!",
                                text: "CEP não encontrado.",
                                imageUrl: "<?= base_url() ?>img/correios.png"
                            });
                        }
                    });

                } //end if.
                else {
                    //cep é inválido.
                    limpa_formulário_cep();
//                    alert("Formato de CEP inválido.");
                    swal({
                        title: "Correios informa!",
                        text: "Formato de CEP inválido.",
                        imageUrl: "<?= base_url() ?>img/correios.png"
                    });
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        });
    });


    tinyMCE.init({
        // General options
        mode: "textareas",
        theme: "advanced",
        plugins: "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",
        // Theme options
        theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,pagebreak,strikethrough,justifyleft,justifycenter,justifyright,justifyfull",
        theme_advanced_buttons2: "styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true,
        // Example content CSS (should be your site CSS)
        //                                    content_css : "css/content.css",
        content_css: "js/tinymce/jscripts/tiny_mce/themes/advanced/skins/default/img/content.css",
        // Drop lists for link/image/media/template dialogs
        template_external_list_url: "lists/template_list.js",
        external_link_list_url: "lists/link_list.js",
        external_image_list_url: "lists/image_list.js",
        media_external_list_url: "lists/media_list.js",
        // Style formats
        style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ],
        // Replace values for the template plugin
        template_replace_values: {
            username: "Some User",
            staffid: "991234"
        }

    });

</script>
<link href="<?= base_url() ?>css/cadastro/paciente-ficha.css" rel="stylesheet"/>
<div class="content ficha_ceatox"> <!-- Inicio da DIV content -->
    <form name="form_paciente" id="form_paciente" action="<?= base_url() ?>cadastros/pacientes/gravarinstituicao/" method="post">
        <!--        Chamando o Script para a Webcam   -->
        <script src="<?= base_url() ?>js/webcam.js"></script>
        <fieldset> 
            
           
            <?
            
            if (@$empresapermissoes[0]->campos_cadastro != '') {
                $campos_obrigatorios = json_decode(@$empresapermissoes[0]->campos_cadastro);
            } else {
                $campos_obrigatorios = array();
            }
            $tecnico_recepcao_editar = @$empresapermissoes[0]->tecnico_recepcao_editar;
            $perfil_id = $this->session->userdata('perfil_id');
//            echo'<pre>';
//            var_dump(@$empresapermissoes); die;
            ?>
            <div class="alert alert-info">Nova Instituição</div>
            <div class=" ">
                <div class="row">

                    <div class="col-lg-3">
                        
                        
                        <div class="form-group">
                            <label>Razão Social</label>
                            <input type="text" id="txtNome" name="nome" class="form-control texto05" value="<?= @$obj[0]->nome; ?>" required="true"  placeholder="Nome da Instituição">
                            <input type="hidden" id="txtinstituicao" name="instituicao_id" value="<?= $instituicao_id; ?>" >
                        </div>
                        <div>
                            <label>Endereço</label>
                                <input type="text" id="endereco" class="form-control texto05" name="endereco"  value="<?= @$obj[0]->endereco; ?>" required="true" placeholder="Endereço da Instituição" />
                        </div>
                        <div>
                            <label>Município</label>
                            <input type="hidden"  id="txtCidadeID" class="texto_id"  name="municipio_id" value="<?= @$obj[0]->municipio_id; ?>" readonly="true" />
                            <input type="text" id="txtCidade" class="form-control texto05 eac-square" value="<?= @$obj[0]->municipio; ?>" placeholder="Município" name="txtCidade" />

                        </div>
                        <div >
                            <label>Email</label>
                            <input  placeholder="Email" type="text"  placeholder="Email da Instituição" id="txtEmail" onchange="validaremail()" name="emailprincipal"  class="form-control texto05" value="<?= @$obj[0]->email; ?>" />
                             <?= (in_array('email', $campos_obrigatorios)) ? 'required' : '' ?>
                        </div >
                        <div>
                            <label>Email Alternativo</label>
                            <input type="text" id="txtemail_alternativo"  placeholder="Email alternativo da Instituição" onchange="validaremail_alternativo()" name="email_alternativo" class="form-control texto05" value="<?= @$obj[0]->email_alternativo; ?>"
                                <?= (in_array('email_alternativo', $campos_obrigatorios)) ? 'required' : '' ?>/>
                        </div>
                        <div>
                            <label>CNPJ</label>
                            <input type="text" placeholder="CNPJ" name="cnpj" id ="txtCnpj" onblur="verificarCNPJ();" maxlength="11" alt="cnpj" class="form-control texto05" value="<?= @$obj[0]->cnpj; ?>"/>
                            <?= (in_array('cnpj', $campos_obrigatorios)) ? 'required' : '' ?> 
                        </div>
                        <div>
                            <h6>CPF</h6>
                            <div>
                                <input type="text" name="cpf" id ="txtCpf" placeholder="CPF" onblur="verificarCPF();" maxlength="11" alt="cnpj" class="form-control texto05" value="<?= @$obj[0]->cpf; ?>" />
                                <?= (in_array('cnpj', $campos_obrigatorios)) ? 'required' : '' ?> 
                            </div>
                        </div>
                         <div class="col-lg-2">
                   
                      
                    </div>
                        

                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>Whatsapp</label>
                             <input type="text"  placeholder="(99)9999-9999" id="txtwhatsapp" name="whatsapp" class="form-control texto05" value="<?= @$obj[0]->whatsapp; ?>"

                        </div>
                        <div>
                            <td>
                                <div class="form-group">
                            <label>Telefone 1*</label>
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
                                ?>
                            
                            <input type="text"  placeholder="(99)9999-99999" id="txtTelefone" name="telefone" class="form-control texto05"  value="<?= @$obj[0]->telefone; ?>" <?= (in_array('telefone', $campos_obrigatorios)) ? 'required' : '' ?>/>
                            <button class="btn btn-outline-danger btn-sm" type=button id="btnWhats" onclick="pegarWhats();"> WP </button>
                        </div>
                            <div class="form-group">
                            <label>Telefone 2*</label>
                            <?
                            if (@$obj[0]->_telefone != '' && strlen(@$obj[0]->telefone) > 3) {

                                if (preg_match('/\(/', @$obj[0]->telefone)) {
                                    $telefone = @$obj[0]->telefone;
                                } else {
                                    $telefone = "(" . substr(@$obj[0]->telefone, 0, 2) . ")" . substr(@$obj[0]->telefone, 2, strlen(@$obj[0]->telefone) - 2);
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
                            if ( @$obj->_whatsapp != '' && strlen(@$obj[0]->whatsapp) > 3) {
                                if (preg_match('/\(/', @$obj->_whatsapp)) {
                                    $whatsapp =  @$obj->_whatsapp;
                                } else {
                                    $whatsapp = "(" . substr( @$obj->_whatsapp, 0, 2) . ")" . substr(@$obj->_whatsapp, 2, strlen( @$obj->_whatsapp) - 2);
                                }
                            } else {
                                $whatsapp = '';
                            }
                            ?>


                            <input type="text"  placeholder="(99)9999-9999" id="txtTelefone2" class="form-control texto05" name="telefone2"  value="<?= @$obj[0]->telefone; ?>" <?= (in_array('telefone2', $campos_obrigatorios)) ? 'required' : '' ?>/>
                            <button class="btn btn-outline-danger btn-sm" type=button id="btnWhats" onclick="pegarWhats();"> WP? </button>
                        </div>
                    </div>
                         <div>
                        <div class="form-group">
                            <label>Observação</label>
                            <input type="text" name="observacao" placeholder="Observações"  id="observações" class="form-control texto05" value="<?= @$obj[0]->observacao; ?>" />
                        </div>
                        <div class="form-group">
                            <label>Complemento</label>
                            <input type="text" name="complemento" placeholder="Complementos"  id="complemento" class="form-control texto05" value="<?= @$obj[0]->complemento; ?>" />
                        </div>
                    </div>
                    <div class="col-lg-3">

                        <div class="form-group">
                         <dd>
                        <select name="tipo_pessoa" id="tipo_pessoa" class="size" required="">

                            <option value="">Selecione</option>
                            <option <?=(@$obj->_tipo_pessoa == 'pessoa_f')? 'selected':'' ; ?> value="pessoa_f">Pessoa Física</option>
                            <option <?=(@$obj->_tipo_pessoa == 'pessoa_j')? 'selected':'' ; ?> value="pessoa_j">Pessoa Jurídica</option>

                        </select>
                    </dd>
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <!--<div class="modal-header">-->
                                        <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
                                        <!--<h4 class="modal-title" id="myModalLabel">Fotografia</h4>-->
                                    <!--</div>-->
                                    <div class="modal-body">
                                        <fieldset>
                                  
                                        </fieldset>
                                    </div>
                                    <div class="modal-footer">
                                        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                                        <a  onClick="imagem_paciente()" class="btn btn-primary" data-dismiss="modal">Fechar</a>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>

                        </div>
                      
                        <div class="col-lg-2">

                        </div>

        </fieldset>
                    
        <div class="panel-body socialdata">
            <div class="row">
                <div class="col-lg-2">
                </div>

                <div class="col-lg-2">
                </div>
            </div>
        </div>
    </div>

        <div class="panel panel-default btngrp">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-3">
                        <?if($tecnico_recepcao_editar == 't' || $perfil_id != 15){?>
                            <button type="submit" class="btn btn-success btn-sm">Enviar</button>
                            <button type="reset" class="btn btn-warning btn-sm">Limpar</button>
                        <?}?>

                        <a href="<?= base_url() ?>ambulatorio/modelolaudo/pesquisar">
                            <button type="button" id="btnVoltar" class="btn btn-secondary btn-sm">Voltar</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <script language="JavaScript">
        Webcam.set({
            width: 140,
            height: 160,
            dest_width: 480,
            dest_height: 360,
            image_format: 'jpeg',
            jpeg_quality: 100
        });
        function ativar_camera() {
            Webcam.attach('#my_camera');
        }
        function take_snapshot() {
            // tira a foto e gera uma imagem para a div
            Webcam.snap(function (data_uri) {
                // display results in page
                document.getElementById('results').innerHTML =
                        '<img height = "160" width = "140" src="' + data_uri + '"/>';
                //              Gera uma variável com o código binário em base 64 e joga numa variável
                var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
                //              Pega o valor do campo mydata, campo hidden que armazena temporariamente o código da imagem
                document.getElementById('mydata').value = raw_image_data;

            });
        }



    </script>

</div>



<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
<script>


$("#txtconvenio").change(function() {
    var $this, secao, atual, campos;
  
    campos = $("div[data-name]");
    
    campos.addClass("hide");

    if (this.value !== "") {
        secao = $('option[data-section][value="' + this.value + '"]', this).attr("data-section");
      
        atual = campos.filter("[data-name=" + secao + "]");
      
        if (atual.length !== 0) {
            atual.removeClass("hide");
        }
    }
});

$("#mostrarDadosExtras").click(function () {
            var botao = $("#mostrarDadosExtras").text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras").text('-');
            } else {
                $("#mostrarDadosExtras").text('+');
            }                                       
            $("#DadosExtras").toggle();

        });


        $(function () {
            $('#txtconvenio').change(function () {
                if ($(this).val()) {
//                                                alert('teste_parada');
                    $('.carregando').show();
//                                                  alert('teste_parada');
                    $.getJSON('<?= base_url() ?>autocomplete/tamanhoconvenio', {txtcbo: $(this).val(), ajax: true}, function (j) {

                        console.log(j);

                        for (var c = 0; c < j.length; c++) {


                            if (j[0].tamanho_carteira != undefined) {
                                options = '<input type="text" class="texto03" name="convenionumero"   maxlength="' + j[c].tamanho_carteira + '" value="<?= @$obj->_convenionumero; ?>" <?= (in_array('numero_carteira', $campos_obrigatorios)) ? 'required' : '' ?>>';

                            } else {
                                options = '<input  type="text" class="texto03" name="convenionumero"   value="<?= @$obj->_convenionumero; ?>" <?= (in_array('numero_carteira', $campos_obrigatorios)) ? 'required' : '' ?>></option>';
                            }


                        }
                        $('#numero_caracter').html(options).show();
                        $('.carregando').hide();



                    });
                }

            });
        });






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
    mascaraTelefone(form_paciente.txtTelefone);
    mascaraTelefone(form_paciente.txtwhatsapp);
    mascaraTelefone(form_paciente.txtCelular);
     $(function () {
         $("#vencimento_carteira").datepicker({
             autosize: true,
             changeYear: true,
             changeMonth: true,
             monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
             dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
             buttonImage: '<?= base_url() ?>img/form/date.png',
             dateFormat: 'dd/mm/yy'
         });
     });

    $("#vencimento_carteira").mask("99/99/9999");
    $("#vencimento_cnh").mask("99/99/9999");

    jQuery("#txtwhatsapp")
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

    jQuery("#txtTelefone2")
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
//(99) 9999-9999


    $(function () {
        $("#txtcbo").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=cboprofissionais",
            minLength: 6,
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


    $(function () {
        $('#txtSexo').change(function () {
//            alert($(this).val());
            if ($(this).val() == 'O') {
                $("#sexo_real_div").show();

            } else {
                $("#sexo_real_div").hide();
            }
        });
    });

    if ($('#txtSexo').val() == 'O') {
        $("#sexo_real_div").show();
    } else {
        $("#sexo_real_div").hide();
    }

    $(function () {
        $('#txtconvenio').change(function () {
            if ($(this).val()) {
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/conveniocarteira', {convenio1: $(this).val()}, function (j) {
                    options = '<option value=""></option>';
                    if (j[0].carteira_obrigatoria == 't') {
                        $("#txtconvenionumero").prop('required', true);
                    } else {
                        $("#txtconvenionumero").prop('required', false);
                    }

                });
            }
        });
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
        $("#txtEstado").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=estado",
            minLength: 2,
            focus: function (event, ui) {
                $("#txtEstado").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#txtEstado").val(ui.item.value);
                $("#txtEstadoID").val(ui.item.id);
                return false;
            }
        });
    });



    $(function () {
        $("#cep").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=cep",
            minLength: 3,
            focus: function (event, ui) {
                $("#cep").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#cep").val(ui.item.cep);
                $("#txtendereco").val(ui.item.logradouro_nome);
                $("#txtBairro").val(ui.item.nome_bairro);
//                        $("#txtCidade").val(ui.item.localidade_nome);
                $("#txtTipoLogradouro").val(ui.item.tipo_logradouro);

                return false;
            }
        });
    });

    function verificarCPF() {
        // txtCpf
        var cpf = $("#txtCpf").val();
        var paciente_id = $("#txtPacienteId").val();
        if($('#cpf_responsavel').prop('checked')){
            var cpf_responsavel = 'on';
        }else{
            var cpf_responsavel = '';
        }
        
        // alert(cpf_responsavel);
        $.getJSON('<?= base_url() ?>autocomplete/verificarcpfpaciente', {cpf: cpf, cpf_responsavel: cpf_responsavel, paciente_id: paciente_id,  ajax: true}, function (j) {
            if(j != ''){
                alert(j);
                $("#txtCpf").val('');
            }
        });
    }


    function validaremail(){
        var email = $("#txtCns").val();
        var email2 = $("#txtCns2").val();
        if(email != ''){
            $.getJSON('<?= base_url() ?>autocomplete/verificaremailpaciente', {email: email,  ajax: true}, function (j) {
                if(j != ''){
                    alert(j);
                    $("#txtCns").val('');
                }else if(email == email2){
                    alert('O E-mail não pode ser Igual ao E-mail Alternativo');
                    $("#txtCns2").val('');
                }
            });
        }
    }

    function validaremail2(){
        var email2 = $("#txtCns").val();
        var email = $("#txtCns2").val();
        if(email != ''){
            $.getJSON('<?= base_url() ?>autocomplete/verificaremailpaciente', {email: email,  ajax: true}, function (j) {
                if(j != ''){
                    alert(j);
                    $("#txtCns2").val('');
                }else if(email == email2){
                    alert('O E-mail Alternativo não pode ser Igual ao E-mail');
                    $("#txtCns2").val('');
                }
            });
        }
    }

    function verificarCnpj() {
        // txtCnpj
        var cnpj = $("#txtCnpj").val();
        var paciente_id = $("#txtPacienteId").val();
        var cnpj = '';

        
        // alert(cnpj);
        $.getJSON('<?= base_url() ?>autocomplete/verificarcnpjpaciente', {cnpj: cnpj, cnpj: cnpj, paciente_id: paciente_id,  ajax: true}, function (j) {
            if(j != ''){
                alert(j);
                $("#txtCnpj").val('');
            }
        });
    }

    $(document).ready(function () {

        function limpa_formulário_cep() {
            // Limpa valores do formulário de cep.
//            $("#rua").val("");
//            $("#bairro").val("");
//            $("#txtCidade").val("");
//            $("#uf").val("");
//            $("#ibge").val("");
        }
        $("#cep").mask("99999-999");
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
                            $("#txtendereco").val(dados.logradouro);
                            $("#txtBairro").val(dados.bairro);
                            $("#txtCidade").val(dados.localidade);
//                            $("#uf").val(dados.uf);
                            $("#ibge").val(dados.ibge);
                            $.getJSON('<?= base_url() ?>autocomplete/cidadeibge', {ibge: dados.ibge}, function (j) {
                                $("#txtCidade").val(j[0].value);
                                $("#txtCidadeID").val(j[0].id);

//                                console.log(j);
                            });
//                            console.log(dados);
//                            console.log(dados.bairro);

                        } //end if.
                        else {
                            //CEP pesquisado não foi encontrado.
//                            limpa_formulário_cep();
                            alert("CEP não encontrado.");

//                            swal({
//                                title: "Correios informa!",
//                                text: "CEP não encontrado.",
//                                imageUrl: "<?= base_url() ?>img/correios.png"
//                            });
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



    function calculoIdade() {
        var data = document.getElementById("txtNascimento").value;

        if (data != '' && data != '//') {

            var ano = data.substring(6, 12);
            var idade = new Date().getFullYear() - ano;
            // var mes = ;
            var dtAtual = new Date();
            var aniversario = new Date(dtAtual.getFullYear(), parseInt(data.substring(3, 5)) - 1, data.substring(0, 2));
            // alert(aniversario);
            // alert(dtAtual.getFullYear());
            if (dtAtual < aniversario) {
                idade--;
            }
//            if (idade <= 10) {
//                $("#cpf_responsavel_label").show();
//                $("#cpf_responsavel").prop('required', true);
//            } else {
//                $("#cpf_responsavel_label").hide();
//                $("#cpf_responsavel").prop('required', false);
//            }

            document.getElementById("idade2").value = idade + " ano(s)";
        } else {
//            $("#cpf_responsavel_label").hide();
//            $("#cpf_responsavel").prop('required', false);
        }
    }
    calculoIdade();

    function calculoidade_conjuge() {
        var data = document.getElementById("nascimento_conjuge").value;

        if (data != '' && data != '//') {

            var ano = data.substring(6, 12);
            var idade_conjuge = new Date().getFullYear() - ano;
            // var mes = ;
            var dtAtual = new Date();
            var aniversario = new Date(dtAtual.getFullYear(), parseInt(data.substring(3, 5)) - 1, data.substring(0, 2));
            // alert(aniversario);
            // alert(dtAtual.getFullYear());
            if (dtAtual < aniversario) {
                idade_conjuge--;
            }
//            if (idade_conjuge <= 10) {
//                $("#cpf_responsavel_label").show();
//                $("#cpf_responsavel").prop('required', true);
//            } else {
//                $("#cpf_responsavel_label").hide();
//                $("#cpf_responsavel").prop('required', false);
//            }

            document.getElementById("idade_conjuge").value = idade_conjuge + " ano(s)";
        } else {
//            $("#cpf_responsavel_label").hide();
//            $("#cpf_responsavel").prop('required', false);
        }
    }

    function pegarWhats(){
    document.getElementById("txtwhatsapp").value  = document.getElementById("txtTelefone").value;
    }
    function pegarWhats2(){
    document.getElementById("txtwhatsapp").value  = document.getElementById("txtTelefone2").value;
    }

    calculoidade_conjuge();

</script>

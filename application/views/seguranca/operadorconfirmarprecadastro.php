<?
        $recomendacao_obrigatorio = $this->session->userdata('recomendacao_obrigatorio');
        $empresa = $this->guia->listarempresapermissoes(1);
        $empresaPermissoes = $this->guia->listarempresapermissoes(1);
        $odontologia_alterar = $empresa[0]->odontologia_valor_alterar;
        $retorno_alterar = $empresa[0]->selecionar_retorno;
        $desabilitar_trava_retorno = $empresa[0]->desabilitar_trava_retorno;
        $logo_clinica = $this->session->userdata('logo_clinica');
        $empresa_id = $this->session->userdata('empresa_id');
        //var_dump($retorno_alterar); die;
        // var_dump($empresaPermissoes); die;
    ?>
    <head>
        <title>STG - SISTEMA DE GESTAO DE CLINICAS v1.0</title>
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <!-- Reset de CSS para garantir o funcionamento do layout em todos os brownsers -->
        <link href="<?= base_url() ?>css/reset.css" rel="stylesheet" type="text/css" />

        <link href="<?= base_url() ?>css/estilo.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>css/batepapo.css" rel="stylesheet" type="text/css" />

        <link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>js/fullcalendar/fullcalendar.css" rel="stylesheet" />
        <link href="<?= base_url() ?>js/fullcalendar/lib/cupertino/jquery-ui.min.css" rel="stylesheet" />

        <!--<script type="text/javascript" src="<?= base_url() ?>js/fullcalendar/lib/jquery.min.js"></script>-->
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>
        <!--<script type="text/javascript" src="<?= base_url() ?>js/fullcalendar/lib/jquery.min.js"></script>-->
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-cookie.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-treeview.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery.bestupper.min.js"  ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/scripts_alerta.js" ></script>
        <!--<script type="text/javascript" src="<?= base_url() ?>js/jquery.js" ></script>-->
        <script type="text/javascript" src="<?= base_url() ?>js/funcoes.js"></script>
    </head>
    <div class="header">
        <div id="imglogo">
            <a href="<?=base_url() . "home"?>">
                <img src="<?= base_url(); ?>img/stg - logo.jpg" alt="Logo"
                    title="Logo" height="70" id="Insert_logo"
                    style="display:block;" />
            </a>
        </div>

        <div id="login">
            <div id="user_info">
                <label style='font-family: serif; font-size: 8pt;'>Seja bem vindo, <?= @$cadastro[0]->nome; ?>! </label>
                <?
                $numLetras = strlen($this->session->userdata('empresa'));
                $css = ($numLetras > 20) ? 'font-size: 7pt' : '';
                ?>
                <label style='font-family: serif; font-size: 8pt;'>Empresa: <span style="<?= $css ?>"><?=$empresa[0]->razao_social;?></span></label>
            </div>


        </div>


        <? if ($logo_clinica == 't') { ?>
            <div id="imgLogoClinica">
                <img src="<?= base_url(); ?>upload/logomarca/<?= $empresa_id; ?>/logomarca.jpg" alt="Logo Clinica"
                        title="Logo Clinica" height="70" id="Insert_logo" />
            </div>
        <? } ?>
    </div>
    <div class="decoration_header">&nbsp;</div>


<style>
    .custom-combobox {
        position: relative;
        display: inline-block;
    }
    .custom-combobox-toggle {
        position: absolute;
        top: 0;
        bottom: 0;
        margin-left: -1px;
        padding: 0;
    }
    .custom-combobox-input {
        margin: 0;
        padding: 5px 10px;
        width: 50px;
    }
    .custom-combobox a {
        display: inline-block;        
    }
</style>
<script src="<?= base_url() ?>js/webcam.js"></script>
<script>
    (function ($) {
        $(function () {
            $('input:text').setMask();
        });
    })(jQuery);

</script>
<style>

    body {
        /*margin: 40px 10px;*/
        padding: 0;
        font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
        background-color: white;
    }
    .content{
        margin-left: 0px;
        margin-bottom:0px;
    }

    .singular table div.bt_link_new .btnTexto {color: #2779aa; }
    .singular table div.bt_link_new .btnTexto:hover{ color: red; font-weight: bolder;}
    .vermelho{
        color: red;
    }
    /*#pop{display:none;position:absolute;top:50%;left:50%;margin-left:-150px;margin-top:-100px;padding:10px;width:300px;height:200px;border:1px solid #d0d0d0}*/

</style>
<?php
$this->load->library('utilitario');
// var_dump($this->session->flashdata('message'));die;
//Utilitario::pmf_mensagem($this->session->flashdata('message'));
$utilitario = new Utilitario();
$utilitario->pmf_mensagem($this->session->flashdata('message'));
?>
<div class="content ficha_ceatox"> <!-- Inicio da DIV content -->
    <div class="bt_link_voltar">
        <a href="<?= base_url() ?>seguranca/operador">
            Voltar
        </a>
    </div>

    <h3 class="singular"><a href="#">Confirmar Cadastro</a></h3>
    <div>
        <form name="form_operador" id="form_operador" action="<?= base_url() ?>seguranca/operador/gravarconfirmarprecadastro/<?= @$cadastro[0]->pacientes_precadastro_id; ?>" method="post" style="margin-bottom: 50px;">
            <fieldset>
                <legend>Dados do Profissional</legend>
                <div>
                    <label>Nome *</label>                      
                    <input type ="hidden" name ="operador_id" value ="" id ="txtoperadorId" >
                    <input type="text" id="txtNome" name="nome"  class="texto09" value="<?= @$cadastro[0]->nome; ?>" required="true"/>
                </div>
                <div>
                    <label>Sexo *</label>


                    <select name="sexo" id="txtSexo" class="size2">
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

                <div>
                    <label>Nascimento</label>


                    <input type="text" name="nascimento" id="txtNascimento" class="texto02" alt="date" value="<?php echo substr(@$obj->_nascimento, 8, 2) . '/' . substr(@$obj->_nascimento, 5, 2) . '/' . substr(@$obj->_nascimento, 0, 4); ?>" onblur="retornaIdade()"/>
                </div>
                <div>
                    <label>Conselho</label>
                    <input type="text" id="txtconselho" name="conselho"  class="texto04" value="<?= @$cadastro[0]->crm; ?>" />
                </div>
                <div>
                    <label>Cor do Mapa Cirúrgico</label>
                    <input type="color" id="txtcolor" name="txtcolor"  class="texto04" value="<?= @$obj->_cor_mapa; ?>" />
                </div>
                <div>
                    <label>CPF *</label>


                    <input type="text" name="cpf" id ="txtCpf" maxlength="11" alt="cpf" class="texto02" value="<?= @$obj->_cpf; ?>" required />
                </div>
                <div>
                    <label>Ocupa&ccedil;&atilde;o</label>
                    <input type="hidden" id="txtcboID" class="texto_id" name="txtcboID" value="<?= @$obj->_cbo_ocupacao_id; ?>" readonly="true" />
                    <input type="text" id="txtcbo" class="texto04" name="txtcbo" value="<?= @$obj->_cbo_nome; ?>" />
                </div>


                <?
                if (@$empresapermissao[0]->cirugico_manual == 't') {
                    ?>
                    <div>
                        <label>Sigla do Conselho</label>  
                        <select name="siglaconselho" id="siglaconselho" > 
                            <option value="" >Escolha</option>
                            <?
                            foreach ($listarsigla as $item) {
                                ?>
                                <option value="<?= $item->sigla_id; ?>" 
                                <?
                                if (@$obj->_sigla_id == $item->sigla_id) {
                                    echo "Selected";
                                } else {
                                    
                                }
                                ?>
                                        title="<?= $item->nome ?>"><?= $item->nome ?></option>
                                        <?
                                    }
                                    ?> 
                        </select>
                    </div> 
                    <div>
                        <label title="UF">UF</label> 
                        <input type="text" name="uf_profissional" id="uf_profissional" value="<?= @$obj->_uf_profissional ?>" class="size1"  max>
                    </div>
                <? } ?>

            </fieldset>
            <fieldset>
                <legend>Domicilio</legend>

                <div>
                    <label>T. logradouro</label>


                    <select name="tipo_logradouro" id="txtTipoLogradouro" class="size2" >
                        <option value='' >selecione</option>
                        <?php
                        $listaLogradouro = $this->paciente->listaTipoLogradouro($_GET);
                        foreach ($listaLogradouro as $item) {
                            ?>
                            <option   value =<?php echo $item->tipo_logradouro_id; ?> 
                            <?
                            if (@$obj->_tipoLogradouro == $item->tipo_logradouro_id):echo 'selected';
                            endif;
                            ?>><?php echo $item->descricao; ?></option>
                                  <?php } ?> 
                    </select>
                </div>
                <div>
                    <label>Endere&ccedil;o</label>


                    <input type="text" id="txtendereco" class="texto10" name="endereco" value="<?= @$obj->_logradouro; ?>" />
                </div>
                <div>
                    <label>N&uacute;mero</label>


                    <input type="text" id="txtNumero" class="texto02" name="numero" value="<?= @$obj->_numero; ?>" />
                </div>
                <div>
                    <label>Bairro</label>


                    <input type="text" id="txtBairro" class="texto03" name="bairro" value="<?= @$obj->_bairro; ?>" />
                </div>
                <div>
                    <label>Complemento</label>


                    <input type="text" id="txtComplemento" class="texto06" name="complemento" value="<?= @$obj->_complemento; ?>" />
                </div>

                <div>
                    <label>Município</label>


                    <input type="hidden" id="txtCidadeID" class="texto_id" name="municipio_id" value="<?= @$cadastro[0]->municipio_id; ?>" readonly="true" />
                    <input type="text" id="txtCidade" class="texto04" name="txtCidade" value="<?= @$cadastro[0]->municipio; ?>" />
                </div>
                <div>
                    <label>CEP</label>


                    <input type="text" id="txtCep" class="texto02" name="cep" alt="cep" value="<?= @$obj->_cep; ?>" />
                </div>
                <?
                
                if (@$cadastro[0]->telefone != '' && strlen(@$cadastro[0]->telefone) > 3) {
                    if (preg_match('/\(/', @$cadastro[0]->telefone)) {
                        $celular = @$cadastro[0]->telefone;
                    } else {
                        $celular = "(" . substr(@$cadastro[0]->telefone, 0, 2) . ")" . substr(@$cadastro[0]->telefone, 2, strlen(@$cadastro[0]->telefone) - 2);
                    }
                } else {
                    $celular = '';
                }
                // var_dump($celular); die;
                ?>

                <div>
                    <label>Telefone</label>


                    <input type="text" id="txtTelefone" class="texto02" name="telefone"  value="" />
                </div>
                <div>
                    <label>Celular *</label>


                    <input type="text" id="txtCelular" class="texto02" name="celular" value="<?= @$cadastro[0]->telefone; ?>" required="true"/>
                </div>
                <div>
                    <label>E-mail *</label>
                    <input type="text" id="txtemail" class="texto06" name="email" value="<?= @$cadastro[0]->email; ?>" />
                </div>
            </fieldset>
            <fieldset>
                <legend>Acesso</legend>
                <div>
                    <label>Nome usu&aacute;rio *</label>

                    <input type="text" id="txtUsuario" name="txtUsuario"  class="texto04" value="<?= @$obj->_usuario; ?>" required="true"/>
                </div>
                <div>
                    <label>Senha: *</label>
                    <input type="password" name="txtSenha" id="txtSenha" class="texto04" value="" <? if (@$obj->_senha == null) {
                    ?>
                               required="true"
                           <? } ?> />

                    <!--                    <label>Confirme a Senha: *</label>
                                        <input type="password" name="verificador" id="txtSenha" class="texto04" value="" onblur="confirmaSenha(this)"/>-->
                </div>
                <div>
                    <label>Tipo perfil *</label>

                    <select name="txtPerfil" id="txtPerfil" class="size4" required="true">
                        <option value="4">Médico</option>
                        
                    </select>
                </div>

            </fieldset>
            <? if (@$empresapermissao[0]->profissional_externo == 't') { ?>
                <fieldset>
                    <legend>Sistema Externo</legend>
                    <div>
                        <label >Endereço (Ex:http://stgclinica.ddns.net/stgsaude)</label>

                        <input type="text" id="endereco_sistema" name="endereco_sistema"  class="texto08" value="<?= @$obj->_endereco_sistema; ?>" />
                    </div>



                </fieldset>
            <? }
            ?>
            <fieldset style="display:block">

                <button type="submit" name="btnEnviar">Enviar</button>

                <button type="reset" name="btnLimpar">Limpar</button>
                <button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
            </fieldset>
        </form>
        <?php
        if (@$obj->_perfil_id == 4 || @$obj->_perfil_id == 19 || @$obj->_perfil_id == 22 || @$obj->_perfil_id == 1) {
            if (count($documentos > 0)) {
                ?>
                <fieldset style="display:block">
                    <legend>Documentação Profissional</legend>
                    <table border='2'>
                        <thead>
                            <tr>
                                <th  class="tabela_header">Nome</th>
                                <th  class="tabela_header">Possui</th>
                            </tr>
                        </thead>
                        <?php
                        foreach ($documentos as $item) {
                            $arquivo_pasta = directory_map("./upload/arquivosoperador/$obj->_operador_id/$item->documentacao_profissional_id");
                            if ($arquivo_pasta != false) {
                                sort($arquivo_pasta);
                            }
                            $i = 0;
                            if ($arquivo_pasta != false) {
                                foreach ($arquivo_pasta as $value) {
                                    @$verificardoc{$item->documentacao_profissional_id} ++;
                                    continue;
                                }
                            }
                        }


                        $estilo_linha = "tabela_content01";
                        foreach ($documentos as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->nome ?></td>
                                <td class="<?php echo $estilo_linha; ?>"> <?php
                if (@$verificardoc{$item->documentacao_profissional_id} > 0) {
                    echo "sim";
                } else {
                    echo "não";
                }
                            ?> </td>
                            </tr>
                                    <?
                                }
                                ?>


                    </table>
                </fieldset>        
                <br><br>
        <?php
    }
}
?>





    </div>

</div> <!-- Final da DIV content -->
<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script>
                     


</script>
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
        $(location).attr('href', '<?= base_url(); ?>sca/operador');
    });

    function confirmaSenha(verificacao) {
        var senha = $("#txtSenha");
        if (verificacao.value != senha.val()) {
            verificacao.setCustomValidity("As senhas não correspondem!");
        } else {
            verificacao.setCustomValidity("");
        }
    }

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


    function frm_number_only_exc() {
// allowed: numeric keys, numeric numpad keys, backspace, del and delete keys
        if (event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || (event.keyCode < 106 && event.keyCode > 95)) {
            return true;
        } else {
            return false;
        }
    }

    $(document).ready(function () {

        $("input#cod_cnes_prof").keydown(function (event) {

            if (frm_number_only_exc()) {

            } else {
                if (event.keyCode < 48 || event.keyCode > 57) {
                    event.preventDefault();
                }
            }
        });

    });

</script>
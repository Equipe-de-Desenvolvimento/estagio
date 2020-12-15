

<meta charset="utf-8">
<title>Pré-cadastro</title>
<link rel="stylesheet" href="<?= base_url() ?>css/estilo.css">
<link rel="stylesheet" href="<?= base_url() ?>css/form.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
 <script type="text/javascript" src="<?= base_url() ?>js/funcoes.js"></script>
     <link href="<?= base_url() ?>css/alertasuporte.css" rel="stylesheet" type="text/css" />
      <script type="text/javascript" src="<?= base_url() ?>js/scripts_alerta.js" ></script>
<style>
    #form_paciente{
  
    }
    .ficha_ceatox div{
        float: none;
    }
    .ficha_ceatox{
        margin-left: 300px;
        margin-right: 300px;
    }
    #sucesso_precadastro{
        color:green;
        font-weight: normal;
    }
</style>
<?php
    $this->load->library('utilitario');         
    //Utilitario::pmf_mensagem($this->session->flashdata('message'));    
    $utilitario = new Utilitario();
    $utilitario->pmf_mensagem($this->session->flashdata('message'));
  ?>
<div style="" class="content ficha_ceatox"> <!-- Inicio da DIV content -->
    
          <?// form_open_multipart(base_url() . 'cadastros/pacientes/gravarprecadastro'); ?>
          <form method="POST" action="<?=base_url() . 'cadastros/pacientes/gravarprecadastro'?>"  enctype="multipart/form-data">
        <!--        Chamando o Script para a Webcam   -->
        <script src="<?= base_url() ?>js/webcam.js"></script>
        <fieldset>
            <div id="mensagem_aviso">
                <?php 
                  if ($this->session->userdata('precadsatro') != "") {
                      echo $this->session->userdata('precadsatro');
                      $this->session->set_userdata('precadsatro','');
                  }
                ?>
            </div>

            <legend>Pré-cadastro</legend>
            <div>
                <label>Nome completo*</label>                       
                <input type="text" id="txtNome" name="nome" class="texto10"  value="" required="true" /> 
            </div>
            <div>
                <label>DDD + Telefone*</label>
                <input type="text" id="txtTelefone" class="texto03" name="telefone"  value="" required="true" />
            </div>

            <div>
                <label>Email</label>
                <input type="text" id="txtEmail" class="texto10" name="email"  value="" required/>
            </div>
            <div>
                <label>Município onde Mora</label> 
                <input type="hidden" id="txtCidadeID" class="texto_id" name="municipio_id" value="" readonly="true"/>
                <input type="text" id="txtCidade" class="texto04" name="txtCidade" value="" required/>
            </div>
            <div style="display: flex;">
                <div >
                    <label>CRM completo</label>
                    <input type="text" id="txtCrm" class="texto04" name="txtCrm"  value="" required/>
                </div>
                <div>
                    <label>Estado CRM</label>
                    <input type="text" id="crm_estado" class="texto02" name="crm_estado"  value=""/>
                </div>
            </div>
            
            
            <div>
                <label>Instituição onde realizou Residência/Aperfeiçoamento</label>
                <input type="text" id="txtInstituResid" class="texto10" name="txtInstituResid"  value=""/>
            </div>
            <div>
                <label>Título do especialista</label>
                <table border="0">
                    <tr>
                        <td style="width: 3%;">Sim</td>
                        <td  style="width: 3%;"><input type="radio" id="txtTitulo_especialistaOp" class="texto2" name="txtTitulo_especialistaOp"  value="sim"/></td>
                        <td style="width: 3%;">Não</td>
                        <td  ><input type="radio" id="txtTitulo_especialistaOp" class="texto2" name="txtTitulo_especialistaOp"  value="nao"/></td>
                    </tr>
                </table>
                <div id="simselecionado">
                    <label>Qual a instituição?</label>
                    <input type="text" id="txtTitulo_especialista" class="texto10" name="txtTitulo_especialista"  value=""/>
                    <label>Área</label>
                    <input type="text" id="area_especialista" class="texto10" name="area_especialista"  value=""/>
                </div>
            </div>
            
            <div>
                <label>Subespecialização?</label>
                <table border="0">
                    <tr>
                        <td style="width: 3%;">Sim</td>
                        <td  style="width: 3%;"><input type="radio" id="txtSubespeciOp" class="texto2" name="txtSubespeciOp"  value="sim"/></td>
                        <td style="width: 3%;">Não</td>
                        <td  ><input type="radio" id="txtSubespeciOp" class="texto2" name="txtSubespeciOp"  value="nao"/></td>
                    </tr>
                </table>
                <div id="simselecionadosub">
                    <label>Qual a instituição?</label>
                    <input type="text" id="txtSubespeci" class="texto10" name="txtSubespeci"  value=""/>
                    <label>Área</label>
                    <input type="text" id="area_subesp" class="texto10" name="area_subesp"  value=""/>
                </div>
            </div>
            <!-- <br> -->
            <div>
                <label>Mestrado?</label>
                <table border="0" >
                    <tr >
                        <td style="width: 3%;">Sim</td>
                        <td  style="width: 3%;"><input type="radio"  id="txtMestradoOp" class="texto2" name="txtMestradoOp"  value="sim"/></td>
                        <td style="width: 3%;">Não</td>
                        <td  ><input type="radio" id="txtMestradoOp" class="texto2" name="txtMestradoOp"  value="nao"/></td>
                    </tr>
                </table>
                <div id="simselecionadomestrado">
                    <label>Qual a instituição?</label>
                    <input type="text" id="txtMestrado" class="texto10" name="txtMestrado"  value=""/>
                    <label>Área</label>
                    <input type="text" id="area_mestrado" class="texto10" name="area_mestrado"  value=""/>
                </div>
            </div>
            <div>
                <label>Doutorado?</label>
                <table border="0" >
                    <tr>
                        <td style="width: 3%;">Sim</td>
                        <td  style="width: 3%;"><input type="radio"  id="txtDoutoradoOp" class="texto2" name="txtDoutoradoOp"  value="sim"/></td>
                        <td style="width: 3%;">Não</td>
                        <td  ><input type="radio" id="txtDoutoradoOp" class="texto2" name="txtDoutoradoOp"  value="nao"/></td>
                    </tr>
                </table>
                <div id="simselecionadodoutorado">
                    <label>Qual a instituição?</label>
                    <input type="text" id="txtDoutorado" class="texto10" name="txtDoutorado"  value=""/>
                    <label>Área</label>
                    <input type="text" id="area_doutorado" class="texto10" name="area_doutorado"  value=""/>
                </div>
            </div>
            <div>
            <label>Disponibilidade de atendimento</label>
            <input type="radio" value="Beneficente/Gratuito" name="disponibilidade_atendimento" />Beneficente/Gratuito
            <input type="radio" value="Particular" name="disponibilidade_atendimento" />Particular
            <input type="radio" value="Convenio" name="disponibilidade_atendimento" />Convênio
            </div>
            <div>
                <label>Outros</label>
                <textarea name="outros" class="texto10"></textarea>
            </div>
            
             <div>
                <label>Anexar Carteira profissional</label>
             <input type="file" multiple="" name="arquivos[]" required/>
            </div>
            
        </fieldset>
        <button type="submit">Enviar</button>
        <button type="reset">Limpar</button>
<!--        <a href="<?= base_url() ?>cadastros/pacientes">
     <button type="button" id="btnVoltar">Voltar</button>
 </a>-->
    </form>
   <?// form_close(); ?>  
 
</div>
 
<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript">
         $(function () {
        $("#accordion").accordion();
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

    $(document).ready(function () {
        $("#simselecionado").hide();
    });
    $("input[name=txtTitulo_especialistaOp]").on("change", function () {

        if ($(this).val() == "sim") {
            $("#simselecionado").show();
        } else if ($(this).val() == "nao") {
            $("#simselecionado").hide();
        }
    });

    $(document).ready(function () {
        $("#simselecionadosub").hide();
    });
    $("input[id=txtSubespeciOp]").on("change", function () {
        if ($(this).val() == "sim") {
            $("#simselecionadosub").show();
        } else if ($(this).val() == "nao") {
            $("#simselecionadosub").hide();
        }
    });

    $(document).ready(function () {
        $("#simselecionadomestrado").hide();
    });
    $("input[id=txtMestradoOp]").on("change", function () {
        if ($(this).val() == "sim") {
            $("#simselecionadomestrado").show();
        } else if ($(this).val() == "nao") {
            $("#simselecionadomestrado").hide();
        }
    });
    $(document).ready(function () {
        $("#simselecionadodoutorado").hide();
    });
    $("input[id=txtDoutoradoOp]").on("change", function () {
        if ($(this).val() == "sim") {
            $("#simselecionadodoutorado").show();
        } else if ($(this).val() == "nao") {
            $("#simselecionadodoutorado").hide();
        }
    });
    
</script>




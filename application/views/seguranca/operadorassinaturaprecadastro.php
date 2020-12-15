<?
        $recomendacao_obrigatorio = $this->session->userdata('recomendacao_obrigatorio');
        $empresa = $this->guia->listarempresapermissoes(1);
        $empresaPermissoes = $this->guia->listarempresapermissoes(1);
        $odontologia_alterar = $empresa[0]->odontologia_valor_alterar;
        $retorno_alterar = $empresa[0]->selecionar_retorno;
        $desabilitar_trava_retorno = $empresa[0]->desabilitar_trava_retorno;
        $logo_clinica = $this->session->userdata('logo_clinica');
        $empresa_id = $this->session->userdata('empresa_id');
       
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
<?
$convenios = $this->convenio->listarconvenionaodinheiro();
?>
<div class="content  "> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3><a href="#">Carregar Arquivo</a></h3>
        <div >
            <?// form_open_multipart(base_url() . 'seguranca/operador/importararquivooperadorprecadastro'); ?>
            <form method="POST" action="<?=base_url() . 'seguranca/operador/importararquivooperadorprecadastro'?>" enctype="multipart/form-data">
            <table>                
                <label>Informe o arquivo para importa&ccedil;&atilde;o</label><br>
                <input type="file" multiple="" name="arquivos[]"/>
                <select name="tipoarquivo" required="">
                    <option value="">Selecione</option>
                    <?php
                    foreach ($documentos as $item) {
                        ?>
                        <option value="<?= $item->documentacao_profissional_id; ?>"><?= $item->nome; ?></option>
                        <?
                    }
                    ?>
                </select> <br><br>
                <button type="submit" name="btnEnviar">Enviar</button>
                <input type="hidden" name="operador_id" value="<?= $operador_id; ?>" />
            </table>
            </form>
            <?// form_close(); ?>  
        </div>

        <h3><a href="#">Vizualizar Arquivos </a></h3>
        <div>
            <table>
                <tr>
                    <?
                    foreach ($documentos as $item) {
                        ?> 
                <tr>
                    <td style="color:white;background-color: blue;" ><?=  $item->nome; ?></td>
                </tr> 
                    <tr>                       
                        <?
                        $arquivo_pasta = directory_map("./upload/arquivosoperador/$operador_id/$item->documentacao_profissional_id");
                        if ($arquivo_pasta != false) {
                            sort($arquivo_pasta);
                        }
                        $i = 0;
                        if ($arquivo_pasta != false) {
                            foreach ($arquivo_pasta as $value) {
                                $i++;
                                ?> 
                                <td width="10px"><img  width="50px" height="50px" onclick="javascript:window.open('<?= base_url() . "upload/arquivosoperador/" . $operador_id . "/" . $item->documentacao_profissional_id . "/" . $value ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');" src="<?= base_url() . "img/paste_on.gif" ?>"><br><? echo substr($value, 0, 10) ?><br><a href="<?= base_url() ?>seguranca/operador/excluirimagem/<?= @$operador_id ?>/<?= $item->documentacao_profissional_id; ?>/<?= $value ?>">Excluir</a></td>
                                <?
                                if ($i == 8) {
                                    ?>
                                <!--</tr><tr>-->
                                    <?
                                }
                            }
                        }
                        ?>
                    </tr>
                    <?
                }
                ?>
            </table>
        </div> <!-- Final da DIV content -->
 
    </div> <!-- Final da DIV content -->
    
</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });



</script>

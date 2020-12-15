<html>
    <?
        $recomendacao_obrigatorio = $this->session->userdata('recomendacao_obrigatorio');
        $empresa = $this->guia->listarempresapermissoes();
        $empresaPermissoes = $this->guia->listarempresapermissoes();
        $odontologia_alterar = $empresa[0]->odontologia_valor_alterar;
        $retorno_alterar = $empresa[0]->selecionar_retorno;
        $desabilitar_trava_retorno = $empresa[0]->desabilitar_trava_retorno;
        $logo_clinica = $this->session->userdata('logo_clinica');
        $empresa_id = $this->session->userdata('empresa_id');
        //var_dump($retorno_alterar); die;
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
                <label style='font-family: serif; font-size: 8pt;'>Seja bem vindo <?= $this->session->userdata('login'); ?>! </label>
                <?
                $numLetras = strlen($this->session->userdata('empresa'));
                $css = ($numLetras > 20) ? 'font-size: 7pt' : '';
                ?>
                <label style='font-family: serif; font-size: 8pt;'>Empresa: <span style="<?= $css ?>"><?= $this->session->userdata('empresa'); ?></span></label>
            </div>
            <div id="login_controles">

                <!--<a href="#" alt="Alterar senha" id="login_pass">Alterar Senha</a>-->

                <a id="login_sair" title="Sair do Sistema" onclick="javascript: return confirm('Deseja realmente sair da aplicação?');"
                    href="<?= base_url() ?>login/sair">Sair</a>

                
            </div>
            <!--<div id="user_foto">Imagem</div>-->

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
    $utilitario = new Utilitario();
    $utilitario->pmf_mensagem($this->session->flashdata('message'));
?>

<div class="content"> <!-- Inicio da DIV content -->
    <div class="bt_link_new">
        <a href="<?php echo base_url() ?>ambulatorio/modelorelatorio/carregarmodelorelatorio/0">
            Novo Modelo
        </a>
    </div>
    <div id="accordion">
        <h3 class="singular"><a href="#">Modelos Relatorios</a></h3>
        <div>
            <table>
                <thead>
                    <tr>
                        <th colspan="5" class="tabela_title">
                            <form method="get" action="<?= base_url() ?>ambulatorio/modelorelatorio/pesquisar">
                                <input type="text" name="nome" class="texto10 bestupper" value="<?php echo @$_GET['nome']; ?>" />
                                <button type="submit" id="enviar">Pesquisar</button>
                            </form>
                        </th>
                    </tr>
                    <tr>
                        <th class="tabela_header">Nome</th>
                        <!-- <th class="tabela_header">Medico</th> -->
                        <th class="tabela_header" colspan="2">Detalhes</th>
                    </tr>
                </thead>
                <?php
                    $url      = $this->utilitario->build_query_params(current_url(), $_GET);
                    $consulta = $this->modelorelatorio->listar($_GET);
                    $total    = $consulta->count_all_results();
                    $limit    = 10;
                    isset ($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                    if ($total > 0) {
                ?>
                <tbody>
                    <?php
                        $lista = $this->modelorelatorio->listar($_GET)->orderby('nome')->limit($limit, $pagina)->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                     ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->nome; ?></td>
                                <!-- <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td> -->
                                <!-- <?if ($item->carregar_automaticamente != 't') { ?>
                                    <td class="<?php echo $estilo_linha; ?>" width="100px;">
                                        <a href="<?= base_url() ?>ambulatorio/modelosolicitarexames/ativarmodeloexameautomatico/<?= $item->ambulatorio_modelo_solicitar_exames_id ?>">
                                            Ativar
                                        </a>
                                    </td>
                                <? } else { ?>
                                    <td class="<?php echo $estilo_linha; ?>" width="100px;">
                                        <a href="<?= base_url() ?>ambulatorio/modelosolicitarexames/desativarmodeloexameautomatico/<?= $item->ambulatorio_modelo_solicitar_exames_id ?>">
                                            Desativar
                                        </a>
                                    </td>
                                <? } ?> -->
                                <td class="<?php echo $estilo_linha; ?>" width="100px;">
                                  
                                    <a href="<?= base_url() ?>ambulatorio/modelorelatorio/carregarmodelorelatorio/<?= $item->ambulatorio_modelo_relatorio_id ?>">
 editar
                                    </a>
                                </td>
                        </tr>

                        </tbody>
                        <?php
                                }
                            }
                        ?>
                        <tfoot>
                            <tr>
                                <th class="tabela_footer" colspan="6">
                                   <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total; ?>
                                </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function() {
        $( "#accordion" ).accordion();
    });

</script>


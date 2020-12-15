<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="pt-BR" >
    <head>
        <title>STG CLINICAS v2.0</title>
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <link href="<?= base_url() ?>bootstrap/vendor/bootstrap/css/bootstrap.css" rel="stylesheet" />
        <link href="<?= base_url() ?>bootstrap/vendor/metisMenu/metisMenu.css" rel="stylesheet" />
        <link href="<?= base_url() ?>bootstrap/assets/css/argon-design-system.css" rel="stylesheet" />
        <link href="<?= base_url() ?>bootstrap/assets/css/font-awesome.css" rel="stylesheet" />
        <link href="<?= base_url() ?>bootstrap/assets/css/all.css" rel="stylesheet" />
        <link href="<?= base_url() ?>bootstrap/vendor/alert/dist/sweetalert.css" rel="stylesheet" type="text/css" />
        <script src="<?= base_url() ?>bootstrap/vendor/jquery/jquery.js"></script>
        <script src="<?= base_url() ?>bootstrap/dist/js/bootstrap.js"></script>
        <script src="<?= base_url() ?>bootstrap/vendor/jquery/jquery.min.js"></script>
         <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">


    </head>
    <?
    $this->db->select('ep.*');
    $this->db->from('tb_empresa_permissoes ep');
    $this->db->orderby('ep.empresa_id');
    $retorno_header = $this->db->get()->result();
    $precadastro = @$retorno_header[0]->precadastro;
    ?>




    <style>
        #bodylogin{
            background-image: url(<?= base_url() ?>img/destaque.jpg);
            background-repeat: no-repeat;
            background-position: 30% 48%;

        }
    </style>
    <body id="bodylogin">
        <div class="container">
            <br>
                <div class="row">
                    <div class="col-xs-12" style="text-align: center;">
                        <!--<div class="panel" style="text-align: center;">-->
                        <img src="<?= base_url() ?>img/logo.png" width="140" height="100" alt="stg - logo"/>

                        <!--</div>--> 
                    </div>
                </div>
                <div class="row">

                </div>
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">

                        <div class="login-panel panel panel-default">

                            <div class=" alert alert-info text-center">

                                <span>
                                    <i class="fas fa-lock fa-fw fa-3x text-right" id="cadeado1" aria-hidden="true"></i>
                                    <i class="fas fa-unlock fa-fw fa-3x text-right" style="display: none;" id="cadeado1d" aria-hidden="true"></i>

                                </span>
                                <span>

                                    <i class="fas fa-lock fa-fw fa-3x text-right" id="cadeado2" aria-hidden="true"></i>
                                    <i class="fas fa-unlock fa-fw fa-3x text-right" style="display: none;" id="cadeado2d" aria-hidden="true"></i>

                                </span>
<!--                                <span>
                                    <i class="fa fa-lock fa-fw fa-3x text-right" id="cadeado3" aria-hidden="true"></i>
                                    <i class="fa fa-unlock fa-fw fa-3x text-right" style="display: none;" id="cadeado3d" aria-hidden="true"></i>
                                </span>-->



                            </div>
                            <div class="panel-body">
                                <form role="form" action="<?= base_url() ?>login/autenticar" method="post">

                                    <fieldset>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Usuario" name="txtLogin" id="txtLogin" type="text" value="<?= @$obj->_login; ?>" autofocus required>
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Senha" name="txtSenha" id="txtSenha" type="password" value="<?= @$obj->_senha; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <select  name="txtempresa" id="txtempresa" class="size08" >
                                                <? if (count($empresa) > 1) { ?>
                                                    <option value="">Selecione</option>
                                                <? } ?>
                                                <? foreach ($empresa as $item) : ?>
                                                    <option value="<?= $item->empresa_id; ?>"><?= $item->nome; ?></option>
                                                <? endforeach; ?>
                                            </select>

                                        </div>

                                        <br>
                                            <!-- Change this to a button or input when using this as a form -->
                                            <button type="submit" class="btn btn-lg btn-info btn-block">Login</button>
                                            <!--<a href="index.html" class="btn btn-lg btn-success btn-block">Login</a>-->
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </div>


    </body>


</html>


<script type="text/javascript" type="<?= base_url() ?>bootstrap/vendor/jquery/jquery.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>bootstrap/vendor/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>bootstrap/vendor/metisMenu/metisMenu.min.js"></script>
<!--<script type="text/javascript" src="--><?//= base_url() ?><!--bootstrap/dist/js/sb-admin-2.js"></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.12.4.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
<script src="<?= base_url() ?>bootstrap/vendor/alert/dist/sweetalert.min.js"></script> 



<script type="text/javascript">

    $("#Txtcpf").mask("999.999.999-99");



    $(function () {
        $('#txtLogin').change(function () {
            if ($(this).val()) {
                $("#cadeado1").hide("fast", "linear");
                $("#cadeado1d").fadeToggle("fast", "linear");
            } else {
                $("#cadeado1d").hide("fast", "linear");
                $("#cadeado1").fadeToggle("fast", "linear");
            }
        });
    });

    $(function () {
        $('#txtSenha').change(function () {
            if ($(this).val()) {
                $("#cadeado2").hide("fast", "linear");
                $("#cadeado2d").fadeToggle("fast", "linear");
            } else {
                $("#cadeado2d").hide("fast", "linear");
                $("#cadeado2").fadeToggle("fast", "linear");
            }
        });
    });

    $(function () {
        $('#Txtcpf').change(function () {
            if ($(this).val()) {
                $("#cadeado3").hide("fast", "linear");
                $("#cadeado3d").fadeToggle("fast", "linear");
            } else {
                $("#cadeado3d").hide("fast", "linear");
                $("#cadeado3").fadeToggle("fast", "linear");
            }
        });
    });


</script>

<?php
if (strlen($mensagem)) {
    if($mensagem != 'Sucesso ao sair do sistema.'){
        $tipo = 'error';
        $titulo = 'Oops...';
    }else{
        $tipo = 'success';
        $titulo = 'Bom trabalho ';
    }
    $divMensagem = "<html>
    <meta charset='UTF-8'>
<script>
    
sweetAlert('$titulo', '$mensagem', '$tipo');

</script>
</html>";
    echo $divMensagem;
    unset($mensagem);
}
?>
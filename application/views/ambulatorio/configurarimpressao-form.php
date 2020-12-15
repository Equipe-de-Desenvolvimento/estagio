<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="STG SAUDE">
    <title>Configurar Impressão</title>
    <!--CSS PADRAO DO BOOTSTRAP COM ALGUMAS ALTERAÇÕES DO TEMA-->
    <link href="<?= base_url() ?>bootstrap/vendor/bootstrap/css/bootstrap.css" rel="stylesheet" />
    <link href="<?= base_url() ?>bootstrap/vendor/metisMenu/metisMenu.css" rel="stylesheet" />
    <link href="<?= base_url() ?>bootstrap/dist/css/sb-admin-2.css" rel="stylesheet" />
    <!--BIBLIOTECA RESPONSAVEL PELOS ICONES-->
    <link href="<?= base_url() ?>bootstrap/vendor/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <!--DEFINE TAMANHO MAXIMO DOS CAMPOS-->
    <link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
    <!--AUTOCOMPLETE NOVO-->
    <link href="<?= base_url() ?>bootstrap/vendor/autocomplete/easy-autocomplete.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>bootstrap/vendor/autocomplete/easy-autocomplete.themes.css" rel="stylesheet" type="text/css" />
    <!--CSS DO ALERTA BONITINHO-->
    <link href="<?= base_url() ?>bootstrap/vendor/alert/dist/sweetalert.css" rel="stylesheet" type="text/css" />


    <link href="<?= base_url() ?>bootstrap/vendor/clock/compiled/flipclock.css" rel="stylesheet" />
</head>
<body bgcolor="#C0C0C0">


    <div class="container-fluid"> <!-- Inicio da DIV content -->
        <div class="row">
            <div class="col-lg-12"> 
                <div class="alert alert-success ">
                    Configuração de Impressão
                </div>
            </div>
        </div>
        <form name="form1" id="form1" action="<?= base_url() ?>ambulatorio/guia/gravarconfiguracaoimpressao" method="post">
            <div class="panel panel-default">


                <div class="alert alert-info">
                    Cabeçalho e Rodapé
                </div> 
                <div class="panel-body">

                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <label>Cabeçalho</label>
                            <input type="hidden" name="impressao_id" value="<?= @$impressao[0]->empresa_impressao_id ?>">
                            <textarea id="cabecalho" name="cabecalho" cols="85" rows="10" ><?= @$impressao[0]->cabecalho ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <label>Rodapé</label>
                            <textarea id="rodape" name="rodape" cols="85" rows="10" ><?= @$impressao[0]->rodape ?></textarea>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-lg-2">
                            <button type="submit" class="btn btn-outline btn-success btn-sm" name="btnEnviar"><i class="fa fa-floppy-o" aria-hidden="true"></i> Enviar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div> <!-- Final da DIV content -->
</body>

<script src="<?= base_url() ?>bootstrap/vendor/jquery/jquery.min.js"></script>

<script src="<?= base_url() ?>bootstrap/vendor/clock/compiled/flipclock.js"></script>
<script src="<?= base_url() ?>bootstrap/vendor/font-awesome/css/fonts.js"></script>

<script  src="<?= base_url() ?>bootstrap/vendor/bootstrap/js/bootstrap.min.js"></script>
<script  src="<?= base_url() ?>bootstrap/vendor/metisMenu/metisMenu.min.js"></script>
<script  src="<?= base_url() ?>bootstrap/dist/js/sb-admin-2.js"></script>

<script type="text/javascript" src="<?= base_url() ?>bootstrap/vendor/autocomplete/jquery.easy-autocomplete.js" ></script>

<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskMoney.js"></script>

<!--        SWEET ALERT. (PLUGIN DO ALERTA BONITINHO)-->

<script src="<?= base_url() ?>bootstrap/vendor/alert/dist/sweetalert.min.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
<script type="text/javascript">
    $('#btnimprimir').click(function () {
        $(location).attr('href', '<?= base_url(); ?>ambulatorio/guia/impressaodeclaracaoguia');
    });


    tinyMCE.init({
        // General options
        mode: "textareas",
        theme: "advanced",
        plugins: "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",
        // Theme options
        theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,pagebreak,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor,|,fullscreen",
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

    $(function () {
        $('#modelo').change(function () {
            if ($(this).val()) {
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/modelosdeclaracao', {modelo: $(this).val()}, function (j) {
                    options = "";

                    options += j[0].texto;
                    //                                                document.getElementById("laudo").value = options

                    $('#declaracao').val(options)
                    var ed = tinyMCE.get('declaracao');
                    ed.setContent($('#declaracao').val());

                    //$('#laudo').val(options);
                    //$('#laudo').html(options).show();
                    //                                                $('.carregando').hide();
                    //history.go(0) 
                });
            } else {
                $('#laudo').html('value=""');
            }
        });
    });
</script>
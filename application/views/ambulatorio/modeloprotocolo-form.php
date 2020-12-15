<!DOCTYPE html>
<head>
<title>Modelo Protocolo</title>
</head>
<div > <!-- Inicio da DIV content -->
    <div class="bt_link_voltar">
        <a href="<?= base_url() ?>ambulatorio/modeloprotocolo">
            Voltar
        </a>
    </div>
    <div>
        <h3 class="singular">Cadastrar Modelo Protocolo</h3>
        <div>
            <form name="form_modelosolicitarexames" id="form_modelosolicitarexames" action="<?= base_url() ?>ambulatorio/modeloprotocolo/gravar" method="post">

                <div>
                    <textarea id="protocolo" name="protocolo" rows="15" cols="80" style="width: 80%"><?= @$obj->_texto; ?></textarea>
                </div>


                <fieldset>
                    <div>
                        <label>Nome</label>
                        <input type="hidden" name="ambulatorio_modelo_protocolo_id" class="texto10" value="<?= @$obj->_ambulatorio_modelo_protocolo_id; ?>" />
                        <input type="text" name="txtNome" id="txtNome" class="texto10" value="<?= @$obj->_nome; ?>" />
                    </div>

                    <hr/>
                    <button type="submit" name="btnEnviar">Enviar</button>
                    <button type="reset" name="btnLimpar">Limpar</button>
                    <button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
            </form>
        </div>
    </div>
</div> <!-- Final da DIV content -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link href="<?= base_url() ?>css/estilo.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/jquery-treeview.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<!-- <script type="text/javascript" src="<?= base_url() ?>js/tinymce2/js/tinymce/tinymce.min.js"></script> -->
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>

<script type="text/javascript" src="<?= base_url() ?>js/tinymce5/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript">
    // NOVO TINYMCE


    <? 
    $totalreceitas = count($listareceita);
    $qtde_receita = 0;
    ?>
    var receitas_tiny = [
    <?
        foreach($listareceita as $receita){
            $qtde_receita++;
            if($qtde_receita != $totalreceitas){
                echo "{ text: '$receita->nome', value: '*$receita->nome*'},";
            }else{
                echo "{ text: '$receita->nome', value: '*$receita->nome*'}";
            }
    }
    ?>
    ];
    
    <? 
    $totalreceitas = count($listareceitaespecial);
    $qtde_receita = 0;
    ?>
    var receitas_especial_tiny = [
    <?
        foreach($listareceitaespecial as $receita){
            $qtde_receita++;
            if($qtde_receita != $listareceitaespecial){
                echo "{ text: '$receita->nome', value: '#$receita->nome#'},";
            }else{
                echo "{ text: '$receita->nome', value: '#$receita->nome#'}";
            }
    }
    ?>
    ];


    <? 
    $totalexames = count($listaexames);
    $qtde_exames = 0;
    ?>
    var exames_tiny = [
    <?
        foreach($listaexames as $exames){
            $qtde_exames++;
            if($qtde_exames != $totalexames){
                echo "{ text: '$exames->nome', value: '@$exames->nome@'},";
            }else{
                echo "{ text: '$exames->nome', value: '@$exames->nome@'}";
            }
    }
    ?>
    ];


    <? 
    $totalterapeuticas = count($listaterapeuticas);
    $qtde_terapeuticas = 0;
    ?>
    var terapeutica_tiny = [
    <?
        foreach($listaterapeuticas as $terapeuticas){
            $qtde_terapeuticas++;
            if($qtde_terapeuticas != $totalterapeuticas){
                echo "{ text: '$terapeuticas->nome', value: '$$terapeuticas->nome$'},";
            }else{
                echo "{ text: '$terapeuticas->nome', value: '$$terapeuticas->nome$'}";
            }
    }
    ?>
    ];


    <? 
    $totalrelatorio = count($listarelatorios);
    $qtde_relatorio = 0;
    ?>
    var relatorio_tiny = [
    <?
        foreach($listarelatorios as $relatorio){
            $qtde_relatorio++;
            if($qtde_relatorio != $totalrelatorio){
                echo "{ text: '$relatorio->nome', value: '%$relatorio->nome%'},";
            }else{
                echo "{ text: '$relatorio->nome', value: '%$relatorio->nome%'}";
            }
    }
    ?>
    ];

    tinymce.init({
        selector: "textarea",
        height: 500,
        setup : function(editor)
        {
            editor.on('init', function() 
            {
                this.getDoc().body.style.fontSize = '12pt';
                this.getDoc().body.style.fontFamily = 'Arial';
            });
            editor.ui.registry.addAutocompleter('receitas_tiny', {
            ch: '*',
            minChars: 0,
            columns: 1,
            fetch: function (pattern) {
                var matchedChars = receitas_tiny.filter(function (char) {
                return char.text.indexOf(pattern) !== -1;
                });

                return new tinymce.util.Promise(function (resolve) {
                var results = matchedChars.map(function (char) {
                    return {
                    value: char.value,
                    text: char.text
                    // icon: char.value
                    }
                });
                resolve(results);
                });
            },
            onAction: function (autocompleteApi, rng, value) {
                editor.selection.setRng(rng);
                editor.insertContent(value);
                autocompleteApi.hide();
            }
            });
            editor.ui.registry.addAutocompleter('receitas_especial_tiny', {
            ch: '#',
            minChars: 0,
            columns: 1,
            fetch: function (pattern) {
                var matchedChars = receitas_especial_tiny.filter(function (char) {
                return char.text.indexOf(pattern) !== -1;
                });

                return new tinymce.util.Promise(function (resolve) {
                var results = matchedChars.map(function (char) {
                    return {
                    value: char.value,
                    text: char.text
                    // icon: char.value
                    }
                });
                resolve(results);
                });
            },
            onAction: function (autocompleteApi, rng, value) {
                editor.selection.setRng(rng);
                editor.insertContent(value);
                autocompleteApi.hide();
            }
            });
            editor.ui.registry.addAutocompleter('exames_tiny', {
            ch: '@',
            minChars: 0,
            columns: 1,
            fetch: function (pattern) {
                var matchedChars = exames_tiny.filter(function (char) {
                return char.text.indexOf(pattern) !== -1;
                });

                return new tinymce.util.Promise(function (resolve) {
                var results = matchedChars.map(function (char) {
                    return {
                    value: char.value,
                    text: char.text
                    // icon: char.value
                    }
                });
                resolve(results);
                });
            },
            onAction: function (autocompleteApi, rng, value) {
                editor.selection.setRng(rng);
                editor.insertContent(value);
                autocompleteApi.hide();
            }
            });
            editor.ui.registry.addAutocompleter('terapeutica_tiny', {
            ch: '$',
            minChars: 0,
            columns: 1,
            fetch: function (pattern) {
                var matchedChars = terapeutica_tiny.filter(function (char) {
                return char.text.indexOf(pattern) !== -1;
                });

                return new tinymce.util.Promise(function (resolve) {
                var results = matchedChars.map(function (char) {
                    return {
                    value: char.value,
                    text: char.text
                    // icon: char.value
                    }
                });
                resolve(results);
                });
            },
            onAction: function (autocompleteApi, rng, value) {
                editor.selection.setRng(rng);
                editor.insertContent(value);
                autocompleteApi.hide();
            }
            });
            editor.ui.registry.addAutocompleter('relatorio_tiny', {
            ch: '%',
            minChars: 0,
            columns: 1,
            fetch: function (pattern) {
                var matchedChars = relatorio_tiny.filter(function (char) {
                return char.text.indexOf(pattern) !== -1;
                });

                return new tinymce.util.Promise(function (resolve) {
                var results = matchedChars.map(function (char) {
                    return {
                    value: char.value,
                    text: char.text
                    // icon: char.value
                    }
                });
                resolve(results);
                });
            },
            onAction: function (autocompleteApi, rng, value) {
                editor.selection.setRng(rng);
                editor.insertContent(value);
                autocompleteApi.hide();
            }
            });
        },
                plugins: [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking help",
            "table directionality emoticons template textcolor paste fullpage colorpicker spellchecker"
        ],

        toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
        toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor | table | removeformat",
        toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft help"
    });



    // tinymce.init({
    //     selector: "textarea",
    //     setup : function(ed)
    //     {
    //         ed.on('init', function() 
    //         {
    //             this.getDoc().body.style.fontSize = '12pt';
    //             this.getDoc().body.style.fontFamily = 'Arial';
    //         });
    //     },
    //     theme: "modern",
    //     skin: "custom",
    //     language: 'pt_BR',
        
    //     // forced_root_block : '',
    //     <?if(@$empresa[0]->impressao_laudo == 33){?>
    //         forced_root_block : '',
    //     <?}?>
    // //                                                            browser_spellcheck : true,
    // //                                                            external_plugins: {"nanospell": "<?= base_url() ?>js/tinymce2/nanospell/plugin.js"},
    // //                                                            nanospell_server: "php",
    // //                                                            nanospell_dictionary: "pt_br" ,
    //     height: 450,
        
    //     plugins: [
    //         "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak",
    //         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking help",
    //         "table directionality emoticons template textcolor paste fullpage colorpicker spellchecker"
    //     ],

    //     toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
    //     toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor | table | removeformat",
    //     toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft help",

    //     menubar: false,
    //     toolbar_items_size: 'small',

    //     style_formats: [{
    //             title: 'Bold text',
    //             inline: 'b'
    //         }, {
    //             title: 'Red text',
    //             inline: 'span',
    //             styles: {
    //                 color: '#ff0000'
    //             }
    //         }, {
    //             title: 'Red header',
    //             block: 'h1',
    //             styles: {
    //                 color: '#ff0000'
    //             }
    //         }, {
    //             title: 'Example 1',
    //             inline: 'span',
    //             classes: 'example1'
    //         }, {
    //             title: 'Example 2',
    //             inline: 'span',
    //             classes: 'example2'
    //         }, {
    //             title: 'Table styles'
    //         }, {
    //             title: 'Table row 1',
    //             selector: 'tr',
    //             classes: 'tablerow1'
    //         }],
    //         fontsize_formats: 'xx-small x-small 8pt 10pt 12pt 14pt 18pt 24pt 36pt 48pt',    

    //         templates: [{
    //                 title: 'Test template 1',
    //                 content: 'Test 1'
    //             }, {
    //                 title: 'Test template 2',
    //                 content: 'Test 2'
    //             }],

    //     init_instance_callback: function () {
    //         window.setTimeout(function () {
    //             $("#div").show();
    //         }, 1000);
    //     }
    // });
    
    
    
    
    
    $('#btnVoltar').click(function() {
        $(location).attr('href', '<?= base_url(); ?>ponto/cargo');
    });

    $(function() {
        $( "#accordion" ).accordion();
    });

    $(document).ready(function(){
        jQuery('#form_modelosolicitarexames').validate( {
            rules: {
                txtNome: {
                    required: true,
                    minlength: 3
                }
            },
            messages: {
                txtNome: {
                    required: "*",
                    minlength: "!"
                }
            }
        });
    });

</script>
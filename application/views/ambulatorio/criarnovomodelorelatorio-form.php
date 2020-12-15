<!DOCTYPE html>
<div > <!-- Inicio da DIV content -->
    <div>
        <h3 class="singular">Nova Relatorio</h3>
        <div>
            <form name="form_modeloreceita" id="form_modeloreceita" action="<?= base_url() ?>ambulatorio/modeloreceita/gravarrelatorioatendimento" method="post">

                <div>

                    <input type='hidden' name="laudo_id" value="<?=$laudo_id?>" />
                    <input type='hidden' name="medico" value="<?=$medico?>" />

                </div>
                <div>
                    <textarea id="receita" name="receita" rows="15" cols="80" style="width: 80%"></textarea>
                </div>

                <fieldset>
                    <div>
                        <label>Nome</label>
                        <input type="text" name="txtNome" id="txtNome" class="texto10" />
                    </div>

                    <hr/>
                    <button type="submit" name="btnEnviar" id="btnEnviar">Salvar Relatorio</button>
                    <button type="submit" name="btnSalvarModelo" id="btnSalvarModelo" disabled>Salvar Modelo</button>
                    <button type="reset" name="btnLimpar">Limpar</button>
            </form>
        </div>

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
<script type="text/javascript" src="<?= base_url() ?>js/tinymce5/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
<!-- <script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script> -->
<script type="text/javascript">


    // NOVO TINYMCE
    tinymce.init({
        selector: "#receita",
        setup : function(ed)
        {
            ed.on('init', function() 
            {
                this.getDoc().body.style.fontSize = '12pt';
                this.getDoc().body.style.fontFamily = 'Arial';
            });
        },
        // theme: "modern",
        // skin: "custom",
        language: 'pt_BR',
        
        // forced_root_block : '',
        <?if(@$empresa[0]->impressao_laudo == 33){?>
            forced_root_block : '',
        <?}?>
    //                                                            browser_spellcheck : true,
    //                                                            external_plugins: {"nanospell": "<?= base_url() ?>js/tinymce2/nanospell/plugin.js"},
    //                                                            nanospell_server: "php",
    //                                                            nanospell_dictionary: "pt_br" ,
        height: 450,
        
        plugins: [
            "advlist autolink autosave save link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking help",
            "table directionality emoticons template textcolor paste fullpage colorpicker spellchecker"
        ],
        toolbar: "fontselect | fontsizeselect | bold italic underline strikethrough | link unlink anchor image | alignleft aligncenter alignright alignjustify | newdocument fullpage | styleselect formatselect | cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | media code | insertdatetime preview | forecolor backcolor | table |hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft help", 
        menubar: false,
        toolbar_items_size: 'small',

        style_formats: [{
                title: 'Bold text',
                inline: 'b'
            }, {
                title: 'Red text',
                inline: 'span',
                styles: {
                    color: '#ff0000'
                }
            }, {
                title: 'Red header',
                block: 'h1',
                styles: {
                    color: '#ff0000'
                }
            }, {
                title: 'Example 1',
                inline: 'span',
                classes: 'example1'
            }, {
                title: 'Example 2',
                inline: 'span',
                classes: 'example2'
            }, {
                title: 'Table styles'
            }, {
                title: 'Table row 1',
                selector: 'tr',
                classes: 'tablerow1'
            }],
            fontsize_formats: 'xx-small x-small 8pt 10pt 12pt 14pt 18pt 24pt 36pt 48pt',    

            templates: [{
                    title: 'Test template 1',
                    content: 'Test 1'
                }, {
                    title: 'Test template 2',
                    content: 'Test 2'
                }],

        init_instance_callback: function () {
            window.setTimeout(function () {
                $("#div").show();
            }, 1000);
        }
    });
    
     
    

    $(function() {
        $( "#accordion" ).accordion();
    });


    $(document).ready(function(){
        $('#txtNome').on('input', function(){
            $('#btnSalvarModelo').prop('disabled', $(this).val().length < 3);
        });
    });


</script>
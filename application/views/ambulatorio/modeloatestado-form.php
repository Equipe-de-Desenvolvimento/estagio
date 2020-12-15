<!DOCTYPE html>
<div > <!-- Inicio da DIV content -->
    <div class="bt_link_voltar">
        <a href="<?= base_url() ?>ambulatorio/modeloatestado">
            Voltar
        </a>
    </div>
    <div>
        <h3 class="singular">Cadastro Modelo Atestado</h3>
        <div>
            <form name="form_modeloatestado" id="form_modeloatestado" action="<?= base_url() ?>ambulatorio/modeloatestado/gravar" method="post">

                <div>
                    <textarea id="atestado" name="atestado" rows="15" cols="80" style="width: 80%"><?= @$obj->_texto; ?></textarea>
                </div>

                <!--                <div>
                                    <textarea id="atestado" name="atestado" class="jqte-test" ><?= @$obj->_texto; ?></textarea>
                                </div>-->

                <fieldset>
                    <div>
                        <label>Nome</label>
                        <input type="hidden" name="ambulatorio_modelo_atestado_id" class="texto10" value="<?= @$obj->_ambulatorio_modelo_atestado_id; ?>" />
                        <input type="text" name="txtNome" id="txtNome" class="texto10" value="<?= @$obj->_nome; ?>" />
                    </div>
                    <div>
                        <label>Medicos</label>
                        <select name="medico" id="medico" class="size4">
                            <? foreach ($medicos as $value) : ?>
                                <option value="<?= $value->operador_id; ?>"<?
                            if (@$obj->_medico_id == $value->operador_id):echo'selected';
                            endif;
                                ?>><?php echo $value->nome; ?></option>
                                    <? endforeach; ?>
                        </select>
                    </div>
                    <hr/>
                    <button type="submit" name="btnEnviar">Enviar</button>
                    <button type="reset" name="btnLimpar">Limpar</button>
                    <button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
            </form>
        </div>
        <div>
            <table border="1">
                <thead>
                <tr class="tabela_header">
                    <th style="text-align: center;">
                        OPÇÕES DE CONFIGURAÇÃO DO ATESTADO
                    </th>
                </tr>
                 </thead>
                <tr class="tabela_content01">
                    <td style="text-align: justify;">
                        Aqui se encontram as opções que você pode estar utilizando na hora de montar seu padrão de laudo.

                        Formate na caixa acima o texto do laudo como quiser e posicione as opções de acordo com sua necessidade.

                        Por exemplo, você pode estar colocando _paciente_ para informar o nome do paciente e ao lado separado por um espaço, colocar _sexo_ para mostrar o sexo

                    </td>
                </tr>
                <tr class="tabela_content01">
                    <td style="text-align: justify;">
                        Segue abaixo a lista com as opções disponíveis de dados.

                        (Copie os traços e a palavra como estão descritos abaixo. Ou seja, o nome do paciente não é paciente e sim _paciente_ )

                    </td>
                </tr>
            </table>
            <br>
            <br>
            <table>
                <tr class="tabela_header">
                    <th >
                        Descrição
                    </th>
                    <th >
                        Como fazer
                    </th>
                </tr>
                <tr class="tabela_content01">
                    <td>
                        Nome Do Paciente  -----------
                    </td>
                    <td style="text-align: left;">
                        _paciente_ 
                    </td>
                </tr>
                <tr class="tabela_content02">
                    <td>
                        Sexo -----------
                    </td>
                    <td style="text-align: left;">
                        _sexo_
                    </td>
                </tr>
                <tr class="tabela_content01">
                    <td>
                        Nascimento -----------
                    </td>
                    <td style="text-align: left;">
                        _nascimento_
                    </td>
                </tr>
<!--                <tr class="tabela_content02">
                    <td>
                        Convênio ----------
                    </td>
                    <td style="text-align: left;">
                        _convenio_
                    </td>
                </tr>-->
                <tr class="tabela_content02">
                    <td>
                        CPF -----------
                    </td>
                    <td style="text-align: left;"> 
                        _CPF_
                    </td>
                </tr>
<!--                <tr class="tabela_content02">
                    <td>
                        Sala ----------
                    </td>
                    <td style="text-align: left;">
                        _sala_
                    </td>
                </tr>-->
                <tr class="tabela_content01">
                    <td>
                        Solicitante -----------
                    </td>
                    <td style="text-align: left;">
                        _solicitante_
                    </td>
                </tr>
                <tr class="tabela_content02">
                    <td>
                        Emissão (Data onde foi realizado o exame-----------
                    </td>
                    <td style="text-align: left;">
                        _data_
                    </td>
                </tr>
                <tr class="tabela_content01">
                    <td>
                        Medico (Médico responsável)-----------
                    </td>
                    <td style="text-align: left;">
                        _medico_
                    </td>
                </tr>
<!--                <tr class="tabela_content02">
                    <td>
                        Medico Revisor-----------
                    </td>
                    <td style="text-align: left;">
                        _revisor_
                    </td>
                </tr>-->
                <tr class="tabela_content02">
                    <td>
                        Procedimento-----------
                    </td>
                    <td style="text-align: left;">
                        _procedimento_ 
                    </td>
                </tr>

                <tr class="tabela_content01">
                    <td>
                        Cid Primário -----------
                    </td>
                    <td style="text-align: left;">
                        _cid1_
                    </td>
                </tr>
                <tr class="tabela_content02">
                    <td>
                        Cid Secundário -----------
                    </td>
                    <td style="text-align: left;">
                        _cid2_
                    </td>
                </tr>
                <tr class="tabela_content01">
                    <td>
                        Peso-----------
                    </td>
                    <td style="text-align: left;">
                        _peso_
                    </td>
                </tr>
                <tr class="tabela_content02">
                    <td>
                        Altura-----------
                    </td>
                    <td style="text-align: left;">
                        _altura_
                    </td>
                </tr>
                <tr class="tabela_content01">
                    <td>
                        Assinatura do médico (Apenas se quiser no corpo do texto. Também pode ser colocado no rodapé nas configurações de rodapé)
                    </td>
                    <td style="text-align: left;">
                        _assinatura_
                    </td>
                </tr>
                <tr class="tabela_content02">
                    <td>
                        Carimbo do médico
                    </td>
                    <td style="text-align: left;">
                        _carimbo_
                    </td>
                </tr>
            </table>

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
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">
    // NOVO TINYMCE
    tinymce.init({
        selector: "#atestado",
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
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking help",
            "table directionality emoticons template textcolor paste fullpage colorpicker spellchecker"
        ],

        toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
        toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor | table | removeformat",
        toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft help",

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
    
    
    
    
    
    $('#btnVoltar').click(function() {
        $(location).attr('href', '<?= base_url(); ?>ponto/cargo');
    });

    $(function() {
        $( "#accordion" ).accordion();
    });

    $(document).ready(function(){
        jQuery('#form_modeloatestado').validate( {
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
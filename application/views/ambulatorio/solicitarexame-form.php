<head>
    <title>Solicitar Exames</title>
</head>
<div >
    <?
    $dataFuturo = date("Y-m-d");
    $dataAtual = @$obj->_nascimento;
    $date_time = new DateTime($dataAtual);
    $diff = $date_time->diff(new DateTime($dataFuturo));
    $teste = $diff->format('%Ya %mm %dd');


    if (count($receita) == 0) {
        $exame_id = 0;
        $texto = "";
        $medico = "";
    } else {
        $texto = $receita[0]->texto;
        $exame_id = $receita[0]->ambulatorio_exame_id;
        $medico = $receita[0]->medico_parecer1;
    }
    $operador_id = $this->session->userdata('operador_id');
    ?>

    <div >
        <form name="form_laudo" id="form_laudo" action="<?= base_url() ?>ambulatorio/laudo/gravarexame/<?= $ambulatorio_laudo_id ?>" method="post">
            <div >
                <fieldset>
                    <legend>Dados</legend>
                    <table> 
                        <tr><td width="400px;">Paciente:<?= @$obj->_nome ?></td>
                            <td width="400px;">Exame: <?= @$obj->_procedimento ?></td>
                            <td>Solicitante: <?= @$obj->_solicitante ?></td>
                        </tr>
                        <tr><td>Idade: <?= $teste ?></td>
                            <td>Nascimento:<?= substr(@$obj->_nascimento, 8, 2) . "/" . substr(@$obj->_nascimento, 5, 2) . "/" . substr(@$obj->_nascimento, 0, 4); ?></td>
                            <td>Sala:<?= @$obj->_sala ?></td>
                        </tr>
                    </table>

                </fieldset>
                <table>
                    <tr>
                        <td>
                            <div class="bt_link_new" style="width: 200px; margin: 5px">
                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/modelosolicitarexames');" style="width: 250px; margin: 5px">
                                    Modelo Exame</a></div>
                        </td>                        
                    </tr>
                </table>
                <div >
                    <div>

                        <fieldset>
                            <legend>Solicitar Exame</legend>
                            <div>
                                <label>Modelos</label>
                                <select name="exame" id="exame" class="size2" >
                                    <option value='' >selecione</option>
                                    <?php foreach ($lista as $item) { ?>
                                        <option value="<?php echo $item->ambulatorio_modelo_solicitar_exames_id; ?>" ><?php echo $item->nome; ?></option>
                                    <?php } ?>
                                </select>

                                <label>Carimbo</label>
                                <input type="checkbox" id="carimbo"  name="carimbo"/>

                                <label>Assinatura</label>
                                <input type="checkbox" id="assinatura" name="assinatura"/>

                            </div>
                            <div>
                                <input type="hidden" id="receituario_id" name="receituario_id" value="<?= $exame_id ?>"/>
                                <input type="hidden" id="ambulatorio_laudo_id" name="ambulatorio_laudo_id" value="<?= $ambulatorio_laudo_id ?>"/>
                                <input type="hidden" id="medico" name="medico" value="<?= $operador_id ?>"/>
                            </div>
                            <?
                            $corpo = '';
                            if(count($modelo_aut) > 0){
                                $corpo = $modelo_aut[0]->texto; 
                            }
                            $corpo = str_replace("_paciente_", @$laudo['0']->paciente, $corpo);
                            $corpo = str_replace("_sexo_", @$laudo['0']->sexo, $corpo);
                            $corpo = str_replace("_nascimento_", date("d/m/Y", strtotime(@$laudo['0']->nascimento)), $corpo);
                            $corpo = str_replace("_convenio_", @$laudo['0']->convenio, $corpo);
                            $corpo = str_replace("_sala_", @$laudo['0']->sala, $corpo);
                            $corpo = str_replace("_CPF_", @$laudo['0']->cpf, $corpo);
                            $corpo = str_replace("_solicitante_", @$laudo['0']->solicitante, $corpo);
                            $corpo = str_replace("_data_", substr(@$laudo['0']->data_cadastro, 8, 2) . '/' . substr(@$laudo['0']->data_cadastro, 5, 2) . '/' . substr(@$laudo['0']->data_cadastro, 0, 4), $corpo);
                            $corpo = str_replace("_medico_", @$laudo['0']->medico, $corpo);
                            $corpo = str_replace("_revisor_", @$laudo['0']->medicorevisor, $corpo);
                            $corpo = str_replace("_procedimento_", @$laudo['0']->procedimento, $corpo);
                            $corpo = str_replace("_laudo_", @$laudo['0']->texto, $corpo);
                            $corpo = str_replace("_nomedolaudo_", @$laudo['0']->cabecalho, $corpo);
                            $corpo = str_replace("_queixa_", @$laudo['0']->cabecalho, $corpo);
                            $corpo = str_replace("_peso_", @$laudo['0']->peso, $corpo);
                            $corpo = str_replace("_altura_", @$laudo['0']->altura, $corpo);
                            $corpo = str_replace("_cid1_", @$laudo['0']->cid1, $corpo);
                            $corpo = str_replace("_cid2_", @$laudo['0']->cid2, $corpo);
                            $corpo = str_replace("_assinatura_", '', $corpo);
                            // echo $corpo;
                            ?>
                            <div>
                                <textarea id="laudo" name="laudo" rows="20" cols="80" style="width: 80%"><?=$corpo?></textarea></td>
                            </div>

                            <hr>
                            <button type="submit" name="btnEnviar">Salvar</button>
                        </fieldset>
                        </form>

                    </div> 
                </div> 
            </div> 
            <?
            if (count($receita) > 0) {
                ?>
                <table id="table_agente_toxico" border="0">
                    <thead>
                        <tr>
                            <th class="tabela_header">Data</th>                            
                            <th class="tabela_header">Médico</th>
                            <th class="tabela_header">Descri&ccedil;&atilde;o</th>
                            <th colspan="3" class="tabela_header">&nbsp;</th>
                        </tr>
                    </thead>
                    <?
                    $estilo_linha = "tabela_content01";
                    foreach ($receita as $item) {
                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
//                        var_dump($item);die;
                        ?>
                        <tbody>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->texto; ?></td>
                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaosolicitarexame/<?= $item->ambulatorio_exame_id; ?>');">Imprimir
                                        </a></div>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/editarexame/<?= $ambulatorio_laudo_id ?>/<?= $item->ambulatorio_exame_id; ?>');">Editar
                                        </a></div>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                        <a onclick="repetir(<?=$item->ambulatorio_exame_id;?>)">Repetir
                                        </a></div>
                                </td>

                            </tr>

                        </tbody>
                        <?
                    }
                }
                ?>

            </table> 

            </fieldset>

    </div> 
</div> 
</div> <!-- Final da DIV content -->
<style>
    #sortable { list-style-type: none; margin: 0; padding: 0; width: 1300px; }
    #sortable li { margin: 3px 3px 3px 0; padding: 1px; float: left; width: 100px; height: 90px; font-size: 4em; text-align: center; }
</style>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link href="<?= base_url() ?>css/estilo.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/style_p.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/jquery-treeview.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<!-- <script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/jquery.tinymce.min.js"></script> -->
<!-- <script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script> -->
<script type="text/javascript" src="<?= base_url() ?>js/tinymce2/js/tinymce/tinymce.min.js"></script>
<!-- <script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/langs/pt_BR.js"></script> -->
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">




                                    // NOVO TINYMCE
                            tinymce.init({
                                selector: "#laudo",
                                setup : function(ed)
                                {
                                    ed.on('init', function() 
                                    {
                                        this.getDoc().body.style.fontSize = '12pt';
                                        this.getDoc().body.style.fontFamily = 'Arial';
                                    });
                                },
                                theme: "modern",
                                skin: "custom",
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

                                    $(function () {
                                        $('#exame').change(function () {
                                            if ($(this).val()) {
                                                //$('#laudo').hide();
                                                $('.carregando').show();
                                                $.getJSON('<?= base_url() ?>autocomplete/modelossolicitarexamesPreenchido', {exame: $(this).val(), ambulatorio_laudo_id: <?=$ambulatorio_laudo_id?>, ajax: true}, function (j) {
                                                    options = "";

                                                    options += j[0].texto;
                                                    //                                                document.getElementById("laudo").value = options

                                                    $('#laudo').val(options)
                                                    var ed = tinyMCE.get('laudo');
                                                    ed.setContent($('#laudo').val());

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

                                    $(function () {
                                        $('#linha').change(function () {
                                            if ($(this).val()) {
                                                //$('#laudo').hide();
                                                $('.carregando').show();
                                                $.getJSON('<?= base_url() ?>autocomplete/modeloslinhas', {linha: $(this).val(), ajax: true}, function (j) {
                                                    options = "";

                                                    options += j[0].texto;
                                                    //                                                document.getElementById("laudo").value = $('#laudo').val() + options
                                                    $('#laudo').val() + options
                                                    var ed = tinyMCE.get('laudo');
                                                    ed.setContent($('#laudo').val());
                                                    //$('#laudo').html(options).show();
                                                });
                                            } else {
                                                $('#laudo').html('value=""');
                                            }
                                        });
                                    });

                                    $(function () {
                                        $("#linha2").autocomplete({
                                            source: "<?= base_url() ?>index.php?c=autocomplete&m=linhas",
                                            minLength: 1,
                                            focus: function (event, ui) {
                                                $("#linha2").val(ui.item.label);
                                                return false;
                                            },
                                            select: function (event, ui) {
                                                $("#linha2").val(ui.item.value);
                                                tinyMCE.triggerSave(true, true);
                                                document.getElementById("laudo").value = $('#laudo').val() + ui.item.id
                                                $('#laudo').val() + ui.item.id
                                                var ed = tinyMCE.get('laudo');
                                                ed.setContent($('#laudo').val());
                                                //$( "#laudo" ).val() + ui.item.id;
                                                document.getElementById("linha2").value = ''
                                                return false;
                                            }
                                        });
                                    });

                                    $(function (a) {
                                        $('#anteriores').change(function () {
                                            if ($(this).val()) {
                                                //$('#laudo').hide();
                                                $('.carregando').show();
                                                $.getJSON('<?= base_url() ?>autocomplete/laudosanteriores', {anteriores: $(this).val(), ajax: true}, function (i) {
                                                    option = "";

                                                    option = i[0].texto;
                                                    tinyMCE.triggerSave();
                                                    document.getElementById("laudo").value = option
                                                    //$('#laudo').val(options);
                                                    //$('#laudo').html(options).show();
                                                    $('.carregando').hide();
                                                    history.go(0)
                                                });
                                            } else {
                                                $('#laudo').html('value="texto"');
                                            }
                                        });
                                    });
                                    //bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
                                    $('.jqte-test').jqte();


                                    function repetir(exame_id) {

                                        $.getJSON('<?= base_url() ?>autocomplete/repetirexame', {exame_id: exame_id, ajax: true}, function (j) {
                                            options = "";

                                            options += j[0].texto;

                                            $('#laudo').val(options);
                                            var ed = tinyMCE.get('laudo');
                                            ed.setContent($('#laudo').val());

                                                
                                        }
                                       ) 
                                    }





</script>

<!DOCTYPE html> 
<title>Laudo Digitador</title>
<div >

    <?
//    var_dump($padrao[0]->texto);
//    die;


    if (@$obj->_texto == "") {

        foreach ($padrao as $item) {
            @$obj->_texto = $item->texto;
        }
    }




    $dataFuturo = date("Y-m-d");
    $dataAtual = @$obj->_nascimento;
    $date_time = new DateTime($dataAtual);
    $diff = $date_time->diff(new DateTime($dataFuturo));
    $teste = $diff->format('%Ya %mm %dd');
    $spellcheck = $empresapermissao[0]->corretor_ortografico;
    ?>

    <div >
        <form name="form_laudo" id="form_laudo" action="<?= base_url() ?>ambulatorio/laudo/gravarlaudodigitador/<?= $ambulatorio_laudo_id ?>/<?= $exame_id ?>/<?= $paciente_id ?>/<?= $procedimento_tuss_id ?>" method="post">
            <div >
                <fieldset>
                    <legend>Dados</legend>
                    <table> 
                        <tr><td width="400px;">Paciente:<?= @$obj->_nome ?></td>
                            <td width="400px;">Exame: <?= @$obj->_procedimento ?></td>
                            <td>Solicitante: <?= @$obj->_solicitante ?></td>
                            <td rowspan="2"><img src="<?= base_url() ?>upload/webcam/pacientes/<?= $paciente_id ?>.jpg" width="100" height="100" /></td>
                        </tr>
                        <tr><td>Idade: <?= $teste ?></td>
                            <td>Nascimento:<?= substr(@$obj->_nascimento, 8, 2) . "/" . substr(@$obj->_nascimento, 5, 2) . "/" . substr(@$obj->_nascimento, 0, 4); ?></td>
                            <td>Sala:<?= @$obj->_sala ?></td>

                        </tr>
                    </table>
                </fieldset>
            </div>
            <table>
                <tr>
                    
                    <td width="60px;"><center>
                        <div class="bt_link_new">
                            <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/laudo/carregarlaudoanterior/" . $paciente_id . "/" . $ambulatorio_laudo_id; ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                                <font size="-1">Laudo anterior</font>
                            </a>
                        </div></center>
                    </td>
                    
                    <td width="60px;"><center>
                        <div class="bt_link_new">
                            <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/modelolaudo/pesquisar2"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650 ');">
                                <font size="-1">Laudo Modelo</font>
                            </a>
                        </div>
                        </td>
                        <td width="60px;"><center>
                            <div class="bt_link_new">
                                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/modelolinha/pesquisar2"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                                    <font size="-1">Linha Modelo </font>
                                </a>
                            </div></center>
                        </td>
                        <td width="60px;"><center>
                            <div class="bt_link_new">
                                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/laudo/calculadora"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=400,height=450');">
                                    <font size="-1">Calculadora </font>
                                </a>
                            </div></center>
                        </td>
                        <td width="60px;">
                            <div class="bt_link_new">
                                <a  onclick="javascript:window.open('<?= base_url() . "ambulatorio/laudo/carregarocorrencias/" . $paciente_id; ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1280,height=650');"  >
                                   <font size="-1"> Ocorrência</font>
                                </a>
                            </div>
                        </td> 
                        </table>
            <div>

                <fieldset>
                    <legend>Laudo</legend>
                    <div>
                        <?
                        if (@$obj->_cabecalho == "") {
                            $cabecalho = @$obj->_procedimento;
                        } else {
                            $cabecalho = @$obj->_cabecalho;
                        }
                        ?>
                        <label>Nome do Laudo</label>
                        <input type="text" id="cabecalho" class="texto10" name="cabecalho" value="<?= $cabecalho ?>"/>
                    </div>
                    <div>
                        <table>
                            <tr>
                                <td>
                                    <label>Laudo</label>
                                    <select name="exame" id="exame" class="size2" >
                                        <option value='' >selecione</option>
                                        <?php foreach ($lista as $item) { ?>
                                            <option <?= ($item->ambulatorio_modelo_laudo_id == @$obj->_laudo_modelo_id) ? 'selected' : ''; ?> value="<?php echo $item->ambulatorio_modelo_laudo_id; ?>" ><?php echo $item->nome; ?></option>
                                        <?php } ?>
                                    </select>

                                </td>
                                <td>
                                    <label>Linha</label>
                                    <input type="text" id="linha2" class="texto02" name="linha2"/>

                                </td>
                                <td>
                                    <div class="bt_link" style="width: 140px;">
                                        <a onclick="visualizarModeloLaudo();">
                                            <font size="-1"> Visual. Modelo</font>
                                        </a>
                                    </div> 
                                </td>
                                <td>
                                    <label>Calculo de Volume</label>
                                    <br>
                                    <input type="number" min=0 step=0.001 id="calc1" class="texto02 calculadora" name="calc1"/> X
                                    <input type="number" min=0 step=0.001 id="calc2" class="texto02 calculadora" name="calc2"/> X
                                    <input type="number" min=0 step=0.001 id="calc3" class="texto02 calculadora" name="calc3"/> X
                                    <input type="number" min=0 step=0.001 id="calc4" class="texto02 calculadora" name="calc4"/> =
                                    <input readonly type="text" step=0.001 id="resul" class="texto02" name="resul"/>
                                </td>
                            </tr>
                        </table>



<!--                        <select name="linha" id="linha" class="size2" >
   <option value='' >selecione</option>
                        <?php foreach ($linha as $item) { ?>
                                                                                   <option value="<?php echo $item->nome; ?>" ><?php echo $item->nome; ?></option>
                        <?php } ?>
</select>-->


                    </div>
                    <div>
                       <textarea id="laudo" class="laudo" name="laudo" rows="30" cols="80" style="width: 80%"><?= @$obj->_texto; ?></textarea>
                    </div>
                    
                    
                    <div>
                        <? $perfil_id = $this->session->userdata('perfil_id'); ?>

                        <label>M&eacute;dico respons&aacutevel</label>
                        <? if ($perfil_id == 1 || $perfil_id == 21 || (@$empresapermissao[0]->liberar_perfil == "t" && ($perfil_id == 7 || $perfil_id == 15 || $perfil_id == 5 || $perfil_id == 21))) { ?>
                        <select name="medico" id="medico" class="size2" >
                            <option value=0 >selecione</option>
                            <? foreach ($operadores as $value) : ?>
                                <option value="<?= $value->operador_id; ?>"<?
                                if (@$obj->_medico_parecer1 == $value->operador_id):echo 'selected';
                                endif;
                                ?>><?= $value->nome; ?></option>
                                    <? endforeach; ?>
                        </select>
                         <?php }else{?>
                        <select name="medico" id="medico" class="size2" >                         
                            <? foreach ($operadores as $value) : ?>
                                    <?php if($value->operador_id == @$obj->_medico_parecer1){?>
                                <option value="<?= $value->operador_id; ?>"><?= $value->nome; ?></option>
                                    <?php }?>
                             <? endforeach; ?>
                        </select>                        
                            <?php }?>

                        <?php
                        if (@$obj->_revisor == "t") {
                            ?>
                            <input type="checkbox" name="revisor" checked ="true" /><label>Revisor</label>
                            <?php
                        } else {
                            ?>
                            <input type="checkbox" name="revisor"  /><label>Revisor</label>
                            <?php
                        }
                        ?>
                        <input type="checkbox" name="carimbo" id="carimbo" <? //=(@$obj->_carimbo == 't')? 'checked': '';   ?> /><label>Carimbo</label>
                        <select name="medicorevisor" id="medicorevisor" class="size2">
                            <option value="">Selecione</option>
                            <? foreach ($operadores as $valor) : ?>
                                <option value="<?= $valor->operador_id; ?>"<?
                                if (@$obj->_medico_parecer2 == $valor->operador_id):echo 'selected';
                                endif;
                                ?>><?= $valor->nome; ?></option>
                                    <? endforeach; ?>
                        </select>
                        <?php
                        if (@$obj->_assinatura == "t") {
                            ?>
                            <input type="checkbox" name="assinatura"  id="assinatura" checked ="true" /><label>Assinatura</label>
                            <?php
                        } else {
                            ?>
                            <input type="checkbox" name="assinatura"  id="assinatura"/><label>Assinatura</label>
                            <?php
                        }
                        ?>

                        <?php
                        if (@$obj->_rodape == "t") {
                            ?>
                            <input type="checkbox" name="rodape" checked ="true" /><label>Rodape</label>
                            <?php
                        } else {
                            ?>
                            <input type="checkbox" name="rodape"  /><label>Rodape</label>
                            <?php
                        }
                        ?>


                        <label>situa&ccedil;&atilde;o</label>
                        <select name="situacao" id="situacao" class="size2" onChange="muda(this)">
                            <option value='DIGITANDO'<?
                            if (@$obj->_status == 'DIGITANDO'):echo 'selected';
                            endif;
                            ?> >DIGITANDO</option>
                            <option value='FINALIZADO' <?
                            if (@$obj->_status == 'FINALIZADO'):echo 'selected';
                            endif;
                            ?> >FINALIZADO</option>
                        </select>
                    </div>
                    <div>
                        <label id="titulosenha">Senha</label>
                        <input type="password" name="senha" id="senha" class="size1" />
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Impress&atilde;o</legend>
                    <div>
                        <table>
                            <tr>
                                <td >
                                    <div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaolaudo/<?= $ambulatorio_laudo_id ?>/<?= $exame_id ?>');">
                                            <font size="-1"> Imprimir</font></a></div></td>
                                <td ><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoimagem/<?= $ambulatorio_laudo_id ?>/<?= $exame_id ?>');">
                                            <font size="-1"> fotos</font></a></div></td>
                                <td ><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/pesquisarlaudoantigo');">
                                            <font size="-1">L. Antigo</font></a></div></td>
                                <td ><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/anexarimagem/<?= $ambulatorio_laudo_id ?>');" >
                                            <font size="-1">Arquivos</font></a></div></td>
                                <td ><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/oit/<?= $ambulatorio_laudo_id ?>/<?= $paciente_id ?>');" >
                                            <font size="-1">OIT</font></a></div></td>
                                <td ><div class="bt_link">
                                         <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaooit/<?= $ambulatorio_laudo_id ?>');" >
                                             <font size="-1">Imp. OIT</font></a></div></td>
                            </tr>
                        </table>
                    </div>
                    <div>


                        <!--<input name="textarea" id="textarea"></input>
                   <!-- <input name="textarea" id="textarea" ></input>-->

                        <hr/>

                        <button type="submit" name="btnEnviar">Salvar</button>
                        <button type="submit" name="btnFinalizar">Salvar e Fechar</button>
                    </div>
                </fieldset>
        </form>

    </div> 
</div> 
</div> 
</div> <!-- Final da DIV content -->
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
<!-- <script type="text/javascript" src="<?= base_url() ?>js/tinymce2/js/tinymce/tinymce.min.js"></script> -->
<!-- <script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/langs/pt_BR.js"></script> -->
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>

<script type="text/javascript" src="<?= base_url() ?>js/tinymce5/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>






<script type="text/javascript">
    
                                            window.onload = function(){     
                                                window.addEventListener("beforeunload", function(e){
                                                // Do something
                                                    $.getJSON('<?= base_url() ?>autocomplete/saiuDoLaudo', {laudo_id: <?=$ambulatorio_laudo_id?>, ajax: true}, function (j) {
                                                        
                                                    });
                                                }, false);  
                                            }
    
    

                                            document.getElementById('titulosenha').style.display = "none";
                                            document.getElementById('senha').style.display = "none";
                                            document.form_laudo.linha2.focus()

                                                    $(document).ready(function () {
                                            $("body").keypress(function (event) {
                                            if (event.keyCode == 119)   // se a tecla apertada for 13 (enter)
                                            {
                                            window.open("<?= base_url() ?>ambulatorio/laudo/impressaolaudo/<?= $ambulatorio_laudo_id ?>/<?= $exame_id ?>"); // abre uma janela
                                                }
                                                if (event.keyCode == 121)   // se a tecla apertada for 13 (enter)
                                                {
                                                document.form_laudo.submit()
                                                }
                                                });
                                                });
                                                
                                                
                                                
                                                $(document).ready(function () {
                                                jQuery('#ficha_laudo').validate({
                                                rules: {
                                                imagem: {
                                                required: true
                                                }
                                                },
                                                        messages: {
                                                        imagem: {
                                                        required: "*"
                                                        }
                                                        }
                                                });
                                                });
                                                
                                                
                                                
                                                $(function () {
                                                    
                                                $('#carimbo').change(function () {
                                                          
                                                if ($(this).prop('checked') == true) {
                                                    
                                                //$('#laudo').hide();
                                                $('.carregando').show();
                                                $.getJSON('<?= base_url() ?>autocomplete/carimbomedico', {medico_id: $('#medico').val(), ajax: true}, function (j) {
                                                options = "";                                                
                                                options += j[0].carimbo;                                            
                                                var texto_antigo = tinyMCE.get('laudo').getContent();
                                                var texto_adicional_html = "</html>";
                                                var texto_adicional_body = "</body>";
                                                texto_antigo = texto_antigo.replace(texto_adicional_html, "");
                                                texto_antigo = texto_antigo.replace(texto_adicional_body, "");
                                                // console.log(texto_antigo);
                                                var texto_final = texto_antigo + options + texto_adicional_body + texto_adicional_html;
                                                tinyMCE.triggerSave(true, true);
                                                document.getElementById("laudo").value = texto_final;
                                                $('#laudo').val() + j;
                                                var ed = tinyMCE.get('laudo');
                                                ed.setContent(texto_final);
//                                                                            tinyMCE.triggerSave(true, true);
//                                                                            document.getElementById("laudo").value = $('#laudo').val() + j[0].carimbo;
//                                                                            $('#laudo').val() + j[0].carimbo;
//                                                                            var ed = tinyMCE.get('laudo');
//                                                                            ed.setContent($('#laudo').val());



                                                });
                                                } else {
                                                //$('#laudo').html('value=""');
                                                }
                                                });
                                                });
                                                
                                                  $(function () {
                                                $('#assinatura').change(function () {
                                                  
                                                   
                                                if ($(this).prop('checked') == true) {
                                                //$('#laudo').hide();
                                                $('.carregando').show();
                                                $.getJSON('<?= base_url() ?>autocomplete/assinaturamedico', {medico_id: $('#medico').val(), ajax: true}, function (j) {
                                                options = "";
                                                // console.log(j);
                                                options += j;
                                                  
                                                var texto_antigo = tinyMCE.get('laudo').getContent();
                                                var texto_adicional_html = "</html>";
                                                var texto_adicional_body = "</body>";
                                                texto_antigo = texto_antigo.replace(texto_adicional_html, "");
                                                texto_antigo = texto_antigo.replace(texto_adicional_body, "");
                                                // console.log(texto_antigo);
                                                var texto_final = texto_antigo + options + texto_adicional_body + texto_adicional_html;
                                                tinyMCE.triggerSave(true, true);
                                                document.getElementById("laudo").value = texto_final;
                                                $('#laudo').val() + j;
                                                var ed = tinyMCE.get('laudo');
                                                ed.setContent(texto_final);
                                                });
                                                } else {
                                                //$('#laudo').html('value=""');
                                                }
                                                }); 
                                                
                                                });
                                                
                                                
                                                
                                                
                                                function muda(obj) {
                                                if (obj.value != 'DIGITANDO') {
                                                document.getElementById('titulosenha').style.display = "block";
                                                document.getElementById('senha').style.display = "block";
                                                } else {
                                                document.getElementById('titulosenha').style.display = "none";
                                                document.getElementById('senha').style.display = "none";
                                                }
                                                }



                                                // NOVO TINYMCE
                                               tinymce.init({
                                                        selector: "#laudo",
                                                        <? 
                                                        if ($empresapermissao[0]->corretor_ortografico == 't'){ ?>
                                                                browser_spellcheck: true,
                                                                contextmenu: false,
                                                        <? } ?>
                                                                setup : function(ed)
                                                                {
                                                                ed.on('init', function()
                                                                {
                                                                this.getDoc().body.style.fontSize = '12pt';
                                                                this.getDoc().body.style.fontFamily = 'Arial';
                                                                <?//if($spellcheck == 't'){?>
                                                                   // this.getBody().setAttribute('spellcheck', true);
                                                                <?//}?>
                                                                });
                                                                ed.on('SetContent', function (e) {
                                                                this.getDoc().body.style.fontSize = '12pt';
                                                                this.getDoc().body.style.fontFamily = 'Arial';
                                                                });
                                                                },
                                                                // theme: "modern",
                                                                // skin: "custom",
                                                                language: 'pt_BR',
                                                                //readonly : 'readonly',
                                                                // forced_root_block : '',
<? if (@$empresa[0]->impressao_laudo == 33) { ?>
                                                            forced_root_block : '',
<? } ?>
                                                        //                                                            browser_spellcheck : true,
                                                        //                                                            external_plugins: {"nanospell": "<?= base_url() ?>js/tinymce2/nanospell/plugin.js"},
                                                        //                                                            nanospell_server: "php",
                                                        //                                                            nanospell_dictionary: "pt_br" ,
                                                        height: 600,
                                                                plugins: [
                                                                        "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak",
                                                                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking help",
                                                                        "table directionality emoticons template textcolor paste fullpage colorpicker textpattern spellchecker"
                                                                ],
                                                                toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
                                                                toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor | table | removeformat",
                                                                // toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft help",
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
                                               
                            // NOVO TINYMCE
                          
                             
                              tinymce.init({
                                                        selector: "#adendo",
                                                                // theme: "modern",
                                                                // skin: "custom",
                                                                <?
                                                                if ($empresapermissao[0]->corretor_ortografico == 't'){ ?>
                                                                        browser_spellcheck: true,
                                                                        contextmenu: false,
                                                                <? } ?>
                                                                language: 'pt_BR',
                                                                setup : function(ed)
                                                                {
                                                                ed.on('init', function()
                                                                {
                                                                this.getDoc().body.style.fontSize = '12pt';
                                                                this.getDoc().body.style.fontFamily = 'Arial';
                                                                <?//if($spellcheck == 't'){?>
                                                                   // this.getBody().setAttribute('spellcheck', true);
                                                                <?//}?>
                                                                });
                                                                ed.on('SetContent', function (e) {
                                                                this.getDoc().body.style.fontSize = '12pt';
                                                                this.getDoc().body.style.fontFamily = 'Arial';
                                                                });
                                                                },
                                                                //                                                            browser_spellcheck : true,
                                                                //                                                            external_plugins: {"nanospell": "<?= base_url() ?>js/tinymce2/nanospell/plugin.js"},
                                                                //                                                            nanospell_server: "php",
                                                                //                                                            nanospell_dictionary: "pt_br" ,
                                                                height: 600,
                                                                plugins: [
                                                                        "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak",
                                                                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                                                                        "table directionality emoticons template textcolor paste fullpage colorpicker textpattern spellchecker"
                                                                ],
                                                                toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
                                                                toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor | table | removeformat",
                                                                // toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft",
                                                                menubar: false,
                                                                toolbar_items_size: 'small',
                                                                fontsize_formats: 'xx-small x-small 8pt 10pt 12pt 14pt 18pt 24pt 36pt 48pt',
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
                                                        $.getJSON('<?= base_url() ?>autocomplete/modeloslaudo', {exame: $(this).val(), ajax: true}, function (j) {
                                                        options2 = "";
                                                        options2 += j[0].texto;
                                                        //                                                document.getElementById("laudo").value = options2

<? if (@$empresapermissao[0]->nao_sobrepor_laudo == 't') : ?>

                                                            //recuperando texto antigo do textarea
                                                            var texto_antigo2 = tinyMCE.get('laudo').getContent();
                                                            var texto_adicional_html2 = "</html>";
                                                            var texto_adicional_body2 = "</body>";
                                                            //aqui ele tira todas as tags html e body
                                                            texto_antigo2 = texto_antigo2.replace(texto_adicional_html2, "");
                                                            texto_antigo2 = texto_antigo2.replace(texto_adicional_body2, "");
                                                            //pegando o texto antigo do textarea e somando com o texto buscado ao selecionar
                                                            var colocartexto = texto_antigo2 + j[0].texto;
                                                            var ed = tinyMCE.get('laudo');
                                                            ed.setContent(colocartexto);
                                                            $('#exame').val("");
<? else: ?>

                                                            $('#laudo').val(options2)
                                                                    var ed = tinyMCE.get('laudo');
                                                            ed.setContent($('#laudo').val());
                                                            //                                                         alert('teste');

<? endif; ?>

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
                                                
                                               
                                                
                                                
                                                function visualizarModeloLaudo() {
                                                
                                                if ($('#exame').val() != '') {
                                                varWindow = window.open('<?= base_url() ?>ambulatorio/laudo/carregarmodelolaudoselecionado/' + $('#exame').val(), 'popup', "width=800, height=600 ");
                                                } else {
                                                alert('Escolha um modelo de laudo antes de tentar visualizá-lo');
                                                }

                                                }

                                               
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
                                                        var linha = ui.item.id;
                                                        var texto_antigo = tinyMCE.get('laudo').getContent();
                                                        var texto_adicional_html = "</html>";
                                                        var texto_adicional_body = "</body>";
                                                        texto_antigo = texto_antigo.replace(texto_adicional_html, "");
                                                        texto_antigo = texto_antigo.replace(texto_adicional_body, "");
                                                        // console.log(texto_antigo);
                                                        var texto_final = texto_antigo + linha + texto_adicional_body + texto_adicional_html;
                                                        document.getElementById("laudo").value = texto_final;
                                                        $('#laudo').val() + ui.item.id;
                                                        var ed = tinyMCE.get('laudo');
                                                        ed.setContent(texto_final);
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
                                                // $('.jqte-test').jqte();

                                                $(function () {
                                                $('.calculadora').change(function () {


                                                var valor1 = parseFloat($('#calc1').val());
                                                var valor2 = parseFloat($('#calc2').val());
                                                var valor3 = parseFloat($('#calc3').val());
                                                var valor4 = parseFloat($('#calc4').val());
                                                if (!valor1 > 0){
                                                valor1 = 0;
                                                }
                                                if (!valor2 > 0){
                                                valor2 = 0;
                                                }
                                                if (!valor3 > 0){
                                                valor3 = 0;
                                                }
                                                if (!valor4 > 0){
                                                valor4 = 0;
                                                }

                                                var resultado = valor1 * valor2 * valor3 * valor4;
                                                // alert(resultado);

                                                $('#resul').val(resultado);
                                                });
                                                });</script>


<? if ($mensagem == 2) { ?>
    <script type="text/javascript">
        alert("Sucesso ao finalizar Laudo");
    </script>
    <?
}
if ($mensagem == 1) {
    ?>
    <script type="text/javascript">
        alert("Erro ao finalizar Laudo");
    </script>
    <?
}
?>
 
<head>
    <title>Laudo</title>
</head>

<div >

    <?
    if (@$listapaciente[0]->estado_civil == ''):$estado_civil = 'Solteiro';
    endif;
    if (@$listapaciente[0]->estado_civil == 2):$estado_civil = 'Casado';
    endif;
    if (@$listapaciente[0]->estado_civil == 3):$estado_civil = 'Divorciado';
    endif;
    if (@$listapaciente[0]->estado_civil == 4):$estado_civil = 'Viuvo';
    endif;
    if (@$listapaciente[0]->estado_civil == 5):$estado_civil = 'Outros';
    endif;


    $dataFuturo = date("Y-m-d");
    $dataAtual = @$listapaciente[0]->nascimento;
    $date_time = new DateTime($dataAtual);
    $diff = $date_time->diff(new DateTime($dataFuturo));
    $teste = $diff->format('%Ya %mm %dd');

    if (@$empresapermissao[0]->dados_atendimentomed != '') {
        $opc_dadospaciente = json_decode(@$empresapermissao[0]->dados_atendimentomed);
    } else {
        $opc_dadospaciente = array();
    }
    ?>

    <div>
        <form name="form_laudo" id="form_laudo" action="<?= base_url() ?>ambulatorio/exametemp/gravarlaudotarefa" method="post">
            <div >
                <fieldset>
                    <legend>Dados</legend>
                    <table> 
                        <tr >
                            <? if (in_array('paciente', $opc_dadospaciente)) { ?>
                                <td colspan="3" width="400px;">Paciente:<?= @$listapaciente[0]->nome ?></td>
                            <? } ?>


                        </tr>
                        <tr>
                            <? if (in_array('idade', $opc_dadospaciente)) { ?>
                                <td colspan="3">Idade: <?= $teste ?></td>
                            <? } ?>
                            <? if (in_array('nascimento', $opc_dadospaciente)) { ?>
                                <td colspan="3">Nascimento:<?= substr(@$listapaciente[0]->nascimento, 8, 2) . "/" . substr(@$listapaciente[0]->nascimento, 5, 2) . "/" . substr(@$listapaciente[0]->nascimento, 0, 4); ?></td>
                            <? } ?>

                        </tr>
                        <tr>
                            <? if (in_array('sexo', $opc_dadospaciente)) { ?>
                                <td colspan="2">Sexo: <?= @$listapaciente[0]->sexo ?></td>
                            <? } ?>
                            <? if (in_array('estadocivil', $opc_dadospaciente)) { ?>
                                <td colspan="1">Ocupação: <?= @$listapaciente[0]->profissao_cbo ?> </td>
                            <? } ?>
                            <? if (in_array('ocupacao', $opc_dadospaciente)) { ?>
                                <td colspan="2">Estado Civíl: <?= @$estado_civil ?> </td>
                            <? } ?>

                            <? if (in_array('telefone', $opc_dadospaciente)) { ?>
                                <td colspan="1" style="width: 200px">Telefone: <?= @$listapaciente[0]->telefone ?></td>
                            <? } ?>

                        </tr>

                        <tr>
                            <? if (in_array('telefone', $opc_dadospaciente)) { ?>
                                <td colspan="2">Indicaçao: <?= @$listapaciente[0]->indicacao ?></td>
                            <? } ?>


                            <? if (in_array('endereco', $opc_dadospaciente)) { ?>
                                <td colspan="2">Endereco: <?= @$listapaciente[0]->logradouro ?>, <?= @$listapaciente[0]->numero . ' ' . @$listapaciente[0]->bairro ?> - <?= @$listapaciente[0]->uf ?></td>
                            <? } ?>
                        </tr>
                        <tr>
                            <? if (in_array('conjuge', $opc_dadospaciente)) { ?>
                                <td colspan="5" style="width: 200px">Cônjuge: <?= @$listapaciente[0]->nome_conjuge ?></td>
                            <? } ?>
                            <?
                            $dataFuturo = date("Y-m-d");
                            $dataAtual = @$listapaciente[0]->nascimento_conjuge;
                            $date_time = new DateTime($dataAtual);
                            $diff2 = $date_time->diff(new DateTime($dataFuturo));
                            $teste2 = $diff2->format('%Ya %mm %dd');
                            ?>
                            <? if (in_array('idade_conjuge', $opc_dadospaciente)) { ?>
                                <td colspan="5" style="width: 200px">Idade do Cônjuge: <?= @$teste2 ?></td>
                            <? } ?>

                            <? if (in_array('prontuario_antigo', $opc_dadospaciente)) { ?>
                                <td colspan="5" style="width: 200px">Prontuário antigo: <?= @$listapaciente[0]->prontuario_antigo ?></td>
                            <? } ?>

                        </tr> 

                    </table>


                </fieldset>

            </div>
            <div>

                <fieldset>
                    <legend>Laudo</legend>
                    <div>
                        <?
//                        if (@$obj->_cabecalho == "") {
//                            $cabecalho = @$obj->_procedimento;
//                        } else {
//                            $cabecalho = @$obj->_cabecalho;
//                        }
                        ?>
                        <label>Nome do Laudo</label>
                        <input type="text" id="cabecalho" class="texto10" name="cabecalho" value="<?= @$listatarefa[0]->nome_laudo; ?>"/>
                        <input  type="hidden" value="<?= @$listatarefa[0]->tarefa_medico_id ?>" name="tarefa_medico_id" id="tarefa_medico_id">
                        <label>Diagnóstico</label>
                        <input type="text" name="txtDiagnostico" id="txtDiagnostico" value="<?= @$listatarefa[0]->diagnostico; ?>" class="size8"  maxlength="50" />

                    </div>
                    <div>
                        <table style="font-size: 10pt;" >

                            <tr>

                                <td style="width: 100px;">
                                    <label>Laudo</label>
                                    <select name="exame" id="exame" class="size2" >
                                        <option value='' >selecione</option>
                                        <?php foreach ($lista as $item) { ?>
                                            <option title="" value="<?php echo $item->ambulatorio_modelo_laudo_id; ?>" <?
                                            if ($listatarefa[0]->modelo_laudo_id == $item->ambulatorio_modelo_laudo_id) {
                                                echo "selected";
                                            } else {
                                                
                                            }
                                            ?>><?php echo $item->nome; ?></option>
                                                <?php } ?>
                                    </select>   
                                </td>
                                <td>
                                    <label>Linha</label>
                                    <br>
                                    <input type="text" id="linha2" class="texto02" name="linha2"/>

                                </td>
                                <td>
                                    <div class="bt_link" style="width: 140px;">
                                        <a onclick="visualizarModeloLaudo();">
                                            <font size="-1"> Visual. Modelo</font></a>
                                    </div>  
                                </td>

                            </tr>


                        </table>
                    </div>
                    <div>
                        <label>Descrição</label><br>
                        <textarea id="descricao" class="descricao" name="descricao" rows="5" cols="80" style="width: 80%"><?= @$listatarefa[0]->observacao; ?></textarea>
                    </div>
                    <br>

                   <div class="row" >
                         <div class="col" >
                             
                        <table  border="0">
                           <tr>
                               <td rowspan="11" >      
                                <textarea id="laudo" class="laudo" name="laudo" rows="30" cols="80" style="width:100%"><?= @$listatarefa[0]->laudo; ?></textarea>
                               </td>
                              <td width="40px;">
                                    <div class="bt_link_new">
                                       <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregaratestadotarefa/<?= $listatarefa[0]->tarefa_medico_id; ?>');" >
                                               Atestado</a>
                                   </div>
                              </td>
                        
                               <td width="40px;"><div class="bt_link_new">

                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarreceituariotarefa/<?= $listatarefa[0]->tarefa_medico_id; ?>');" >
                                                            Receituário</a>
                                                    </div>
                                 </td> 
                                 </tr><tr> 
                                  <td width="40px;">
                                                    <div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarreceituarioespecialtarefa/<?= $listatarefa[0]->tarefa_medico_id; ?> ');" >
                                                            R. especial
                                                        </a>
                                                    </div>
                                  </td>
                                  <td width="40px;">
                                                    <div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarhistorico/<?= $listapaciente[0]->paciente_id; ?>/<?= $listatarefa[0]->tarefa_medico_id; ?>');" >
                                                           Histórico
                                                        </a>
                                                    </div>
                                  </td>
                           </tr>
                        </table>
                         </div>
                   </div>

                    <div style="display:none;">
                        <table>
                            <tr>
                                <td>
                                    <div>
                                        <label for="informacao_extra">Informação</label><br>
                                        <textarea id="informacao_laudo" name="informacao_laudo" class="informacao_laudo" rows="10" cols="40" ><?= @$obj->_informacao_laudo ?></textarea>
                                    </div>

                                </td>

                            </tr>
                        </table>
                    </div>   
                    <div>
                        <label>
                            M&eacute;dico respons&aacutevel
                        </label>
                        <select name="medico" id="medico" class="size2" >
                            <? if ($perfil_id == 1 || (@$empresapermissao[0]->liberar_perfil == "t" && ($perfil_id == 7 || $perfil_id == 15))) { ?>
                                <option value=0 >selecione</option>
                                <?
                            }
                            foreach ($operadores as $value) :
                                if (($perfil_id != 1 && (@$empresapermissao[0]->liberar_perfil == "t" && ($perfil_id != 7 || $perfil_id != 15))) && @$listatarefa[0]->medico_id != $value->operador_id) {
                                    continue;
                                }
                                ?>

                                <option value="<?= $value->operador_id; ?>" <?= (@$listatarefa[0]->medico_id == $value->operador_id) ? 'selected' : ''; ?>>
                                    <?= $value->nome; ?>
                                </option>
                            <? endforeach; ?>
                        </select>
                        <?php
                        if (@$listatarefa[0]->revisor == "t") {
                            ?>
                            <input type="checkbox" name="revisor" checked ="true" /><label>Revisor</label>
                            <?php
                        } else {
                            ?>
                            <input type="checkbox" name="revisor"  /><label>Revisor</label>
                            <?php
                        }
                        ?>
                        <select name="medicorevisor" id="medicorevisor" class="size2">
                            <option value="">Selecione</option>
                            <? foreach ($operadores as $valor) : ?>
                                <option value="<?= $valor->operador_id; ?>"<?
                                if (@$listatarefa[0]->medico_revisor == $valor->operador_id):echo 'selected';
                                endif;
                                ?>><?= $valor->nome; ?></option>
                                    <? endforeach; ?>
                        </select>
                        <? if (@$empresapermissao[0]->desativar_personalizacao_impressao != 't') { ?>
                            <?php
                            if (@$listatarefa[0]->assinatura == "t") {
                                ?>
                                <input type="checkbox" name="assinatura" id="assinatura" checked ="true" /><label>Assinatura</label>
                                <?php
                            } else {
                                ?>
                                <input type="checkbox" name="assinatura" id="assinatura"  /><label>Assinatura</label>
                                <?php
                            }
                            ?>
                            <input type="checkbox" name="carimbo" id="carimbo" <? //=(@$obj->_carimbo == 't')? 'checked': '';                              ?> /><label>Carimbo</label>
                            <?php
                            if (@$listatarefa[0]->indicado == "t") {
                                ?>
                                <input type="checkbox" name="indicado" checked ="true" /><label>Indicado</label>
                                <?php
                            } else {
                                ?>
                                <input type="checkbox" name="indicado"  /><label>Indicado</label>
                                <?php
                            }
                            ?>
                        <? } ?>

                        <label>situa&ccedil;&atilde;o</label>
                        <select name="situacao" id="situacao" class="size2" <? if ($empresapermissao[0]->senha_finalizar_laudo == 't') { ?>onChange="muda(this)" <? } ?> >
                            <option value='ATENDENDO'<?
                            if (@$listatarefa[0]->status == 'ATENDENDO'):echo 'selected';
                            endif;
                            ?> >ATENDENDO</option>
                            <option value='FINALIZADO' <?
                            if (@$listatarefa[0]->status == 'FINALIZADO'):echo 'selected';
                            endif;
                            ?> >FINALIZADO</option> 
                        </select>
                    </div>

                    <div class="dias" style="display: inline">

                    </div>
                </fieldset>
                <fieldset>
                    <? ?>
                    <legend>Impress&atilde;o</legend>

                    <div>
                        <hr/>
                        <table>
                            <tr>
                                <td> 
                                    <button type="submit" name="btnEnviar">Salvar</button>
                                </td> 
                                <td> 
                                    <button type="submit" name="btnEnviar"  onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exametemp/impressaotarefa/<?= @$listatarefa[0]->tarefa_medico_id ?>');">Imprimir</button>
                                </td>
                            </tr>
                        </table>
                    </div>


                </fieldset>



        </form>

        <br>

    </div> 
</div> 
</div> 
 <!-- Final da DIV content -->
<style>
    .bt_link {width: 200pt;}

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
<!-- <script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script> -->
<script type="text/javascript" src="<?= base_url() ?>js/tinymce2/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>



<script type="text/javascript">

                                        tinymce.init({
                                            selector: "#laudo",
                                            setup: function (ed)
                                            {
                                                ed.on('init', function ()
                                                {
                                                    this.getDoc().body.style.fontSize = '12pt';
                                                    this.getDoc().body.style.fontFamily = 'Arial';
                                                });
                                                ed.on('SetContent', function (e) {
                                                    this.getDoc().body.style.fontSize = '12pt';
                                                    this.getDoc().body.style.fontFamily = 'Arial';
                                                });
                                            },
                                            themes: "modern",
                                            skin: "custom",
                                            height: 450,
                                            language: 'pt_BR',
                                            plugins: [
                                                "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak",
                                                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking help",
                                                "table directionality emoticons template textcolor paste fullpage colorpicker textpattern spellchecker"
                                            ],
                                            toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
                                            toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor | table | removeformat",
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
                                                }]
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
<? endif; ?>


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


                                        $(function () {
                                            $('#assinatura').change(function () {
//                                                                            alert('adasd');
                                                if ($(this).prop('checked') == true) {
                                                    //$('#laudo').hide();
                                                    $('.carregando').show();
                                                    $.getJSON('<?= base_url() ?>autocomplete/assinaturamedico', {medico_id: $('#medico').val(), ajax: true}, function (j) {
                                                        options = "";
                                                        // console.log(j);
                                                        options += j;
                                                        var texto_antigo = tinyMCE.get('laudo').getContent();
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



                                        $(function () {
                                            $('#carimbo').change(function () {
//                                                        alert('adasd');
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
//                                                        tinyMCE.triggerSave(true, true);
//                                                        document.getElementById("laudo").value = $('#laudo').val() + j[0].carimbo;
//                                                        $('#laudo').val() + j[0].carimbo;
//                                                        var ed = tinyMCE.get('laudo');
//                                                        ed.setContent($('#laudo').val());


                                                    });
                                                } else {
                                                    //$('#laudo').html('value=""');
                                                }
                                            });
                                        });

</script>


<? if (@$mensagem == 2) { ?>
    <script type="text/javascript">
        alert("Sucesso ao finalizar Laudo");
    </script>
    <?
}
if (@$mensagem == 1) {
    ?>
    <script type="text/javascript">
        alert("Erro ao finalizar Laudo");
    </script>
    <?
}
?>
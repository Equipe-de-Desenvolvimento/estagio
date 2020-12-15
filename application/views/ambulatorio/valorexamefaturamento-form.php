<?
//echo "<pre>";
//print_r($exames);
?>
<div class="content ficha_ceatox">
    <div >
        <h3 class="singular"><a href="#">Marcar exames</a></h3>
        <div>
            <form name="form_guia" id="form_guia" action="<?= base_url() ?>ambulatorio/guia/valorexamesfaturamento" method="post">
                <fieldset>
                    <legend>Dados do Paciente</legend>
                    <div>
                        <label>Nome</label>                      
                        <input type="text" id="txtNome" name="nome"  class="texto09" value="<?= $paciente['0']->nome; ?>" readonly/>
                        <input type="hidden" id="txtpaciente_id" name="txtpaciente_id"  value="<?= $paciente_id; ?>"/>
                    </div>
                    <div>
                        <label>Sexo</label>
                        <select name="sexo" id="txtSexo" class="size2">
                            <option value="M" <?
                            if ($paciente['0']->sexo == "M"):echo 'selected';
                            endif;
                            ?>>Masculino</option>
                            <option value="F" <?
                            if ($paciente['0']->sexo == "F"):echo 'selected';
                            endif;
                            ?>>Feminino</option>
                            <option value="O" <?
                            if ($paciente['0']->sexo == "O"):echo 'selected';
                            endif;
                            ?>>Outro</option>
                        </select>
                    </div>

                    <div>
                        <label>Nascimento</label>


                        <input type="text" name="nascimento" id="txtNascimento" class="texto02" alt="date" value="<?php echo substr($paciente['0']->nascimento, 8, 2) . '/' . substr($paciente['0']->nascimento, 5, 2) . '/' . substr($paciente['0']->nascimento, 0, 4); ?>" onblur="retornaIdade()" readonly/>
                    </div>

                    <div>

                        <label>Idade</label>
                        <?
                        @$dataFuturo2 = date("Y-m-d");
                        @$dataAtual2 = $paciente['0']->nascimento;
                        @$date_time2 = new DateTime($dataAtual2);
                        @$diff2 = $date_time2->diff(new DateTime($dataFuturo2));
                        @$teste2 = $diff2->format('%Ya %mm %dd');
                        ?>
                        <input type="text" name="idade2" id="txtIdade2" class="texto01" alt="numeromask" title="<?= @$teste2 ?>" value="<?= @$teste2 ?>" readonly />

                        <input type="hidden" name="idade" id="txtIdade" class="texto01" alt="numeromask" value="<?= $paciente['0']->idade; ?>" readonly />

                    </div>

                    <div>
                        <label>Nome da M&atilde;e</label>


                        <input type="text" name="nome_mae" id="txtNomeMae" class="texto08" value="<?= $paciente['0']->nome_mae; ?>" readonly/>
                    </div>

                    <?
                    if ($empresaPermissao[0]->cirugico_manual == 't') {
                        ?> 
                        <div>
                            <label>Incluir Atendimento à RN</label>
                            <select name="incluir_atend_rn">
                                 <option value="" >Escolha</option>
                                 <?
                                foreach($incluir_atendimento as $item){
                                    ?>  
                                <option value="<?= $item->rn_id; ?>"  <? if (@$exames[0]->incluir_atend_rn == $item->rn_id ) {
                                echo "selected";
                            }
                            ?>><?= $item->codigo; ?> - <?= $item->descricao; ?></option>
                                <?
                                    
                                    
                                }
                                ?>
                            </select>

                        </div>
                    
                    
                    <div>
                        <label>Caráter de Atendimento</label>
                            <select name="carater">
                                <option value="" >Escolha</option>
                                <?
                                foreach($carater as $item){
                                    ?>  
                                <option value="<?= $item->carater_id; ?>"  <? if (@$exames[0]->carater_id == $item->carater_id ) {
                                echo "selected";
                            }
                            ?>><?= $item->codigo; ?> - <?= $item->descricao; ?></option>
                                <?
                                    
                                    
                                }
                                ?>  
                            </select>

                        </div>
                    
                     <div>
                            <label>Indicação Acidente</label>
                            <select name="indicacao_acidente">
                                <option value="" >Escolha</option>
                                <?
                                foreach($indicacao_acidente as $item){
                                    ?>
                                
                                <option value="<?= $item->indicacao_acidente_id; ?>"  <? if ($exames[0]->indicacao_acidente_id == $item->indicacao_acidente_id ) {
                                echo "selected";
                               }
                            ?>><?= $item->codigo; ?> - <?= $item->descricao; ?></option>
                                <?
                                    
                                    
                                }
                                ?>
                                 
                            </select>
                        </div> 
                    
                     <div>
                            <label>Tipo Cirurgia</label>
                            <select name="tipo_cirurgia">
                                <option value="" >Escolha</option>
 
                                <?
                                foreach($tipos_cirurgia as $item){
                                    ?>
                                
                                <option value="<?= $item->tipos_cirurgia_id ?>"  <? if ($exames[0]->tipos_cirurgia_id == $item->tipos_cirurgia_id ) {
                                echo "selected";
                            }
                            ?>   ><?= $item->codigo; ?> - <?= $item->descricao; ?></option>
                                <?
                                    
                                    
                                }
                                    ?> 
                                
                                
                            </select>
                        </div>   
                    
                    
                    
                    
                    <? } ?>


                </fieldset>

                <fieldset>
                    <dl>
                        <dt>Quantidade</dt>
                        <dd><input type="text" name="qtde1" id="qtde1" value="1" class="texto00"/></dd>
                        <input type="hidden" name="agenda_exames_id" id="agenda_exames_id" value="<?= $ambulatorio_guia_id; ?>"/>
                        <input type="hidden" name="guia_id" id="guia_id" value="<?= $guia_id; ?>"/>
                        <dt>Convenio</dt>
                        <dd><select  name="convenio1" id="convenio1" class="size2" >
                                <option value="-1">Selecione</option>
                                <? foreach ($convenio as $item) : ?>
                                    <option value="<?= $item->convenio_id; ?>"<?
                                    if ($exames[0]->convenio == $item->nome) {
                                        echo "selected";
                                    } else {
                                        
                                    }
                                    ?>><?= $item->nome; ?></option>
                                        <? endforeach; ?>
                            </select></dd>
  
                        <dt>Procedimento</dt>
                        <dd>
                            <select  name="procedimento1" id="procedimento1" class="size10" >
                                <option value="-1">-- Escolha um procedimento --</option>
                                <?
                                foreach ($procedimentos as $item) {
                                    ?>
                                    <option value="<?= $item->procedimento_convenio_id ?>" <?
                                    if ($exames[0]->procedimento_tuss_id == $item->procedimento_convenio_id) {
                                        echo "selected";
                                    } else {
                                        
                                    }
                                    ?> ><?= $item->procedimento; ?> </option>
                                        <? } ?>

                            </select></dd> 

                        <?
                        if ($empresaPermissao[0]->cirugico_manual == 't') {
                            ?>   

                            <dt>Solicitante</dt>
                            <dd>
                                <input type="text" name="medico1" id="medico1" value="<?= @$exames[0]->solicitante; ?>" class="size2"/>
                                <input type="hidden" name="crm1" id="crm1" value="<?= @$exames[0]->medico_solicitante; ?>" class="texto01"/>
                            </dd>


                            <dt>Grau de Participação</dt>
                            <dd>
                                <select  name="grau_participacao" id="grau_participacao" class="size06" >
                                    <option value="">Escolha</option>
                                    <?
                                    foreach ($listargrauparticipacao as $item) {
                                        ?>
                                        <option value="<?= $item->grau_participacao_id; ?>" <?
                                        if ($exames[0]->grau_participacao_id == $item->grau_participacao_id) {
                                            echo "selected";
                                        } else {
                                            
                                        }
                                        ?> ><?= $item->descricao; ?></option>
                                                <?
                                            }
                                            ?>
                                </select>
                            </dd>

                            <dt>Nº Guia</dt>
                            <dd>
                                <input type="text" name="guia_convenio" id="guia_convenio" value="<?= @$exames[0]->guiaconvenio; ?>" class="size1"/>
                            </dd>  

                        <? } ?>


                        <dt>autorizacao</dt>
                        <dd><input type="text" name="autorizacao1" id="autorizacao" value="<?= @$exames[0]->autorizacao; ?>" class="size1"/></dd>
                        
                        
                        
                        <dt>Valor Unitario</dt>
                        <dd><input type="text" name="valor1" id="valor1" class="texto01" value="<?= @$exames[0]->valor; ?>"/></dd>
                        <dt>Pagamento</dt>
                        <dd><select  name="formapamento" id="formapamento" class="size2" >
                                <option value="0">Selecione</option>
                                <? foreach ($forma_pagamento as $item) : ?>
                                    <option value="<?= $item->forma_pagamento_id; ?>" <?
                                    if (@$exames[0]->forma_pagamento_id == $item->forma_pagamento_id) {
                                        echo "selected";
                                    }
                                    ?>   ><?= $item->nome; ?></option>
                                        <? endforeach; ?>
                            </select></dd>
                    </dl>
                    <hr/>
                    <button type="submit" name="btnEnviar">Enviar</button>
                </fieldset>
            </form>
        </div> 
    </div> 
</div> <!-- Final da DIV content -->
<!--<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">-->
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>-->
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>-->


<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<!--<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">-->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>


<script type="text/javascript">
                            $(function () {
                                $("#medico1").autocomplete({
                                    source: "<?= base_url() ?>index.php?c=autocomplete&m=medicosranqueado",
                                    minLength: 3,
                                    focus: function (event, ui) {
                                        $("#medico1").val(ui.item.label);
                                        return false;
                                    },
                                    select: function (event, ui) {
                                        $("#medico1").val(ui.item.value);
                                        $("#crm1").val(ui.item.id);
                                        return false;
                                    }
                                });
                            });



                            $(function () {
                                $("#accordion").accordion();
                            });

                            $(function () {
                                $('#convenio1').change(function () {
                                    if ($(this).val()) {
                                        $('.carregando').show();
                                        $.getJSON('<?= base_url() ?>autocomplete/procedimentoconveniotodos', {convenio1: $(this).val(), ajax: true}, function (j) {
                                            var options = '<option value=""></option>';
                                            for (var c = 0; c < j.length; c++) {
                                                options += '<option value="' + j[c].procedimento_convenio_id + '">' + j[c].procedimento + '</option>';
                                            }
                                            $('#procedimento1').html(options).show();
                                            $('.carregando').hide();
                                        });
                                    } else {
                                        $('#procedimento1').html('<option value="">-- Escolha um exame --</option>');
                                    }
                                });
                            });


                            $(function () {
                                $('#procedimento1').change(function () {
                                    if ($(this).val()) {
                                        $('.carregando').show();
                                        $.getJSON('<?= base_url() ?>autocomplete/procedimentovalor', {procedimento1: $(this).val(), ajax: true}, function (j) {
                                            options = "";
                                            options += j[0].valortotal;
                                            document.getElementById("valor1").value = options
                                            $('.carregando').hide();
                                        });
                                    } else {
                                        $('#valor1').html('value=""');
                                    }
                                });
                            });


                            $(document).ready(function () {
                                jQuery('#form_guia').validate({
                                    rules: {
                                        medico1: {
                                            required: true,
                                            minlength: 3
                                        },
                                        crm: {
                                            required: true
                                        },
                                        sala1: {
                                            required: true
                                        }
                                    },
                                    messages: {
                                        medico1: {
                                            required: "*",
                                            minlength: "!"
                                        },
                                        crm: {
                                            required: "*"
                                        },
                                        sala1: {
                                            required: "*"
                                        }
                                    }
                                });
                            });

</script>
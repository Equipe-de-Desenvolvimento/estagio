<? 
$empresapermissoes = $this->guia->listarempresapermissoes();
$medicinadotrabalho = $empresapermissoes[0]->medicinadotrabalho; 

?>
<div class="panel-body ficha_ceatox"> <!-- Inicio da DIV content -->
    <div class="alert alert-primary"><b> Dados do Convenio</b></div>
        <form name="form_convenio" id="form_convenio" action="<?= base_url() ?>cadastros/convenio/gravar" method="post">
            <fieldset>

                <div class="row">
                        <div class="col-lg-4">
                            <label>Nome</label>
                            <input type="hidden" name="txtconvenio_id" class="form-control" value="<?= @$obj->_convenio_id; ?>" />
                            <input type="text" name="txtNome" class="form-control" value="<?= @$obj->_nome; ?>" /> 
                        </div>    

<!--                        <div class="col-lg-2">      
                                <label>Raz&atilde;o social</label>
                                <input type="text" name="txtrazaosocial" class="form-control" value="<?= @$obj->_razao_social; ?>" />
                        </div>-->
                    <!-- <div class="col-lg-2">
                        <div>
                            <label>Teste</label>
                            <input type="text" name="txtcodigo" class="form-control" value="<?= @$obj->_codigoidentificador; ?>" />               
                        </div>
                    </div> -->
<!--                        <div class="col-lg-2">   
                            <div>
                                <label>CNPJ</label>
                                <input type="text" id="txtCNPJ" name="txtCNPJ" alt="cnpj" class="form-control" value="<?= @$obj->_cnpj; ?>" />
                            </div>
                        </div>-->
                    <!-- <div class="col-lg-2">
                        <div>
                            <label>Codigo identifica&ccedil;&atilde;o</label>
                            <input type="text" name="txtcodigo" class="form-control" value="<?= @$obj->_codigoidentificador; ?>" />               
                        </div> 

                    </div> -->
                </div>
            </fieldset>
            <br>
            <fieldset>
                <div class="alert alert-primary"><b>Instituição</b></div>
                    <div class="row">
                          
                        <div class="col-lg-2">    
                             <div>
                                <label>Instituição</label>
                                <select name="instituicao" id="instituicao"  class="form-control">
                                    <option value="">Selecione</option>
                                    <?php foreach($instituicao as $item){ ?>
                                         <option value="<?= $item->instituicao_id; ?>" <?= ($item->instituicao_id == @$obj->_instituicao_id) ? "selected": ""; ?>><?= $item->nome; ?></option>
                                   <? } ?>
                                </select> 
                            </div>
                            <div>
                                <label>Endere&ccedil;o</label>
                                <input type="text" id="txtendereco"  class="form-control" name="endereco" value="<?= @$obj->_logradouro; ?>" />
                            </div>
                           
                        </div>
                        <div class="col-lg-2">  
                             <div>
                                <label>N&uacute;mero</label>
                                <input type="text" id="txtNumero"  class="form-control" name="numero" value="<?= @$obj->_numero; ?>" />
                            </div>
                            <div>
                                <label>Bairro</label>
                                <input type="text" id="txtBairro"  class="form-control" name="bairro" value="<?= @$obj->_bairro; ?>" />
                            </div>
                          
                        </div>
                        <div class="col-lg-2"> 
                              <div>
                                <label>Complemento</label>
                                <input type="text" id="txtComplemento"  class="form-control" name="complemento" value="<?= @$obj->_complemento; ?>" />
                            </div>
                            <div>
                                <label>Município</label>
                                <input type="hidden" id="txtCidadeID"  class="form-control" name="municipio_id" value="<?= @$obj->_municipio_id; ?>" readonly="true" />
                                <input type="text" id="txtCidade" class="form-control" name="txtCidade" value="<?= @$obj->_cidade_nome; ?>" />
                            </div>
                          
                        </div>
                        <div class="col-lg-2">   
                            <div>
                                <label>CEP</label>
                                <input type="text" id="txtCep" class="form-control" name="cep" alt="cep" value="<?= @$obj->_cep; ?>" />
                            </div>
                            <div>
                                <label>Telefone</label>
                                <input type="text" id="txtTelefone" class="form-control" name="telefone" alt="phone" value="<?= @$obj->_telefone; ?>" />
                            </div>
                            
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Celular</label>
                                <input type="text" id="txtCelular"  class="form-control" name="celular" alt="phone" value="<?= @$obj->_celular; ?>" />
                            </div>
                            
                        </div>
                    </div>
            </fieldset>
            <br>
            <fieldset>
                <div class="alert alert-primary"><b> Detalhes</b></div>
                    <div class="row">
                            <div class="col-lg-2">
                                    <label for="">Valor por Estagiario</label>
                                    <input type="text" id="valor" class="form-control" name="valor_por_estagio" value="<?=@$obj->_valor_por_estagio?>">    
                            </div>
                        <!-- <div class="col-lg-2">
                            <div>
                                <label>Tabela</label>
                                <select  name="tipo" id="tipo" class="size1" >
                                    <option value="SIGTAP" <?
                                    if (@$obj->_tabela == "SIGTAP"):echo 'selected';
                                    endif;
                                    ?>>SIGTAP</option>
                                    <option value="AMB92" <?
                                    if (@$obj->_tabela == "AMB92"):echo 'selected';
                                    endif;
                                    ?>>AMB92</option>
                                    <option value="TUSS" <?
                                    if (@$obj->_tabela == "TUSS"):echo 'selected';
                                    endif;
                                    ?>>TUSS</option>
                                    <option value="CBHPM" <?
                                    if (@$obj->_tabela == "CBHPM"):echo 'selected';
                                    endif;
                                    ?>>CBHPM</option>
                                    <option value="PROPRIA" <?
                                    if (@$obj->_tabela == "PROPRIA"):echo 'selected';
                                    endif;
                                    ?>>TABELA PROPRIA</option>
                                </select>

                            </div>
                            <div id="ajuste-cbhpmgrupo">
                                <label>Grupo</label>
                                <select name="grupo" id="grupo" class="size1" >
                                    <option value="">Todos</option>
                                    <? foreach ($grupos as $value) : ?>
                                        <option value="<?= $value->nome; ?>" <?= ($value->nome == @$obj->_grupo_cbhpm)? 'selected' : ''?> ><?php echo $value->nome; ?></option>
                                    <? endforeach; ?>
                                </select>

                            </div>
                            <div id="ajuste-cbhpm"> 
                                <label>Ajuste CBHPM Porte (%)</label>
                                <input type="number" id="valor_ajuste_cbhpm" class="form-control" name="valor_ajuste_cbhpm" step="0.01" value="<?= @$obj->_valor_ajuste_cbhpm; ?>" />
                            </div>

                            <div id="ajuste-cbhpmfilme"> 
                                <label>Ajuste CBHPM Filme (Valor)</label>
                                <input type="number" id="valor_ajuste_cbhpm_filme" class="form-control" name="valor_ajuste_cbhpm_filme" step="0.01" value="<?= @$obj->_valor_ajuste_cbhpm_filme; ?>" />
                            </div>

                            <div id="ajuste-cbhpmuco"> 
                                <label>Ajuste CBHPM Uco (%)</label>
                                <input type="number" id="valor_ajuste_cbhpm_uco" class="form-control" name="valor_ajuste_cbhpm_uco" step="0.01" value="<?= @$obj->_valor_ajuste_cbhpm_uco; ?>" />
                            </div>
                            <div>
                                <label>Grupo convenio</label>
                                <select name="grupoconvenio" id="grupoconvenio" class="form-control" >
                                    <option value='' >selecione</option>
                                    <?php
                                    $grupoconvenio = $this->grupoconvenio->listargrupoconvenios();
                                    foreach ($grupoconvenio as $item) {
                                        ?>

                                        <option   value =<?php echo $item->convenio_grupo_id; ?> <?
                                        if (@$obj->_convenio_grupo_id == $item->convenio_grupo_id):echo 'selected';
                                        endif;
                                        ?>><?php echo $item->nome; ?></option>
                                                <?php
                                            }
                                            ?> 
                                </select>
                            </div>

                        </div>
                        <div class="col-lg-2">      
                            <div>
                                <label>IR</label>
                                <input type="text" id="ir" class="form-control" name="ir" alt="decimal" value="<?= @$obj->_ir; ?>" />
                            </div>
                            <div>
                                <label>PIS</label>
                                <input type="text" id="pis" class="form-control" name="pis" alt="decimal" value="<?= @$obj->_pis; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">      
                            <div>
                                <label>COFINS</label>
                                <input type="text" id="cofins" class="form-control" name="cofins" alt="decimal" value="<?= @$obj->_cofins; ?>" />
                            </div>
                            <div>
                                <label>CSLL</label>
                                <input type="text" id="csll" class="form-control" name="csll" alt="decimal" value="<?= @$obj->_csll; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">      
                            <div>
                                <label>Valor do Contrato</label>
                                <input type="text" id="valor_contrato" class="form-control" name="valor_contrato" alt="decimal" value="<?= @$obj->_valor_contrato; ?>" />
                            </div>
                            <div>
                                <label>ISS</label>
                                <input type="text" id="iss" class="form-control" name="iss" alt="decimal" value="<?= @$obj->_iss; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">      
                            <div>
                                <label>Valor Base para Imposto</label>
                                <input type="text" id="valor_base" class="form-control" name="valor_base" alt="decimal" value="<?= @$obj->_valor_base; ?>" />
                            </div>
                            <div>
                                <label>Data entrega</label>
                                <input type="text" id="entrega" class="form-control" name="entrega" alt="integer" value="<?= @$obj->_entrega; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">      
                            <div>
                                <label>Tempo para pagamento</label>
                                <input type="text" id="pagamento" class="form-control" name="pagamento" alt="integer" value="<?= @$obj->_pagamento; ?>" />

                            </div>
                            <div>
                                <label>Dia de Aquisição</label>
                                <input type="number" id="dia_aquisicao" name="dia_aquisicao" class="form-control" value="<?= @$obj->_dia_aquisicao; ?>" max="31" min="1"/>
                            </div>
                        </div>  -->
                    </div>

            </fieldset>
                <br>
            <fieldset>
                <div class="alert alert-primary"><b>Condi&ccedil;&atilde;o de recebimento</b></div>
                    <div class="row">
                        <div class="col-lg-2"> 
                            <div>
                                <label>Credor / Devedor</label>
                                <input type="text" id="credor_devedor" class="form-control" name="credor_devedor" value="<?= @$obj->_credor; ?>" readonly=""/>
                            </div>
                        </div>
                        <div class="col-lg-2"> 
                            <div>
                                <label>Conta</label>
                                <select name="conta" id="conta" class="form-control" >
                                    <option value='' >selecione</option>
                                    <?php
                                    $forma = $this->convenio->listarforma();
                                    // var_dump($forma);
                                    foreach ($forma as $item) {
                                        ?>

                                        <option   value =<?php echo $item->forma_entradas_saida_id; ?> <?
                                        if (@$obj->_conta_id == $item->forma_entradas_saida_id):echo 'selected';
                                        endif;
                                        ?>><?php echo $item->descricao; ?></option>
                                                <?php
                                            }
                                            ?> 
                                </select>
                            </div>
                        </div>
                    </div>

            </fieldset>
            <br>
                <div>
                    <button type="submit" class="btn btn-outline-default btn-sm" name="btnEnviar">Enviar</button>
                    <button type="reset"  class="btn btn-outline-default btn-sm" name="btnLimpar">Limpar</button>
                    <a href="<?= base_url() ?>cadastros/convenio">
                        <button type="button" id="btnVoltar" class="btn btn-outline-default btn-sm">Voltar</button>
                        </a>
                    </div>
                </div>
        </form>
    </div>
</div> <!-- Final da DIV content -->


<script type="text/javascript"> 
   $("#txtCNPJ").mask("99.999.999/9999-99");
   
     jQuery("#txtTelefone")
            .mask("(99) 9999-9999?9")
            .focusout(function (event) {
                var target, phone, element;
                target = (event.currentTarget) ? event.currentTarget : event.srcElement;
                phone = target.value.replace(/\D/g, '');
                element = $(target);
                element.unmask();
                if (phone.length > 10) {
                    element.mask("(99) 99999-999?9");
                } else {
                    element.mask("(99) 9999-9999?9");
                }
            });

   
   $(function () {
            $('#instituicao').change(function () {
                if ($(this).val()) { 
                    $('.carregando').show(); 
                    $.getJSON('<?= base_url() ?>autocomplete/listarinstituicao', {instituicao_id: $(this).val(), ajax: true}, function (j) {
                       $("#txtNumero").val(j[0].numero);
                       $("#txtComplemento").val(j[0].complemento);
                       $("#txtCep").val(j[0].cep);
                       $("#txtendereco").val(j[0].endereco);
                       $("#txtBairro").val(j[0].bairro);
                       $("#txtCidade").val(j[0].municipio);
                       $("#txtCidadeID").val(j[0].municipio_id); 
                       if(j[0].telefone == "" || j[0].telefone == null){
                             $("#txtTelefone").val(j[0].telefone2);  
                       }else{
                             $("#txtTelefone").val(j[0].telefone);  
                       }
//                       alert(j[0].telefone);
                     
                    });
                }else{
                      $("#txtNumero").val("");
                      $("#txtComplemento").val("");
                      $("#txtCep").val("");
                      $("#txtendereco").val("");
                      $("#txtBairro").val("");
                      $("#txtCidade").val("");
                      $("#txtCidadeID").val("");  
                      $("#txtTelefone").val("");  
                }

            });
        });
        
        
        
   
<? if (@$obj->_associado != 't') { ?>
        $("#div_associacao").hide();
<?
}
if (@$obj->_tabela != "CBHPM") {
    ?>
        $("#ajuste-cbhpm").hide();
        $("#ajuste-cbhpmuco").hide();
        $("#ajuste-cbhpmfilme").hide();
        $("#ajuste-cbhpmgrupo").hide();
<? } ?>
    $('#associaconvenio').change(function () {
        if ($(this).is(":checked")) {
            $("#div_associacao").show();
            $("#convenio_associacao").prop('required', true);
            $("#valorpercentual").prop('required', true);

        } else {
            $("#div_associacao").hide();
            $("#convenio_associacao").prop('required', false);
            $("#valorpercentual").prop('required', false);
        }
    });
    var teste = '<? echo $obj->_tabela; ?>';
    if (teste == 'CBHPM' || teste == 'PROPRIA') {
        $("#procedimento1").prop('required', true);
        $("#procedimento2").prop('required', true);
    } else {
        $("#procedimento1").prop('required', false);
        $("#procedimento2").prop('required', false);
    }

    $(function () {
        $('#tipo').change(function () {
            if ($(this).val() == 'CBHPM') {
                $("#ajuste-cbhpm").show();
                $("#ajuste-cbhpmuco").show();
                $("#ajuste-cbhpmfilme").show();
                $("#ajuste-cbhpmgrupo").show();
            } else {
                $("#ajuste-cbhpm").hide();
                $("#ajuste-cbhpmuco").hide();
                $("#ajuste-cbhpmfilme").hide();
                $("#ajuste-cbhpmgrupo").hide();
            }

            if ($(this).val() == 'PROPRIA' || $(this).val() == 'CBHPM') {
                $("#procedimento1").prop('required', true);
                $("#procedimento2").prop('required', true);
            } else {
                $("#procedimento1").prop('required', false);
                $("#procedimento2").prop('required', false);
            }
        });
    });

    $(function () {
        $("#txtCidade").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=cidade",
            minLength: 3,
            focus: function (event, ui) {
                $("#txtCidade").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#txtCidade").val(ui.item.value);
                $("#txtCidadeID").val(ui.item.id);
                return false;
            }
        });
    });
</script>

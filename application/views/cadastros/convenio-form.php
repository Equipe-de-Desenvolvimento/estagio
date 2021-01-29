<? 
$empresapermissoes = $this->guia->listarempresapermissoes();
$medicinadotrabalho = $empresapermissoes[0]->medicinadotrabalho; 

?>
<div class="content ficha_ceatox"> <!-- Inicio da DIV content -->
    <div class="alert alert-primary">Dados do Convenio</div>
        <form name="form_convenio" id="form_convenio" action="<?= base_url() ?>cadastros/convenio/gravar" method="post">
            <fieldset>
            <div class="panel-body">
                <div class="row">
                        <div class="col-lg-4">
                            <label>Nome</label>
                            <input type="hidden" name="txtconvenio_id" class="form-control" value="<?= @$obj->_convenio_id; ?>" />
                            <input type="text" name="txtNome" class="form-control" value="<?= @$obj->_nome; ?>" /> 
                        </div>      
                    <div class="col-lg-2">      
                            <label>Raz&atilde;o social</label>
                            <input type="text" name="txtrazaosocial" class="form-control" value="<?= @$obj->_razao_social; ?>" />
                    </div>
                    <div class="col-lg-2">
                        <div>
                            <label>Teste</label>
                            <input type="text" name="txtcodigo" class="form-control" value="<?= @$obj->_codigoidentificador; ?>" />               
                        </div>
                    </div>
                    <div class="col-lg-2">   
                        <div>
                            <label>CNPJ</label>
                            <input type="text" name="txtCNPJ" alt="cnpj" class="form-control" value="<?= @$obj->_cnpj; ?>" />
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div>
                            <label>Codigo identifica&ccedil;&atilde;o</label>
                            <input type="text" name="txtcodigo" class="form-control" value="<?= @$obj->_codigoidentificador; ?>" />               
                        </div> 

                    </div>
                </div>
            </fieldset>
            <br>
            <fieldset>
                <div class="alert alert-primary">Endereço</div>
                    <div class="row">
                        <div class="col-lg-2">      
                            <div>
                                <label>Endere&ccedil;o</label>
                                <input type="text" id="txtendereco"  class="form-control" name="endereco" value="<?= @$obj->_logradouro; ?>" />
                            </div>
                            <div>
                                <label>N&uacute;mero</label>
                                <input type="text" id="txtNumero"  class="form-control" name="numero" value="<?= @$obj->_numero; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">      
                            <div>
                                <label>Bairro</label>
                                <input type="text" id="txtBairro"  class="form-control" name="bairro" value="<?= @$obj->_bairro; ?>" />
                            </div>
                            <div>
                                <label>Complemento</label>
                                <input type="text" id="txtComplemento"  class="form-control" name="complemento" value="<?= @$obj->_complemento; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">      
                            <div>
                                <label>Município</label>
                                <input type="hidden" id="txtCidadeID"  class="form-control" name="municipio_id" value="<?= @$obj->_municipio_id; ?>" readonly="true" />
                                <input type="text" id="txtCidade" class="texto04" name="txtCidade" value="<?= @$obj->_cidade_nome; ?>" />
                            </div>
                            <div>
                                <label>CEP</label>
                                <input type="text" id="txtCep" class="form-control" name="cep" alt="cep" value="<?= @$obj->_cep; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">      
                            <div>
                                <label>Telefone</label>
                                <input type="text" id="txtTelefone" class="form-control" name="telefone" alt="phone" value="<?= @$obj->_telefone; ?>" />
                            </div>
                            <div>
                                <label>Celular</label>
                                <input type="text" id="txtCelular"  class="form-control" name="celular" alt="phone" value="<?= @$obj->_celular; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>T. logradouro</label>
                                <select name="tipo_logradouro" id="txtTipoLogradouro"  class="form-control" >
                                    <option value='' >selecione</option>
                                    <?php
                                    $listaLogradouro = $this->paciente->listaTipoLogradouro($_GET);
                                    foreach ($listaLogradouro as $item) {
                                        ?>

                                        <option   value =<?php echo $item->tipo_logradouro_id; ?> <?
                                        if (@$obj->_tipo_logradouro == $item->tipo_logradouro_id):echo 'selected';
                                        endif;
                                        ?>><?php echo $item->descricao; ?></option>
                                                <?php
                                            }
                                            ?> 
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <br>
            <fieldset>
                <div class="alert alert-primary">Detalhes</div>
                    <div class="row">
                        <div class="col-lg-2">
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
                            <!-- <div>
                                <label>Primeiro procedimento</label>
                                <input type="text" id="procedimento1" class="texto01" name="procedimento1" alt="integer" value="<?= @$obj->_procedimento1; ?>" />%
                            </div>
                            <div>
                                <label>Outros procedimento</label>
                                <input type="text" id="procedimento2" class="texto01" name="procedimento2" alt="integer" value="<?= @$obj->_procedimento2; ?>" />%
                            </div> -->
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
                        </div>
                    </div>
                </div>
            </fieldset>
            <br>
                <? if ($medicinadotrabalho == "t") { ?>

                    <fieldset>
                        <!-- <legend>Medicina do Trabalho</legend>
                        <div id="divcoordenador">
                            <label>Médico Coordenador</label>
                            <select name="coordenador" id="coordenador" class="size2">
                                <option value="">Selecione</option>
                                <? foreach ($medicos as $item) : ?>
                                    <option value="<?= $item->operador_id; ?>" <?= (@$obj->_coordenador == $item->operador_id) ? 'selected' : '' ?>>
                                        <?= $item->nome; ?>
                                    </option>
                                <? endforeach; ?>
                            </select>
                        </div> -->
                        <div>
                            <?php
                            if (@$obj->_padrao_particular == "t") {
                                ?>
                                <input type="checkbox" name="padrao_particular" checked ="true" />Padrão Particular
                                <?php
                            } else {
                                ?>
                                <input type="checkbox" name="padrao_particular"  />Padrão Particular
                                <?php
                            }
                            ?>
                        </div>
                    </fieldset>
                <? } ?>
                <br>
            <fieldset>
                <div class="alert alert-primary">Condi&ccedil;&atilde;o de recebimento</div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div>
                                <?php
                                if (@$obj->_dinheiro == "t") {
                                    ?>
                                    <input type="checkbox" name="txtdinheiro" checked ="true" />Recebimento em Caixa
                                    <?php
                                } else {
                                    ?>
                                    <input type="checkbox" name="txtdinheiro"  />Recebimento em Caixa
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-lg-2"> 
                            <div>
                                <label>Credor / Devedor</label>
                                <input type="text" id="credor_devedor" class="texto08" name="credor_devedor" value="<?= @$obj->_credor; ?>" readonly=""/>
                            </div>
                        </div>
                        <div class="col-lg-2"> 
                            <div>
                                <label>Conta</label>


                                <select name="conta" id="conta" class="size2" >
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
                </div>
            </fieldset>
            <br>
            <fieldset>
                <div class="alert alert-primary">Fidelidade</div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div>
                                <label>Endereço IP</label>
                                <input type="text" name="fidelidade_endereco_ip" class="form-control" placeholder="Ex: stgfidelidade.ddns.net/fidelidade" value="<?= @$obj->_fidelidade_endereco_ip; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2"> 
                            <div>
                                <label>Parceiro ID</label>
                                <input type="number" name="fidelidade_parceiro_id" class="form-control" value="<?= @$obj->_fidelidade_parceiro_id; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-4"> 
                            <div>
                                <label>Observa&ccedil;&atilde;o</label>
                            </div>
                            <div>
                                <textarea cols="" rows="" name="txtObservacao" class="form-control" class="texto_area"><?= @$obj->_observacao; ?></textarea>
                            </div>
                        <div>
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

<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript">
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

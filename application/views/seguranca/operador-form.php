<div class="panel-body"> <!-- Inicio da DIV content -->
    <div class="bt_link_voltar">
        <a class="btn btn-outline-default btn-round btn-sm" href="<?= base_url() ?>seguranca/operador">
            Voltar
        </a>
    </div>
    <br>

    <div class="alert alert-primary"><b>Cadastro de Operador - Dados do Profissional</b></div>

        <form name="form_operador" id="form_operador" action="<?= base_url() ?>seguranca/operador/gravar" method="post" style="margin-bottom: 50px;">
            <fieldset>
                <div class="row">
                
                <div class="col-lg-4">
                    <label>Nome *</label>                      
                    <input type ="hidden" name ="operador_id" value ="<?= @$obj->_operador_id; ?>" id ="txtoperadorId" >
                    <input type="text" id="txtNome" name="nome"  class="form-control" value="<?= @$obj->_nome; ?>" required="true"/>
                </div>

                <div class="col-lg-2">
                    <label>Sexo *</label> 
                    <select name="sexo" id="txtSexo" class="form-control">
                        <option value="">Selecione</option>
                        <option value="M" <?
                        if (@$obj->_sexo == "M"):echo 'selected';
                        endif;
                        ?>>Masculino</option>
                        <option value="F" <?
                        if (@$obj->_sexo == "F"):echo 'selected';
                        endif;
                        ?>>Feminino</option>
                    </select>
                </div>

                <div class="col-lg-2">
                    <label>Nascimento</label>
                    <input type="text" name="nascimento" id="txtNascimento" class="form-control" alt="date" value="<?php echo substr(@$obj->_nascimento, 8, 2) . '/' . substr(@$obj->_nascimento, 5, 2) . '/' . substr(@$obj->_nascimento, 0, 4); ?>" onblur="retornaIdade()"/>
                </div>

                <div class="col-lg-2">
                    <label>Conselho</label>
                    <input type="text" id="txtconselho" name="conselho"  class="form-control" value="<?= @$obj->_conselho; ?>" />
                </div>

                <?if($empresapermissao[0]->certificado_digital == 't'){?>
                <!-- <div>
                    <label>Certificado Digital Bird ID</label>
                    <input type="text" id="txtcertificado" name="txtcertificado"  class="texto03" value="<?= @$obj->_certificado_digital; ?>" />
                </div> -->
                <?}?>
                <?if($empresapermissao[0]->certificado_digital_manual == 't'){?>

                <div class="col-lg-2">
                    <label>Senha do C. Digital</label>
                    <input type="password" id="senha_cert" name="senha_cert"  class="form_control" value="<?= @$obj->_senha_cert; ?>" />
                </div>
                <?}?>
                
                    <br>

                <div class="col-lg-2"> 
                    <label>CPF *</label>
                    <input type="text" name="cpf" id ="txtCpf" maxlength="11" alt="cpf" class="form-control" value="<?= @$obj->_cpf; ?>" required />
                </div>

                <div class="col-lg-2">
                    <label>Ocupa&ccedil;&atilde;o</label>
                    <input type="hidden" id="txtcboID" class="texto_id" name="txtcboID" value="<?= @$obj->_cbo_ocupacao_id; ?>" readonly="true" />
                    <input type="text" id="txtcbo" class="form-control" name="txtcbo" value="<?= @$obj->_cbo_nome; ?>" />
                </div>
                

                <?
                if (@$empresapermissao[0]->cirugico_manual == 't') {
                    ?>
                    <div class="col-lg-2">
                        <label>Sigla do Conselho</label>  
                        <select name="siglaconselho" id="siglaconselho" class="form-control"> 
                            <option value="" >Escolha</option>
                            <?
                            foreach ($listarsigla as $item) {
                                ?>
                                <input type="checkbox" name="txtsolicitante" <? if (@$obj->_solicitante == "t") echo 'checked' ?> />Médico Solicitante
                                <input type="checkbox" name="ocupacao_painel" <? if (@$obj->_ocupacao_painel == "t") echo 'checked' ?> />Ocupação no Painel
                                <input type="checkbox" name="atendimento_medico" <? if (@$obj->_atendimento_medico == "t") echo 'checked' ?> title="Algumas colunas são retiradas na listagem de atendimentos médicos e no atendimento médico, a caixa de texto não aparece inicialmente"/>Atendimento Médico Dif.
                            <? } ?>

                            <? if (@$empresapermissao[0]->profissional_agendar == 't') { ?>
                                <input type="checkbox" name="profissional_agendar_o" <? if (@$obj->_profissional_agendar_o == "t") echo 'checked' ?> />Médico Agendamento
                            <? } ?>

                            <input type="checkbox" name="medico_cirurgiao" <? if (@$obj->_medico_cirurgiao == "t") echo 'checked' ?> />Médico Cirurgião
                            <input type="checkbox" name="medico_agenda" <? if (@$obj->_medico_agenda == "t") echo 'checked' ?>  title="Ao marcar essa flag, o médico poder realizar agendamentos"/><b style="font-weight: normal;" title="Ao marcar essa flag o médico poderá realizar agendamentos.">Médico Agendar</b>

                            <input type="checkbox" name="profissional_aluguel" <? if (@$obj->_profissional_aluguel == "t") echo 'checked' ?>  title="Ao marcar essa flag, o médico poder realizar agendamentos"/><b style="font-weight: normal;" title="Ao marcar essa flag o médico poderá realizar agendamentos.">Profissional Alugar Sala</b>
                            </div>
                        </div>    

                    <div class="col-lg-2">
                        <label title="UF">UF</label> 
                        <input type="text" name="uf_profissional" id="uf_profissional" value="<?= @$obj->_uf_profissional ?>" class="size1"  max>
                    </div>
                <? } ?>


                <?
                if (@$empresapermissao[0]->tabela_bpa == 't') {
                    ?> 
                    <div class="col-lg-2">
                        <label title="Codigo CNES prof.">Codigo CNES prof.</label> 
                        <input type="text"  maxlength="15" minlength="15"  name="cod_cnes_prof" id="cod_cnes_prof" value="<?= @$obj->_cod_cnes_prof ?>" class="form-control"   >
                    </div> 
                <? } ?>
                <div class="col-lg-2">
                    <label title="Link Reunião Zoom">Link Reunião Zoom</label> 
                    <input type="text" name="link_reuniao" id="link_reuniao" value="<?= @$obj->_link_reuniao?>" class="form-control"  >
                </div>    
                </div>

                <div class="row">
                <?php
                    if (@$obj->_consulta == "t") {
                        ?>
                        &nbsp; &nbsp; <input type="checkbox" name="txtconsulta" checked ="true"/>Realiza Consulta / Exame 
                        <?php
                    } else {
                        ?>
                        &nbsp; &nbsp; <input type="checkbox" name="txtconsulta"  />Realiza Consulta / Exame
                        <?php
                    }

                    if (@$empresapermissao[0]->retirar_flag_solicitante == 'f') {
                        ?>
                        &nbsp; &nbsp; <input type="checkbox" name="txtsolicitante" <? if (@$obj->_solicitante == "t") echo 'checked' ?> /> Médico Solicitante
                        &nbsp; &nbsp; <input type="checkbox" name="ocupacao_painel" <? if (@$obj->_ocupacao_painel == "t") echo 'checked' ?> />Ocupação no Painel
                        &nbsp; &nbsp; <input type="checkbox" name="atendimento_medico" <? if (@$obj->_atendimento_medico == "t") echo 'checked' ?> title="Algumas colunas são retiradas na listagem de atendimentos médicos e no atendimento médico, a caixa de texto não aparece inicialmente"/>Atendimento Médico Dif.
                    <? } ?>

                    <? if (@$empresapermissao[0]->profissional_agendar == 't') { ?>
                        &nbsp; &nbsp; <input type="checkbox" name="profissional_agendar_o" <? if (@$obj->_profissional_agendar_o == "t") echo 'checked' ?> />Médico Agendamento
                    <? } ?>

                    &nbsp; &nbsp; <input type="checkbox" name="medico_cirurgiao" <? if (@$obj->_medico_cirurgiao == "t") echo 'checked' ?> />Médico Cirurgião
                    &nbsp; &nbsp; <input type="checkbox" name="medico_agenda" <? if (@$obj->_medico_agenda == "t") echo 'checked' ?>  title="Ao marcar essa flag, o médico poder realizar agendamentos"/><b style="font-weight: normal;" title="Ao marcar essa flag o médico poderá realizar agendamentos.">Médico Agendar</b>

                    &nbsp; &nbsp; <input type="checkbox" name="profissional_aluguel" <? if (@$obj->_profissional_aluguel == "t") echo 'checked' ?>  title="Ao marcar essa flag, o médico poder realizar agendamentos"/><b style="font-weight: normal;" title="Ao marcar essa flag o médico poderá realizar agendamentos.">Profissional Alugar Sala</b>
                </div>
                
            </fieldset>
                  
                    <br>
         <div class="alert alert-primary"><b> Domicilio </b> </div>
            <fieldset>

            <div class="row">
                <div class="col-lg-2">
                    <label>T. logradouro</label>
                    <select name="tipo_logradouro" id="txtTipoLogradouro" class="form-control" >
                        <option value='' >selecione</option>
                        <?php
                        $listaLogradouro = $this->paciente->listaTipoLogradouro($_GET);
                        foreach ($listaLogradouro as $item) {
                            ?>
                            <option   value =<?php echo $item->tipo_logradouro_id; ?> 
                            <?
                            if (@$obj->_tipoLogradouro == $item->tipo_logradouro_id):echo 'selected';
                            endif;
                            ?>><?php echo $item->descricao; ?></option>
                                  <?php } ?> 
                    </select>
                </div>


                <div class="col-lg-4">
                    <label>Endere&ccedil;o</label>
                    <input type="text" id="txtendereco" class="form-control" name="endereco" value="<?= @$obj->_logradouro; ?>" />
                </div>


                <div class="col-lg-2">
                    <label>N&uacute;mero</label>
                    <input type="text" id="txtNumero" class="form-control" name="numero" value="<?= @$obj->_numero; ?>" />
                </div>

                <div class="col-lg-2">
                    <label>Bairro</label>
                    <input type="text" id="txtBairro" class="form-control" name="bairro" value="<?= @$obj->_bairro; ?>" />
                </div>


                <div class="col-lg-2">
                    <label>Complemento</label>
                    <input type="text" id="txtComplemento" class="form-control" name="complemento" value="<?= @$obj->_complemento; ?>" />
                </div>

                <div class="col-lg-2">
                    <label>Município</label>
                    <input type="hidden" id="txtCidadeID" class="texto_id" name="municipio_id" value="<?= @$obj->_cidade; ?>" readonly="true" />
                    <input type="text" id="txtCidade" class="form-control" name="txtCidade" value="<?= @$obj->_cidade_nome; ?>" />
                </div>


                <div class="col-lg-2">
                    <label>CEP</label>
                    <input type="text" id="txtCep" class="form-control" name="cep" alt="cep" value="<?= @$obj->_cep; ?>" />
                </div>

                <?
                if (@$obj->_telefone != '' && strlen(@$obj->_telefone) > 3) {

                    if (preg_match('/\(/', @$obj->_telefone)) {
                        $telefone = @$obj->_telefone;
                    } else {
                        $telefone = "(" . substr(@$obj->_telefone, 0, 2) . ")" . substr(@$obj->_telefone, 2, strlen(@$obj->_telefone) - 2);
                    }
                } else {
                    $telefone = '';
                }
                if (@$obj->_celular != '' && strlen(@$obj->_celular) > 3) {
                    if (preg_match('/\(/', @$obj->_celular)) {
                        $celular = @$obj->_celular;
                    } else {
                        $celular = "(" . substr(@$obj->_celular, 0, 2) . ")" . substr(@$obj->_celular, 2, strlen(@$obj->_celular) - 2);
                    }
                } else {
                    $celular = '';
                }
                ?>

                <div class="col-lg-2">
                    <label>Telefone</label>  
                    <input type="text" id="txtTelefone" class="form-control" name="telefone"  value="<?= $telefone ?>" />
                </div>

                <div class="col-lg-2">
                    <label>Celular *</label>
                    <input type="text" id="txtCelular" class="form-control" name="celular" value="<?= $celular; ?>" required="true"/>
                </div>

                <div class="col-lg-3">
                    <label>E-mail *</label>
                    <input type="text" id="txtemail" class="form-control" name="email" value="<?= @$obj->_email; ?>" />
                </div>
            </div>

            </fieldset>

                <br>
                <div class="alert alert-primary"><b>Acesso</b></div>
            <fieldset>

                <div class="row">

                    <div class="col-lg-3">
                        <label>Nome usu&aacute;rio *</label>
                        <input type="text" id="txtUsuario" name="txtUsuario"  class="form-control" value="<?= @$obj->_usuario; ?>" required="true"/>
                    </div>

                    <div class="col-lg-3">
                        <label>Senha: *</label>
                        <input type="password" name="txtSenha" id="txtSenha" class="form-control" value="" <? if (@$obj->_senha == null) {
                        ?>
                                required="true"
                            <? } ?> />
                    </div>

                    <div class="col-lg-3">
                        <label>Tipo perfil *</label>
                        <select name="txtPerfil" id="txtPerfil" class="form-control" required="true">
                            <option value="">Selecione</option>
                            <?
                            foreach ($listarPerfil as $item) :
                                if ($this->session->userdata('perfil_id') == 1) {
                                    ?>
                                    <option value="<?= $item->perfil_id; ?>"<?
                                    if (@$obj->_perfil_id == $item->perfil_id):echo 'selected';
                                    endif;
                                    ?>>
                                        <?= $item->nome; ?></option>
                                    <?
                                } else {
                                    if (!($item->perfil_id == 1)) {
                                        ?>
                                        <option value="<?= $item->perfil_id; ?>"<?
                                        if (@$obj->_perfil_id == $item->perfil_id):echo 'selected';
                                        endif;
                                        ?>><?= $item->nome; ?></option>
                                                <?
                                            }
                                        }
                                        ?>
                                    <? endforeach; ?>
                        </select>
                    </div>
                </div>
            </fieldset>


            <? if (@$empresapermissao[0]->profissional_externo == 't') { ?>
                <fieldset>
                    <legend>Sistema Externo</legend>
                    <div>
                        <label >Endereço (Ex:http://stgclinica.ddns.net/stgsaude)</label>

                        <input type="text" id="endereco_sistema" name="endereco_sistema"  class="texto08" value="<?= @$obj->_endereco_sistema; ?>" />
                    </div>



                </fieldset>
            <? }
            ?>

                <br>
            <div class="alert alert-primary"><b>Financeiro</b></div>

            <fieldset>
            <div class="row">
                <div class="col-lg-3">
                    <label>Credor / Devedor</label>
                    <input type="text" id="credor_devedor" class="form-control" name="credor_devedor" value="<?= @$obj->_credor; ?>" readonly=""/>
                </div>

                <div class="col-lg-2">
                    <label>Conta</label>
                    <select name="conta" id="conta" class="form-control" >
                        <option value='' >Selecione</option>
                        <?php
                        $conta = $this->forma->listarforma();

                        foreach ($conta as $item) {
                            ?> 
                            <option   value =<?php echo $item->forma_entradas_saida_id; ?> <?
                            if (@$obj->_conta_id == $item->forma_entradas_saida_id):echo 'selected';
                            endif;
                            ?>><?php echo $item->descricao . " - " . $item->empresa; ?></option>
                                      <?php
                                  }
                                  ?> 
                    </select>
                    <?
//                    var_dump($conta); die;
                    ?>
                </div>
                <div class="col-lg-2">
                    <label>Tipo</label>


                    <select name="tipo" id="tipo" class="form-control">
                        <option value='' >Selecione</option>
                        <?php
                        $tipo = $this->tipo->listartipo();

                        foreach ($tipo as $item) {
                            ?>

                            <option   value = "<?= $item->descricao; ?>" <?
                            if (@$obj->_tipo_id == $item->descricao):echo 'selected';
                            endif;
                            ?>><?php echo $item->descricao; ?></option>
                                      <?php
                                  }
                                  ?> 
                    </select>
                </div>
                
               
                <div class="col-lg-2">
                    <label>Conta</label>
                    <input type="text"  name="txtConta"  class="form-control" id="txtConta"  value="<?= @$obj->_conta; ?>"> 
                </div> 
                
                <div class="col-lg-2">
                    <label>Agência</label>
                    <input type="text"  name="txtAgencia"  class="form-control" id="txtAgencia" value="<?= @$obj->_agencia; ?>" > 
                </div>  
                <div class="col-lg-2">
                    <label>Classe</label> 

                    <select name="classe" id="classe" class="form-control">
                        <option value="">Selecione</option>
                        <? foreach ($classe as $value) : ?>
                            <option value="<?= $value->descricao; ?>"
                            <?
                            if ($value->descricao == @$obj->_classe):echo'selected';
                            endif;
                            ?>
                                    ><?php echo $value->descricao; ?></option>
                                <? endforeach; ?>
                    </select>
                </div>

                <div class="col-lg-3">
                    <label>Desconto / Seguro</label>
                    <input type="text"  alt="decimal" class="form-control" name="desconto_seguro" value="<?= @$obj->_desconto_seguro; ?>"  >
                </div>

                <div class="col-lg-2">
                <br>
                <select name="tipo_desc_seguro" id="classe" class="form-control">
                        <option value="percentual" <?= (@$obj->_tipo_desc_seguro == "percentual") ? "selected":""; ?>>Percentual</option>
                        <option value="fixo"  <?= (@$obj->_tipo_desc_seguro == "fixo") ? "selected":""; ?>>fixo</option>     
                    </select>
                </div>

                </div>
                </fieldset>
                        <br>

                        <div class="alert alert-primary">Impostos e Taxas</div>
                <fieldset>
                    <? if (@$empresapermissao[0]->desativar_taxa_administracao == 'f') { ?>
                    <div class="row">
                        <div class="col-lg-3">
                            <label>Taxa Administração Percentual</label>
                            <select name="taxaadm_perc" id="taxaadm_perc" class="form-control">
                                <option value="NAO" <?
                                if (@$obj->_taxaadm_perc == 'NAO'):echo 'selected';
                                endif;
                                ?>>NÃO</option>
                                <option value="SIM" <?
                                if (@$obj->_taxaadm_perc == 'SIM'):echo 'selected';
                                endif;
                                ?>>SIM</option>
                                <option value="FIXO" <?
                                if (@$obj->_taxaadm_perc == 'FIXO'):echo 'selected';
                                endif;
                                ?>>FIXO</option>
                            </select>
                        </div>

                        <div class="col-lg-2">
                            <label>Taxa Administração</label>
                            <input type="text" id="taxaadm" class="form-control" name="taxaadm" alt="decimal" value="<?= @$obj->_taxa_administracao; ?>" />
                        </div>
                    <? } ?>
                    <div class="col-lg-2">
                        <label>IR</label>
                        <input type="text" id="ir" class="form-control" name="ir" alt="decimal" value="<?= @$obj->_ir; ?>" />
                    </div>

                    <div class="col-lg-2">
                        <label>PIS</label>
                        <input type="text" id="pis" class="form-control" name="pis" alt="decimal" value="<?= @$obj->_pis; ?>" />
                    </div>

                    <div class="col-lg-2">
                        <label>COFINS</label>
                        <input type="text" id="cofins" class="form-control" name="cofins" alt="decimal" value="<?= @$obj->_cofins; ?>" />
                    </div>

                    <div class="col-lg-2">
                        <label>CSLL</label>
                        <input type="text" id="csll" class="form-control" name="csll" alt="decimal" value="<?= @$obj->_csll; ?>" />
                    </div>

                    <div class="col-lg-2">
                        <label>ISS</label>
                        <input type="text" id="iss" class="form-control" name="iss" alt="decimal" value="<?= @$obj->_iss; ?>" />
                    </div>

                    <div class="col-lg-2">
                        <label>Valor Base para Imposto</label>
                        <input type="text" id="valor_base" class="form-control" name="valor_base" alt="decimal" value="<?= @$obj->_valor_base; ?>" />
                    </div>

                    <div class="col-lg-2">
                        <label>Piso Produção</label>
                        <input type="text" id="piso_medico" class="form-control" name="piso_medico" alt="decimal" value="<?= @$obj->_piso_medico; ?>" />
                    </div>

                    </div>
                </fieldset>
                
            </fieldset>

                <br>
            <fieldset style="dislpay:block">
                <button class="btn btn-outline-default btn-round btn-sm" type="submit" name="btnEnviar">Enviar</button>
                <button class="btn btn-outline-default btn-round btn-sm" type="reset" name="btnLimpar">Limpar</button>
                <button class="btn btn-outline-default btn-round btn-sm" type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
            </fieldset>

        </form>
         
        
        <?php
        if (@$obj->_perfil_id == 4 || @$obj->_perfil_id == 19 || @$obj->_perfil_id == 22 || @$obj->_perfil_id == 1) {
            if (count($documentos > 0)) {
                ?>
                <fieldset style="dislpay:block">
                    <legend>Documentação Profissional</legend>
                    <table  class="table table-striped table-bordered table-hover" border='2'>
                        <thead>
                            <tr>
                                <th  class="tabela_header">Nome</th>
                                <th  class="tabela_header">Possui</th>
                            </tr>
                        </thead>
                        <?php
                        foreach ($documentos as $item) {
                            $arquivo_pasta = directory_map("./upload/arquivosoperador/$obj->_operador_id/$item->documentacao_profissional_id");
                            if ($arquivo_pasta != false) {
                                sort($arquivo_pasta);
                            }
                            $i = 0;
                            if ($arquivo_pasta != false) {
                                foreach ($arquivo_pasta as $value) {
                                    @$verificardoc{$item->documentacao_profissional_id} ++;
                                    continue;
                                }
                            }
                        }


                        $estilo_linha = "tabela_content01";
                        foreach ($documentos as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->nome ?></td>
                                <td class="<?php echo $estilo_linha; ?>"> <?php
                if (@$verificardoc{$item->documentacao_profissional_id} > 0) {
                    echo "sim";
                } else {
                    echo "não";
                }
                            ?> </td>
                            </tr>
                                    <?
                                }
                                ?>


                    </table>
                </fieldset>        
                <br><br>
        <?php
    }
}
?>

</div> 


<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>
<script>
                        function mascaraTelefone(campo) {

                            function trata(valor, isOnBlur) {

                                valor = valor.replace(/\D/g, "");
                                valor = valor.replace(/^(\d{2})(\d)/g, "($1)$2");

                                if (isOnBlur) {

                                    valor = valor.replace(/(\d)(\d{4})$/, "$1-$2");
                                } else {

                                    valor = valor.replace(/(\d)(\d{3})$/, "$1-$2");
                                }
                                return valor;
                            }

                            campo.onkeypress = function (evt) {

                                var code = (window.event) ? window.event.keyCode : evt.which;
                                var valor = this.value

                                if (code > 57 || (code < 48 && code != 8 && code != 0)) {
                                    return false;
                                } else {
                                    this.value = trata(valor, false);
                                }
                            }

                            campo.onblur = function () {

                                var valor = this.value;
                                if (valor.length < 13) {
                                    this.value = ""
                                } else {
                                    this.value = trata(this.value, true);
                                }
                            }

                            campo.maxLength = 14;
                        }


</script>
<script type="text/javascript">


function mostrarDadosExtras(){
            var botao = $("#mostrarDadosExtra").text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtra").text('-');
            } else {
                $("#mostrarDadosExtra").text('+');
            }                                       
            $("#coresagendaid").toggle();

    }

   
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

    jQuery("#txtCelular")
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

    $('#btnVoltar').click(function () {
        $(location).attr('href', '<?= base_url(); ?>sca/operador');
    });

    function confirmaSenha(verificacao) {
        var senha = $("#txtSenha");
        if (verificacao.value != senha.val()) {
            verificacao.setCustomValidity("As senhas não correspondem!");
        } else {
            verificacao.setCustomValidity("");
        }
    }

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


    $(function () {
        $('#tipo').change(function () {
            if ($(this).val()) {
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/classeportiposaidalistadescricao', {nome: $(this).val(), ajax: true}, function (j) {
                    options = '<option value=""></option>';
                    for (var c = 0; c < j.length; c++) {
                        options += '<option value="' + j[c].classe + '">' + j[c].classe + '</option>';
                    }
                    $('#classe').html(options).show();
                    $('.carregando').hide();
                });
            } else {
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/classeportiposaidalistadescricaotodos', {nome: $(this).val(), ajax: true}, function (j) {
                    options = '<option value=""></option>';
                    for (var c = 0; c < j.length; c++) {
                        options += '<option value="' + j[c].classe + '">' + j[c].classe + '</option>';
                    }
                    $('#classe').html(options).show();
                    $('.carregando').hide();
                });
            }
        });
    });

    $(function () {
        $("#txtcbo").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=cboprofissionais",
            minLength: 3,
            focus: function (event, ui) {
                $("#txtcbo").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#txtcbo").val(ui.item.value);
                $("#txtcboID").val(ui.item.id);
                return false;
            }
        });
    });


    tinyMCE.init({
        // General options
        mode: "textareas",
        theme: "advanced",
        plugins: "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",
        // Theme options
        theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,pagebreak,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,image",
        theme_advanced_buttons2: "styleselect,formatselect,fontselect,fontsizeselect",
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




    function frm_number_only_exc() {
// allowed: numeric keys, numeric numpad keys, backspace, del and delete keys
        if (event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || (event.keyCode < 106 && event.keyCode > 95)) {
            return true;
        } else {
            return false;
        }
    }

    $(document).ready(function () {

        $("input#cod_cnes_prof").keydown(function (event) {

            if (frm_number_only_exc()) {

            } else {
                if (event.keyCode < 48 || event.keyCode > 57) {
                    event.preventDefault();
                }
            }
        });

    });


  $(function () {
        $('#grupo1').change(function () { 
            if ($(this).val()) { 
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/carregarprocedimentoslimite', {grupo: $('#grupo1').val(), ajax: true}, function (j) {                 
                 
                    var options = '<option value=""></option>';
                    for (var c = 0; c < j.length; c++) {
                        options += '<option value="' + j[c].procedimento_tuss_id + '">' + j[c].procedimento + '</option>';
                    }  
                    $('#procedimento1 option').remove();
                    $('#procedimento1').append(options);
                    $("#procedimento1").trigger("chosen:updated");
                    $('.carregando').hide();
                });
            } else {
                $('#procedimento1 option').remove();
                $('#procedimento1').append('');
                $("#procedimento1").trigger("chosen:updated");
            } 
            
        });
    }); 
    
    
     
    
    function adicionarLimiteProcedimento(){
       var procedimento_tuss_id = $("#procedimento1").val(); 
       var empresa_id = $("#empresa").val();
       var quantidade = $("#qtd_retorno_dia").val();
       var medico_id = $("#txtoperadorId").val();
       
       if(procedimento_tuss_id > 0){
//           alert(procedimento_convenio_id); 
           $.ajax({
                type: "POST",
                data: {
                    procedimento_tuss_id: procedimento_tuss_id, 
                    empresa_id:empresa_id,
                    quantidade:quantidade,
                    medico_id:medico_id
                    },
                url: "<?= base_url() ?>ambulatorio/exametemp/adicionarlimiteprocedimento",
                success: function (data) {
                    alert(data); 
                    window.location.href = "<?= base_url(); ?>seguranca/operador/alterar/"+$("#txtoperadorId").val();
                },
                error: function (data) {
                    console.log(data);
                }
            });
           
        }else{
            alert("Favor, escolha um procedimento");
        }
       
    }


 
 
</script>

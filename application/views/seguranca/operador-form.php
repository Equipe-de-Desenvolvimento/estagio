<div class="content ficha_ceatox"> <!-- Inicio da DIV content -->
    <div class="bt_link_voltar">
        <a href="<?= base_url() ?>seguranca/operador">
            Voltar
        </a>
    </div>

    <h3 class="singular"><a href="#">Cadastro de Operador</a></h3>
    <div>
        <form name="form_operador" id="form_operador" action="<?= base_url() ?>seguranca/operador/gravar" method="post" style="margin-bottom: 50px;">
            <fieldset>
                <legend>Dados do Profissional</legend>
                <div>
                    <label>Nome *</label>                      
                    <input type ="hidden" name ="operador_id" value ="<?= @$obj->_operador_id; ?>" id ="txtoperadorId" >
                    <input type="text" id="txtNome" name="nome"  class="texto09" value="<?= @$obj->_nome; ?>" required="true"/>
                </div>
                <div>
                    <label>Sexo *</label> 
                    <select name="sexo" id="txtSexo" class="size2">
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

                <div>
                    <label>Nascimento</label>


                    <input type="text" name="nascimento" id="txtNascimento" class="texto02" alt="date" value="<?php echo substr(@$obj->_nascimento, 8, 2) . '/' . substr(@$obj->_nascimento, 5, 2) . '/' . substr(@$obj->_nascimento, 0, 4); ?>" onblur="retornaIdade()"/>
                </div>
                <div>
                    <label>Conselho</label>
                    <input type="text" id="txtconselho" name="conselho"  class="texto04" value="<?= @$obj->_conselho; ?>" />
                </div>

                <?if($empresapermissao[0]->certificado_digital == 't'){?>
                <!-- <div>
                    <label>Certificado Digital Bird ID</label>
                    <input type="text" id="txtcertificado" name="txtcertificado"  class="texto03" value="<?= @$obj->_certificado_digital; ?>" />
                </div> -->
                <?}?>
                <?if($empresapermissao[0]->certificado_digital_manual == 't'){?>
                <div>
                    <label>Senha do C. Digital</label>
                    <input type="password" id="senha_cert" name="senha_cert"  class="texto03" value="<?= @$obj->_senha_cert; ?>" />
                </div>
                <?}?>
                
                <div>
                    <label>Cor do Mapa</label>
                    <input type="color" id="txtcolor" name="txtcolor"  class="texto04" value="<?= @$obj->_cor_mapa; ?>" />
                </div>
                    <br>

                <div>
                    <label>CPF *</label>


                    <input type="text" name="cpf" id ="txtCpf" maxlength="11" alt="cpf" class="texto02" value="<?= @$obj->_cpf; ?>" required />
                </div>
                <div>
                    <label>Ocupa&ccedil;&atilde;o</label>
                    <input type="hidden" id="txtcboID" class="texto_id" name="txtcboID" value="<?= @$obj->_cbo_ocupacao_id; ?>" readonly="true" />
                    <input type="text" id="txtcbo" class="texto04" name="txtcbo" value="<?= @$obj->_cbo_nome; ?>" />


                    <?php
                    if (@$obj->_consulta == "t") {
                        ?>
                        <input type="checkbox" name="txtconsulta" checked ="true"/>Realiza Consulta / Exame
                        <?php
                    } else {
                        ?>
                        <input type="checkbox" name="txtconsulta"  />Realiza Consulta / Exame
                        <?php
                    }

                    if (@$empresapermissao[0]->retirar_flag_solicitante == 'f') {
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
                
                <div>
                    <label>Grupos</label>
                    <select data-placeholder="Selecione um ou mais grupo" name="grupo_agenda[]" id="grupo_agenda" class="size2 chosen-select" multiple>
                        <?
                      
                        
                        if (@$obj->_grupo_agenda != '') {
                            $gruposExi = json_decode(@$obj->_grupo_agenda);
                        } else {
                            $gruposExi = array();
                        }
                        
                        ?>
                        <?foreach ($grupos as $key => $value) {?>
                            <option <?= (@in_array($value->nome, $gruposExi)) ? 'selected' : ''; ?> value="<?=$value->nome?>"><?=$value->nome?></option>
                        <?}?>
                    </select>
                </div>

                <?
                if (@$empresapermissao[0]->cirugico_manual == 't') {
                    ?>
                    <div>
                        <label>Sigla do Conselho</label>  
                        <select name="siglaconselho" id="siglaconselho" > 
                            <option value="" >Escolha</option>
                            <?
                            foreach ($listarsigla as $item) {
                                ?>
                                <option value="<?= $item->sigla_id; ?>" 
                                <?
                                if (@$obj->_sigla_id == $item->sigla_id) {
                                    echo "Selected";
                                } else {
                                    
                                }
                                ?>
                                        title="<?= $item->nome ?>"><?= $item->nome ?></option>
                                        <?
                                    }
                                    ?> 
                        </select>
                    </div> 
                    <div>
                        <label title="UF">UF</label> 
                        <input type="text" name="uf_profissional" id="uf_profissional" value="<?= @$obj->_uf_profissional ?>" class="size1"  max>
                    </div>
                <? } ?>


                <?
                if (@$empresapermissao[0]->tabela_bpa == 't') {
                    ?> 
                    <div>
                        <label title="Codigo CNES prof.">Codigo CNES prof.</label> 
                        <input type="text"  maxlength="15" minlength="15"  name="cod_cnes_prof" id="cod_cnes_prof" value="<?= @$obj->_cod_cnes_prof ?>" class="size1"   >
                    </div> 
                <? } ?>
                <div>
                    <label title="Link Reunião Zoom">Link Reunião Zoom</label> 
                    <input type="text" name="link_reuniao" id="link_reuniao" value="<?= @$obj->_link_reuniao?>" class="texto08"  >
                </div>    
                
                <div>
                    <label title="Link Reunião Zoom">Faixa Etaria Atendimento Agenda</label> 
                    <input type="number" name="faixa_etaria" id="faixa_etaria" value="<?= @$obj->_faixa_etaria; ?>" class="texto01"  >
                    à
                    <input type="number" name="faixa_etaria_final" id="faixa_etaria_final" value="<?= @$obj->_faixa_etaria_final; ?>" class="texto01"  > Anos
                </div> 


                <div>
                <label>Cor da Agenda <button  type="button" id="mostrarDadosExtra" onclick="mostrarDadosExtras()">+</button></label>
                    <div id="coresagendaid" hidden>
                    <table>
                        <tr>
                            <td><input type="radio" name="coragenda" <?=(@$obj->_coragenda == "1" ? "checked" : "" );?> value="1"><span style="width:20px;height:20px;border-radius:4px;background-color:#7986cb;display:inline-block;margin-right:15px"></span></td>
                            <td><input type="radio" name="coragenda" <?=(@$obj->_coragenda == "2" ? "checked" : "" );?> value="2"><span style="width:20px;height:20px;border-radius:4px;background-color:#33b679;display:inline-block;margin-right:15px"></span></td>
                            <td><input type="radio" name="coragenda" <?=(@$obj->_coragenda == "3" ? "checked" : "" );?> value="3"><span style="width:20px;height:20px;border-radius:4px;background-color:#8e24aa;display:inline-block;margin-right:15px"></span></td>
                            <td><input type="radio" name="coragenda" <?=(@$obj->_coragenda == "4" ? "checked" : "" );?> value="4"><span style="width:20px;height:20px;border-radius:4px;background-color:#e67c73;display:inline-block;margin-right:15px"></span></td>
                            <td><input type="radio" name="coragenda" <?=(@$obj->_coragenda == "5" ? "checked" : "" );?> value="5"><span style="width:20px;height:20px;border-radius:4px;background-color:#f6c026;display:inline-block;margin-right:15px"></span></td>
                            <td><input type="radio" name="coragenda" <?=(@$obj->_coragenda == "6" ? "checked" : "" );?> value="6"><span style="width:20px;height:20px;border-radius:4px;background-color:#f5511d;display:inline-block;margin-right:15px"></span></td>
                            <td><input type="radio" name="coragenda" <?=(@$obj->_coragenda == "7" ? "checked" : "" );?> value="7"><span style="width:20px;height:20px;border-radius:4px;background-color:#039be5;display:inline-block;margin-right:15px"></span></td>
                            <td><input type="radio" name="coragenda" <?=(@$obj->_coragenda == "8" ? "checked" : "" );?> value="8"><span style="width:20px;height:20px;border-radius:4px;background-color:#616161;display:inline-block;margin-right:15px"></span></td>
                            <td><input type="radio" name="coragenda" <?=(@$obj->_coragenda == "9" ? "checked" : "" );?> value="9"><span style="width:20px;height:20px;border-radius:4px;background-color:#3f51b5;display:inline-block;margin-right:15px"></span></td>
                            <td><input type="radio" name="coragenda" <?=(@$obj->_coragenda == "10" ? "checked" : "" );?> value="10"><span style="width:20px;height:20px;border-radius:4px;background-color:#0b8043;display:inline-block;margin-right:15px"></span></td>
                            <td><input type="radio" name="coragenda" <?=(@$obj->_coragenda == "11" ? "checked" : "" );?> value="11"><span style="width:20px;height:20px;border-radius:4px;background-color:#d60000;display:inline-block;margin-right:15px"></span></td>
                            
                        </tr>
                    </table>
                    </div>
                </div>
                
            </fieldset>
          <?php if(isset($obj->_operador_id) && $obj->_operador_id != ""){?> 
            <fieldset id="limite">      
                <legend>Config. Nº limite de atendimentos p/dia</legend> 
                <div>
                     <label>Empresa</label>
                       <select name="empresa" id="empresa" class="size2">  
                            <?
                            $empresas = $this->exame->listarempresas();  
                            $empresa_logada = $this->session->userdata("empresa_id");
                            $selected = false;
                            foreach ($empresas as $value) :  ?>
                                <option value="<?= $value->empresa_id; ?>" <?
                                    if ($empresa_logada == $value->empresa_id ) {
                                         echo 'selected'; 
                                    } 
                                ?>><?php echo $value->nome; ?></option>
                               <? endforeach; ?>
                     </select>
                </div> 

                <div>
                    <label >Grupo</label>
                   <select  name="grupo1" id="grupo1" class="size1" >
                      <option value="">Selecione</option>
                      <?
                      foreach ($grupos as $item) :
                          ?>
                          <option value="<?= $item->nome; ?>"  >
                              <?= $item->nome; ?>
                          </option>
                      <? endforeach; ?>
                  </select>
                </div>

                <div> 
                      <label >Procedimento</label>
                    <select  name="procedimento1" id="procedimento1" class="size1"  >
                        <option value="">Selecione</option>
                    </select> 
                </div>
                
                <div>
                    <label title="Quantidade de retorno que o medico pode realizar em um dia. 0 para infinito">Quantidade de Retornos p/dia</label> 
                    <input type="number" name="qtd_retorno_dia" id="qtd_retorno_dia"   class="texto01"  >
                </div> 
                <div>
                    <label >&nbsp;</label>
                    <button type="button" onclick="adicionarLimiteProcedimento()" >Adicionar</button>
                </div> 
                
                    <table border='2'>
                        <thead>
                            <tr>
                                <th  class="tabela_header">Procedimento</th> 
                                <th  class="tabela_header">Empresa</th>  
                                <th  class="tabela_header">Quantidade</th> 
                                <th  class="tabela_header" width="90px;" style="text-align: center;">Ações</th> 
                            </tr>  
                        </thead> 
                       
                        <?  
                        $estilo_linha = "tabela_content01";
                        foreach ($limite_procedimento as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->procedimento ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->empresa ?></td> 
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->quantidade ?></td>
                                <td  class="<?php echo $estilo_linha; ?>" >
                                    <div class="bt_link" >
                                        <a  onclick="javascript:window.open('<?= base_url(); ?>ambulatorio/exametemp/excluirlimiteprocedimento/<?= $item->limite_procedimento_id; ?>', '_blank', 'toolbar=no,Location=no,menubar=no,\n\width=500,height=400');"  >Excluir</a>
                                    </div>
                                </td> 
                            </tr>
                        <?  }  ?> 
                    </table>   
            </fieldset>
                 <?php }?>
            
            
            
         
            <fieldset>
                <legend>Domicilio</legend>

                <div>
                    <label>T. logradouro</label>


                    <select name="tipo_logradouro" id="txtTipoLogradouro" class="size2" >
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
                <div>
                    <label>Endere&ccedil;o</label>


                    <input type="text" id="txtendereco" class="texto10" name="endereco" value="<?= @$obj->_logradouro; ?>" />
                </div>
                <div>
                    <label>N&uacute;mero</label>


                    <input type="text" id="txtNumero" class="texto02" name="numero" value="<?= @$obj->_numero; ?>" />
                </div>
                <div>
                    <label>Bairro</label>


                    <input type="text" id="txtBairro" class="texto03" name="bairro" value="<?= @$obj->_bairro; ?>" />
                </div>
                <div>
                    <label>Complemento</label>


                    <input type="text" id="txtComplemento" class="texto06" name="complemento" value="<?= @$obj->_complemento; ?>" />
                </div>

                <div>
                    <label>Município</label>


                    <input type="hidden" id="txtCidadeID" class="texto_id" name="municipio_id" value="<?= @$obj->_cidade; ?>" readonly="true" />
                    <input type="text" id="txtCidade" class="texto04" name="txtCidade" value="<?= @$obj->_cidade_nome; ?>" />
                </div>
                <div>
                    <label>CEP</label>


                    <input type="text" id="txtCep" class="texto02" name="cep" alt="cep" value="<?= @$obj->_cep; ?>" />
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

                <div>
                    <label>Telefone</label>  
                    <input type="text" id="txtTelefone" class="texto02" name="telefone"  value="<?= $telefone ?>" />
                </div>
                <div>
                    <label>Celular *</label>


                    <input type="text" id="txtCelular" class="texto02" name="celular" value="<?= $celular; ?>" required="true"/>
                </div>
                <div>
                    <label>E-mail *</label>
                    <input type="text" id="txtemail" class="texto06" name="email" value="<?= @$obj->_email; ?>" />
                </div>
            </fieldset>
            <fieldset>
                <legend>Acesso</legend>
                <div>
                    <label>Nome usu&aacute;rio *</label>

                    <input type="text" id="txtUsuario" name="txtUsuario"  class="texto04" value="<?= @$obj->_usuario; ?>" required="true"/>
                </div>
                <div>
                    <label>Senha: *</label>
                    <input type="password" name="txtSenha" id="txtSenha" class="texto04" value="" <? if (@$obj->_senha == null) {
                    ?>
                               required="true"
                           <? } ?> />

                    <!--                    <label>Confirme a Senha: *</label>
                                        <input type="password" name="verificador" id="txtSenha" class="texto04" value="" onblur="confirmaSenha(this)"/>-->
                </div>
                <div>
                    <label>Tipo perfil *</label>

                    <select name="txtPerfil" id="txtPerfil" class="size4" required="true">
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
            <fieldset>
                <legend>Financeiro</legend>
                <!--                <div>
                                    <label>Criar Credor</label>
                                    <input type="checkbox" name="criarcredor"/></div>-->

                <div>



                    <label>Credor / Devedor</label>
                    <input type="text" id="credor_devedor" class="texto08" name="credor_devedor" value="<?= @$obj->_credor; ?>" readonly=""/>
<!--                    <select name="credor_devedor" id="credor_devedor" class="size4" disabled="">
                        <option value='' >Selecione</option>
                    <?php
//                        $credor_devedor = $this->convenio->listarcredordevedor();
//                        foreach ($credor_devedor as $item) {
                    ?>

                            <option   value =<?php echo $item->financeiro_credor_devedor_id; ?> <?
//                            if (@$obj->_credor_devedor_id == $item->financeiro_credor_devedor_id):echo 'selected';
//                            endif;
                    ?>><?php // echo $item->razao_social;  ?></option>
                    <?php
//                                  }
                    ?> 
                    </select>-->
                </div>
                <div>
                    <label>Conta</label>
                    <select name="conta" id="conta" class="size2" >
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
                <div>
                    <label>Tipo</label>


                    <select name="tipo" id="tipo" class="size2">
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
                
               
                <div>
                    <label>Conta</label>
                    <input type="text"  name="txtConta"  class="size1" id="txtConta"  value="<?= @$obj->_conta; ?>"> 
                </div> 
                
                <div>
                    <label>Agência</label>
                    <input type="text"  name="txtAgencia"  class="size1" id="txtAgencia" value="<?= @$obj->_agencia; ?>" > 
                </div>  
                <div>
                    <label>Classe</label> 

                    <select name="classe" id="classe" class="size2">
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
                  
                <fieldset>
                    <legend>&nbsp;</legend>
                   <div>
                    <label>Desconto / Seguro</label>
                    <input type="text"  alt="decimal" name="desconto_seguro" value="<?= @$obj->_desconto_seguro; ?>"  >
 
                    <select name="tipo_desc_seguro" id="classe" class="size2">
                        <option value="percentual" <?= (@$obj->_tipo_desc_seguro == "percentual") ? "selected":""; ?>>Percentual</option>
                        <option value="fixo"  <?= (@$obj->_tipo_desc_seguro == "fixo") ? "selected":""; ?>>fixo</option>     
                    </select>
                   </div>
                </fieldset>

                <fieldset>
                    <legend>Impostos e Taxas</legend>
                    <? if (@$empresapermissao[0]->desativar_taxa_administracao == 'f') { ?>
                        <div>
                            <label>Taxa Administração Percentual</label>
                            <select name="taxaadm_perc" id="taxaadm_perc" class="size1">
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
                        <div>
                            <label>Taxa Administração</label>
                            <input type="text" id="taxaadm" class="texto02" name="taxaadm" alt="decimal" value="<?= @$obj->_taxa_administracao; ?>" />
                        </div>
                    <? } ?>
                    <div>
                        <label>IR</label>
                        <input type="text" id="ir" class="texto02" name="ir" alt="decimal" value="<?= @$obj->_ir; ?>" />
                    </div>
                    <div>
                        <label>PIS</label>
                        <input type="text" id="pis" class="texto02" name="pis" alt="decimal" value="<?= @$obj->_pis; ?>" />
                    </div>
                    <div>
                        <label>COFINS</label>
                        <input type="text" id="cofins" class="texto02" name="cofins" alt="decimal" value="<?= @$obj->_cofins; ?>" />
                    </div>
                    <div>
                        <label>CSLL</label>
                        <input type="text" id="csll" class="texto02" name="csll" alt="decimal" value="<?= @$obj->_csll; ?>" />
                    </div>
                    <div>
                        <label>ISS</label>
                        <input type="text" id="iss" class="texto02" name="iss" alt="decimal" value="<?= @$obj->_iss; ?>" />
                    </div>

                    <div>
                        <label>Valor Base para Imposto</label>
                        <input type="text" id="valor_base" class="texto02" name="valor_base" alt="decimal" value="<?= @$obj->_valor_base; ?>" />
                    </div>

                    <div>
                        <label>Piso Produção</label>
                        <input type="text" id="piso_medico" class="texto02" name="piso_medico" alt="decimal" value="<?= @$obj->_piso_medico; ?>" />
                    </div>

                </fieldset>
                
            </fieldset>

            <?
            if (@$empresapermissao[0]->profissional_agendar == 't') {
                ?>
                <fieldset>
                    <legend>Config. Horas por Turno</legend>
                    <table >
                        <tr>
                            <td></td>
                            <td style="color:#9b383e;"  >Segunda</td>
                            <td style="color:#9b383e;"  >Terça</td>
                            <td  style="color:#9b383e;" >Quarta</td>
                            <td style="color:#9b383e;" >Quinta</td>
                            <td style="color:#9b383e;"  >Sexta</td>
                            <td style="color:#9b383e;" >Sábado</td>
                        </tr>

                        <tr>
                            <td style="color:#9b383e;" >Manhã</td>
                            <td><input  class="texto01"  alt="time" name="seg_manha"  value="<?= @$obj->_seg_manha; ?>"  ></td>
                            <td><input  class="texto01"  alt="time"  name="ter_manha" value="<?= @$obj->_ter_manha; ?>"  ></td>
                            <td><input   class="texto01" alt="time" name="qua_manha" value="<?= @$obj->_qua_manha; ?>"   ></td>
                            <td><input  class="texto01" alt="time" name="qui_manha" value="<?= @$obj->_qui_manha; ?>"  ></td>
                            <td><input  class="texto01" alt="time" name="sex_manha" value="<?= @$obj->_sex_manha; ?>"   ></td>
                            <td><input  class="texto01" alt="time"  name="sab_manha" value="<?= @$obj->_sab_manha; ?>"   ></td>
                        </tr>

                        <tr>
                            <td style="color:#9b383e;"  >Tarde</td>
                            <td><input  class="texto01" alt="time" name="seg_tarde" value="<?= @$obj->_seg_tarde; ?>"  ></td>
                            <td><input  class="texto01" alt="time" name="ter_tarde" value="<?= @$obj->_ter_tarde; ?>"  ></td>
                            <td><input   class="texto01" alt="time" name="qua_tarde"  value="<?= @$obj->_qua_tarde; ?>"  ></td>
                            <td><input  class="texto01" alt="time" name="qui_tarde" value="<?= @$obj->_qui_tarde; ?>"  ></td>
                            <td><input  class="texto01" alt="time"  name="sex_tarde" value="<?= @$obj->_sex_tarde; ?>"  ></td>
                            <td><input  class="texto01" alt="time" name="sab_tarde" value="<?= @$obj->_sab_tarde; ?>"  ></td>
                        </tr>

                    </table> 

                    <!--                    <div>
                                            <label>Manhã(Quantidade de horas)</label>
                                            <input type="text" id="txtUsuario" name="hora_manha"  alt="time"  class="texto04" value="<?= @$obj->_hora_manha; ?>" />
                                        </div> 
                     
                                        <div>
                                            <label>Tarde(Quantidade de horas)</label>
                                            <input type="text" id="txtUsuario" name="hora_tarde" alt="time"    class="texto04" value="<?= @$obj->_hora_tarde; ?>" />
                                        </div>-->

                </fieldset> 
                <?
            }
            ?>   


            <? if (@$empresapermissao[0]->desativar_personalizacao_impressao == 'f') { ?>
                <fieldset>
                    <div>
                        <label>Carimbo</label>
                        <textarea name="carimbo" id="carimbo" rows="5" cols="30"  ><?= @$obj->_carimbo; ?></textarea>
                    </div>
                    <div>
                        <label>Mini-Curriculo</label>
                        <textarea name="curriculo" id="curriculo" rows="5" cols="50"  ><?= @$obj->_curriculo; ?></textarea>
                    </div>
                </fieldset>
                <fieldset>
                    <div>
                        <label>Cabeçalho</label>
                        <textarea name="cabecalho" id="cabecalho" rows="10" cols="100"  ><?= @$obj->_cabecalho; ?></textarea>
                    </div>
                    <div>
                        <label>Rodapé</label>
                        <textarea name="rodape" id="rodape" rows="10" cols="100"  ><?= @$obj->_rodape; ?></textarea>
                    </div>
                    <div>
                        <label>Timbrado</label>
                        <textarea name="timbrado" id="timbrado" rows="10" cols="100"  ><?= @$obj->_timbrado; ?></textarea>
                    </div>
                    <div>
                        <p>
                            Obs: O tamanho da imagem é padrão: 800px X 600px <br>
                            Obs²: O formato da imagem importada deverá ser .png (Sendo possivel dessa forma, aplicar opacidade na imagem através de edição da mesma por softwares de terceiros)
                        </p>
                    </div>
                </fieldset>


                <fieldset>
                    <div>
                        <label>Cabeçalho (S. Exames, Terapeuticas, Relatorios)</label>
                        <textarea name="cabecalho2" id="cabecalho2" rows="10" cols="100"  ><?= @$obj->_cabecalho2; ?></textarea>
                    </div>
                    <div>
                        <label>Rodapé (S. Exames, Terapeuticas, Relatorios)</label>
                        <textarea name="rodape2" id="rodape2" rows="10" cols="100"  ><?= @$obj->_rodape2; ?></textarea>
                    </div>
                </fieldset>
            <? } ?>

            <fieldset style="dislpay:block">

                <button type="submit" name="btnEnviar">Enviar</button>

                <button type="reset" name="btnLimpar">Limpar</button>
                <button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
            </fieldset>
        </form>
         
        
        <?php
        if (@$obj->_perfil_id == 4 || @$obj->_perfil_id == 19 || @$obj->_perfil_id == 22 || @$obj->_perfil_id == 1) {
            if (count($documentos > 0)) {
                ?>
                <fieldset style="dislpay:block">
                    <legend>Documentação Profissional</legend>
                    <table border='2'>
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

</div> <!-- Final da DIV content -->
<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<!--<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">-->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
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

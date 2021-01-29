<link href="<?= base_url() ?>css/cadastro/paciente-ficha.css" rel="stylesheet"/>
<div class="content ficha_ceatox"> <!-- Inicio da DIV content -->
    <form name="form_paciente" id="form_paciente" action="<?= base_url() ?>cadastros/pacientes/gravar" method="post">
        <!--        Chamando o Script para a Webcam   -->
        <script src="<?= base_url() ?>js/webcam.js"></script>
        <fieldset>
            <?
            if (@$empresapermissoes[0]->campos_cadastro != '') {
                $campos_obrigatorios = json_decode(@$empresapermissoes[0]->campos_cadastro);
            } else {
                $campos_obrigatorios = array();
            }
            $tecnico_recepcao_editar = @$empresapermissoes[0]->tecnico_recepcao_editar;
            $perfil_id = $this->session->userdata('perfil_id');
            
            ?>
            <div class="alert alert-primary"><b>Dados do Estagiário</b></div>
                <div class="panel-body infodados">
                    <div class="row">
                        <div class="col-lg-3">
                            <div>
                                <label>Nome*</label>
                                <input type="text" id="txtNome" name="nome" class="form-control" value="<?= @$obj[0]->_nome; ?>" required="true"  placeholder="Nome do Estagiário">
                                <input type ="hidden" name ="paciente_id"  value ="<?= @$obj[0]->_paciente_id; ?>" id ="txtPacienteId">
                                <input type ="hidden" name ="paciente_operador_id"  value ="<?= @$obj[0]->_paciente_operador_id; ?>" id ="txtPacienteId">
                            </div>
                            <div>
                                <label>Nome do responsável legal</label>
                                <input type="text" name="nome_mae" id="txtNomeMae"  placeholder="Nome do responsável" class="form-control" value="<?= @$obj[0]->_nomemae; ?>"
                                    <?= (in_array('nome_mae', $campos_obrigatorios)) ? 'required' : '' ?>/>
                                <? if (@$empresapermissoes[0]->ocupacao_mae == 't') { ?>
                                    <label>Ocupação da M&atilde;e</label>
                                    <input type="text" name="ocupacao_mae" id="ocupacao_mae" class="form-control" value="<?= @$obj[0]->_ocupacao_mae; ?>"/>
                                <? } ?>
                            </div>
                        </div>
                       
                        <div class="col-lg-3">
                            <div>
                                <label>Email</label>
                                <input  placeholder="Email" required type="text" id="txtEmail" name="email"  class="form-control" value="<?= @$obj[0]->_cns; ?>" />
                            </div>
                            <div>
                                <label>Email Alternativo</label>
                                <input type="text" id="txtemailalternativo" name="email_alternativo" placeholder="Email Alternativo" onchange="validaremail2()" class="form-control" value="<?= @$obj[0]->_email; ?>"
                                
                                    <?= (in_array('email2', $campos_obrigatorios)) ? 'required' : '' ?>/>
                            </div>
                        </div>
                        <div class="col-lg-2">
                                    <label>Idade</label>
                                    <input type="text" name="idade2" id="idade2" class="form-control" />
                            </div>    
                        <div class="col-lg-2">
                            <div>
                                <label>Sexo*</label>
                                <select name="sexo" id="txtSexo" class="form-control" required="">
                                    <option value="" <?
                                    if (@$obj[0]->_sexo == ""):echo 'selected';
                                    endif;
                                    ?>>Selecione</option>
                                    <option value="M" <?
                                    if (@$obj[0]->_sexo == "M"):echo 'selected';
                                    endif;
                                    ?>>Masculino</option>
                                    <option value="F" <?
                                    if (@$obj[0]->_sexo == "F"):echo 'selected';
                                    endif;
                                    ?>>Feminino</option>
                                </select>
                                    <label>Data de Nascimento*</label>
                                    <input type="text" name="nascimento" id="txtNascimento" required="true" alt="date" class="form-control"
                                        placeholder="00/00/0000"

                                        value="<?php
                                        if (@$obj[0]->_nascimento != '') {
                                            echo substr(@$obj[0]->_nascimento, 8, 2) . '/' . substr(@$obj[0]->_nascimento, 5, 2) . '/' . substr(@$obj[0]->_nascimento, 0, 4);
                                        }
                                        ?>"
                                    >
                                </div>
                            
                        </div>
                    </div>            

                            <!-- <div>
                                <label>Fotografia</label>
                            </div> -->

                            <!-- <div> -->
                                <!--<label>Fotografia</label>-->
                                <!-- <a class="btn btn-primary" data-toggle="modal" onClick="ativar_camera()" data-target="#myModal">
                                    <i class="fa fa-camera fa-1x" aria-hidden="true"></i>

                                </a>
                                <span id="imagem_paciente">
                                        <? if (file_exists("./upload/webcam/pacientes/" . @$obj[0]->_paciente_id . ".jpg")) { ?>
                                            <img class="img-thumbnail img-rounded img-responsive" src="<?= base_url() ?>upload/webcam/pacientes/<?= @$obj[0]->_paciente_id ?>.jpg" alt="" style="width: 100pt; height: 100pt;" />
                                        <? } else { ?>
                                            <img class="img-thumbnail img-rounded img-responsive" src="" alt="" style="width: 100pt; height: 100pt;" />
                                        <? }
                                        ?>


                                        <!- Modal -->
                                <!-- </span> -->

                            <!-- </div> -->
                            <!-- <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog"> -->
                                    <!-- <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <!- <h4 class="modal-title" id="myModalLabel">Fotografia</h4> -->
                                        <!-- </div>
                                        <div class="modal-body"> -->
                                            <!-- <fieldset> -->
                                                <!--<legend>Fotografia</legend>-->
                                                <!-- <table>
                                                    <tr>
                                                        <th>
                                                            Câmera
                                                        </th>
                                                        <th>
                                                        </th>
                                                        <th>
                                                            Resultado
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td >
                                                            <div id="my_camera" ></div>
                                                        </td>

                                                        <td>
                                                        </td>
                                                        <td>
                                                            <div id="results">

                                                            </div>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <a class="btn btn-danger" onClick="take_snapshot()"><i class="fa fa-camera fa-1x" aria-hidden="true"></i></a>
                                                        </td>
                                                    </tr>
                                                </table> -->
                                            <!-- </fieldset> -->
                                        <!-- </div> -->
                                        <!-- <div class="modal-footer"> -->
                                            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                                            <!-- <a  onClick="imagem_paciente()" class="btn btn-primary" data-dismiss="modal">Fechar</a> -->
                                        <!-- </div> -->
                                    <!-- </div> -->
                                    <!-- /.modal-content -->
                                <!-- </div> -->
                                <!-- /.modal-dialog -->
                            <!-- </div> -->
                        <!-- </div> -->
                    <!-- </div> -->
                </div>
          
            <div class="alert alert-primary"><b>Documentos</b></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-2">
                            <div>
                                <label>CPF</label>
                                <input type="text" <?= (in_array('cpf', $campos_obrigatorios)) ? 'required' : '' ?> name="cpf" id ="txtCpf" onblur="verificarCPF();" alt="cpf" class="form-control cpf" value="<?= @$obj[0]->_cpf; ?>"/>
                                <input type="checkbox" name="cpf_responsavel" id ="txtCpf" <? if (@$obj[0]->_cpf_responsavel_flag == 't') echo "checked"; ?>> CPF do resposável
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>RG</label>
                                <input type="text" name="rg" <?= (in_array('rg', $campos_obrigatorios)) ? 'required' : '' ?>  id="txtDocumento" class="form-control" value="<?= @$obj[0]->_documento; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>CNH</label>
                                <input type="text" name="cnh" <?= (in_array('cnh', $campos_obrigatorios)) ? 'required' : '' ?>  id="txtDocumento" class="form-control"value="<?= @$obj[0]->_cnh; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Vencimento CNH</label>
                                <input type="text" id="vencimento_cnh" class="form-control" name="vencimento_cnh" value="<?
                                if (@$obj[0]->_vencimento_cnh != '') {
                                    echo date("d/m/Y", strtotime(@$obj[0]->_vencimento_cnh));
                                }
                                ?>" <?= (in_array('vencimento_cnh', $campos_obrigatorios)) ? 'required' : '' ?>/>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>Vencimento Carteira</label>
                                <input type="text" id="vencimento_carteira" class="form-control" name="vencimento_carteira" value="<?
                                if (@$obj[0]->_vencimento_carteira != '') {
                                 echo date("d/m/Y", strtotime(@$obj[0]->_vencimento_carteira));
                                 }
                                 ?>" <?= (in_array('vencimento_carteira', $campos_obrigatorios)) ? 'required' : '' ?>/>
                            </div>
                        </div>
                     </div>
                </div>
            </div>


        </fieldset>
        <div class="panel panel-default alertresid">
            <div class="alert alert-primary ">
            <b>Domicilio</b>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2">
                        <div>
                            <label>Município</label>
                            <input type="hidden" id="txtCidadeID" class="texto_id" name="municipio_id" value="<?= @$obj[0]->_municipio_id; ?>" readonly="true" />
                            <input type="text" id="txtCidade" class="form-control" name="txtCidade" value="<?= @$obj[0]->_municipio_id; ?>" />
                        </div>
                        <div>
                                <label>CEP</label>
                                <input type="text" id="cep" class="form-control" name="cep"  value="<?= @$obj[0]->_cep; ?>" />
                                <input type="hidden" id="ibge" name="ibge" />
                            </div>
                    </div>
                    <div class="col-lg-2">
                            <div>
                                <label>Endere&ccedil;o</label>
                                <input type="text" id="rua" class="form-control" name="endereco" value="<?= @$obj[0]->_endereco; ?>" />
                            </div>
                            <div>
                                <label>Bairro</label>
                                <input type="text" id="bairro" class="form-control" name="bairro" value="<?= @$obj[0]->_bairro; ?>" />
                            </div>   
                        </div>
                        <?
                        if (@$obj[0]->_telefone != '' && strlen(@$obj[0]->_telefone) > 3) {

                            if (preg_match('/\(/', @$obj[0]->_telefone)) {
                                $telefone = @$obj[0]->_telefone;
                            } else {
                                $telefone = "(" . substr(@$obj[0]->_telefone, 0, 2) . ")" . substr(@$obj[0]->_telefone, 2, strlen(@$obj[0]->_telefone) - 2);
                            }
                        } else {
                            $telefone = '';
                        }
                        if (@$obj[0]->_celular != '' && strlen(@$obj[0]->_celular) > 3) {
                            if (preg_match('/\(/', @$obj[0]->_celular)) {
                                $celular = @$obj[0]->_celular;
                            } else {
                                $celular = "(" . substr(@$obj[0]->_celular, 0, 2) . ")" . substr(@$obj[0]->_celular, 2, strlen(@$obj[0]->_celular) - 2);
                            }
                        } else {
                            $celular = '';
                        }
                        if (@$obj[0]->_whatsapp != '' && strlen(@$obj[0]->_whatsapp) > 3) {
                            if (preg_match('/\(/', @$obj[0]->_whatsapp)) {
                                $whatsapp = @$obj[0]->_whatsapp;
                            } else {
                                $whatsapp = "(" . substr(@$obj[0]->_whatsapp, 0, 2) . ")" . substr(@$obj[0]->_whatsapp, 2, strlen(@$obj[0]->_whatsapp) - 2);
                            }
                        } else {
                            $whatsapp = '';
                        }
                        ?>
                        <div class="col-lg-2">
                            <div>
                                <label>Celular</label>
                                <input type="text" id="txtCelular" class="form-control" name="celular" value="<?= @$celular; ?>" <?= (in_array('celular', $campos_obrigatorios)) ? 'required' : '' ?>/>
                                <!-- <button class="btn btn-outline-danger btn-sm"  type=button id="btnWhats" onclick="pegarWhats2();"> WP? </button> -->
                            </div>
                            <div>
                            <label>WhatsApp</label>
                            <input type="text" id="txtwhatsapp" class="form-control" name="txtwhatsapp" value="<?= @$whatsapp; ?>" <?= (in_array('whatsapp', $campos_obrigatorios)) ? 'required' : '' ?>/>
                        </div>
                        </div>
                   
                        <div class="col-lg-2">
                            <div>
                                <label>Telefone 1*</label>
                                
                                <input type="text" id="txtTelefone" class="form-control" name="telefone"  value="<?= @$telefone; ?>" <?= (in_array('telefone1', $campos_obrigatorios)) ? 'required' : '' ?>/>
                                <!-- <button class="btn btn-outline-danger btn-sm" type=button id="btnWhats" onclick="pegarWhats();"> WP? </button> -->
                            </div>
                            <div>
                                <label>N&uacute;mero</label>
                                <input type="text" id="txtNumero" class="form-control" name="numero" value="<?= @$obj[0]->_numero; ?>" />
                            </div>
                        </div>
                    
                        <div class="col-lg-2">
                            <label>Observa&ccedil;&atilde;o</label>
                            <textarea cols="70" rows="3" class="form-control" name="observacao" placeholder="Observações" id="observacao"><?= @$obj[0]->_observacao; ?></textarea><br/>
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    <div class="panel panel-default socialalert">
        <div class="alert alert-primary">
            <b>Dados Sociais</b>
        </div>
        <div class="panel-body socialdata">
            <div class="row">
                <div class="col-lg-2">
                    <div>
                        <label>Plano de Saude</label>
                        <select <?= (@$empresapermissoes[0]->carteira_administrador == 't' && $perfil_id != 1) ? 'disabled' : '' ?> name="convenio" id="txtconvenio" class="form-control texto04" <?= (in_array('plano_saude', $campos_obrigatorios)) ? 'required' : '' ?>>
                            <option value='' >selecione</option>
                            <?php
                            $listaconvenio = $this->paciente->listaconvenio($_GET);
                            foreach ($listaconvenio as $item) {
                                $operador_id = $this->session->userdata('operador_id');
                                if(@$obj[0]->_convenio != $item->convenio_id && @$obj[0]->_convenio > 0 && $empresapermissoes[0]->travar_convenio_paciente == 't' && $operador_id != 1){
                                    continue;
                                }
                                ?>

                                <option value =<?php echo $item->convenio_id; ?> <?
                                if (@$obj[0]->_convenio == $item->convenio_id):echo 'selected';
                                endif;
                                ?>><?php echo $item->nome; ?></option>
                                          <?php
                                      }
                                      ?>
                                      <option data-section="plano" value="OUTROS">OUTROS</option>
                        </select>
                    </div>
                    <div>
                        <label>Outro Plano de Saude</label>
                        <input type="text" name="outroplano" class="form-control" id="outroplano" value="<?=@$obj[0]->_outro_convenio?>">
                    </div>
                </div>
            
                    <div class="col-lg-2">
                        <div>
                            <label>Ra&ccedil;a / Cor</label>
                            <select name="raca_cor" id="txtRacaCor" class="form-control" <?= (in_array('raca_cor', $campos_obrigatorios)) ? 'required' : '' ?>>

                                <option value=''  <?
                                if (@$obj[0]->_raca_cor == ''):echo 'selected';
                                endif;
                                ?>>Selecione</option>
                                <option value=1 <?
                                if (@$obj[0]->_raca_cor == 1):echo 'selected';
                                endif;
                                ?>>Branca</option>
                                <option value=2 <?
                                if (@$obj[0]->_raca_cor == 2):echo 'selected';
                                endif;
                                ?>>Amarela</option>
                                <option value=3 <?
                                if (@$obj[0]->_raca_cor == 3):echo 'selected';
                                endif;
                                ?>>Preta</option>
                                <option value=4 <?
                                if (@$obj[0]->_raca_cor == 4):echo 'selected';
                                endif;
                                ?>>Parda</option>
                                <option value=5 <?
                                if (@$obj[0]->_raca_cor == 5):echo 'selected';
                                endif;
                                ?>>Ind&iacute;gena</option>
                            </select>
                        </div>
                        <div>
                            <label>Nacionalidade</label>
                            <input type="text" id="nacionalidade" class="form-control" name="nacionalidade" value="<?= @$obj[0]->_nacionalidade; ?>" <?= (in_array('nacionalidade', $campos_obrigatorios)) ? 'required' : '' ?>/>
                        </div>
                    </div>
                    <div class="col-lg-2">
                    <div>
                            <label>Estado civil</label>
                            <select name="estado_civil_id" id="txtEstadoCivil" class="form-control" selected="<?= @$obj[0]->_estado_civil; ?>" <?= (in_array('estado_civil', $campos_obrigatorios)) ? 'required' : '' ?>>
                                <option value='' <?
                                if (@$obj[0]->_estado_civil == ''):echo 'selected';
                                endif;
                                ?>>Selecione</option>
                                <option value=1 <?
                                if (@$obj[0]->_estado_civil == 1):echo 'selected';
                                endif;
                                ?>>Solteiro</option>
                                <option value=2 <?
                                if (@$obj[0]->_estado_civil == 2):echo 'selected';
                                endif;
                                ?>>Casado</option>
                                <option value=3 <?
                                if (@$obj[0]->_estado_civil == 3):echo 'selected';
                                endif;
                                ?>>Divorciado</option>
                                <option value=4 <?
                                if (@$obj[0]->_estado_civil == 4):echo 'selected';
                                endif;
                                ?>>Viuvo</option>
                                <option value=5 <?
                                if (@$obj[0]->_estado_civil == 5):echo 'selected';
                                endif;
                                ?>>Outros</option>
                            </select>
                        </div>
                        <div>
                            <label>N&uacute;mero</label>
                            <div name="numero_caracter" id="numero_caracter" class="size2">
                                <input type="text"  id="txtconvenionumero" class="form-control" name="convenionumero" value="<?= @$obj[0]->_convenionumero; ?>" <?= (in_array('numero_carteira', $campos_obrigatorios)) ? 'required' : '' ?>>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div>
                            <label>Escolaridade</label>

                            <select name="escolaridade" id="escolaridade" class="form-control" selected="<?= @$obj[0]->_escolaridade_id; ?>" <?= (in_array('escolaridade', $campos_obrigatorios)) ? 'required' : '' ?>>
                                <option value='' <?
                                if (@$obj[0]->_escolaridade_id == ''):echo 'selected';
                                endif;
                                ?>>Selecione</option>
                                <option value=1 <?
                                if (@$obj[0]->_escolaridade_id == 1):echo 'selected';
                                endif;
                                ?>>Fundamental-Incompleto </option>
                                <option value=2 <?
                                if (@$obj[0]->_escolaridade_id == 2):echo 'selected';
                                endif;
                                ?>>Fundamental-Completo</option>

                                <option value=3 <?
                                if (@$obj[0]->_escolaridade_id == 3):echo 'selected';
                                endif;
                                ?>>Médio
                                    -
                                    Incompleto</option>
                                <option value=4 <?
                                if (@$obj[0]->_escolaridade_id == 4):echo 'selected';
                                endif;
                                ?>>Médio
                                    -
                                    Completo
                                </option>
                                <option value=5 <?
                                if (@$obj[0]->_escolaridade_id == 5):echo 'selected';
                                endif;
                                ?>>Superior
                                    -
                                    Incompleto</option>
                                <option value=6 <?
                                if (@$obj[0]->_escolaridade_id == 6):echo 'selected';
                                endif;
                                ?>>Superior-Completo </option>

                            </select>
                        </div>
                        <div>
                            <label>Ocupa&ccedil;&atilde;o</label>
                            <input type="hidden" id="txtcboID" class="form-control" class="texto_id" name="txtcboID" value="<?= @$obj[0]->_cbo_ocupacao_id; ?>" readonly="true" />
                            <input type="text" id="txtcbo" class="form-control" name="txtcbo" value="<?= @$obj[0]->_cbo_nome; ?>" <?= (in_array('ocupacao', $campos_obrigatorios)) ? 'required' : '' ?>/>
                            <input type="hidden" id="txtcbohidden" class="form-control" name="txtcbohidden" value="<?= @$obj[0]->_cbo_nome; ?>" />
                        </div>
                </div>
                <div class="col-lg-2">
                    <div>
                        <label>Instagram (ex: @exemplo)</label>
                        <input type="text" id="instagram" class="form-control" name="instagram" value="<?= @$obj[0]->_instagram; ?>" <?= (in_array('instagram', $campos_obrigatorios)) ? 'required' : '' ?>/>
                    </div>
                </div>




            </div>
        </div>
    </div>
    <div class="alert alert-primary"><b>Acesso</b></div>
    <div class="row">
                    <div class="col-lg-3">
                        <label>Nome Usuario</label>

                        <input type="text" id="txtUsuarioapp" name="txtUsuarioapp"  class="form-control" value="<?= @$obj[0]->_usuario_app; ?>" required/>
                    </div>
                    <div class="col-lg-3">
                        <label>Senha Usuario:</label>
                        <input type="password" name="txtSenhaapp" id="txtSenhaapp" class="form-control" >
                    </div>
        </div>

<!--        <fieldset>-->
<!--            <legend>Fotografia</legend>-->
<!---->
<!--            <label>Câmera</label>-->
<!--            <input id="mydata" type="hidden" name="mydata" value=""/>-->
<!--            <div id="my_camera"></div>-->
<!--            <div></div>-->
<!--            <div><input type=button value="Ativar Câmera" onClick="ativar_camera()">-->
<!--                <input type=button value="Tirar Foto" onClick="take_snapshot()"></div>-->
<!---->
<!--            <div id="results">A imagem capturada aparece aqui...</div>-->
<!---->
<!--        </fieldset>-->
        <!--        <fieldset>
                    <legend>Outros</legend>
                    
        
                </fieldset>-->
        <div class="panel panel-default btngrp">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-3">
                        <?if($tecnico_recepcao_editar == 't' || $perfil_id != 15){?>
                            <button type="submit" class="btn btn-success btn-sm">Enviar</button>
                            <button type="reset" class="btn btn-warning btn-sm">Limpar</button>
                        <?}?>




                        <a href="<?= base_url() ?>cadastros/pacientes">
                            <button type="button" id="btnVoltar" class="btn btn-secondary btn-sm">Voltar</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <script language="JavaScript">
        Webcam.set({
            width: 140,
            height: 160,
            dest_width: 480,
            dest_height: 360,
            image_format: 'jpeg',
            jpeg_quality: 100
        });
        function ativar_camera() {
            Webcam.attach('#my_camera');
        }
        function take_snapshot() {
            // tira a foto e gera uma imagem para a div
            Webcam.snap(function (data_uri) {
                // display results in page
                document.getElementById('results').innerHTML =
                        '<img height = "160" width = "140" src="' + data_uri + '"/>';
                //              Gera uma variável com o código binário em base 64 e joga numa variável
                var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
                //              Pega o valor do campo mydata, campo hidden que armazena temporariamente o código da imagem
                document.getElementById('mydata').value = raw_image_data;

            });
        }



    </script>

</div>


<script>


$("#txtconvenio").change(function() {
    var $this, secao, atual, campos;
  
    campos = $("div[data-name]");
    
    campos.addClass("hide");

    if (this.value !== "") {
        secao = $('option[data-section][value="' + this.value + '"]', this).attr("data-section");
      
        atual = campos.filter("[data-name=" + secao + "]");
      
        if (atual.length !== 0) {
            atual.removeClass("hide");
        }
    }
});

$("#mostrarDadosExtras").click(function () {
            var botao = $("#mostrarDadosExtras").text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras").text('-');
            } else {
                $("#mostrarDadosExtras").text('+');
            }                                       
            $("#DadosExtras").toggle();

        });


        $(function () {
            $('#txtconvenio').change(function () {
                if ($(this).val()) {
//                                                alert('teste_parada');
                    $('.carregando').show();
//                                                  alert('teste_parada');
                    $.getJSON('<?= base_url() ?>autocomplete/tamanhoconvenio', {txtcbo: $(this).val(), ajax: true}, function (j) {

                        console.log(j);

                        for (var c = 0; c < j.length; c++) {


                            if (j[0].tamanho_carteira != undefined) {
                                options = '<input type="text" name="convenionumero"   maxlength="' + j[c].tamanho_carteira + '" value="<?= @$obj[0]->_convenionumero; ?>" <?= (in_array('numero_carteira', $campos_obrigatorios)) ? 'required' : '' ?>>';

                            } else {
                                options = '<input  type="text" name="convenionumero"   value="<?= @$obj[0]->_convenionumero; ?>" <?= (in_array('numero_carteira', $campos_obrigatorios)) ? 'required' : '' ?>></option>';
                            }


                        }
                        $('#numero_caracter').html(options).show();
                        $('.carregando').hide();



                    });
                }

            });
        });






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
//    mascaraTelefone(form_paciente.txtTelefone);
//    mascaraTelefone(form_paciente.txtwhatsapp);
//    mascaraTelefone(form_paciente.txtCelular);
    // $(function () {
    //     $("#vencimento_carteira").datepicker({
    //         autosize: true,
    //         changeYear: true,
    //         changeMonth: true,
    //         monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
    //         dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
    //         buttonImage: '<?= base_url() ?>img/form/date.png',
    //         dateFormat: 'dd/mm/yy'
    //     });
    // });

    $("#vencimento_carteira").mask("99/99/9999");
    $("#vencimento_cnh").mask("99/99/9999");

    jQuery("#txtwhatsapp")
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
//(99) 9999-9999


    $(function () {
        $("#txtcbo").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=cboprofissionais",
            minLength: 6,
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


    $(function () {
        $('#txtSexo').change(function () {
//            alert($(this).val());
            if ($(this).val() == 'O') {
                $("#sexo_real_div").show();

            } else {
                $("#sexo_real_div").hide();
            }
        });
    });

    if ($('#txtSexo').val() == 'O') {
        $("#sexo_real_div").show();
    } else {
        $("#sexo_real_div").hide();
    }

    $(function () {
        $('#txtconvenio').change(function () {
            if ($(this).val()) {
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/conveniocarteira', {convenio1: $(this).val()}, function (j) {
                    options = '<option value=""></option>';
                    if (j[0].carteira_obrigatoria == 't') {
                        $("#txtconvenionumero").prop('required', true);
                    } else {
                        $("#txtconvenionumero").prop('required', false);
                    }

                });
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
    $(function () {
        $("#txtEstado").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=estado",
            minLength: 2,
            focus: function (event, ui) {
                $("#txtEstado").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#txtEstado").val(ui.item.value);
                $("#txtEstadoID").val(ui.item.id);
                return false;
            }
        });
    });



    $(function () {
        $("#cep").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=cep",
            minLength: 3,
            focus: function (event, ui) {
                $("#cep").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#cep").val(ui.item.cep);
                $("#txtendereco").val(ui.item.logradouro_nome);
                $("#txtBairro").val(ui.item.nome_bairro);
//                        $("#txtCidade").val(ui.item.localidade_nome);
                $("#txtTipoLogradouro").val(ui.item.tipo_logradouro);

                return false;
            }
        });
    });

    function verificarCPF() {
        // txtCpf
        // var cpf = $("#txtCpf").val();
        // var paciente_id = $("#txtPacienteId").val();
        // if($('#cpf_responsavel').prop('checked')){
        //     var cpf_responsavel = 'on';
        // }else{
        //     var cpf_responsavel = '';
        // }
        
        // // alert(cpf_responsavel);
        // $.getJSON('<?= base_url() ?>autocomplete/verificarcpfpaciente', {cpf: cpf, cpf_responsavel: cpf_responsavel, paciente_id: paciente_id,  ajax: true}, function (j) {
        //     if(j != ''){
        //         alert(j);
        //         $("#txtCpf").val('');
        //     }
        // });
    }

    function verificarCPFmae() {
        // txtCpf
        var cpf = $("#txtCpfmae").val();
        var paciente_id = $("#txtPacienteId").val();
        var cpf_responsavel = '';
        
        // alert(cpf_responsavel);
        $.getJSON('<?= base_url() ?>autocomplete/verificarcpfpaciente', {cpf: cpf, cpf_responsavel: cpf_responsavel, paciente_id: paciente_id,  ajax: true}, function (j) {
            if(j != ''){
                alert(j);
                $("#txtCpfmae").val('');
            }
        });
    }

    function validaremail(){
        var email = $("#txtCns").val();
        var email2 = $("#txtCns2").val();
        if(email != ''){
            $.getJSON('<?= base_url() ?>autocomplete/verificaremailpaciente', {email: email,  ajax: true}, function (j) {
                if(j != ''){
                    alert(j);
                    $("#txtCns").val('');
                }else if(email == email2){
                    alert('O E-mail não pode ser Igual ao E-mail Alternativo');
                    $("#txtCns2").val('');
                }
            });
        }
    }

    function validaremail2(){
        var email2 = $("#txtCns").val();
        var email = $("#txtCns2").val();
        if(email != ''){
            $.getJSON('<?= base_url() ?>autocomplete/verificaremailpaciente', {email: email,  ajax: true}, function (j) {
                if(j != ''){
                    alert(j);
                    $("#txtCns2").val('');
                }else if(email == email2){
                    alert('O E-mail Alternativo não pode ser Igual ao E-mail');
                    $("#txtCns2").val('');
                }
            });
        }
    }

    function verificarCPFpai() {
        // txtCpf
        var cpf = $("#txtCpfpai").val();
        var paciente_id = $("#txtPacienteId").val();
        var cpf_responsavel = '';

        
        // alert(cpf_responsavel);
        $.getJSON('<?= base_url() ?>autocomplete/verificarcpfpaciente', {cpf: cpf, cpf_responsavel: cpf_responsavel, paciente_id: paciente_id,  ajax: true}, function (j) {
            if(j != ''){
                alert(j);
                $("#txtCpfpai").val('');
            }
        });
    }

    $(document).ready(function () {

        function limpa_formulário_cep() {
            // Limpa valores do formulário de cep.
//            $("#rua").val("");
//            $("#bairro").val("");
//            $("#txtCidade").val("");
//            $("#uf").val("");
//            $("#ibge").val("");
        }
        $("#cep").mask("99999-999");
        //Quando o campo cep perde o foco.
        $("#cep").blur(function () {

            //Nova variável "cep" somente com dígitos.
            var cep = $(this).val().replace(/\D/g, '');

            //Verifica se campo cep possui valor informado.
            if (cep != "") {

                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;

                //Valida o formato do CEP.
                if (validacep.test(cep)) {

                    //Preenche os campos com "..." enquanto consulta webservice.
//                    $("#rua").val("Aguarde...");
//                    $("#bairro").val("Aguarde...");
//                    $("#txtCidade").val("Aguarde...");
//                    $("#uf").val("Aguarde...");

                    //Consulta o webservice viacep.com.br/
                    $.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                        if (!("erro" in dados)) {
                            //Atualiza os campos com os valores da consulta.
                            $("#txtendereco").val(dados.logradouro);
                            $("#txtBairro").val(dados.bairro);
                            $("#txtCidade").val(dados.localidade);
//                            $("#uf").val(dados.uf);
                            $("#ibge").val(dados.ibge);
                            $.getJSON('<?= base_url() ?>autocomplete/cidadeibge', {ibge: dados.ibge}, function (j) {
                                $("#txtCidade").val(j[0].value);
                                $("#txtCidadeID").val(j[0].id);

//                                console.log(j);
                            });
//                            console.log(dados);
//                            console.log(dados.bairro);

                        } //end if.
                        else {
                            //CEP pesquisado não foi encontrado.
//                            limpa_formulário_cep();
                            alert("CEP não encontrado.");

//                            swal({
//                                title: "Correios informa!",
//                                text: "CEP não encontrado.",
//                                imageUrl: "<?= base_url() ?>img/correios.png"
//                            });
                        }
                    });

                } //end if.
                else {
                    //cep é inválido.
                    limpa_formulário_cep();
//                    alert("Formato de CEP inválido.");
                    swal({
                        title: "Correios informa!",
                        text: "Formato de CEP inválido.",
                        imageUrl: "<?= base_url() ?>img/correios.png"
                    });
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        });
    });



    function calculoIdade() {
        var data = document.getElementById("txtNascimento").value;

        if (data != '' && data != '//') {

            var ano = data.substring(6, 12);
            var idade = new Date().getFullYear() - ano;
            // var mes = ;
            var dtAtual = new Date();
            var aniversario = new Date(dtAtual.getFullYear(), parseInt(data.substring(3, 5)) - 1, data.substring(0, 2));
            // alert(aniversario);
            // alert(dtAtual.getFullYear());
            if (dtAtual < aniversario) {
                idade--;
            }
//            if (idade <= 10) {
//                $("#cpf_responsavel_label").show();
//                $("#cpf_responsavel").prop('required', true);
//            } else {
//                $("#cpf_responsavel_label").hide();
//                $("#cpf_responsavel").prop('required', false);
//            }

            document.getElementById("idade2").value = idade + " ano(s)";
        } else {
//            $("#cpf_responsavel_label").hide();
//            $("#cpf_responsavel").prop('required', false);
        }
    }
    calculoIdade();

    function calculoidade_conjuge() {
        var data = document.getElementById("nascimento_conjuge").value;

        if (data != '' && data != '//') {

            var ano = data.substring(6, 12);
            var idade_conjuge = new Date().getFullYear() - ano;
            // var mes = ;
            var dtAtual = new Date();
            var aniversario = new Date(dtAtual.getFullYear(), parseInt(data.substring(3, 5)) - 1, data.substring(0, 2));
            // alert(aniversario);
            // alert(dtAtual.getFullYear());
            if (dtAtual < aniversario) {
                idade_conjuge--;
            }
//            if (idade_conjuge <= 10) {
//                $("#cpf_responsavel_label").show();
//                $("#cpf_responsavel").prop('required', true);
//            } else {
//                $("#cpf_responsavel_label").hide();
//                $("#cpf_responsavel").prop('required', false);
//            }

            document.getElementById("idade_conjuge").value = idade_conjuge + " ano(s)";
        } else {
//            $("#cpf_responsavel_label").hide();
//            $("#cpf_responsavel").prop('required', false);
        }
    }

    function pegarWhats(){
    document.getElementById("txtwhatsapp").value  = document.getElementById("txtTelefone").value;
    }
    function pegarWhats2(){
    document.getElementById("txtwhatsapp").value  = document.getElementById("txtCelular").value;
    }

    calculoidade_conjuge();

</script>

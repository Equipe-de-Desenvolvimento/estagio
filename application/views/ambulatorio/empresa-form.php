
<div class="content"> <!-- Inicio da DIV content -->
    <style>
        #accordion dl dt{
            min-width:300pt;
        }
    </style>
    <div id="accordion">
        <h3 class="singular"><a href="#">Cadastro de Empresa</a></h3>
        <div>
            <form name="form_empresa" id="form_empresa" action="<?= base_url() ?>ambulatorio/empresa/gravar" method="post">
                <dl class="dl_desconto_lista">
                    <dt>
                        <label>Nome</label>
                    </dt>
                    <dd>
                        <input type="hidden" name="txtempresaid" class="texto08" value="<?= @$obj->_empresa_id; ?>" />
                        <input type="text" name="txtNome" class="texto08" value="<?= @$obj->_nome; ?>" />
                    </dd>
                    <dt>
                        <label>Raz&atilde;o social</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtrazaosocial" id="txtrazaosocial" class="texto08" value="<?= @$obj->_razao_social; ?>" />
                    </dd>
                    <dt>
                        <label>Raz&atilde;o social (XML)</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtrazaosocialxml" id="txtrazaosocial" class="texto08" value="<?= @$obj->_razao_social; ?>" />
                    </dd>
                    <dt>
                        <label>CNPJ</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtCNPJ" maxlength="14" alt="cnpj" class="texto03" value="<?= @$obj->_cnpj; ?>" />
                    </dd>
                    <dt>
                        <label>CNPJ (XML)</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtCNPJxml" maxlength="14" alt="cnpj" class="texto03" value="<?= @$obj->_cnpjxml; ?>" />
                    </dd>
                    <!--                     <dt>
                                            <label>CNAE (XML)</label>
                                        </dt>
                                        <dd>
                                            <input type="text" name="cnae"    class="texto03" value="<?= @$obj->_cnae; ?>" />
                                        </dd>-->
                    <!--                      <dt>
                                            <label>Item lista (XML)</label>
                                        </dt>
                                        <dd>
                                            <input type="text" name="item_lista"     class="texto03" value="<?= @$obj->_item_lista; ?>" />
                                        </dd>  -->
                    <!--                    <dt>
                                            <label>Aliquota (XML)</label>
                                        </dt>
                                        <dd>
                                            <input type="text" name="aliquota"    class="texto03" value="<?= @$obj->_aliquota; ?>" />
                                        </dd>-->
                    <!--                      <dt>
                                            <label>Inscrição Municipal (XML)</label>
                                        </dt>
                                        <dd>
                                            <input type="text" name="inscri_municipal"    class="texto03" value="<?= @$obj->_inscri_municipal; ?>" />
                                        </dd>-->
                    <dt>
                        <label>CNES</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtCNES" maxlength="14" class="texto03" value="<?= @$obj->_cnes; ?>" />
                    </dd>
                    <dt>
                        <label>Endere&ccedil;o</label>
                    </dt>
                    <dd>
                        <input type="text" id="txtendereco" class="texto08" name="endereco" value="<?= @$obj->_logradouro; ?>" />
                    </dd>
                    <dt>
                        <label>N&uacute;mero</label>
                    </dt>
                    <dd>
                        <input type="text" id="txtNumero" class="texto02" name="numero" value="<?= @$obj->_numero; ?>" />
                    </dd>
                    <dt>
                        <label>Bairro</label>
                    </dt>
                    <dd>
                        <input type="text" id="txtBairro" class="texto03" name="bairro" value="<?= @$obj->_bairro; ?>" />
                    </dd>
                    <dt>
                        <label>CEP</label>
                    </dt>
                    <dd>
                        <input type="text" id="txtCEP" class="texto02" name="CEP" alt="cep" value="<?= @$obj->_cep; ?>" />
                    </dd>
                    <dt>
                        <label>Telefone</label>
                    </dt>
                    <dd>
                        <?
//                        if (@$obj->_telefone != '' && strlen(@$obj->_telefone) > 3) {
//
//                            if (preg_match('/\(/', @$obj->_telefone)) {
//                                $telefone = @$obj->_telefone;
//                            } else {
//                                $telefone = "(" . substr(@$obj->_telefone, 0, 2) . ")" . substr(@$obj->_telefone, 2, strlen(@$obj->_telefone) - 2);
//                            }
//                        } else {
//                            $telefone = '';
//                        }
                        ?>
                        <input type="text" id="txtTelefone" class="texto03" name="telefone" value="<?= @$obj->_telefone; ?>" />
                    </dd>
                    <dt>
                        <label>Celular</label>
                    </dt>
                    <dd>
                        <?
//                        if (@$obj->_celular != '' && strlen(@$obj->_celular) > 3) {
//                            if (preg_match('/\(/', @$obj->_celular)) {
//                                $celular = @$obj->_celular;
//                            } else {
//                                $celular = "(" . substr(@$obj->_celular, 0, 2) . ")" . substr(@$obj->_celular, 2, strlen(@$obj->_celular) - 2);
//                            }
//                        } else {
//                            $celular = '';
//                        }
                        ?>
                        <input type="text" id="txtCelular" class="texto03" name="celular" value="<?= @$obj->_celular; ?>" />
                    </dd>
                    <dt>
                        <label>Email</label>
                    </dt>
                    <dd>
                        <input type="text" id="email" class="texto03" name="email" value="<?= @$obj->_email; ?>" />
                    </dd>
                    <dt>
                        <label>Município</label>
                    </dt>
                    <dd>
                        <input type="hidden" id="txtCidadeID" class="texto_id" name="municipio_id" value="<?= @$obj->_municipio_id; ?>" readonly="true" />
                        <input type="text" id="txtCidade" class="texto04" name="txtCidade" value="<?= @$obj->_municipio; ?>" />
                    </dd>

                    <?
                    if (@$obj->_cirugico_manual == 't') {
                        ?>
                        <dt>
                            <label>Codigo IBGE</label>
                        </dt>
                        <dd>
                            <input type="text" id="codigoibge" class="texto04" name="codigoibge" value="<?= @$obj->_codigo_ibge; ?>" disabled=""/>
                        </dd>
                        <?
                    }
                    ?>

                    <? if (@$obj->_convenio_padrao == 't') { ?>
                        <dt>
                            <label>Convenio Padrão</label>
                        </dt>
                        <dd>
                            <select name="convenio_padrao_id" id="convenio_padrao_id" class="size2">
                                <option value='0'>Selecione</option>
                                <? foreach ($convenio as $value) : ?>
                                    <option value="<?= $value->convenio_id; ?>" <?= (@$obj->_convenio_padrao_id == $value->convenio_id) ? 'selected' : '' ?>><?php echo $value->nome; ?></option>
                                <? endforeach; ?>
                            </select>
                        </dd>
                    <? } ?>
                    <? if ($this->session->userdata('operador_id') == 1) { ?>
                        <dt>
                            <label>Horário Seg à Sex</label>
                        </dt>
                        <dd>
                            <input type="text" id="horSegSexta_i" class="texto01" name="horSegSexta_i" alt="29:99" value="<?= @$obj->_horario_seg_sex_inicio; ?>" /> às
                            <input type="text" id="horSegSexta_f" class="texto01" name="horSegSexta_f" alt="29:99" value="<?= @$obj->_horario_seg_sex_fim; ?>" />
                        </dd>
                        <dt>
                            <label>Horário Sab</label>
                        </dt>
                        <dd>
                            <input type="text" id="horSab_i" class="texto01" name="horSab_i" alt="29:99" value="<?= @$obj->_horario_sab_inicio; ?>" /> às
                            <input type="text" id="horSab_f" class="texto01" name="horSab_f" alt="29:99" value="<?= @$obj->_horario_sab_fim; ?>" />
                        </dd>

                        <dt>
                            <label>Horário para Informar Faltas dos Pacientes (min)</label>
                        </dt>
                        <dd>
                            <input type="number"  step="1" id="horFalta_p" class="texto01" name="horFalta_p" value="<?= @$obj->_horario_para_informar_faltas; ?>" />
                        </dd>

                        <dt>
                            <label>Qtds de Faltas para Aviso</label>
                        </dt>
                        <dd>
                            <input type="number"  step="1" id="qtdefaltas" class="texto01" name="qtdefaltas" value="<?= @$obj->_qtdefaltas_pacientes; ?>" />
                        </dd>


                        <dt>
                            <label>Serviço de SMS</label>
                        </dt>
                        <dd>
                            <input type="checkbox" id="sms" name="sms" <? if (@$obj->_servicosms == 't') echo "checked"; ?>/>
                        </dd>  
                         <dt>
                            <label>Serviço de Whatsapp (Envio automático)</label>
                        </dt>
                        <dd>
                            <input type="checkbox" id="servicowhatsapp" name="servicowhatsapp" <? if (@$obj->_servicowhatsapp == 't') echo "checked"; ?>/>
                        </dd> 
 

                        <dt>
                            <label title="Mandar email para os pacientes lembrando das consultas.">Serviço de Email</label>
                        </dt>
                        <dd>
                            <input type="checkbox" id="servicoemail" name="servicoemail" <? if (@$obj->_servicoemail == 't') echo "checked"; ?>/>
                        </dd>
                        <dt>
                            <label title="Habilitar o chat.">Serviço de Chat</label>
                        </dt>
                        <dd>
                            <input type="checkbox" id="chat" name="chat" <? if (@$obj->_chat == 't') echo "checked"; ?>/> 
                        </dd>
                        <dt>
                            <label title="Habilitar o whatsapp.">Serviço de WhatsApp</label>
                        </dt>
                        <dd>
                            <input type="checkbox" id="whatsapp" name="whatsapp" <? if (@$obj->_whatsapp == 't') echo "checked"; ?>/> 
                        </dd>
                        <dt>
                            <label>Impressão Tipo (Impressão ficha e imagem)</label>
                        </dt>
                        <dd>
                            <input type="text" id="impressao_tipo" class="texto01" name="impressao_tipo" value="<?= @$obj->_impressao_tipo; ?>" />
                        </dd>
                        <dt>
                            <label>Impressão Laudo</label>
                        </dt>
                        <dd>
                            <input type="text" id="impressao_laudo" class="texto01" name="impressao_laudo" value="<?= @$obj->_impressao_laudo; ?>" />
                        </dd>
                        <dt>
                            <label>Impressão Recibo</label>
                        </dt>
                        <dd>
                            <input type="text" id="impressao_recibo" class="texto01" name="impressao_recibo" value="<?= @$obj->_impressao_recibo; ?>" />
                        </dd>
                        <dt>
                            <label>Impressão Orçamento</label>
                        </dt>
                        <dd>
                            <input type="text" id="impressao_orcamento" class="texto01" name="impressao_orcamento" value="<?= @$obj->_impressao_orcamento; ?>" />
                        </dd>
                        <dt>
                            <label>Impressão Declaração</label>
                        </dt>
                        <dd>
                            <input type="text" id="impressao_declaracao" class="texto01" name="impressao_declaracao" value="<?= @$obj->_impressao_declaracao; ?>" />
                        </dd>
                        <dt>
                            <label>Impressão Internação</label>
                        </dt>
                        <dd>
                            <input type="text" id="impressao_internacao" class="texto01" name="impressao_internacao" value="<?= @$obj->_impressao_internacao; ?>" />
                        </dd>
                        <dt>
                            <label>Valor Consulta APP</label>
                        </dt>
                        <dd>
                            <input type="text" alt="decimal"  id="valor_consulta_app" class="texto01" name="valor_consulta_app" value="<?= number_format(@$obj->_valor_consulta_app, 2, ',', '.') ?>" />
                        </dd>
                        <dt>
                            <label>Client_Id GerenciaNet</label>
                        </dt>
                        <dd>
                            <input type="text"  id="client_id" class="texto08" name="client_id" value="<?= @$obj->_client_id; ?>" />
                        </dd>
                        <dt>
                            <label>Client_Secret GerenciaNet</label>
                        </dt>
                        <dd>
                            <input type="text"  id="client_secret" class="texto08" name="client_secret" value="<?= @$obj->_client_secret; ?>" />
                        </dd>
                        <dt>
                            <label>Modo de Testes GerenciaNet</label>
                        </dt>
                        <dd>
                            <input type="checkbox"  id="client_sandbox" class="texto01" name="client_sandbox" <?=(@$obj->_client_sandbox == 't') ? 'checked' : '' ?> />
                        </dd>
                        
                        
                        <dt>
                            <label title="Site da Empresa">Site</label>
                        </dt> 
                        <dd>
                            <input title="Site da Empresa" type="text" id="site_empresa" class="texto08" name="site_empresa" value="<?= @$obj->_site_empresa; ?>" />
                        </dd>
                        
                        <dt>
                            <label title="Facebook da Empresa">Facebook</label>
                        </dt> 
                        <dd>
                            <input title="Facebook da Empresa" type="text" id="facebook_empresa" class="texto08" name="facebook_empresa" value="<?= @$obj->_facebook_empresa; ?>" />
                        </dd>
                        
                         <dt>
                            <label title="Instagram da Empresa">Instagram</label>
                        </dt> 
                        <dd>
                            <input title="Instagram da Empresa" type="text" id="instagram_empresa" class="texto08" name="instagram_empresa" value="<?= @$obj->_instagram_empresa; ?>" />
                        </dd>
                        
                        
                        <dt>
                            <label title="Endereço do sistema para receber resultados (Feito pra ficha da Santa Clara)">Endereço do sistema na ficha (SantaClara)</label>
                        </dt> 
                        <dd>
                            <input title="Endereço do sistema para receber resultados (Feito pra ficha da Santa Clara)" type="text" id="endereco_externo" class="texto08" name="endereco_ficha" value="<?= @$obj->_endereco_ficha; ?>" />
                        </dd>
                        <dt>
                            <label title="Endereço do sistema de cadastro de pacientes">Endereço Externo Cadastro (http://192.168.25.35/cadastro)</label>
                        </dt> 
                        <dd>
                            <input title="Endereço do sistema de cadastro de pacientes" type="text" id="endereco_externo" class="texto08" name="endereco_externo" value="<?= @$obj->_endereco_externo; ?>" />
                        </dd>
                        <dt>
                            <label title="Endereço do sistema externo. Pode ser ddns ou o link">Endereço Externo (http://200.168.25.35/)</label>
                        </dt> 
                        <dd>
                            <input title="Endereço do sistema externo. Pode ser ddns ou o link" type="text" id="endereco_externo_base" class="texto08" name="endereco_externo_base" value="<?= @$obj->_endereco_externo_base; ?>" />
                        </dd>
                        <dt>
                            <label title="Endereço de integração com o sistema de Laboratório Ex:(https://labluz.lisnet.com.br/lisnetws/APOIO)">Endereço Integração Lab </label>
                        </dt> 
                        <dd>
                            <input title="Endereço de integração com o sistema de Laboratório Ex:(https://labluz.lisnet.com.br/lisnetws/APOIO)" type="text" id="endereco_integracao_lab" class="texto08" name="endereco_integracao_lab" value="<?= @$obj->_endereco_integracao_lab; ?>" />
                        </dd>
                        <dt>
                            <label title="Identificação do Código Lis para integração">IdentificadorLis </label>
                        </dt> 
                        <dd>
                            <input title="Identificação do Código Lis para integração" type="text" id="identificador_lis" class="texto08" name="identificador_lis" value="<?= @$obj->_identificador_lis; ?>" />
                        </dd>
                        <dt>
                            <label title="OrigemLis também para a integração">OrigemLis </label>
                        </dt> 
                        <dd>
                            <input title="OrigemLis também para a integração" type="text" id="origem_lis" class="texto08" name="origem_lis" value="<?= @$obj->_origem_lis; ?>" />
                        </dd>
                        <dt>
                            <label title="Link para Integração com Certificado">Link Certificado API (http://192.168.30.3:8081/)</label>
                        </dt> 
                        <dd>
                            <input title="Link para Integração com Certificado" type="text" id="link_certificado" class="texto08" name="link_certificado" value="<?= @$obj->_link_certificado; ?>" />
                        </dd>
                        <dt>
                            <label title="Endereço da pasta de upload do sistema. Padrão Ubuntu: /home/sisprod/projetos/clinica/upload    Padrão no CentOS: /var/www/html/NOME DA PASTA DA CLINICA/upload">Endereço Upload (Mouse em cima para mais inf.)</label>
                        </dt> 
                        <dd>
                            <input type="text"title="Endereço da pasta de upload do sistema. Padrão Ubuntu: /home/sisprod/projetos/clinica/upload | Padrão no CentOS: /var/www/html/NOME DA PASTA DA CLINICA/upload" id="endereco_externo" class="texto08" name="endereco_upload" value="<?= @$obj->_endereco_upload; ?>" />
                        </dd>
                        <dt>
                            <label title="Nome da Pasta do sistema. Padrão: clinica. Outros Exemplos: paciente - clinicamed - sinapse - todoctor">Pasta do Sistema Paciente (Mouse em cima para mais inf.)</label>
                        </dt>


                        <dd>
                            <input type="text"title="Nome da Pasta do sistema. Padrão: paciente. Outros Exemplos: paciente - clinicamed - sinapse - todoctor" id="endereco_upload_pasta_paciente" class="texto08" name="endereco_upload_pasta_paciente" value="<?= @$obj->_endereco_upload_pasta_paciente; ?>" />
                        </dd>
                        <dt>
                            <label title="Nome da Pasta do sistema. Padrão: clinica. Outros Exemplos: paciente - clinicamed - sinapse - todoctor">Pasta do Sistema (Mouse em cima para mais inf.)</label>
                        </dt>


                        <dd>
                            <input type="text"title="Nome da Pasta do sistema. Padrão: clinica. Outros Exemplos: paciente - clinicamed - sinapse - todoctor" id="endereco_upload_pasta" class="texto08" name="endereco_upload_pasta" value="<?= @$obj->_endereco_upload_pasta; ?>" />
                        </dd>


                        <dt>
                            <label>Endereço Toten EX: (http://192.168.25.47:8099)</label>
                        </dt>
                        <dd>
                            <input type="text" id="endereco_toten" class="texto08" name="endereco_toten" value="<?= @$obj->_endereco_toten; ?>" />
                        </dd>
                        <dt>
                            <label>Numero Empresa (Painel)</label>
                        </dt>
                        <dd>
                            <input type="text" id="numero_empresa_painel" name="numero_empresa_painel" class="texto05" value="<?= @$obj->_numero_empresa_painel; ?>" />
                        </dd>
                         <dt>
                            <label>Solicitações tempo para chamar</label>
                        </dt>
                        <dd>
                            <input type="text" id="solicitacaotempo"  alt="29:99"   name="solicitacaotempo" class="texto05" value="<?= @$obj->_solicitacaotempo; ?>" />
                        </dd>
                         <dt>
                            <label>Link do Sistema de Pacientes</label>
                        </dt>
                        <dd>
                            <input type="text" id="link_sistema_paciente"  name="link_sistema_paciente" class="texto05" value="<?= @$obj->_link_sistema_paciente; ?>" />
                        </dd>
                        
                        
                        <br>
                        <div>
                            <dt>
                                <label title="Definir os campos que serao obrigatorios no cadastro de paciente.">Campos Obrigatorios</label>
                            </dt>
                            <dd>
                                <?
                                if (@$obj->_campos_cadastro != '') {
                                    $campos_obrigatorios = json_decode(@$obj->_campos_cadastro);
                                } else {
                                    $campos_obrigatorios = array();
                                }
                                ?>
                                <select name="campos_obrigatorio[]" style="width: 47%;" class="chosen-select" data-placeholder="Selecione os campos..." multiple>
                                    <!--<option value="nome" <?= (in_array('nome', $campos_obrigatorios)) ? 'selected' : ''; ?>>Nome</option>-->
                                    <option value="nascimento" <?= (in_array('nascimento', $campos_obrigatorios)) ? 'selected' : ''; ?>>Nascimento</option>
                                    <option value="nome_mae" <?= (in_array('nome_mae', $campos_obrigatorios)) ? 'selected' : ''; ?>>Nome da Mãe</option>
                                    <option value="nome_pai" <?= (in_array('nome_pai', $campos_obrigatorios)) ? 'selected' : ''; ?>>Nome do Pai</option>
                                    <option value="nome_conjuge" <?= (in_array('nome_conjuge', $campos_obrigatorios)) ? 'selected' : ''; ?>>Nome do Cônjuge</option>
                                    <option value="nascimento_conjuge" <?= (in_array('nascimento_conjuge', $campos_obrigatorios)) ? 'selected' : ''; ?>>Nascimento do Cônjuge</option>
                                    <option value="email" <?= (in_array('email', $campos_obrigatorios)) ? 'selected' : ''; ?>>Email</option>
                                    <option value="sexo" <?= (in_array('sexo', $campos_obrigatorios)) ? 'selected' : ''; ?>>Sexo</option>
                                    <option value="cpf" <?= (in_array('cpf', $campos_obrigatorios)) ? 'selected' : ''; ?>>CPF</option>
                                    <option value="cpf_mae" <?= (in_array('cpf_mae', $campos_obrigatorios)) ? 'selected' : ''; ?>>CPF Mãe</option>
                                    <option value="cpf_pai" <?= (in_array('cpf_pai', $campos_obrigatorios)) ? 'selected' : ''; ?>>CPF Pai</option>
                                    <option value="rg" <?= (in_array('rg', $campos_obrigatorios)) ? 'selected' : ''; ?>>RG</option>
                                    <!--<option value="logradouro" <?= (in_array('logradouro', $campos_obrigatorios)) ? 'selected' : ''; ?>>T. logradouro</option>-->
                                    <option value="endereco" <?= (in_array('endereco', $campos_obrigatorios)) ? 'selected' : ''; ?>>Endereço</option>
                                    <option value="numero" <?= (in_array('numero', $campos_obrigatorios)) ? 'selected' : ''; ?>>Número</option>
                                    <option value="complemento" <?= (in_array('complemento', $campos_obrigatorios)) ? 'selected' : ''; ?>>Complemento</option>
                                    <option value="indicacao" <?= (in_array('indicacao', $campos_obrigatorios)) ? 'selected' : ''; ?>>Indicacao</option>
                                    <option value="bairro" <?= (in_array('bairro', $campos_obrigatorios)) ? 'selected' : ''; ?>>Bairro</option>
                                    <option value="municipio" <?= (in_array('municipio', $campos_obrigatorios)) ? 'selected' : ''; ?>>Município</option>
                                    <option value="cep" <?= (in_array('cep', $campos_obrigatorios)) ? 'selected' : ''; ?>>CEP</option>
                                    <option value="telefone1" <?= (in_array('telefone1', $campos_obrigatorios)) ? 'selected' : ''; ?>>Telefone 1</option>
                                    <option value="telefone2" <?= (in_array('telefone2', $campos_obrigatorios)) ? 'selected' : ''; ?>>Telefone 2</option>
                                    <option value="whatsapp" <?= (in_array('whatsapp', $campos_obrigatorios)) ? 'selected' : ''; ?>>WhatsApp</option>
                                    <option value="plano_saude" <?= (in_array('plano_saude', $campos_obrigatorios)) ? 'selected' : ''; ?>>Plano de Saude</option>
                                    <option value="leito" <?= (in_array('leito', $campos_obrigatorios)) ? 'selected' : ''; ?>>Leito</option>
                                    <option value="numero_carteira" <?= (in_array('numero_carteira', $campos_obrigatorios)) ? 'selected' : ''; ?>>Número Carteira</option>
                                    <option value="vencimento_carteira" <?= (in_array('vencimento_carteira', $campos_obrigatorios)) ? 'selected' : ''; ?>>Vencimento Carteira</option>
                                    <option value="ocupacao" <?= (in_array('ocupacao', $campos_obrigatorios)) ? 'selected' : ''; ?>>Ocupação</option>
                                    <option value="nacionalidade" <?= (in_array('nacionalidade', $campos_obrigatorios)) ? 'selected' : ''; ?>>Nacionalidade</option>
                                    <option value="raca_cor" <?= (in_array('raca_cor', $campos_obrigatorios)) ? 'selected' : ''; ?>>Raça / Cor</option>
                                    <option value="estado_civil" <?= (in_array('estado_civil', $campos_obrigatorios)) ? 'selected' : ''; ?>>Estado civil</option>
                                    <option value="escolaridade" <?= (in_array('escolaridade', $campos_obrigatorios)) ? 'selected' : ''; ?>>Escolaridade</option>
                                    <option value="instagram" <?= (in_array('instagram', $campos_obrigatorios)) ? 'selected' : ''; ?>>Instagram</option>
                                    <option value="prontuario_antigo" <?= (in_array('prontuario_antigo', $campos_obrigatorios)) ? 'selected' : ''; ?>>Prontuário Antigo</option>
                                    <option value="cnh" <?= (in_array('cnh', $campos_obrigatorios)) ? 'selected' : ''; ?>>CNH</option>
                                    <option value="vencimento_cnh" <?= (in_array('vencimento_cnh', $campos_obrigatorios)) ? 'selected' : ''; ?>>Vencimento CNH</option>


                                </select>
                            </dd>
                        </div>
                        <br>
                        <br>





                        <div><br><br>
                            <dt>
                                <label title="Definir os campos visíveis na tela de atendimento médico">Tela de Atendimento Médico 2º Versão</label>
                            </dt>
                            <dd>
                                <?
                                if (@$obj->_campos_atendimentomed != '') {
                                    $opc_telatendimento = json_decode(@$obj->_campos_atendimentomed);
                                } else {
                                    $opc_telatendimento = array();
                                }
                                ?>
                                <select name="opc_telatendimento[]" style="width: 47%;" class="chosen-select" data-placeholder="Selecione os campos..." multiple>
                                    <option value="cirurgias" <?= (in_array('cirurgias', $opc_telatendimento)) ? 'selected' : ''; ?>>Cirurgias</option>
                                    <option value="lab" <?= (in_array('lab', $opc_telatendimento)) ? 'selected' : ''; ?>>Exames Laboratoriais</option>
                                    <option value="eco" <?= (in_array('eco', $opc_telatendimento)) ? 'selected' : ''; ?>>Ecocardiograma</option>
                                    <option value="ecostress" <?= (in_array('ecostress', $opc_telatendimento)) ? 'selected' : ''; ?>>Eco Stress</option>
                                    <option value="cate" <?= (in_array('cate', $opc_telatendimento)) ? 'selected' : ''; ?>>Cateterismo Cardiaco</option>
                                    <option value="holter" <?= (in_array('holter', $opc_telatendimento)) ? 'selected' : ''; ?>>Holter 24h</option>
                                    <option value="cintil" <?= (in_array('cintil', $opc_telatendimento)) ? 'selected' : ''; ?>>Cintilografia</option>
                                    <option value="mapa" <?= (in_array('mapa', $opc_telatendimento)) ? 'selected' : ''; ?>>Mapa</option>
                                    <option value="te" <?= (in_array('te', $opc_telatendimento)) ? 'selected' : ''; ?>>Teste Ergométrico</option>
                                    <option value="receituario" <?= (in_array('receituario', $opc_telatendimento)) ? 'selected' : ''; ?>>Receituário</option>
                                    <option value="rotinas" <?= (in_array('rotinas', $opc_telatendimento)) ? 'selected' : ''; ?>>Rotinas</option>
                                    <option value="historicoimprimir" <?= (in_array('historicoimprimir', $opc_telatendimento)) ? 'selected' : ''; ?>>Imprimir Histórico</option>


                                    <option value="receituarioesp" <?= (in_array('receituarioesp', $opc_telatendimento)) ? 'selected' : ''; ?>>Receituário Especial</option>
                                    <option value="solicitar_exames" <?= (in_array('solicitar_exames', $opc_telatendimento)) ? 'selected' : ''; ?>>Solicitar Exames</option>
                                    <option value="atestado" <?= (in_array('atestado', $opc_telatendimento)) ? 'selected' : ''; ?>>Atestado</option>
                                    <option value="declaracao" <?= (in_array('declaracao', $opc_telatendimento)) ? 'selected' : ''; ?>>Declaração</option>
                                    <option value="arquivos" <?= (in_array('arquivos', $opc_telatendimento)) ? 'selected' : ''; ?>>Arquivos</option>
                                    <option value="aih" <?= (in_array('aih', $opc_telatendimento)) ? 'selected' : ''; ?>>Laudo AIH</option>
                                    <option value="consultar_procedimento" <?= (in_array('consultar_procedimento', $opc_telatendimento)) ? 'selected' : ''; ?>>Consultar Procedimento</option>
                                    <option value="sadt" <?= (in_array('sadt', $opc_telatendimento)) ? 'selected' : ''; ?>>Solicitação SADT</option>
                                    <option value="cadastro_aso" <?= (in_array('cadastro_aso', $opc_telatendimento)) ? 'selected' : ''; ?>>Cadastro ASO</option>
                                    <option value="chamar" <?= (in_array('chamar', $opc_telatendimento)) ? 'selected' : ''; ?>>Chamar</option>




                                    <option value="editar" <?= (in_array('editar', $opc_telatendimento)) ? 'selected' : ''; ?>>Editar</option>
                                    <option value="pendente" <?= (in_array('pendente', $opc_telatendimento)) ? 'selected' : ''; ?>>Pendente</option>
                                    <option value="encaminhar" <?= (in_array('encaminhar', $opc_telatendimento)) ? 'selected' : ''; ?>>Encaminhar</option>
                                    <option value="histconsulta" <?= (in_array('histconsulta', $opc_telatendimento)) ? 'selected' : ''; ?>>Histórico Consulta</option>
                                    <option value="histantigo" <?= (in_array('histantigo', $opc_telatendimento)) ? 'selected' : ''; ?>>Histórico Antigo</option>
                                    <option value="preencherform" <?= (in_array('preencherform', $opc_telatendimento)) ? 'selected' : ''; ?>>Preencher Formulário</option>
                                    <option value="parecercirurgia" <?= (in_array('parecercirurgia', $opc_telatendimento)) ? 'selected' : ''; ?>>Parecer Cirurgia Pediátrica</option>
                                    <option value="laudoapendicite" <?= (in_array('laudoapendicite', $opc_telatendimento)) ? 'selected' : ''; ?>>Laudo Apendicite</option>
                                    <option value="solicitarparecer" <?= (in_array('solicitarparecer', $opc_telatendimento)) ? 'selected' : ''; ?>>Solicitar Parecer</option>
                                    <option value="riscocirurgico" <?= (in_array('riscocirurgico', $opc_telatendimento)) ? 'selected' : ''; ?>>Risco Cirúrgico</option>
                                    <option value="zoom" <?= (in_array('zoom', $opc_telatendimento)) ? 'selected' : ''; ?>>Zoom</option>

                                    <option value="pesquisar_codigo_tuss" <?= (in_array('pesquisar_codigo_tuss', $opc_telatendimento)) ? 'selected' : ''; ?>>Pesquisar Codigo Tuss</option>
                                    <option value="diagnostico" <?= (in_array('diagnostico', $opc_telatendimento)) ? 'selected' : ''; ?>>Diagnostico</option>
                                    <option value="relogio" <?= (in_array('relogio', $opc_telatendimento)) ? 'selected' : ''; ?>>Relogio</option>
                                </select>
                            </dd>
                        </div>
                        <div><br><br>
                            <dt>
                                <label title="Definir os botões no app">Botões do APP</label>
                            </dt>
                            <dd>
                                <?
                                if (@$obj->_botoes_app != '') {
                                    $botoes_app = json_decode(@$obj->_botoes_app);
                                } else {
                                    $botoes_app = array();
                                }
                                ?>
                                <select name="botoes_app[]" style="width: 47%;" class="chosen-select" data-placeholder="Selecione os campos..." multiple>
                                    <option value="p_hexames" <?= (in_array('p_hexames', $botoes_app)) ? 'selected' : ''; ?>>Histórico Exame</option>
                                    <option value="p_hconsulta" <?= (in_array('p_hconsulta', $botoes_app)) ? 'selected' : ''; ?>>Histórico Consulta</option>
                                    <option value="p_marcar_consulta" <?= (in_array('p_marcar_consulta', $botoes_app)) ? 'selected' : ''; ?>>Marcar Consulta</option>
                                    <option value="p_solicitar_consulta" <?= (in_array('p_solicitar_consulta', $botoes_app)) ? 'selected' : ''; ?>>Solicitar Consulta</option>
                                    <option value="p_risco_cirurgico" <?= (in_array('p_risco_cirurgico', $botoes_app)) ? 'selected' : ''; ?>>Risco Cirurgico</option>
                                    <option value="p_carterinha_virtual" <?= (in_array('p_carterinha_virtual', $botoes_app)) ? 'selected' : ''; ?>>Carterinha Virtual</option>
                                    <option value="p_mensalidades" <?= (in_array('p_mensalidades', $botoes_app)) ? 'selected' : ''; ?>>Mensalidades</option>
                                    <option value="p_dicas_saude" <?= (in_array('p_dicas_saude', $botoes_app)) ? 'selected' : ''; ?>>Informativos</option>
                                    <option value="p_como_chegar" <?= (in_array('p_como_chegar', $botoes_app)) ? 'selected' : ''; ?>>Como chegar</option>
                                    <option value="p_convenios" <?= (in_array('p_convenios', $botoes_app)) ? 'selected' : ''; ?>>Convênios</option>
                                    <option value="p_atendimento" <?= (in_array('p_atendimento', $botoes_app)) ? 'selected' : ''; ?>>Atendimento</option>
                                    <option value="p_pesquisa_de_satisfacao" <?= (in_array('p_pesquisa_de_satisfacao', $botoes_app)) ? 'selected' : ''; ?>>Pesquisa de satisfação</option>
                                    <option value="p_receituario" <?= (in_array('p_receituario', $botoes_app)) ? 'selected' : ''; ?>>Listar Receituário</option>
                                    <option value="p_contato" <?= (in_array('p_contato', $botoes_app)) ? 'selected' : ''; ?>>Contato</option>
                                    <option value="p_questionario" <?= (in_array('p_questionario', $botoes_app)) ? 'selected' : ''; ?>>Questionário</option>
                                    
                                </select>
                            </dd>
                        </div>

                        <div><br><br>
                            <dt>
                                <label title="Definir os botões no app">Botões do APP Médico</label>
                            </dt>
                            <dd>

                                <select name="botoes_app[]" style="width: 47%;" class="chosen-select" data-placeholder="Selecione os campos..." multiple>
                                    <option value="m_agenda" <?= (in_array('m_agenda', $botoes_app)) ? 'selected' : ''; ?>>Agenda (Médico)</option>
                                    <option value="m_escala" <?= (in_array('m_escala', $botoes_app)) ? 'selected' : ''; ?>>Escala (Médico)</option>
                                    <option value="m_recebiveis" <?= (in_array('m_recebiveis', $botoes_app)) ? 'selected' : ''; ?>>Recebíveis (Médico)</option>
                                    <option value="m_pagamento_conta" <?= (in_array('m_pagamento_conta', $botoes_app)) ? 'selected' : ''; ?>>Pagamento de Contas (Médico)</option>
                                    <option value="m_gerenciamento_escala" <?= (in_array('m_gerenciamento_escala', $botoes_app)) ? 'selected' : ''; ?>>Gerenciamento de Escalas (Médico)</option>
                                    <option value="m_chat" <?= (in_array('m_chat', $botoes_app)) ? 'selected' : ''; ?>>Chat (Médico)</option>
                                    
                                </select>
                            </dd>
                        </div>


                        <div><br><br>
                            <dt>
                                <label title="Definir os campos que serão mostrados na lista de atendimentos">Campos Lista de Atendimento Médico 2º Versão</label>
                            </dt>
                            <dd>
                                <?
                                if (@$obj->_campos_listaatendimentomed != '') {
                                    $opc_listaatendimentomed = json_decode(@$obj->_campos_listaatendimentomed);
                                } else {
                                    $opc_listaatendimentomed = array();
                                }
                                ?>
                                <select name="opc_listaatendimentomed[]" style="width: 47%;" class="chosen-select" data-placeholder="Selecione os campos..." multiple>
                                    <option value="ordem" <?= (in_array('ordem', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Ordem</option>
                                    <option value="status" <?= (in_array('status', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Status</option>
                                    <option value="nome" <?= (in_array('nome', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Nome</option>
                                    <option value="espera" <?= (in_array('espera', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Espera</option>
                                    <option value="idade" <?= (in_array('idade', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Idade</option>
                                    <option value="convenio" <?= (in_array('convenio', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Convênio</option>
                                    <option value="agenda" <?= (in_array('agenda', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Agenda</option>
                                    <option value="data" <?= (in_array('data', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Data (Imagem)</option>
                                    <option value="telefone" <?= (in_array('telefone', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Telefone (Fisioterapia)</option>
                                    <option value="statuslaudo" <?= (in_array('statuslaudo', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Laudo (Imagem)</option>
                                    <option value="agendamento" <?= (in_array('agendamento', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Agendamento</option>
                                    <option value="autorizacao" <?= (in_array('autorizacao', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Autorização</option>
                                    <option value="procedimento" <?= (in_array('procedimento', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Procedimento</option>
                                    <option value="obs" <?= (in_array('obs', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>OBS</option>
                                    <option value="batender" <?= (in_array('batender', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Botão Atender</option>
                                    <option value="bimprimir" <?= (in_array('bimprimir', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Botão Imprimir</option>
                                    <option value="bconfirmar" <?= (in_array('bconfirmar', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Botão Confirmar</option>
                                    <option value="bhistorico" <?= (in_array('bhistorico', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Botão Histórico</option>
                                    <option value="bcancelar" <?= (in_array('bcancelar', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Botão Cancelar</option>
                                    <option value="barquivos" <?= (in_array('barquivos', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Botão Arquivos</option>
                                    <option value="bchamar" <?= (in_array('bchamar', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Botão Chamar</option>
                                    <option value="b2via" <?= (in_array('b2via', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Botão 2ª Via (Laudo)</option>
                                    <option value="bexame" <?= (in_array('bexame', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Botão Exame (Laudo)</option>
                                    <option value="bbloquear" <?= (in_array('bbloquear', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Botão Bloquear (Fisioterapia)</option>
                                    <option value="bconsulta" <?= (in_array('bconsulta', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Botão Consultas (Fisioterapia)</option>
                                </select>
                            </dd>
                        </div>

                        <br>
                        <br>

                        <div><br><br>

                            <dt>
                                <label title=" ">Grupos para Associar</label>    
                                <?
                                if (@$obj->_campos_atendimentomed != '') {
                                    $opc_telatendimento = json_decode(@$obj->_campos_atendimentomed);
                                } else {
                                    $opc_telatendimento = array();
                                }
                                ?>
                                <select name="opc_telatendimento_assoc[]" style="width: 52%;" class="chosen-select" data-placeholder="Selecione os campos..." multiple>
                                    <?
                                    foreach ($opc_telatendimento as $value) {
                                        ?>               
                                        <option value="<?= $value; ?>" >
                                            <?
                                            if ($value == "cirurgias") {
                                                echo "Cirurgias";
                                            }
                                            if ($value == "lab") {
                                                echo "Exames Laboratoriais";
                                            }
                                            if ($value == "eco") {
                                                echo "Ecocardiograma";
                                            }
                                            if ($value == "ecostress") {
                                                echo "Eco Stress";
                                            }
                                            if ($value == "cate") {
                                                echo "Cateterismo Cardiaco";
                                            }
                                            if ($value == "holter") {
                                                echo "Holter 24h";
                                            }
                                            if ($value == "cintil") {
                                                echo "Cintilografia";
                                            }
                                            if ($value == "mapa") {
                                                echo "Mapa";
                                            }
                                            if ($value == "te") {
                                                echo "Teste Ergométrico";
                                            }
                                            if ($value == "receituario") {
                                                echo "Receituário";
                                            }
                                            if ($value == "rotinas") {
                                                echo "Rotinas";
                                            }
                                            if ($value == "historicoimprimir") {
                                                echo "Imprimir Histórico";
                                            }

                                            if ($value == "receituarioesp") {
                                                echo "Receituário Especial";
                                            }

                                            if ($value == "solicitar_exames") {
                                                echo "Solicitar Exames";
                                            }

                                            if ($value == "atestado") {
                                                echo "Atestado";
                                            }


                                            if ($value == "declaracao") {
                                                echo "Declaração";
                                            }

                                            if ($value == "arquivos") {
                                                echo "Arquivos";
                                            }

                                            if ($value == "aih") {
                                                echo "Laudo AIH";
                                            }

                                            if ($value == "consultar_procedimento") {
                                                echo "Consultar Procedimento";
                                            }

                                            if ($value == "sadt") {
                                                echo "Solicitação SADT";
                                            }

                                            if ($value == "cadastro_aso") {
                                                echo "Cadastro ASO";
                                            }

                                            if ($value == "chamar") {
                                                echo "Chamar";
                                            }
                                            if ($value == "editar") {
                                                echo "Editar";
                                            }
                                            if ($value == "pendente") {
                                                echo "Pendente";
                                            }
                                            if ($value == "encaminhar") {
                                                echo "Encaminhar";
                                            }
                                            if ($value == "histconsulta") {
                                                echo "Histórico Consulta";
                                            }
                                            if ($value == "histantigo") {
                                                echo "Histórico Antigo";
                                            }
                                            if ($value == "preencherform") {
                                                echo "Preencher Formulário";
                                            }
                                            if ($value == "parecercirurgia") {
                                                echo "Parecer Cirurgia Pediátrica";
                                            }
                                            if ($value == "laudoapendicite") {
                                                echo "Laudo Apendicite";
                                            }
                                            ?>  

                                            <?
//                                   $value; 
                                            ?>

                                        </option>


                                        <?
                                    }
                                    ?>
                                </select>

                            </dt>



                            <select name="opc_grupos" style="width: 47%;" class="chosen-select" data-placeholder="Selecione os campos..."   >                                 
                                <?
                                foreach ($grupos as $value) {
                                    ?>

                                    <option value="<?= $value->ambulatorio_grupo_id; ?>" selected> <?= $value->nome; ?></option>

                                    <?
                                }
                                ?>

                            </select><br><br>
                            <input type="submit" value="Associar">

                        </div>



                        <br>
                        <br>



                        <div><br><br>


                            <?
// echo "<pre>";
//                            
// print_r($associados);
                            ?>
                            <dt>
                                <label title=" ">Associações Feitas</label>
                            </dt>
                            <dd>

                                <select  multiple name="associados_grupos[]" style="width: 47%;" class="chosen-select" data-placeholder="Selecione os campos..."  >                                 
                                    <?
                                    foreach ($associados as $value) {
                                        ?>           
                                        <option selected value="<? echo $value->empresa_associacoes_grupo_id; ?>" > <?
                                            if ($value->tela_atendimento == "cirurgias") {
                                                echo "Cirurgias";
                                            }
                                            if ($value->tela_atendimento == "lab") {
                                                echo "Exames Laboratoriais";
                                            }
                                            if ($value->tela_atendimento == "eco") {
                                                echo "Ecocardiograma";
                                            }
                                            if ($value->tela_atendimento == "ecostress") {
                                                echo "Eco Stress";
                                            }
                                            if ($value->tela_atendimento == "cate") {
                                                echo "Cateterismo Cardiaco";
                                            }
                                            if ($value->tela_atendimento == "holter") {
                                                echo "Holter 24h";
                                            }
                                            if ($value->tela_atendimento == "cintil") {
                                                echo "Cintilografia";
                                            }
                                            if ($value->tela_atendimento == "mapa") {
                                                echo "Mapa";
                                            }
                                            if ($value->tela_atendimento == "te") {
                                                echo "Teste Ergométrico";
                                            }
                                            if ($value->tela_atendimento == "receituario") {
                                                echo "Receituário";
                                            }
                                            if ($value->tela_atendimento == "rotinas") {
                                                echo "Rotinas";
                                            }
                                            if ($value->tela_atendimento == "historicoimprimir") {
                                                echo "Imprimir Histórico";
                                            }

                                            if ($value->tela_atendimento == "receituarioesp") {
                                                echo "Receituário Especial";
                                            }

                                            if ($value->tela_atendimento == "solicitar_exames") {
                                                echo "Solicitar Exames";
                                            }

                                            if ($value->tela_atendimento == "atestado") {
                                                echo "Atestado";
                                            }


                                            if ($value->tela_atendimento == "declaracao") {
                                                echo "Declaração";
                                            }

                                            if ($value->tela_atendimento == "arquivos") {
                                                echo "Arquivos";
                                            }

                                            if ($value->tela_atendimento == "aih") {
                                                echo "Laudo AIH";
                                            }

                                            if ($value->tela_atendimento == "consultar_procedimento") {
                                                echo "Consultar Procedimento";
                                            }

                                            if ($value->tela_atendimento == "sadt") {
                                                echo "Solicitação SADT";
                                            }

                                            if ($value->tela_atendimento == "cadastro_aso") {
                                                echo "Cadastro ASO";
                                            }

                                            if ($value->tela_atendimento == "chamar") {
                                                echo "Chamar";
                                            }
                                            if ($value->tela_atendimento == "editar") {
                                                echo "Editar";
                                            }
                                            if ($value->tela_atendimento == "pendente") {
                                                echo "Pendente";
                                            }
                                            if ($value->tela_atendimento == "encaminhar") {
                                                echo "Encaminhar";
                                            }
                                            if ($value->tela_atendimento == "histconsulta") {
                                                echo "Histórico Consulta";
                                            }
                                            if ($value->tela_atendimento == "histantigo") {
                                                echo "Histórico Antigo";
                                            }
                                            if ($value->tela_atendimento == "preencherform") {
                                                echo "Preencher Formulário";
                                            }
                                            if ($value->tela_atendimento == "parecercirurgia") {
                                                echo "Parecer Cirurgia Pediátrica";
                                            }
                                            if ($value->tela_atendimento == "laudoapendicite") {
                                                echo "Laudo Apendicite";
                                            }

//                                    $value->tela_atendimento; 
                                            ?> -  <?= $value->nome; ?></option>
                                        <?
                                    }
                                    ?>

                                </select>
                            </dd>
                        </div>

                        <br>
                        <br>

                        <div><br><br>
                            <dt>
                                <label title="Definir os modelos que o médico pode criar na Nova Tela de atendimento do médico">Modelos Nova Tela de Atendimento</label>
                            </dt>
                            <dd>
                                <?
                                if (@$obj->_modelos_atendimento != '') {
                                    $modelos_atendimento = json_decode(@$obj->_modelos_atendimento);
                                } else {
                                    $modelos_atendimento = array();
                                }
                                ?>
                                <select name="modelos_atendimento[]" style="width: 47%;" class="chosen-select" data-placeholder="Selecione os campos..." multiple>
                                    <option value="receitas" <?= (in_array('receitas', $modelos_atendimento)) ? 'selected' : ''; ?>>Modelo Receita</option>
                                    <option value="teraupeticas" <?= (in_array('teraupeticas', $modelos_atendimento)) ? 'selected' : ''; ?>>Modelo Teraupeticas</option>
                                    <option value="protocolo" <?= (in_array('protocolo', $modelos_atendimento)) ? 'selected' : ''; ?>>Modelo Protocolo</option>
                                    <option value="relatorio" <?= (in_array('relatorio', $modelos_atendimento)) ? 'selected' : ''; ?>>Modelo Relatorio</option>
                                    <option value="S_exames" <?= (in_array('S_exames', $modelos_atendimento)) ? 'selected' : ''; ?>>Modelo S. Exames</option>

                                </select>
                            </dd>
                        </div>

                        <div><br><br>
                            <dt>
                                <label title="Definir os dados do paciente visíveis">Dados do Paciente (Atendimento)</label>
                            </dt>
                            <dd>
                                <?
                                if (@$obj->_dados_atendimentomed != '') {
                                    $opc_dadospaciente = json_decode(@$obj->_dados_atendimentomed);
                                } else {
                                    $opc_dadospaciente = array();
                                }
                                ?>
                                <select name="opc_dadospaciente[]" style="width: 47%;" class="chosen-select" data-placeholder="Selecione os campos..." multiple>
                                    <option value="paciente" <?= (in_array('paciente', $opc_dadospaciente)) ? 'selected' : ''; ?>>Nome</option>
                                    <option value="idade" <?= (in_array('idade', $opc_dadospaciente)) ? 'selected' : ''; ?>>Idade</option>
                                    <option value="sexo" <?= (in_array('sexo', $opc_dadospaciente)) ? 'selected' : ''; ?>>Sexo</option>
                                    <option value="indicacao" <?= (in_array('indicacao', $opc_dadospaciente)) ? 'selected' : ''; ?>>Indicação</option>
                                    <option value="exame" <?= (in_array('exame', $opc_dadospaciente)) ? 'selected' : ''; ?>>Exame</option>
                                    <option value="nascimento" <?= (in_array('nascimento', $opc_dadospaciente)) ? 'selected' : ''; ?>>Nascimento</option>
                                    <option value="ocupacao" <?= (in_array('ocupacao', $opc_dadospaciente)) ? 'selected' : ''; ?>>Ocupação</option>
                                    <option value="endereco" <?= (in_array('endereco', $opc_dadospaciente)) ? 'selected' : ''; ?>>Endereço</option>
                                    <option value="estadocivil" <?= (in_array('estadocivil', $opc_dadospaciente)) ? 'selected' : ''; ?>>Estado Civil</option>
                                    <option value="convenio" <?= (in_array('convenio', $opc_dadospaciente)) ? 'selected' : ''; ?>>Convênio</option>
                                    <option value="solicitante" <?= (in_array('solicitante', $opc_dadospaciente)) ? 'selected' : ''; ?>>Solicitante</option>
                                    <option value="agendamento" <?= (in_array('agendamento', $opc_listaatendimentomed)) ? 'selected' : ''; ?>>Agendamento</option>
                                    <option value="sala" <?= (in_array('sala', $opc_dadospaciente)) ? 'selected' : ''; ?>>Sala</option>
                                    <option value="telefone" <?= (in_array('telefone', $opc_dadospaciente)) ? 'selected' : ''; ?>>Telefone</option>
                                    <option value="ocupacao_pai" <?= (in_array('ocupacao_pai', $opc_dadospaciente)) ? 'selected' : ''; ?>>Ocupação do Pai</option>
                                    <option value="ocupacao_mae" <?= (in_array('ocupacao_mae', $opc_dadospaciente)) ? 'selected' : ''; ?>>Ocupação da Mãe</option>
                                    <option value="nome_pai" <?= (in_array('nome_pai', $opc_dadospaciente)) ? 'selected' : ''; ?>>Nome do Pai</option>
                                    <option value="nome_mae" <?= (in_array('nome_mae', $opc_dadospaciente)) ? 'selected' : ''; ?>>Nome da Mãe</option>
                                    <option value="cidade" <?= (in_array('cidade', $opc_dadospaciente)) ? 'selected' : ''; ?>>Cidade</option>
                                    <option value="conjuge" <?= (in_array('conjuge', $opc_dadospaciente)) ? 'selected' : ''; ?>>Cônjuge</option>
                                    <option value="idade_conjuge" <?= (in_array('idade_conjuge', $opc_dadospaciente)) ? 'selected' : ''; ?>>Idade do Cônjuge</option>
                                    <option value="prontuario_antigo" <?= (in_array('prontuario_antigo', $opc_dadospaciente)) ? 'selected' : ''; ?>>Prontuário Antigo</option>
                                    
                                    <option value="celular" <?= (in_array('celular', $opc_dadospaciente)) ? 'selected' : ''; ?>>Telefone 2</option>
                                    <option value="cpf" <?= (in_array('cpf', $opc_dadospaciente)) ? 'selected' : ''; ?>>CPF</option>
                                    <option value="cpf_mae" <?= (in_array('cpf_mae', $opc_dadospaciente)) ? 'selected' : ''; ?>>CPF Mãe</option>
                                    <option value="cpf_pai" <?= (in_array('cpf_pai', $opc_dadospaciente)) ? 'selected' : ''; ?>>CPF Pai</option>

                                </select>
                            </dd>
                        </div>

                        <div><br><br>
                            <dt>
                                <label title="Definir as abas que o médico pode ver na Nova Tela de atendimento do médico">Abas Tela de Atendimento (Nova tela de Atendimento)</label>
                            </dt>
                            <dd>
                                <?
                                if (@$obj->_abas_atendimento != '') {
                                    $abas_atendimento = json_decode(@$obj->_abas_atendimento);
                                } else {
                                    $abas_atendimento = array();
                                }
                                ?>
                                <select name="abas_atendimento[]" style="width: 47%;" class="chosen-select" data-placeholder="Selecione os campos..." multiple>
                                    <option value="receitas" <?= (in_array('receitas', $abas_atendimento)) ? 'selected' : ''; ?>>Aba Receituário</option>
                                    <option value="s_exames" <?= (in_array('s_exames', $abas_atendimento)) ? 'selected' : ''; ?>>Aba S. Exames</option>
                                    <option value="anotacao_privada" <?= (in_array('anotacao_privada', $abas_atendimento)) ? 'selected' : ''; ?>>Aba Anotação Privada</option>
                                    <option value="solicitacao_sadt" <?= (in_array('solicitacao_sadt', $abas_atendimento)) ? 'selected' : ''; ?>>Aba Solicitação SATD</option>
                                    <option value="tomadas" <?= (in_array('tomadas', $abas_atendimento)) ? 'selected' : ''; ?>>Aba Tomadas</option>
                                    <option value="visualizar" <?= (in_array('visualizar', $abas_atendimento)) ? 'selected' : ''; ?>>Aba Visualizar (Imprimir)</option>
                                    <option value="historico" <?= (in_array('historico', $abas_atendimento)) ? 'selected' : ''; ?>>Aba Histórico</option>
                                    <option value="dados" <?= (in_array('dados', $abas_atendimento)) ? 'selected' : ''; ?>>Aba Dados</option>
                                    <option value="arquivos" <?= (in_array('arquivos', $abas_atendimento)) ? 'selected' : ''; ?>>Aba Arquivos</option>
                                    <option value="evolucao" <?= (in_array('evolucao', $abas_atendimento)) ? 'selected' : ''; ?>>Aba 1a Consulta</option>
                                    <option value="receituario_simples" <?= (in_array('receituario_simples', $abas_atendimento)) ? 'selected' : ''; ?>>Aba Receituario Simples</option>
                                    <option value="receituario_especial" <?= (in_array('receituario_especial', $abas_atendimento)) ? 'selected' : ''; ?>>Aba Receituario Especial</option>
                                    <option value="atestado" <?= (in_array('atestado', $abas_atendimento)) ? 'selected' : ''; ?>>Aba Atestado</option>
                                    <option value="relatorio" <?= (in_array('relatorio', $abas_atendimento)) ? 'selected' : ''; ?>>Aba Relatório</option>
                                    <option value="historico2" <?= (in_array('historico2', $abas_atendimento)) ? 'selected' : ''; ?>>Aba Histórico 2</option>
                                    <option value="condutas" <?= (in_array('condutas', $abas_atendimento)) ? 'selected' : ''; ?>>Aba Condutas</option>

                                </select>
                            </dd>
                        </div>
                        <br>
                        <br><br><br>


                        <fieldset>
                            <fieldset>
                                <legend><b><u>Configurações Gerais</u></b></legend><br>
                                <table align="center" style="width:100%">
                                    <tr>
                                        <td> <input type="checkbox" id="imagem" name="imagem" <? if (@$obj->_imagem == 't') echo "checked"; ?>/></td><td><label title="Habilitar Modulo de Imagem.">Imagem</label></td>
                                        <td> <input type="checkbox" id="consulta" name="consulta" <? if (@$obj->_consulta == 't') echo "checked"; ?>/></td><td><label title="Habilitar Modulo de Consulta.">Consulta</label></td>
                                        <td> <input type="checkbox" id="especialidade" name="especialidade" <? if (@$obj->_especialidade == 't') echo "checked"; ?>/></td><td><label title="Habilitar Modulo de Especialidade.">Especialidade</label></td>
                                        <td> <input type="checkbox" id="odontologia" name="odontologia" <? if (@$obj->_odontologia == 't') echo "checked"; ?>/></td><td><label title="Habilitar Modulo de Odontologia.">Odontologia</label></td>
                                        <td> <input type="checkbox" id="oftamologia" name="oftamologia" <? if (@$obj->_oftamologia == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, oftamologia irá aparecer na consulta.">Oftalmologia</label></td>
                                        <td> <input type="checkbox" id="laboratorio" name="laboratorio" <? if (@$obj->_laboratorio == 't') echo "checked"; ?>/></td><td><label title="Habilitar Laboratorio.">Integração Laboratório</label></td>
                                    </tr>
                                    <tr>                                        
                                        <td> <input type="checkbox" id="geral" name="geral" <? if (@$obj->_geral == 't') echo "checked"; ?>/></td><td><label title="Habilitar Geral.">Geral</label></td>
                                        <td><input type="checkbox" id="faturamento" name="faturamento" <? if (@$obj->_faturamento == 't') echo "checked"; ?>/></td><td><label title="Habilitar Faturamento.">Faturamento</label></td>
                                        <td><input type="checkbox" id="estoque" name="estoque" <? if (@$obj->_estoque == 't') echo "checked"; ?>/></td><td><label title="Habilitar Estoque.">Estoque</label></td>
                                        <td><input type="checkbox" id="financeiro" name="financeiro" <? if (@$obj->_financeiro == 't') echo "checked"; ?>/></td><td><label title="Habilitar Financeiro.">Financeiro</label></td>
                                        <td><input type="checkbox" id="marketing" name="marketing" <? if (@$obj->_marketing == 't') echo "checked"; ?>/></td><td><label title="Habilitar Marketing.">Marketing</label></td>
                                        <td><input type="checkbox" id="internacao" name="internacao" <? if (@$obj->_internacao == 't') echo "checked"; ?>/></td><td><label title="Habilitar Internação.">Internação</label></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" id="internacao" name="farmacia" <? if (@$obj->_farmacia == 't') echo "checked"; ?>/></td><td><label title="Habilitar Internação.">Farmácia</label></td>
                                        <td><input type="checkbox" id="centro_cirurgico" name="centro_cirurgico" <? if (@$obj->_centro_cirurgico == 't') echo "checked"; ?>/></td><td><label title="Habilitar Centro Cirurgico.">Centro Cirurgico</label></td>
                                        <td><input type="checkbox" id="ponto" name="ponto" <? if (@$obj->_ponto == 't') echo "checked"; ?>/></td><td><label title="Habilitar Ponto.">Ponto</label></td>                                        
                                        <td><input type="checkbox" id="botao_ativar_sala" name="botao_ativar_sala" <? if (@$obj->_botao_ativar_sala == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag o botão de reativar sala aparece.">Botão de reativar sala</label></td>
                                        <td><input type="checkbox" id="enfermagem" name="enfermagem" <? if (@$obj->_enfermagem == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag o perfil de Técnico passa a poder dar entrada e solicitar.">Enfermagem</label></td>
                                        <td><input type="checkbox" id="integracaosollis" name="integracaosollis" <? if (@$obj->_integracaosollis == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag o sistema fará integração com a API da Sollis para o receituário médico.">Sollis</label></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" id="medicinadotrabalho" name="medicinadotrabalho" <? if (@$obj->_medicinadotrabalho == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag todas as funções relacionadas com Medicina do Trabalho estarão disponíveis no sistema.">Medicina do Trabalho</label></td> 
                                        <td><input type="checkbox" id="laboratorio_sc" name="laboratorio_sc" <? if (@$obj->_laboratorio_sc == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag não será necessário selecionar um médico na hora de adicionar um novo exame.">Laboratório</label></td> 
                                        <td><input type="checkbox" id="guia_procedimento" name="guia_procedimento" <? if (@$obj->_guia_procedimento == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, será criada uma guia para cada procedimento no sistema.">Guia por procedimento</label></td> 
                                        <td><input type="checkbox" id="guia_procedimento" name="hora_agendamento" <? if (@$obj->_hora_agendamento == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, será possivel ter  Opção de Horário de Agendamento"> Opção de Horário de Agendamento</label></td> 
                                        <td><input type="checkbox" id="agenda_modificada" name="agenda_modificada" <? if (@$obj->_agenda_modificada == 't') echo "checked"; ?>/></td><td><label title="">Relatorio Agenda Modificado</label></td> 
                                        <td><input type="checkbox" id="cirugico_manual" name="cirugico_manual" <? if (@$obj->_cirugico_manual == 't') echo "checked"; ?>/></td><td><label title="">Centro Cirúgico Manual
                                            </label></td>  

                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" id="tecnico_acesso_acesso" name="tecnico_acesso_acesso" <? if (@$obj->_tecnico_acesso_acesso == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o Técnico podera ver a lista de pacientes e as informações dos pacientes">Técnico acesso</label></td>
                                        <td><input type="checkbox" id="tabela_bpa" name="tabela_bpa" <? if (@$obj->_tabela_bpa == 't') echo "checked"; ?>/></td><td><label title="">Tabela BPA</label></td>
                                        <td><input type="checkbox" id="empresas_unicas" name="empresas_unicas" <? if (@$obj->_empresas_unicas == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, a empresa poderá ver informações das outras empresas.">Empresas Únicas</label></td>
                                        <td><input type="checkbox" id="precadastro" name="precadastro" <? if (@$obj->_precadastro == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, será possível utilizar o pre-cadastro para médicos na tela de login">Pré-Cadastro</label></td>
                                        <td><input type="checkbox" id="filaaparelho" name="filaaparelho" <? if (@$obj->_filaaparelho == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, será possivel utilizar as funções de aparelho no sistema">Fila Aparelho</label></td>
                                        <td><input type="checkbox" id="setores" name="setores" <? if (@$obj->_setores == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, será possivel utilizar a função de setores no sistema">Setores</label></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" id="certificado_digital" name="certificado_digital" <? if (@$obj->_certificado_digital == 't') echo "checked"; ?>/></td><td><label title="">Certificado Digital API</label></td>
                                        <td><input type="checkbox" id="certificado_digital_manual" name="certificado_digital_manual" <? if (@$obj->_certificado_digital_manual == 't') echo "checked"; ?>/></td><td><label title="">Certificado Manual STG</label></td>
                                        <td><input type="checkbox" id="dashboard_administrativo" name="dashboard_administrativo" <? if (@$obj->_dashboard_administrativo == 't') echo "checked"; ?>/></td><td><label title="">Dashboard Administrativo</label></td>
                                        <td><input type="checkbox" id="integrar_google" name="integrar_google" <? if (@$obj->_integrar_google == 't') echo "checked"; ?>/></td><td><label title="">Integrar com Google</label></td>
                                         
                                    </tr>
                                    
                                </table>
                            </fieldset>
                            <br><br><br>
                            <fieldset>
                                <fieldset>
                                    <legend><b><u>Configurações de Impressões</u></b></legend><br>
                                    <table align="center" style="width:100%">
                                        <tr>
                                            <td><input type="checkbox" id="cabecalho_config" name="cabecalho_config" <? if (@$obj->_cabecalho_config == 't') echo "checked"; ?>/></td><td><label title="Impressao .">Cabeçalho Configurável</label></td>
                                            <td><input type="checkbox" id="rodape_config" name="rodape_config" <? if (@$obj->_rodape_config == 't') echo "checked"; ?>/></td><td><label title="Impressao .">Rodapé Configurável</label></td>
                                            <td><input type="checkbox" id="laudo_config" name="laudo_config" <? if (@$obj->_laudo_config == 't') echo "checked"; ?>/></td><td><label title="Impressao .">Laudo Configurável</label></td>
                                            <td><input type="checkbox" id="recibo_config" name="recibo_config" <? if (@$obj->_recibo_config == 't') echo "checked"; ?>/></td><td><label title="Impressao .">Recibo Configurável</label></td>
                                            <td><input type="checkbox" id="ficha_config" name="ficha_config" <? if (@$obj->_ficha_config == 't') echo "checked"; ?>/></td><td><label title="Impressao .">Receita/Rotinas/Solic. Exames Configurável</label></td>                                        
                                            <td><input type="checkbox" id="declaracao_config" name="declaracao_config" <? if (@$obj->_declaracao_config == 't') echo "checked"; ?>/></td><td><label title="Impressao .">Declaração Configurável</label></td>   

                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="atestado_config" name="atestado_config" <? if (@$obj->_atestado_config == 't') echo "checked"; ?>/></td><td><label title="Impressao .">Atestado Configurável</label></td>
                                            <td><input type="checkbox" id="orcamento_config" name="orcamento_config" <? if (@$obj->_orcamento_config == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o orçamento será configurável.">Orçamento Configurável:</label></td>
                                            <td><input type="checkbox" id="impressao_cimetra" name="impressao_cimetra" <? if (@$obj->_impressao_cimetra == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção, irá aparecer a opção de impressão para papel carta.">Impressão Papel Carta - Cimetra</label></td>
                                            <td><input type="checkbox" id="nao_sobrepor_laudo" name="nao_sobrepor_laudo" <? if (@$obj->_nao_sobrepor_laudo == 't') echo "checked"; ?>/></td><td><label title="Impressao ."> Não Sobrepor Laudo</label></td>  
                                            <td><input type="checkbox" id="alterar_data_emissao" name="alterar_data_emissao" <? if (@$obj->_alterar_data_emissao == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, ao alterar a Data de Atendimento, a Data de Emissão será a nova Data de Atendimento."> Alterar Data de Emissão</label></td>
                                            <td><input type="checkbox" id="a4_receituario_especial" name="a4_receituario_especial" <? if (@$obj->_a4_receituario_especial == 't') echo "checked"; ?>/></td><td><label title="Ao ativar essa flag, o receituário especial será impresso em uma folha A4(sem repetir)">Impressão A4 receituário especial</label></td>     
                                        </tr>
                                    </table>
                                </fieldset> <br><br><br>
                                <fieldset>
                                    <legend><b><u>Configurações Sistema Paciente</u></b></legend><br>
                                    <table align="center" style="width:100%">
                                        <tr>
                                            <td><input type="checkbox" id="botao_laudo_paciente" name="botao_laudo_paciente" <? if (@$obj->_botao_laudo_paciente == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção o botão de laudo irá aparecer no sistema de pacientes">Botão Laudo no Sistema Paciente</label></td>
                                            <td><input type="checkbox" id="botao_arquivos_paciente" name="botao_arquivos_paciente" <? if (@$obj->_botao_arquivos_paciente == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção o botão de arquivos irá aparecer no sistema de pacientes">Botão Arquivos no Sistema Paciente</label></td>
                                            <td><input type="checkbox" id="botao_imagem_paciente" name="botao_imagem_paciente" <? if (@$obj->_botao_imagem_paciente == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção o botão de Imprimir Imagens aparece no sistema de pacientes">Botão Imagem no Sistema Paciente</label></td>
                                            <td><input type="checkbox" id="login_paciente" name="login_paciente" <? if (@$obj->_login_paciente == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o login será obrigatório no Sistema de Pacientes.">Login no Sistema de Paciente</label></td>                                                                                
                                        </tr>                                    
                                    </table>
                                </fieldset>
                                <br><br><br>
                                <fieldset>
                                    <legend><b><u>Configurações Padrão MED</u></b></legend><br>
                                    <table align="center" style="width:100%">
                                        <tr>
                                            <td><input type="checkbox" id="calendario_layout" name="calendario_layout" <? if (@$obj->_calendario_layout == 't') echo "checked"; ?>/></td><td><label title="Habilitar o layout de calendario criado para a MED (apenas na multifunção geral).">Calendario (Layout Personalizado)</label></td>                                                                             
                                            <td><input type="checkbox" id="relatorios_clinica_med" name="relatorios_clinica_med" <? if (@$obj->_relatorios_clinica_med == 't') echo "checked"; ?>/></td><td><label title="Relatórios utilizados na Clínica MED">Relatórios Clínica MED</label></td>                                                                             
                                            <td><input type="checkbox" id="retirar_preco_procedimento" name="retirar_preco_procedimento" <? if (@$obj->_retirar_preco_procedimento == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção, a tela Preço Procedimento irá sumir e irá ficar somente a tela do orçamento.">Retirar Preço Procedimento</label></td>                                                                             
                                            <td><input type="checkbox" id="ajuste_pagamento_procedimento" name="ajuste_pagamento_procedimento" <? if (@$obj->_ajuste_pagamento_procedimento == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção irá aparecer a opção de informar o ajuste no cadastro de pagamento (procedimento). Além disso, a tela de faturamento irá mudar">Ajuste no Pagamento (Procedimento)</label></td>                                                                             

                                        </tr>                                    
                                        <tr>

                                            <td><input type="checkbox" id="apenas_procedimentos_multiplos" name="apenas_procedimentos_multiplos" <? if (@$obj->_apenas_procedimentos_multiplos == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção, o sistema so irá disponibilizar o botão de procedimentos multiplos.">Deixar apenas procedimentos múltiplos</label></td>                                                                             
                                            <td><input type="checkbox" id="cadastrar_painel_sala" name="cadastrar_painel_sala" <? if (@$obj->_cadastrar_painel_sala == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção, ao criar uma sala, o sistema irá criar vincular 10 paineis automaticamente.">Vincular paineis ao criar salas</label></td>                                                                             
                                            <td><input type="checkbox" id="retirar_flag_solicitante" name="retirar_flag_solicitante" <? if (@$obj->_retirar_flag_solicitante == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção, as flags de medico solicitante e ocupação no painel não irão aparecer no cadastro de profissionais">Retirar flag de solicitante</label></td>                                                                             
                                            <td><input type="checkbox" id="senha_finalizar_laudo" name="senha_finalizar_laudo" <? if (@$obj->_senha_finalizar_laudo == 't') echo "checked"; ?>/></td><td><label title="Ao ativar, o sistema irá solicitar a senha do medico responsavel para finalizar o laudo. Do contrário, não será necessário.">Solicitar Senha ao Finalizar Laudo</label></td>                                                                             

                                        </tr>                                    
                                        <tr>
                                            <td><input type="checkbox" id="valor_laboratorio" name="tecnica_promotor" <? if (@$obj->_tecnica_promotor == 't') echo "checked"; ?>/></td><td><label title="No momento de enviar da sala de espera, não mostrar a opção do promotor caso o usuário não possua perfil de Administrador">Promotor Sala de Espera</label></td>                                                                             
                                            <td><input type="checkbox" id="profissional_completo" name="profissional_completo" <? if (@$obj->_profissional_completo == 't') echo "checked"; ?>/></td><td><label title="O valor do laboratório é retirado antes do valor do médico no relatório de produção médica">Profissional Convênio Completo</label></td>                                                                             
                                            <td><input type="checkbox" id="caixa_personalizado" name="caixa_personalizado" <? if (@$obj->_caixa_personalizado == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o relatorio de caixa normal some e so fica disponivel o caixa personalizado. Além disso os relatorios de caixa cartão irão ficar com o layout do personalizado e o rel. previsão irá sumir.">Caixa personalizado</label></td>                                                                             
                                            <td><input type="checkbox" id="recomendacao_configuravel" name="recomendacao_configuravel" <? if (@$obj->_recomendacao_configuravel == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o manter indicação irá aparecer nas configurações.">Tornar a recomendação configuravel</label></td>                                                                             

                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="percentual_multiplo" name="percentual_multiplo" <? if (@$obj->_percentual_multiplo == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção, a tela de cadastrar percentual irá seguir o padrão dos procedimentos multiplos.">Percentual similar ao Proc. Multiplos</label></td>
                                            <td><input type="checkbox" id="procedimentos_multiempresa" name="procedimentos_multiempresa" <? if (@$obj->_procedimento_multiempresa == 't') echo "checked"; ?>/></td><td><label title="Procedimentos separados por empresa.">Proc. Multiempresa</label></td>
                                            <td><input type="checkbox" id="procedimento_excecao" name="procedimento_excecao" <? if (@$obj->_procedimento_excecao == 't') echo "checked"; ?>/></td><td><label title="Ao asssociar procedimentos aos médicos (CONFIGURAÇÕES - RECEPÇÃO - LISTAR PROFISSIONAIS - CONVÊNIO), estes serão tratados como exceção.">Cadastrar procedimentos como exceção.</label></td>
                                            <td><input type="checkbox" id="desabilitar_trava_retorno" name="desabilitar_trava_retorno" <? if (@$obj->_desabilitar_trava_retorno == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o sistema não irá mais barrar o lançamento de procedimentos do tipo Retorno.">Desabilitar trava no Retorno</label></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="desativar_personalizacao_impressao" name="desativar_personalizacao_impressao" <? if (@$obj->_desativar_personalizacao_impressao == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, as opçoes de impressao, tais como assinatura e carimbo, deixaram de aparecer na tela do médico.">Desativar personalização da impressao dos medicos</label></td>
                                            <td><input type="checkbox" id="orcamento_multiplo" name="orcamento_multiplo" <? if (@$obj->_orcamento_multiplo == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção os orçamentos do cadastro e recepção terão o padrão multiplo. Será possível adicionar mais de um procedimento ao mesmo tempo.">Orçamento Multiplo</label></td>
                                            <td><input type="checkbox" id="agenda_modelo2" name="agenda_modelo2" <? if (@$obj->_agenda_modelo2 == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção, a agenda será criada a partir do modelo pedido pela Clinica Med. Agora a agenda é diretamente associada ao médico e ao Tipo Agenda antes de ser de fato criada">Criação de Agenda Modelo 2</label></td>
                                            <td><input type="checkbox" id="relatorio_dupla" name="relatorio_dupla" <? if (@$obj->_relatorio_dupla == 't') echo "checked"; ?>/></td><td><label title="Ao ativar essa opção, o relatório de Dupla Assinatura aparecerá">Relatório Dupla Assinatura</label></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="aparecer_orcamento" name="aparecer_orcamento" <? if (@$obj->_aparecer_orcamento == 't') echo "checked"; ?>/></td><td><label title="Ao ativar essa opção, o link(Recepção-Rotinas-Preço Procedimento) será substuido por o link(Recepção-Rotinas-Orçamento)">Substituir "Preço Procedimento" por "Orçamento"</label></td>    
                                        </tr>
                                    </table>
                                </fieldset>
                                <br><br><br>
                                <fieldset>
                                    <legend><b><u>Configurações Recepção</u></b></legend><br>
                                    <table align="center" style="width:100%">
                                        <tr>
                                            <td><input type="checkbox" id="calendario" name="calendario" <? if (@$obj->_calendario == 't') echo "checked"; ?>/></td><td><label title="Habilitar Calendario.">Calendario</label></td>                                                                               
                                            <td><input type="checkbox" id="conjuge" name="conjuge" <? if (@$obj->_conjuge == 't') echo "checked"; ?>/></td><td><label title="Nome do cônjuge e data de nascimento do mesmo">Nome do cônjuge</label></td>                                                                               
                                            <td><input type="checkbox" id="gerente_cancelar" name="gerente_cancelar_sala" <? if (@$obj->_gerente_cancelar_sala == 't') echo "checked"; ?>/></td><td><label title="Ao ativar essa flag o gerente de recepção consegue cancelar na sala de espera independente de outra flag">Gerente Cancelar Sala de Espera</label></td>                                                                               
                                            <td><input type="checkbox" id="gerente_recepcao_top_saude" name="gerente_recepcao_top_saude" <? if (@$obj->_gerente_recepcao_top_saude == 't') echo "checked"; ?>/></td><td><label title="Ao ativar essa flag o gerente de recepção tem acesso a alguns relatórios do Financeiro a pedido da Top Saude">Gerente de Recepção Relatórios Financeiro (Top Saúde)</label></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="gerente_cancelar" name="autorizar_sala_espera" <? if (@$obj->_autorizar_sala_espera == 't') echo "checked"; ?>/></td><td><label title="Ao ativar essa flag o paciente passa para sala de espera ao ser autorizado">Sala de Espera</label></td>
                                            <td><input type="checkbox" id="gerente_cancelar" name="gerente_cancelar" <? if (@$obj->_gerente_cancelar == 't') echo "checked"; ?>/></td><td><label title="Ao ativar essa flag o Gerente de Recepção e perfil Recepção conseguem cancelar na recepção">Recepção Cancelar (Gerente e Recepção)</label></td>
                                            <td><input type="checkbox" id="chamar_consulta" name="chamar_consulta" <? if (@$obj->_chamar_consulta == 't') echo "checked"; ?>/></td><td><label title="Chamar Consulta.">Chamar Consulta na sala de espera</label></td>
                                            <td><input type="checkbox" id="procedimento_excecao" name="ordem_chegada" <? if (@$obj->_ordem_chegada == 't') echo "checked"; ?>/></td><td><label title="Ordem de chegada na sala de espera">Ordem Chegada Recepção</label></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="cancelar_sala_espera" name="cancelar_sala_espera" <? if (@$obj->_cancelar_sala_espera == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o botão de cancelar na sala de espera irá aparecer.">Cancelar Sala de Espera</label></td>
                                            <td><input type="checkbox" id="administrador_cancelar" name="administrador_cancelar" <? if (@$obj->_administrador_cancelar == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o administrador TOTAL pode cancelar na sala de espera.">Administrador cancelar na sala de espera</label></td>
                                            <td><input type="checkbox" id="gerente_contasapagar" name="gerente_contasapagar" <? if (@$obj->_gerente_contasapagar == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, a gerente de recepção consegue lançar despesas no contas a pagar">Gerente de Recepção Contas a Pagar</label></td>
                                            <td><input type="checkbox" id="orcamento_recepcao" name="orcamento_recepcao" <? if (@$obj->_orcamento_recepcao == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o preço procedimento aparece para a Recepção">Perfis da recepção Preço Procedimento</label></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="gerente_recepcao_financeiro" name="gerente_relatorio_financeiro" <? if (@$obj->_gerente_relatorio_financeiro == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção o Gerente de Recepção irá ter acesso á alguns relatórios do financeiro e do faturamento">Gerente de Recepção Rel. Financeiro</label></td>
                                            <td><input type="checkbox" id="relatorios_recepcao" name="relatorios_recepcao" <? if (@$obj->_relatorios_recepcao == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, os relatórios aparecem para recepção e recepção agendamento">Relatórios para recepção</label></td>
                                            <td><input type="checkbox" id="tecnica_enviar" name="tecnica_enviar" <? if (@$obj->_tecnica_enviar == 't') echo "checked"; ?>/></td><td><label title="A técnica irá poder chamar na tela de atendimento">Técnica Chamar</label></td>
                                            <td><input type="checkbox" id="botao_ficha_convenio" name="botao_ficha_convenio" <? if (@$obj->_botao_ficha_convenio == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção o botão de Ficha-Convenio irá aparecer na tabela de consultas marcadas">Botão Ficha-Convenio</label></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="recomendacao_obrigatorio" name="recomendacao_obrigatorio" <? if (@$obj->_recomendacao_obrigatorio == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, ao adcionar ou autorizar um(a) novo(a) exame/consulta o campo recomendação é obrigatorio.">Tornar a recomendação Obrigatorio.</label></td>
                                            <td><input type="checkbox" id="selecionar_retorno" name="selecionar_retorno" <? if (@$obj->_selecionar_retorno == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, quando o sistema encontrar um procedimento com retorno no sistema, ele irá perguntar se a pessoa deseja associar, se sim, ele irá automáticamente selecionar o procedimento
                                                                                                                                                                                                       Caso isso não esteja ativado, o sistema vai tirar a seleção do procedimento.">Selecionar Retorno Automaticamente</label></td>
                                            <td><input type="checkbox" id="repetir_horarios_agenda" name="repetir_horarios_agenda" <? if (@$obj->_repetir_horarios_agenda == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, no momento de criar a agenda, aparece uma opçao para o usuario informar quantas vezes ele ira querer repetir os horarios.">Permitir criação de horarios repetidos na agenda.</label></td>

                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="retirar_botao_ficha" name="retirar_botao_ficha" <? if (@$obj->_retirar_botao_ficha == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, os botões para imprimir a ficha não serão mostrados.">Retirar botões de Ficha</label></td>
                                            <td><input type="checkbox" id="subgrupo_procedimento" name="subgrupo_procedimento" <? if (@$obj->_subgrupo_procedimento == 't') echo "checked"; ?>/></td><td><label title="Ao ativar, irá aparecer uma tela para cadastrar subgrupos no sistema (associados aos grupos). Isso pode ser usado no relátorio de conferência.">Subgrupo de Procedimento</label></td>
                                            <td><input type="checkbox" id="encaminhamento_email" name="encaminhamento_email" <? if (@$obj->_encaminhamento_email == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, quando encaminhar um paciente será disparado um email para o médico que recebe (Padrão Citycor).">Encaminhamento Email</label></td>
                                            <td><input type="checkbox" id="relatorio_ordem" name="relatorio_ordem" <? if (@$obj->_relatorio_ordem == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o relatório de ordem de atendimento aparece">Relatório Ordem de Atendimento</label></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="manter_indicacao" name="manter_indicacao" <? if (@$obj->_manter_indicacao == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o Manter Indicação vai ser ativado na Recepção">Manter Indicação</label></td>
                                            <td><input type="checkbox" id="fila_impressao" name="fila_impressao" <? if (@$obj->_fila_impressao == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o Fila Impressão vai ser ativado na Recepção">Fila de Impressão</label></td>
                                            <td><input type="checkbox" id="medico_solicitante" name="medico_solicitante" <? if (@$obj->_medico_solicitante == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, a opção de Editar Médico Solicitante vai ser ativada na Recepção">Editar Médico Solicitante</label></td>
                                            <td><input type="checkbox" id="uso_salas" name="uso_salas" <? if (@$obj->_uso_salas == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, a opção de Uso de Salas vai ser ativada na Recepção">Uso de Salas</label></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="relatorio_operadora" name="relatorio_operadora" <? if (@$obj->_relatorio_operadora == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, a opção de Relatório Operadora vai ser ativada na Recepção">Relatório Operadora</label></td>
                                            <td><input type="checkbox" id="relatorio_demandagrupo" name="relatorio_demandagrupo" <? if (@$obj->_relatorio_demandagrupo == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, a opção de Relatório de Demanda Grupo vai ser ativada na Recepção">Relatório de Demanda Grupo</label></td>
                                            <td><input type="checkbox" id="relatorio_rm" name="relatorio_rm" <? if (@$obj->_relatorio_rm == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, a opção de Relatório RM vai ser ativada na Recepção">Relatório RM</label></td>
                                            <td><input type="checkbox" id="relatorio_caixa" name="relatorio_caixa" <? if (@$obj->_relatorio_caixa == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, a opção de Relatório Caixa vai ser ativada na Recepção">Relatório Caixa</label></td>

                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="reservar_escolher_proc" name="reservar_escolher_proc" <? if (@$obj->_reservar_escolher_proc == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, você deve escolher o procedimento ao reservar um horário.">Escolher Procedimento Ao Reservar</label></td>
                                            <td><input type="checkbox" id="ocupacao_mae" name="ocupacao_mae" <? if (@$obj->_ocupacao_mae == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, aparecerá na página de cadastro o campo de ocupação da mãe.">Ocupação Mãe</label></td>
                                            <td><input type="checkbox" id="ocupacao_pai" name="ocupacao_pai" <? if (@$obj->_ocupacao_pai == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, aparecerá na página de cadastro o campo de ocupação do pai.">Ocupação Pai</label></td>
                                            <td><input type="checkbox" id="limitar_acesso" name="limitar_acesso" <? if (@$obj->_limitar_acesso == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o Perfil de Recepção não terá acesso as configurações.">Limitar Acesso Recepção</label></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="perfil_marketing_p" name="perfil_marketing_p" <? if (@$obj->_perfil_marketing_p == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, você dará ao perfil de Marketing acesso a todos os itens da Recepção e ao Solicitar Material (no estoque).">Perfil Marketing_P</label></td>
                                            <td><input type="checkbox" id="filtrar_agenda" name="filtrar_agenda" <? if (@$obj->_filtrar_agenda == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, você terá os filtros de status, situação e procedimento aparecendo na Multifuncao Geral.">Filtrar Agenda</label></td>
                                            <td><input type="checkbox" id="liberar_perfil" name="liberar_perfil" <? if (@$obj->_liberar_perfil == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, os perfis de Técnico e Técnico Recepção poderão alterar o médico responsável pelo atendimento.">Liberar Perfil</label></td>
                                            <td><input type="checkbox" id="filtro_exame_cadastro" name="filtro_exame_cadastro" <? if (@$obj->_filtro_exame_cadastro == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, você poderá filtrar os pacientes no cadastro pelo número de guia.">Filtrar por exame (guia) no cadastro de pacientes</label></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="convenio_paciente" name="convenio_paciente" <? if (@$obj->_convenio_paciente == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, os convênios liberados para autorização do paciente serão apenas os particulares e o que está no cadastro do mesmo.">Bloquear convênio pelo cadastro do paciente (Com particulares)</label></td>
                                            <td><input type="checkbox" id="travar_convenio_paciente" name="travar_convenio_paciente" <? if (@$obj->_travar_convenio_paciente == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o convênio liberado para autorização do paciente será apenas o que está no cadastro do mesmo.">Bloquear convênio pelo cadastro do paciente (Sem particulares)</label></td>
                                            <td><input type="checkbox" id="carteira_administrador" name="carteira_administrador" <? if (@$obj->_carteira_administrador == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o convênio no cadastro do paciente só será alterado pelo administrador">Apenas administrador alterar convênio do paciente</label></td>
                                            <td><input type="checkbox" id="prontuario_antigo" name="prontuario_antigo" <? if (@$obj->_prontuario_antigo == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, Aparecerá o campo: Prontuário Antigo, no Cadastro do Paciente ">Flag Prontuário Antigo</label></td>
                                           
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" id="convenio_padrao" name="convenio_padrao" <? if (@$obj->_convenio_padrao == 't') echo "checked"; ?>/>
                                            </td>
                                            <td>
                                                <label title="Ativando essa flag, ao cadastrar um paciente o sistema irá redirecionar para uma tela onde será escolhido um dos grupos do sistema e será lançado um procedimento 'padrão' associado previamente a esse grupo no Manter Convênio.
                                                       O convênio padrão desse procedimento será associado aqui mesmo no Manter Empresa">
                                                    Após cadastrar paciente ir para a tela de adicionar "Procedimento Padrão"
                                                </label>
                                            </td>
                                            <td><input type="checkbox" id="tecnico_recepcao_editar" name="tecnico_recepcao_editar" <? if (@$obj->_tecnico_recepcao_editar == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o técnico recepção poderá editar o cadastro do paciente">Técnico Recepção editar Paciente</label></td>
                                            <td><input type="checkbox" id="finalizar_atendimento_medico" name="finalizar_atendimento_medico" <? if (@$obj->_finalizar_atendimento_medico == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, ao finalizar no atendimento o exame será finalizado no atendimento do médico">Finalizar Atendimento/Finalizar Médico</label></td>
                                         <td><input type="checkbox" id="fidelidade_paciente_antigo" name="fidelidade_paciente_antigo" <? if (@$obj->_fidelidade_paciente_antigo == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o campo 'Prontuário Antigo' será utilizado para integração com o sistema de fidelidade">Prontuário Antigo na Integração (Fidelidade)</label></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" id="inadimplencia" name="inadimplencia" <? if (@$obj->_inadimplencia == 't') echo "checked"; ?>/>
                                            </td>
                                            <td>
                                                <label title="Ativando essa flag, será possível lançar inadimplências para os pacientes">Inadimplência</label>
                                            </td>

                                            <td>
                                                <input type="checkbox" id="agendahias" name="agendahias" <? if (@$obj->_agendahias == 't') echo "checked"; ?>/>
                                            </td>
                                            <td>
                                                <label title="Ativando essa flag, O Relatório Recepção Agenda é modificado.">Agenda HIAS</label>
                                            </td>
                                            <td>
                                                <input type="checkbox" id="desativarelatend" name="desativarelatend" <? if (@$obj->_desativarelatend == 't') echo "checked"; ?>/>
                                            </td>
                                            <td>
                                                <label title="Ativando essa flag, O Relatório ordem atendimento irá ficar desativado.">Desativar Rel. Ordem AtendImento</label>
                                            </td>
                                              <td><input type="checkbox" id="agenda_albert" name="agenda_albert" <? if (@$obj->_agenda_albert == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, será cadastro convênio e grupo no horário da agenda">Agenda Albert Sabin</label></td>
 
                                        </tr>
                                        <tr> 
                                          <td>
                                             <input type="checkbox" id="espera_intercalada" name="espera_intercalada" <? if (@$obj->_espera_intercalada == 't') echo "checked"; ?> />
                                          </td>
                                          <td>
                                             <label title="Ativando essa flag, os procedimentos da sala de espera que estão como prioridade e normal irá ficar intercalado">Sala de espera intercalada</label>
                                          </td>
                                          
                                          <td>
                                             <input type="checkbox" id="nguia_autorizacao" name="nguia_autorizacao" <? if (@$obj->_nguia_autorizacao == 't') echo "checked"; ?> />
                                          </td>
                                          <td>
                                             <label title="Ativando essa flag, o número da autorização irá ser replicado para o numero de guia do procedimento">Autorização Número de Guia</label>
                                          </td>
                                          <td>
                                             <input type="checkbox" id="origem_agendamento" name="origem_agendamento" <? if (@$obj->_origem_agendamento == 't') echo "checked"; ?> />
                                          </td>
                                          <td>
                                             <label title="Ativando essa flag, o campo Origem vai aparecer no agendamento dos pacientes">Origem no Agendamento</label>
                                          </td>
                                          <td>
                                             <input type="checkbox" id="manter_gastos" name="manter_gastos" <? if (@$obj->_manter_gastos == 't') echo "checked"; ?> />
                                          </td>
                                          <td>
                                             <label title="Ativando essa flag, o campo Manter Gastos vai aparecer na recepção">Manter Gastos</label>
                                          </td>
                                          
                                        </tr>
                                        <tr>
                                          <td>
                                             <input type="checkbox" id="filtrar_agenda_2" name="filtrar_agenda_2" <? if (@$obj->_filtrar_agenda_2 == 't') echo "checked"; ?> />
                                          </td>
                                          <td>
                                             <label title="">Filtrar Agenda 2</label>
                                          </td> 

                                          <td><input type="checkbox" id="informar_faltas" name="informar_faltas" <? if (@$obj->_informar_faltas == 't') echo "checked"; ?> /></td><td><label>Informar Pacientes Faltou</label></td>

                                          <td><input type="checkbox" id="data_hora_sala_espera" name="data_hora_sala_espera" <? if (@$obj->_data_hora_sala_espera == 't') echo "checked"; ?> /></td><td><label>Sala de Espera com Data e Hora de autorização</label></td>

                                          <td><input type="checkbox" id="email_obrigatorio" name="email_obrigatorio" <? if (@$obj->_email_obrigatorio == 't') echo "checked"; ?> /></td><td><label>Email Obrigatorio ao Agendar</label></td>
                                        </tr>

                                        <tr>
                                            <td><input type="checkbox" id="nota_fiscal_sp" name="nota_fiscal_sp" <? if (@$obj->_nota_fiscal_sp == 't') echo "checked"; ?> /></td><td><label>Nota Fiscal Prefeitura de Sp</label></td>
                                            <td><input type="checkbox" id="status_faltou_manual" name="status_faltou_manual" <? if (@$obj->_status_faltou_manual == 't') echo "checked"; ?> /></td><td><label>Status Faltou Manualmente</label></td>
                                        </tr>

                                    </table>
                                </fieldset>
                                <br><br><br>
                                <fieldset>
                                    <legend><b><u>Configurações do Financeiro</u></b></legend><br>
                                    <table align="center" style="width:100%">
                                        <tr>
                                            <td><input type="checkbox" id="calendario" name="botao_faturar_proc" <? if (@$obj->_botao_faturar_proc == 't') echo "checked"; ?>/></td><td><label title="Aparecer o botão de faturar procedimento no cadastro.">Botão Faturar Procedimentos</label></td>                                                                                
                                            <td><input type="checkbox" id="calendario" name="botao_faturar_guia" <? if (@$obj->_botao_faturar_guia == 't') echo "checked"; ?>/></td><td><label title="Aparecer  o botão de faturar guia no cadastro.">Botão Faturar Guia</label></td>                                                                                
                                            <td><input type="checkbox" id="producao_medica_saida" name="producao_medica_saida" <? if (@$obj->_producao_medica_saida == 't') echo "checked"; ?>/></td><td><label title="Ao fechar a produção médica, os valores ja irão cair como saida no Financeiro.">Produção Médica ir direto para Saida</label></td>                                                                                
                                            <td><input type="checkbox" id="financeiro_cadastro" name="financeiro_cadastro" <? if (@$obj->_financeiro_cadastro == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o financeiro tem acesso ao Fila Caixa e ao cadastro de pacientes">Financeiro Cadastro Paciente (Faturar)</label></td>                                                                                

                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="fila_caixa" name="fila_caixa" <? if (@$obj->_caixa == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, a fila de caixa vai ser ativada.">Fila de Caixa</label></td>
                                            <td><input type="checkbox" id="excluir_transferencia" name="excluir_transferencia" <? if (@$obj->_excluir_transferencia == 't') echo "checked"; ?>/></td><td><label title="Excluir Transferência">Excluir Transferência (Financeiro)</label></td>
                                            <td><input type="checkbox" id="associa_credito_procedimento" name="associa_credito_procedimento" <? if (@$obj->_associa_credito_procedimento == 't') echo "checked"; ?>/></td><td><label title="Desativando essa flag, na tela de crédito o usuário irá informar apenas o valor do crédito (sem travar o crédito ao valor de procedimentos).">Crédito associado a procedimentos</label></td>
                                            <td><input type="checkbox" id="credito" name="credito" <? if (@$obj->_credito == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o crédito irá aparecer no sistema.">Aparecer Crédito</label></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="promotor_medico" name="promotor_medico" <? if (@$obj->_promotor_medico == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o valor do promotor vai ser descontado do médico.">Tirar Valor Promotor do médico</label></td>
                                            <td><input type="checkbox" id="valor_recibo_guia" name="valor_recibo_guia" <? if (@$obj->_valor_recibo_guia == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o valor do recibo sera o da Guia.">Valor do Recibo é o da guia</label></td>
                                            <td><input type="checkbox" id="odontologia_valor_alterar" name="odontologia_valor_alterar" <? if (@$obj->_odontologia_valor_alterar == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, valor da odontologia poderá ser alterado ao lançar.">Valor da Odontologia Alterável</label></td>
                                            <td><input type="checkbox" id="valor_autorizar" name="valor_autorizar" <? if (@$obj->_valor_autorizar == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o valor aparece ao autorizar procedimentos.">Valor aparece ao autorizar procedimentos</label></td>
                                            
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="valor_laboratorio" name="valor_laboratorio" <? if (@$obj->_valor_laboratorio == 't') echo "checked"; ?>/></td><td><label title="O valor do laboratório é retirado antes do valor do médico no relatório de produção médica">Valor do Laboratório (Produção médica)</label></td>
                                            <td><input type="checkbox" id="valor_convenio_nao" name="valor_convenio_nao" <? if (@$obj->_valor_convenio_nao == 't') echo "checked"; ?>/></td><td><label title="No lançamento de procedimentos não aparece o valor de procedimentos não particulares.">Valor Convenio não aparecer</label></td>
                                            <td><input type="checkbox" id="desativar_taxa_administracao" name="desativar_taxa_administracao" <? if (@$obj->_desativar_taxa_administracao == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção, irá sumir a taxa de administração.">Desativar Taxa Administração</label></td>
                                            <td><input type="checkbox" id="orcamento_cadastro" name="orcamento_cadastro" <? if (@$obj->_orcamento_cadastro == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção irá necessitar de cadastro e logicamente, desativando não irá necessitar">Orçamento com cadastro</label></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="producao_alternativo" name="producao_alternativo" <? if (@$obj->_producao_alternativo == 't') echo "checked"; ?>/> </td><td><label title="Ativando essa opção, o relatório de produção terá o visual alternativo (Ronaldo).">Relatório Produção M. Alternativo</label></td>
                                            <td><input type="checkbox" id="data_contaspagar" name="data_contaspagar" <? if (@$obj->_data_contaspagar == 't') echo "checked"; ?>/></td><td><label title="Data manual na produção médica .">Data Manual Produção M.</label></td>
                                            <td><input type="checkbox" id="faturamento_novo" name="faturamento_novo" <? if (@$obj->_faturamento_novo == 't') echo "checked"; ?>/></td><td><label title="Novo modelo de faturamento (Particular) que permite a possibilidade de faturar parcialmente e fechar o caixa mais de uma vez">Faturamento Novo</label></td>
                                            <td><input type="checkbox" id="financ_4n" name="financ_4n" <? if (@$obj->_financ_4n == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o financeiro passará a ser composto por 4 níveis ao invés de dois.">Financeiro em 4 Níveis</label></td>    
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="grupo_convenio_proc" name="grupo_convenio_proc" <? if (@$obj->_grupo_convenio_proc == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, será possível pesquisar no Manter Procedimento Convênio utilizando grupo convênio">Filtro Grupo Convênio Manter Proc.</label></td>    
                                            <td><input type="checkbox" id="faturar_parcial" name="faturar_parcial" <? if (@$obj->_faturar_parcial == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, será permitido no sistema faturar um procedimento parcialmente (Apenas no modelo novo de faturamento)">Faturamento Parcial (Particular)</label></td>    
                                            <td><input type="checkbox" id="caixa_grupo" name="caixa_grupo" <? if (@$obj->_caixa_grupo == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o fechamento de caixa será separado por grupos de procedimentos (Apenas no modelo novo de faturamento)">Entrada de Caixa Por Grupo (Particular)</label></td>   
                                            <td><input type="checkbox" id="pagar_todos" name="pagar_todos" <? if (@$obj->_pagar_todos == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, aparece a opção para pagar todos Procedimentos Faturados">Pagar todos Procedimentos</label></td>   
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="relatorio_caixa_antigo" name="relatorio_caixa_antigo" <? if (@$obj->_relatorio_caixa_antigo == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção será possível ver o relatório de caixa antigo mesmo com o faturamento novo ativado">Relatório de Caixa Modelo Antigo</label></td>
                                            <td><input type="checkbox" id="data_pesquisa_financeiro" name="data_pesquisa_financeiro" <? if (@$obj->_data_pesquisa_financeiro == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção, a data na pesquisa do financeiro será automaticamente preenchida com o mês atual ao entrar pela primeira vez na tela ">Preenchimento Automático Data Financeiro</label></td>
                                            <td><input type="checkbox" id="id_linha_financeiro" name="id_linha_financeiro" <? if (@$obj->_id_linha_financeiro == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção, será possível pesquisar pelo ID de uma entrada de dados em todas as telas do financeiro">Pesquisa por ID no Financeiro</label></td>
                                            <td><input type="checkbox" id="valores_recepcao" name="valores_recepcao" <? if (@$obj->_valores_recepcao == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o valor aparece ao lançar procedimentos e também aparece o botão de recibo nas guias.">Valores Recepção</label></td>
                                        </tr>
                                        
                                        <tr>
                                         <td><input type="checkbox" id="producao_por_valor" name="producao_por_valor" <? if (@$obj->_producao_por_valor == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o valor que aparece na Produção médica é equivalente ao que a clinica recebeu pelo procedimento">Produção Médica por valor recebido</label></td>
                                         <td><input type="checkbox" id="redutor_valor_liquido" name="redutor_valor_liquido" <? if (@$obj->_redutor_valor_liquido == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, Retira do valor líquido e não valor Bruto">Redutor Valor líquido</label></td>
                                        </tr>

                                    </table>
                                </fieldset>
                                <br><br><br>
                                <fieldset>
                                    <legend><b><u>Configurações Atendimento Médico</u></b></legend><br>
                                    <table align="center" style="width:100%">
                                        <tr>
                                            <td><input type="checkbox" id="data_contaspagar" name="medico_laudodigitador" <? if (@$obj->_medico_laudodigitador == 't') echo "checked"; ?>/></td><td><label title="Médico pode pesquisar por outros médicos no Laudo Digitador .">Medico Laudo Digitador.</label></td>
                                            <td><input type="checkbox" id="modelo_laudo_medico" name="modelo_laudo_medico" <? if (@$obj->_modelo_laudo_medico == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção os modelos de Laudo no atendimento serão apenas os do médico que está atendendo">Modelo Laudo Por Médico</label></td>
                                            <td><input type="checkbox" id="relatorio_producao" name="relatorio_producao" <? if (@$obj->_relatorio_producao == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o relatório de produção nas telas de atendimento aparece">Relatório Produção (Nas telas do médico)</label></td>
                                            <td><input type="checkbox" id="carregar_modelo_receituario" name="carregar_modelo_receituario" <? if (@$obj->_carregar_modelo_receituario == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, quando só tiver um modelo de receituario cadastrado ele será carregado automaticamente.">Carregar Modelo Receiturario Automaticamente</label></td>                                                                                                                      
                                        </tr>                                    
                                        <tr>
                                            <td><input type="checkbox" id="profissional_externo" name="profissional_externo" <? if (@$obj->_profissional_externo == 't') echo "checked"; ?>/></td><td><label title="Aparece o campo de endereço externo no profissional para integração do STG com outro STG">Endereço Externo Médico.</label></td>
                                            <td><input type="checkbox" id="profissional_agendar" name="profissional_agendar" <? if (@$obj->_profissional_agendar == 't') echo "checked"; ?>/></td><td><label title="Ativando essa opção, será possível o agendamento por médicos na multifunção fisioterapia e geral">Agendamento Médico (Fisioterapia)</label></td>
                                            <td><input type="checkbox" id="ordenacao_situacao" name="ordenacao_situacao" <? if (@$obj->_ordenacao_situacao == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, o sistema irá ordenar por situação no atendimento médico">Ordem Chegada Médico</label></td>
                                            <td><input type="checkbox" id="atendimento_medico" name="atendimento_medico" <? if (@$obj->_atendimento_medico == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, a tela de atendimento médico vai ficar na 2º versão.">Atendimento Médico 2º Versão</label></td>
                                        </tr>                                    
                                        <tr>
                                            <td><input type="checkbox" id="medico_estoque" name="medico_estoque" <? if (@$obj->_medico_estoque == 't') echo "checked"; ?>/></td><td><label title="Libera o acesso do médico ao estoque (apenas manter solicitações)">Médico Estoque</label></td>
                                            <td><input type="checkbox" id="bloquear_botao" name="bloquear_botao" <? if (@$obj->_bloquear_botao == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag não irá aparecer o Botão de Imprimir nos atendimentos médicos para os seguintes perfis: RECEPÇÃO - RECEPÇÃO AGENDA - TÉCNICO - TÉCNICO RECEPÇÃO.">Bloquear Botão Impressão</label></td>
                                            <td><input type="checkbox" id="editar_historico_antigo" name="editar_historico_antigo" <? if (@$obj->_editar_historico_antigo == 't') echo "checked"; ?>/></td><td><label title="Permite a edição do histórico de atendimentos importados em 'Histórico de consultas antigas'">Editar Histórico de Consultas Antigas</label></td>
                                            <td><input type="checkbox" id="informacao_adicional" name="informacao_adicional" <? if (@$obj->_informacao_adicional == 't') echo "checked"; ?>/></td><td><label title="Mostra o campo de informação adicional na tela de atendimento de conusltas">Informação Adicional Consulta</label></td>
                                        </tr>

                                        <tr>
                                            <td><input type="checkbox" id="agenda_atend" name="agenda_atend" <? if (@$obj->_agenda_atend == 't') echo "checked"; ?>/></td><td><label title="Ao ativar essa flag, ao clicar em atender aparecerá um Botão Agendar Atend.">Agendar Atendimentos</label></td>
                                            <td><input type="checkbox" id="historico_antigo_administrador" name="historico_antigo_administrador" <? if (@$obj->_historico_antigo_administrador == 't') echo "checked"; ?>/></td><td><label title="">Histórico de Consultas Antigas Apenas Para Administrador</label></td>
                                            <td><input type="checkbox" id="imprimir_medico" name="imprimir_medico" <? if (@$obj->_imprimir_medico == 't') echo "checked"; ?>/></td><td><label title="">Botão de imprimir apenas para médicos e administradores</label></td>
                                            <td><input type="checkbox" id="retirar_ordem_medico" name="retirar_ordem_medico" <? if (@$obj->_retirar_ordem_medico == 't') echo "checked"; ?>/></td><td><label title="">Retirar colunas Ordem/Espera/Agenda/Autorização</label></td>
                                        </tr>  
                                        <tr>
                                            <td><input type="checkbox" id="laudo_sigiloso" name="laudo_sigiloso" <? if (@$obj->_laudo_sigiloso == 't') echo "checked"; ?>/></td><td><label title="O laudo só vai ser editado pelo nosso operador e aparecerá a opção de Adendo para o médico responsável">Laudo Sigiloso</label></td>
                                            <td><input type="checkbox" id="sem_margens_laudo" name="sem_margens_laudo" <? if (@$obj->_sem_margens_laudo == 't') echo "checked"; ?>/></td><td><label title="A impressão do laudo não terá bordas (Configurável e o modelo geral, as clinicas que tem modelo próprio não recebem essa flag)">Sem Margens na Impressão do Laudo/Anaminese</label></td>
                                            <td><input type="checkbox" id="gerente_cancelar_atendimento" name="gerente_cancelar_atendimento" <? if (@$obj->_gerente_cancelar_atendimento == 't') echo "checked"; ?>/></td><td><label title=" ">Gerente cancelar atendimento</label></td>
                                            <td><input type="checkbox" id="enviar_para_atendimento" name="enviar_para_atendimento" <? if (@$obj->_enviar_para_atendimento == 't') echo "checked"; ?>/></td><td><label title=" ">Enviar Todos para Atendimento</label></td>
                                        </tr>  
                                        <tr>
                                            <td><input type="checkbox" id="pesquisar_responsavel" name="pesquisar_responsavel" <? if (@$obj->_pesquisar_responsavel == 't') echo "checked"; ?>/></td><td><label title="Ao pesquisar pelo nome o sistema irá buscar também pelo nome do pai e da mãe no atendimento médico">Pesquisar Responsável</label></td>
                                            <td><input type="checkbox" id="prontuario_antigo_pesquisar" name="prontuario_antigo_pesquisar" <? if (@$obj->_prontuario_antigo_pesquisar == 't') echo "checked"; ?>/></td><td><label title="Ao ativar essa flag será possível pesquisar pelo prontuário antigo no atendimento médico e os campos de CID e Procedimento irão desaparecer">Pesquisar Prontuário Antigo </label></td>
                                            <td><input type="checkbox" id="tarefa_medico" name="tarefa_medico" <? if (@$obj->_tarefa_medico == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, poderá cadastrar tarefas para médicos.">Tarefas dos Médicos </label></td>
                                            <td><input type="checkbox" id="solicitar_sabin" name="solicitar_sabin" <? if (@$obj->_solicitar_sabin == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag, a solicitação de exames SADT funciona no modelo do Sabin">Solicitar Exames Sabin </label></td>
                                        </tr>  
                                        <tr>
                                            <td><input type="checkbox" id="corretor_ortografico" name="corretor_ortografico" <? if (@$obj->_corretor_ortografico == 't') echo "checked"; ?>/></td><td><label title="Será possível usar o corretor ortográfico do navegador no atendimento médico">Corretor Ortográfico</label></td>
                                            <td><input type="checkbox" id="remove_margem_cabecalho_rodape" name="remove_margem_cabecalho_rodape" <? if (@$obj->_remove_margem_cabecalho_rodape == 't') echo "checked"; ?>/></td><td><label title="Ao ativar essa flag, ao imprimir, irá tirar a margem do cabeçalho e do rodapé">Retirar margem do cabeçalho e rodapé</label></td>
                                            <td><input type="checkbox" id="laudo_status_f" name="laudo_status_f" <? if (@$obj->_laudo_status_f == 't') echo "checked"; ?>/></td><td><label title="">Laudo Status F</label></td>
                                            <td><input type="checkbox" id="historico_completo" name="historico_completo" <? if (@$obj->_historico_completo == 't') echo "checked"; ?>/></td><td><label title="">Historico completo Atendimento medico</label></td>
                                        </tr>
                                        <tr>
                                        <td><input type="checkbox" id="entrega_laudos" name="entrega_laudos" <? if (@$obj->_entrega_laudos == 't') echo "checked"; ?>/></td><td><label title="">Entrega de Laudos</label></td>
                                        <td><input type="checkbox" id="bardeira_status" name="bardeira_status" <? if (@$obj->_bardeira_status == 't') echo "checked"; ?>/></td><td><label title="">Bardeiras de Status</label></td>
                                        <td><input type="checkbox" id="bardeira_status" name="diagnostico_medico" <? if (@$obj->_diagnostico_medico == 't') echo "checked"; ?>/></td><td><label title="">Diagnostico Médico</label></td>
                                        <td><input type="checkbox" id="impressoes_acompanhamento" name="impressoes_acompanhamento" <? if (@$obj->_impressoes_acompanhamento == 't') echo "checked"; ?>/></td><td><label title="">Impressões no Acompanhamento</label></td>
                                        </tr>  

                                        <tr>
                                         <td><input type="checkbox" id="atendimento_medico_3" name="atendimento_medico_3" <? if (@$obj->_atendimento_medico_3 == 't') echo "checked"; ?>/></td><td><label title="">Atendimento Médico Novo</label></td>
                                         <td><input type="checkbox" id="atender_todos" name="atender_todos" <? if (@$obj->_atender_todos == 't') echo "checked"; ?>/></td><td><label title="">Liberar botão atender(TODOS)</label></td>
                                         <td><input type="checkbox" id="btn_encaixe" name="btn_encaixe" <? if (@$obj->_btn_encaixe == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag o botao de encaixe ficara bloqueado para todos os usuarios">Bloquear Botão Encaixe</label></td>
                                         <td><input type="checkbox" id="modificar_btn_multifuncao" name="modificar_btn_multifuncao" <? if (@$obj->_modificar_btn_multifuncao == 't') echo "checked"; ?>/></td><td><label title="Ativando essa flag só irá aparecer na multifunção geral o botão Atender e Chamar">Modificar botões Multifunção</label></td>
                                        </tr>
                                    </table>
                                </fieldset>
                                <br><br><br>
                                <fieldset>
                                    <legend><b><u>Configurações do Estoque</u></b></legend><br>
                                    <table align="center" style="width:100%">
                                        <tr>
                                            <td><input type="checkbox" id="manternota" name="manternota" <? if (@$obj->_manternota == 't') echo "checked"; ?>/> <label title="O Estoque passará a funcionar cadastrando a nota fiscal antes de cadastrar os produtos.">Manter Nota Fiscal</label></td>                                                                                                           
                                        </tr>
                                    </table>
                                </fieldset>

                            <? } ?>

                            </dl>    
                            <hr/>
                            <button type="submit" name="btnEnviar">Enviar</button>
                            <button type="reset" name="btnLimpar">Limpar</button>
                            <button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
                            </form>
                            </div>
                            </div>
                            </div> <!-- Final da DIV content -->

                            <link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
                            <script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
                            <script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
                            <script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
                            <script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
                            <link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
                            <!--<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">-->
                            <link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
                            <script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
                            <!--<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
                            <script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>

                            <script type="text/javascript">

                                //    $('#btnVoltar').click(function () {
                                //        $(location).attr('href', '<?= base_url(); ?>ponto/cargo');
                                //    });

                                $(function () {
                                    $("#accordion").accordion();
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
                                            $("#codigoibge").val(ui.item.codigo_ibge);
                                            return false;
                                        }
                                    });
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

                            </script>

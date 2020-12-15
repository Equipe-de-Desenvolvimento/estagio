<div class="content ficha_ceatox">

    <div class="bt_link_new" style="width: 150pt">
        <a style="width: 150pt" onclick="javascript:window.open('<?= base_url() ?>seguranca/operador/novorecepcao');">
            Novo Medico Solicitante
        </a>
    </div><div class="bt_link_new">
        <a href="<?= base_url() ?>cadastros/pacientes">
            Cadastros
        </a>
    </div>
    <div >
        <?
        $sala = "";
        $ordenador1 = "";
        $sala_id = "";
        $medico_id = "";
        $medico = "";
        $medico_solicitante = "";
        $medico_solicitante_id = "";
        $convenio_paciente = "";
        $empresa_id = $this->session->userdata('empresa_id');
        ?>
        <h3 class="singular"><a href="#">Faturar Guia</a></h3>
        <div>
            <form name="form_guia" id="form_guia" action="<?= base_url() ?>ambulatorio/guia/gravarprocedimentosfaturamento" method="post">
                <fieldset>
                    <legend>Dados do Paciente</legend>
                    <div>
                        <label>Nome</label>                      
                        <input type="text" id="txtNome" name="nome"  class="texto09" value="<?= $paciente['0']->nome; ?>" readonly/>
                        <input type="hidden" id="txtpaciente_id" name="txtpaciente_id"  value="<?= $paciente_id; ?>"/>
                        <input type="hidden" id="txtguia_id" name="txtguia_id"  value="<?= $guia_id; ?>"/>
                        <input type="hidden" id="txtdata" name="txtdata"  value="<?= $exames['0']->data; ?>"/>
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
                            ?>>Outros</option>
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

                    <?
                    if ($empresaPermissao[0]->cirugico_manual == 't') {
                        ?>
                        <div>
                            <label>Nº Carteira</label> 

                            <?
                            if (@$paciente['0']->convenionumero == "") {
                                @$escondernumcat = "";
                            } else {
                                @$escondernumcat = "readonly";
                            }
                            ?>
                            <input type="text" name="numcarteira" id="numcarteira" title="<?= @$paciente['0']->convenionumero; ?>" class="texto03" value="<?= @$paciente['0']->convenionumero; ?>" <?= @$escondernumcat; ?>/>
                        </div>
                    <? }
                    ?>

                    <br> <br> <br>
                  


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
                                <option  value="<?= $item->rn_id; ?>"   ><?= $item->codigo; ?> - <?= $item->descricao; ?> </option>
                                
                                <?
                                    
                                }
                                ?>
                                 
<!--                         <option value="1" >1 - NÃO</option>
                             <option value="2">2 - SIM</option>
                                -->
                                
                                
                                
                            </select>
                        </div>
                    
                    
                        <div>
                            <label> Caráter de Atendimento</label>
                            <select name="carater">
                                 <option value="" >Escolha</option>
                                <?
                                foreach($carater as $item){
                                    ?>
                                
                                   <option value="<?= @$item->carater_id; ?>"  ><?= @$item->codigo; ?> - <?= @$item->descricao; ?></option>
                                
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
                                <option value="<?= @$item->indicacao_acidente_id; ?>"  ><?= @$item->codigo; ?> - <?= @$item->descricao; ?></option>
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
                                
                                <option value="<?= @$item->tipos_cirurgia_id ?>"><?= @$item->codigo; ?> - <?= @$item->descricao; ?></option>
                                <?
                                    
                                    
                                }
                                    ?> 
                                
                                
                            </select>
                        </div>   

                    <? } ?>
                     
                    
                    
                </fieldset>

                <fieldset>

                    <table id="table_justa">
                        <thead> 
                            <tr>
                                <th class="tabela_header">Qtde</th>
                                <th class="tabela_header">Convenio</th>
                                <?
                                if ($empresaPermissao[0]->cirugico_manual == 't') {
                                    ?>
                                    <th class="tabela_header">Grupo</th>
                                <? } ?>
                                <th class="tabela_header">Procedimento</th>
                                <th class="tabela_header">Medico</th>

                                <?
                                if ($empresaPermissao[0]->cirugico_manual == 't') {
                                    ?>
                                    <th class="tabela_header">Solicitante</th>

                                <? } ?>
                                   <!--<th class="tabela_header">Tipo</th>-->
                                <th class="tabela_header">Autorizacao</th> 
                                <?
                                if ($empresaPermissao[0]->cirugico_manual == 't') {
                                    ?>
                                    <th class="tabela_header">Nº Guia </th>
                                <? } ?>
                                <?
                                if ($empresaPermissao[0]->cirugico_manual == 't') {
                                    ?>
                                    <th class="tabela_header">Grau participação</th>
                                <? } ?>
                                <th class="tabela_header">V. Unit</th>
                                <th class="tabela_header">Empresa</th>
                                <th class="tabela_header">Laudo</th>
<!--                                <th class="tabela_header">Observa&ccedil;&otilde;es</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td  width="10px;"><input type="text" name="qtde1" id="qtde1" value="1" onchange="alteraQuantidade()" class="texto00"/></td>
                                <td  width="50px;">
                                    <select  name="convenio1" id="convenio1" class="size1" required="">
                                        <option value="-1">Selecione</option>
                                        <? foreach ($convenios as $item) : ?>
                                            <option value="<?= $item->convenio_id; ?>"><?= $item->nome; ?></option>
                                        <? endforeach; ?>
                                    </select>
                                </td>
                                <?
                                if ($empresaPermissao[0]->cirugico_manual == 't') {
                                    ?>
                                    <td >
                                        <select  name="grupo1" id="grupo1" class="size1" >
                                            <option value="">Selecione</option>
                                            <?
                                            foreach ($grupos as $item) :
                                                ?>
                                                <option value="<?= $item->nome; ?>">
                                                    <?= $item->nome; ?>
                                                </option>
                                            <? endforeach; ?>
                                        </select>
                                    </td>
                                    <?
                                }else{
                                  ?>
                        <input type="hidden" name="grupo1" id="grupo1" value="" >
                                    <?
                                }
                                ?>

                                <td  width="50px;">
                                    <select name="procedimento1" id="procedimento1" class="size2   " data-placeholder="Selecione" tabindex="1" required="">
                                        <option value="">Selecione</option>
                                    </select>
                                </td>
                                <td > 
                                    <select  name="medicoagenda" id="medicoagenda" class="size2" required="">
                                        <option value="">Selecione</option>
                                        <? foreach ($medicos as $item) : ?>
                                            <option value="<?= $item->operador_id; ?>"<?
                                        if ($medico == $item->nome):echo 'selected';
                                        endif;
                                            ?>><?= $item->nome; ?></option>
                                                <? endforeach; ?>
                                    </select></td>
                                <?
                                if ($empresaPermissao[0]->cirugico_manual == 't') {
                                    ?>
                                    <td>
                                        <input type="text" name="medico1" id="medico1" value="<?= $medico_solicitante; ?>" class="size2"/>
                                        <input type="hidden" name="crm1" id="crm1" value="<?= $medico_solicitante_id; ?>" class="texto01"/>
                                    </td>
                                <? } ?>
                                <td  width="50px;"><input type="text" name="autorizacao1" id="autorizacao" class="size1"/></td>

                                <?
                                if ($empresaPermissao[0]->cirugico_manual == 't') {
                                    ?>
                                    <td  width="50px;">
                                        <input type="text" name="guia_convenio" id="guia_convenio" class="size1"/>
                                    </td>
                                <? } ?>
                                <?
                                if ($empresaPermissao[0]->cirugico_manual == 't') {
                                    ?>
                                    <td>
                                        <select  name="grau_participacao" id="grau_participacao" class="size06" >
                                            <option value="">Escolha</option>
                                            <?
                                            foreach ($listargrauparticipacao as $item) {
                                                ?>
                                                <option value="<?= $item->grau_participacao_id; ?>"  ><?= $item->codigo; ?> -<?= $item->descricao; ?></option>
                                                <?
                                            }
                                            ?>
                                        </select> 
                                    </td>
                                <? } ?>

                                <td  width="20px;">
                                    <?
                                    if ($empresaPermissao[0]->cirugico_manual == 't') {
                                        $desativar = "";
                                    } else {
                                        $desativar = 'readonly=""';
                                    }
                                    ?>

                                    <input type="text" name="valor1" id="valor1" class="texto01" <?= $desativar; ?>/>
                                    <input type="hidden" name="valortot" id="valortot" class="texto01" readonly=""/>

                                </td>
                                <td>
                                    <select  name="txtempresa" id="empresa" class="size06" >
                                        <? foreach ($empresa as $item) : ?>
                                            <option value="<?= $item->empresa_id; ?>" <?
                                        if ($empresa_id == $item->empresa_id):echo 'selected';
                                        endif;
                                            ?>>
                                                    <?= $item->nome; ?></option>
                                            <? endforeach; ?>
                                    </select>
                                </td>
                                <td  width="10px;"><input type="checkbox" name="laudo" /></td>
        <!--                                <td  width="70px;"><input type="text" name="observacao" id="observacao" class="texto04"/></td>-->
                            </tr>

                        </tbody>

                        <tfoot>
                            <tr>
                                <th class="tabela_footer" colspan="4">
                                </th>
                            </tr>
                        </tfoot>
                    </table> 
                    <hr/>
                    <button type="submit" name="btnEnviar">Adicionar</button>
                </fieldset>
            </form>
            <fieldset>
                <?
                $total = 0;
                $guia = 0;
                ?>
                <table id="table_agente_toxico" border="0">
                    <thead>

                        <tr>
                            <th width="130px;" class="tabela_header">Data de Atendimento</th>
                            <th width="130px;" class="tabela_header">Data de Faturamento</th>
                            <th class="tabela_header">Hora</th>
                            <?
                            if ($empresaPermissao[0]->cirugico_manual == 't') {
                                ?>
                                <th class="tabela_header">Solicitante</th>
                                <th class="tabela_header">Grau de Participação</th>
                                <th class="tabela_header">Nº Guia</th>
                                <th class="tabela_header"> 
                                    Incluir Atendimento à RN </th>
                                <th class="tabela_header"> 
                                   Caráter de Atendimento </th>
                                <th class="tabela_header">
                                    Indicação Acidente </th>
                                
                                <th class="tabela_header">
                                  Tipo Cirurgia </th>
                            <? } ?>
                            <th class="tabela_header">Valor</th>
                            <th class="tabela_header">Convênio</th>
                            <th class="tabela_header">Autorização</th>
                            <th class="tabela_header">Procedimento</th>
                            <th class="tabela_header">QTDE</th>

                            <th colspan="4" class="tabela_header">&nbsp;</th>
                        </tr>
                    </thead>
                    <?
               
                   
                    $estilo_linha = "tabela_content01";
                    foreach ($exames as $item) {
                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                        $total = $total + $item->valor_total;
                        $guia = $item->guia_id;
                        ?>
                        <tbody>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= substr($item->data, 8, 2) . '/' . substr($item->data, 5, 2) . '/' . substr($item->data, 0, 4); ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= substr($item->data_faturar, 8, 2) . '/' . substr($item->data_faturar, 5, 2) . '/' . substr($item->data_faturar, 0, 4); ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= substr($item->data_autorizacao, 11, 8); ?></td>


                                <?
                                if ($empresaPermissao[0]->cirugico_manual == 't') {
                                    ?>
                                    <td class="<?php echo $estilo_linha; ?>"><?= @$item->solicitante ?></td>
                                    <td class="<?php echo $estilo_linha; ?>"><?= @$item->grau_participacao ?></td>
                                    
                                    <td class="<?php echo $estilo_linha; ?>">
                                        <a style="cursor: pointer;" onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/guiaconvenioexamefaturar/" . $item->guia_id . '/' . $item->agenda_exames_id; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=600,height=450');">
                                            =><?= @$item->guiaconvenio ?>
                                        </a>
                                    </td>


                                    <td class="<?php echo $estilo_linha; ?>"> 
                                        
                                        <?= @$item->rn ?>
                                    </td>
                                    
                                    
                                    
                                    
                                    <td class="<?php echo $estilo_linha; ?>"><?=
                                           @$item->carater  
                                   
                          
                                    ?>
                                    </td>
                                    <td class="<?php echo $estilo_linha; ?>">
                                        <?=
                                           $item->indicacao_acidente  
                                   
                          
                                    ?>
                                    </td>  <td class="<?php echo $estilo_linha; ?>">
                                        <?=
                                           $item->tipo_cirurgia  
                                   
                          
                                    ?>
                                    </td>
                                  
                                <? } ?>

                                <td class="<?php echo $estilo_linha; ?>"><?= number_format($item->valor_total, 2, ',', '.'); ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->convenio; ?></td>
                                <td class="<?php echo $estilo_linha; ?>">
                                    <a style="cursor: pointer;" onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/alterarautorizacao/" . $item->agenda_exames_id; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=600,height=450');">
                                        =><?= $item->autorizacao; ?>
                                    </a>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->exame; ?></td>
                                <td class="<?php echo $estilo_linha; ?>">
                                    <a style="cursor: pointer;" onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/alterarquantidadeprocedimento/" . $item->guia_id . '/' . $item->agenda_exames_id; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=600,height=450');">
                                        =><?= @$item->quantidade ?>
                                    </a>
                                </td>
                                <? if ($item->faturado != "t") { ?>
                                    <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                            <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/faturarconvenio/" . $item->agenda_exames_id; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=600,height=250');">Faturar
                                            </a></div>
                                    </td>
                            <input type="hidden" id="txtpaciente_id" name="txtpaciente_id"  value="<?= $paciente_id; ?>"/>
                            <input type="hidden" id="txtguia_id" name="txtguia_id"  value="<?= $guia_id; ?>"/>
                                    <?php 
                                if ($item->sala_id != "") {
                                        ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;">
                                            <div >
                                                <div class="bt_link">
                                                    <a style="cursor: pointer;" onclick="javascript:window.open('<?= base_url() . "ambulatorio/exame/gastosdesala/$item->exames_id/$item->convenio_id/$item->sala_id" ?>', '_blank', 'toolbar=no,Location=no,menubar=no,scrollbars=yes,width=1000,height=900');">
                                                        Inserir
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    <?php } ?>
                             <td class="<?php echo $estilo_linha; ?>" width="60px;">
                                <div class="bt_link">
                                    <a href="<?= base_url() . "ambulatorio/guia/valorexamefaturamento/{$paciente_id}/{$guia_id}/{$item->agenda_exames_id}"; ?>">
                                        Editar
                                    </a>
                                </div>
                            </td>                            
                        <? } else { ?>
                            <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                    <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/desfazerfaturadoconvenio/" . $item->agenda_exames_id; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=600,height=250');">Desfazer
                                    </a></div>
                            </td>                          
                        <? } ?>
                            
                            <td class="<?php echo $estilo_linha; ?>" colspan="3">
                            <div class="bt_link_new" width="140" >
                                <a  onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/alterardata/" . $item->agenda_exames_id; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=600,height=450');">Alterar Data Agend.
                                </a>
                            </div>
                            <div class="bt_link_new"  width="140">
                                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/alterardatafaturamento/" . $item->agenda_exames_id; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=600,height=450');">Alterar Data Fatura.
                                </a>
                            </div>
                        </td>

                        </tr>

                        </tbody>
                        <?
                    }
                    ?>
                    <tfoot>
                        <tr>
                            <th class="tabela_footer" colspan="6">
                                Valor Total: R$ <?php echo number_format($total, 2, ',', '.'); ?>
                            </th>
                            <th colspan="2"><div class="bt_link_new">
                                    <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/faturarguias/" . $guia_id; ?> ');">Faturar todos
                                    </a>
                                </div><div class="bt_link_new">
                                    <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/desfazerfaturadoguiaconvenio/" . $guia_id; ?> ');">Desfazer todos
                                    </a>
                                </div></th>
                            <th colspan="2">
                                <div class="bt_link_new">
                                    <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/imprimirespelho/" . $guia_id . '/' . $paciente_id ?> ');">Imprimir Espelho
                                    </a>
                                </div>
                            </th>
                        </tr>
                    </tfoot>
                </table> 

            </fieldset>

        </div> 
    </div> 
</div> <!-- Final da DIV content -->
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>

-->

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

<style>
    .chosen-container{ margin-top: 5pt;}
    #procedimento1_chosen a { width: 230px; }
</style>

<script type="text/javascript">

$(":submit").click(function() {
  $(this).prop("disabled",true);
});


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

                                        function percentual() {
                                            var valordesconto = parseFloat(document.form_guia.desconto.value.replace(",", "."));
                                            var desconto = valordesconto / 100;
                                            var valortot = document.getElementById("valortot").value;
                                            var valor = valortot * desconto;
                                            var r = valor.toFixed(2);

                                            document.getElementById("valor1").value = r;
                                        }

                                        $(function () {
                                            $("#data").datepicker({
                                                autosize: true,
                                                changeYear: true,
                                                changeMonth: true,
                                                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                                                dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                                                buttonImage: '<?= base_url() ?>img/form/date.png',
                                                dateFormat: 'dd/mm/yy'
                                            });
                                        });

                                        $(function () {
                                            $("#accordion").accordion();
                                        });



                                        $(function () {
                                            $('#convenio1').change(function () {
                                                if ($(this).val()) {
                                                    $('.carregando').show();
                                                    $.getJSON('<?= base_url() ?>autocomplete/procedimentoconveniofaturar', {convenio1: $(this).val(), grupo1: $("#grupo1").val(), ajax: true}, function (j) {
                                                        options = '<option value=""></option>';
                                                        for (var c = 0; c < j.length; c++) {
                                                            options += '<option value="' + j[c].procedimento_convenio_id + '">' + j[c].procedimento + '</option>';
                                                        }
//                                                        $('#procedimento1').html(options).show();
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


                                        $(function () {
                                            $('#procedimento1').change(function () {
                                                if ($(this).val()) {
                                                    $('.carregando').show();
                                                    $.getJSON('<?= base_url() ?>autocomplete/procedimentovalor', {procedimento1: $(this).val(), ajax: true}, function (j) {

                                                        var valorTotal = parseFloat(j[0].valortotal);
                                                        var qt = document.getElementById("qtde1").value;
                                                        document.getElementById("valor1").value = valorTotal;
                                                        document.getElementById("valortot").value = valorTotal;
                                                        $('.carregando').hide();

                                                    });
                                                } else {
                                                    $('#valor1').html('value=""');
                                                }
                                            });
                                        });

                                        function alteraQuantidade() {
                                            if ($("#procedimento1").val()) {
                                                $('.carregando').show();
                                                $.getJSON('<?= base_url() ?>autocomplete/procedimentovalor', {procedimento1: $("#procedimento1").val(), ajax: true}, function (j) {

                                                    var valorTotal = parseFloat(j[0].valortotal);
                                                    var qt = document.getElementById("qtde1").value;
//                                                    document  .getElementById("valor1").value = qt * valorTotal;
                                                    document.getElementById("valortot").value = valorTotal;
                                                    $('.carregando').hide();

                                                });
                                            } else {
                                                $('#valor1').html('value=""');
                                            }
                                        }


                                        $(function () {
                                            $('#grupo1').change(function () {
                                                if ($(this).val()) {
                                                    $('.carregando').show();
                                                    $.getJSON('<?= base_url() ?>autocomplete/procedimentoconveniofaturar', {convenio1: $("#convenio1").val(), grupo1: $(this).val(), ajax: true}, function (j) {
                                                        options = '<option value=""></option>';
                                                        for (var c = 0; c < j.length; c++) {
                                                            options += '<option value="' + j[c].procedimento_convenio_id + '">' + j[c].procedimento + '</option>';
                                                        }
//                                                        $('#procedimento1').html(options).show();
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



</script>
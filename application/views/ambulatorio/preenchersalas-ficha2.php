<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title >Agendar Atendimentos</title>
<link href="<?= base_url() ?>css/estilo.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/style_p.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/jquery-treeview.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/scripts_alerta.js" ></script>

<style>
    td{
        font-family: arial;
    }
     #fontletras{
        font-size: 12px;
    }#txtconclusao{
        width: 73%;
    }#teste{
          font-size: 10px;
    }#teste2{
         font-size: 10px;
    }#teste:hover{
         font-size: 14px;
         background-color: silver;         
    }
    #teste2:hover{
         font-size: 14px;
         background-color: silver;
         
    }
    
</style>


<?
  $endereco = $empresa[0]->endereco_toten;          
if (@$listarmedico[0]->hora_tarde > 0) {
    @list($h_tarde, $m_tarde) = explode(':', $listarmedico[0]->hora_tarde);
    @$tempo_total_tarde = $h_tarde * 60 + $m_tarde;
    ?>
    <input type='text' value="<?= $tempo_total_tarde ?>" id="verifiq_tarde" hidden> 
    <?
}

if (@$listarmedico[0]->hora_manha > 0) {
    @list($h_manha, $m_manha) = explode(':', $listarmedico[0]->hora_manha);
    @$tempo_total_manha = $h_manha * 60 + $m_manha;
    ?>

    <input type='text' value="<?= $tempo_total_manha ?>" id="verifiq_manha" hidden>
    <?
}
?> 

<div>
    <?
    $dataFuturo = date("Y-m-d");
    $dataAtual = @$obj->_nascimento;
    $date_time = new DateTime($dataAtual);
    $diff = $date_time->diff(new DateTime($dataFuturo));
    $teste = $diff->format('%Ya %mm %dd');

    if (@$empresapermissao[0]->campos_atendimentomed != '') {
        $opc_telatendimento = json_decode(@$empresapermissao[0]->campos_atendimentomed);
    } else {
        $opc_telatendimento = array();
    }
    ?>
    <?php
    $this->load->library('utilitario');
//    var_dump($this->session->flashdata('message')); die;
    //Utilitario::pmf_mensagem($this->session->flashdata('message'));
    $utilitario = new Utilitario();
    $utilitario->pmf_mensagem($this->session->flashdata('message'));
    ?>
    
    <input type="hidden" value="<?= $empresa[0]->endereco_toten; ?>" name="endereco_toten" id="endereco_toten">
    
    <div>
        <form name="holter_laudo" id="holter_laudo" action="<?= base_url() ?>ambulatorio/laudo/gravarhorapoltronamedico" method="post">
            <div> 
                <fieldset>
                    <legend>Agendamento Geral</legend>
                    <div>
                        <label>Nome</label>
                        <input type="hidden" id="txtNomeid" class="texto_id" name="paciente_id" readonly="true" />
                        <input type="text" id="txtNome" required name="txtNome" class="texto10" onblur="calculoIdade(document.getElementById('nascimento').value)"/>
                        <div style="display: none">
                            <input type="text" id="medicoid" name="medicoid" class="texto_id" value="<?= @$medico; ?>"/>
                            <input type="text" id="agendaid" name="agendaid" class="texto_id" value="<?= @$agenda_exames_id; ?>"/>
                        </div>
                         
                    </div>
                    <!--                    <div>
                                            <label>Dt de nascimento</label>
                                            <input type="text" name="nascimento" id="nascimento" class="texto02"  />                
                                        </div>-->
                    <div>
                        <label>Prontuario Antigo</label>
                        <input type="number" name="prontu_antigo" id="prontu_antigo" class="texto02" required=""/> 
                        <input type="number" name="prontu_antigo_v" id="prontu_antigo_v" class="texto02"  hidden=""/> 
                    </div>
                    <div>
                        <label>Sala</label>
                        <select name="exame_sala_id" id="exame_sala_id"> 
                            <option value="0">Selecione</option>
                            <?php foreach($salas as $item){ ?>
                                <option value="<?= $item->exame_sala_id ;?>" <?= ($item->nome == "POLTRONA")? "selected":"";?>><?= $item->nome ;?></option>
                            <?}?>
                        </select> 
                      
                    </div>
                   
                    </form>
                </fieldset>
                <fieldset>
                    <table align = "center"  width="500px">
                        <tr>                            
                            <td><h1 id="fontletras"  align = "center">Agendar Atendimentos</h1></td>   
                            <td>
<!--                                <button type="button" name="btnconsultacate" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/listaragendaatendimentos/<?= $sala_id; ?>');" >
                                    Consultar Agendar Atendimentos
                                </button>-->
                            </td>
                        </tr>
                    </table>
                </fieldset>

                <?
//                    $exameslab = json_decode(@$exameslab[0]->exameslab);
                ?>

                <fieldset  style="border-bottom: none;">
                    <table align="center" style="margin-left:5%;">
                        <td><button type="submit" name="btnEnviar">Salvar</button></td>

                    </table> 
                    <table align="left" >


                        <tr>
                            <td>
                                <table width="600px" height="450px">

                                    <tr>
                                        <td>
                                            Data:   
                                        </td>                                       
                                        <td>
                                            <input type="text" alt="date" name="data" id="data" class="" required="">
                                            <!--<input type="hidden" alt="text" name="agenda_exames_id" id="agenda_exames_id" class="" value="<?= $agenda_exames_id; ?>"  required="">-->
                                        </td> 
                                    </tr> 
                                    <tr>
                                        <td>
                                            Tempo de Atendimento (Horas:Minutos):   
                                        </td>                                       
                                        <td id="tempo_atendento_td">
                                            <div id="tempo_atendimento2_div">
                                                <input type="text"  alt="time"  id="tempo_atendimento" name="tempo_atendimento"  required="" >
                                            </div>

                                            <input type="hidden"   id="medico_id" name="medico_id" value="<?= @$this->session->userdata('operador_id') ?>">

                                        </td>         
                                    </tr>

                                    <tr>
                                        <td>
                                            Turno  
                                        </td>
                                        <td  id="turno_escolha_td">

                                            <div id="sopraremover_turno">
                                                Manhã  <input type="radio"   id="manha" name="turno" value="manha" required="" >
                                                Tarde <input type="radio"   id="turno" name="turno" value="tarde" required="" > 
                                            </div>


                                        </td>           
                                    </tr> 

                                    <tr>
                                        <td>
                                            Observação:   
                                        </td>                                       
                                        <td>
                                            <textarea type="text" id="txtconclusao" name="observacao" class="texto"  value="" cols="50" rows="2"><?= @$obj->conclusao; ?></textarea>
                                        </td> 
                                    </tr>  
                                    <tr>
                                        <td>
                                            Medicamentos:   
                                        </td>                                       
                                        <td>
                                            <textarea type="text" id="txtconclusao" name="medicamentos" class="texto"  value="" cols="50" rows="2"><?= @$obj->medicamentos; ?></textarea>
                                        </td> 
                                    </tr>  
                                </table>
                            </td>
                        </tr>
                    </table>   
                </fieldset>
                <br> 


            </div>
        </form>
    </div>





    <fieldset style="margin-left: 45%; position: absolute; margin-top: -530px;" >


        <table border="2" style="border-collapse: collapse;" border="1" id="tabeladehoras" >
            <tr><td>
                    <b style="font-size: 12px;">  Horas - Manhã</b> 
                </td>

                <td>
                    <b style="font-size: 12px;">  Horas - Tarde</b> 

                </td>

            </tr>
 

        </table>


        <hr>

        <table id="tabelapolmanha" border="2" style="border-collapse: collapse;" border="1">
            <tr> 


            </tr>

        </table>

        <hr>    
        <br>
        <label>Sala</label>
                        <select name="sala_totem" id="sala_totem"> 
                            <option value="0">Selecione</option>
                            <?php foreach($salas as $item){ ?>
                                <option value="<?= $item->toten_sala_id ;?>" <?= ($item->nome == "POLTRONA")? "selected":"";?>><?= $item->nome ;?></option>
                            <?}?>
        </select> 
               <hr>                 
        <h1 style="font-size: 12px;">Agendamentos do dia</h1>

        <?
        $dia = "09-04-2019";
        $diaa = substr($dia, 0, 2) . "-";

        $mes = substr($dia, 3, 2) . "-";

        $ano = substr($dia, 6, 4);

        @$diasemana = date("w", mktime(0, 0, 0, $mes, $diaa, $ano));

        switch ($diasemana) {

            case"0": $dia_semana = "domingo";
                break;

            case"1": $dia_semana = "segunda";
                break;

            case"2": $dia_semana = "terca";
                break;

            case"3": $dia_semana = "quarta";
                break;

            case"﻿4": $dia_semana = "quinta";
                break;

            case"5": $dia_semana = "sexta";
                break;

            case"6": $dia_semana = "sabado";
                break;
        }

//        echo $dia_semana;
        ?>

        <?php
        @$tamanho = 'style="font-size: 12px;"';
        ?>
        <div id="agendamentos_data"  style=""> 
            <table border="2" id="tabelapol" style="border-collapse: collapse;" border="1"> 
                <tr>
                    <th <?= $tamanho; ?>>Médico</th>
                    <th <?= $tamanho; ?>>Turno</th>
                    <th <?= $tamanho; ?>>Tempo atendimento</th>
                    <th <?= $tamanho; ?>>Paciente</th>
                </tr>
            </table>
        </div>
        <br>  
        <br>
    </fieldset>

<fieldset style="margin:1px; ; width: 41%;"  >
    <h2 id="fontletras" align = "center">Todos Horários</h2>
    <div id="agendamentos_data2"  style=""> 
         <table border="2" id="tabelapoltodos" style="border-collapse: collapse;" border="1"> 
                <tr>
                    <th <?= $tamanho; ?>>Médico</th>
                    <th  <?= $tamanho; ?>>Turno</th>
                    <th  <?= $tamanho; ?>>Tempo atendimento</th>
                    <th  <?= $tamanho; ?>>Paciente</th>
                </tr>
            </table>     
    </div>
</fieldset>
</div>
  <br><br>

 


<?
// CALCULANDO A QUANTIDADES TOTAIS DE HORAS QUE O MEDICO ATENDE POR DIA
//                        $entrada = $item->hora_manha;
//                        $saida = $item->hora_tarde;
//                        $hora1 = explode(":", $entrada);
//                        $hora2 = explode(":", $saida);
//                        @$acumulador1 = ($hora1[0] * 3600) + ($hora1[1] * 60) + $hora1[2];
//                        @$acumulador2 = ($hora2[0] * 3600) + ($hora2[1] * 60) + $hora2[2];
//                        $resultado = $acumulador2 + $acumulador1;
//                        $hora_ponto = floor($resultado / 3600);
//                        $resultado = $resultado - ($hora_ponto * 3600);
//                        $min_ponto = floor($resultado / 60);
//                        $resultado = $resultado - ($min_ponto * 60);
//                        $secs_ponto = $resultado;
//Grava na variável resultado final
//                        $tempo = $hora_ponto . ":" . $min_ponto . ":" . $secs_ponto;
//                        echo $tempo;
?> 

<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>

<style>
    ui.datepick-current-Month a{
        color:red;
    }
</style>
 

<script type="text/javascript">
    var endereco_toten = $("#endereco_toten").val();
    window.onload = function() {
       
	  if ($('#data').val()) {
             
                                        $.getJSON('<?= base_url() ?>autocomplete/listarpoltronascomplete', {data: $('#data').val(), medico_id: $('#medico_id').val()}, function (j) {

                                            var options = '';
                                            var optionstodos = '';
                                            var tempo_total_hora_min = 0;
                                            var tempo_total_min = 0;
                                            var total_min = 0;
                                            var total_verificar_min = 0;
                                            var min_tarde_mais_manha;
                                            for (var t = 1; t <= 2; t++) {
                                                if (t == 1) {
                                                    var turnoordem = "Manhã";
                                                } else if (t == 2) {
                                                    var turnoordem = "Tarde";
                                                } else {
                                                }

                                                if (j.length > 0) {
                                                    options += ' <tr id="teste"> <td colspan="4" ><center> <b style="color:black;"> ' + turnoordem + ' </b></center></td>   </tr> ';
                                                }
                                                if (j.length > 0) {
                                                    optionstodos += ' <tr id="teste"> <td colspan="4" ><center> <b style="color:black;"> ' + turnoordem + ' </b></center></td>   </tr> ';
                                                }
                                                
                                                for (var c = 0; c < j.length; c++) {
                                                    var nome_paciente = "";
                                                    if (j[c].paciente != "") {
                                                        nome_paciente = j[c].paciente;
                                                    }
                                                    var cpf =  "null";
                                                    if (j[c].cpf != "") {
                                                        cpf = j[c].cpf;
                                                    }
                                                    var medico_poltrona = "null";
                                                    if (j[c].operador_id != "") {
                                                        medico_poltrona = j[c].operador_id;
                                                    }
                                                    var toten_sala_id = "null"; 
                                                    if (j[c].toten_sala_id != "") {
                                                        toten_sala_id = j[c].toten_sala_id;
                                                    }
                                                    var toten_fila_id = "null";
                                                    if (j[c].toten_fila_id != "") {
                                                        toten_fila_id = j[c].toten_fila_id;
                                                    }
                                                    var id_totem = "null";
                                                    if (j[c].id_totem != "") {
                                                        id_totem = j[c].id_totem;
                                                    }
                                                    console.log(id_totem);
                                                 
                                                   
                                                    var tempo_total = j[c].tempo_atendimento.substr(0, 2) * 1; //QUANTIDADE DE HORAS ##:00, onde ## = Horas pegadas
                                                    tempo_total_hora_min = tempo_total * 60; // CONVERTENDO HORAS EM MINUTOS
                                                    tempo_total_min = j[c].tempo_atendimento.substr(3, 2) * 1; //QUANTIDADE DE MINUTOS 00:##, onde ## = Minutos pegados
                                                    total_min = parseInt(tempo_total_min) + parseInt(tempo_total_hora_min); //SOMANDO MINUTOS horas+minutos 
                                                    total_verificar_min = parseInt(total_verificar_min) + parseInt(total_min); //saber qual a quantidade total de minutos de atedimento do medico

                                                    var hora_tarde_hora = j[c].hora_tarde.substr(0, 2) * 1; //PEGANDO QUANTIDADE DE HORAS
                                                    var hora_tarde_hora_min = hora_tarde_hora * 60; //CONVERTENDO HORAS EM MINUTOS
                                                    var hora_tarde_min = j[c].hora_tarde.substr(3, 2) * 1; //PEGANDO MINUTOS
                                                    var min_tarde = parseInt(hora_tarde_min) + parseInt(hora_tarde_hora_min); //SOMANDO TOTAL DE MINUTOS DA TARDE


                                                    var hora_manha_hora = j[c].hora_manha.substr(0, 2) * 1; //PEGANDO HORAS DA MANHA
                                                    var hora_manha_hora_min = hora_manha_hora * 60; //CONVERTENDO HORAS DA MANHA EM MINUTOS
                                                    var hora_manha_min = j[c].hora_manha.substr(3, 2) * 1; //PEGANDO MINUTOS DA MANHA
                                                    var min_manha = parseInt(hora_manha_min) + parseInt(hora_manha_hora_min); //SOMANDO TOTAL DE MINUTOS DA MANHA

                                                    min_tarde_mais_manha = parseInt(min_manha) + parseInt(min_tarde); //SOMANDO MUNITOS DA TARDE+MANHA

                                                    if (j[c].turno == "manha") { //ISSO É SÓ PRA CORRIGIR O NOME PARA FICAR MAIS BONITO
                                                        var turno = "Manhã";
                                                        var indent = 1;
                                                    } 
                                                    if (j[c].turno == "tarde") { //ISSO É SÓ PRA CORRIGIR O NOME PARA FICAR MAIS BONITO
                                                        var turno = "Tarde";
                                                        var indent = 2;
                                                    }
                                                    
                                                     var str = nome_paciente; 
                                                     var res = str.replace(/\s{2,}/g, ' ').replace(/\s/g, '@'); 
//                                                   console.log(res);
                                                    
                                                    //CONTRUINDO LINHAS DA TABELA COM O NOME DO MEDICO TURNO E TEMPO DE ATENDIMENTO
                                                    if (indent == t) {//serve para separar Os turnos  operador_id
                                                          
                                                         var url_enviar_ficha =   endereco_toten+'/webService/telaAtendimento/enviarFicha/'+id_totem+'/'+res+'/'+cpf+'/'+medico_poltrona+'/'+medico_poltrona;
                                                         var botao_chamar = "";
                                                         var hora_agendamento_id = j[c].hora_agendamento_id;
                                                        <? if ($endereco != '') { ?> 
                                                          botao_chamar = '<td><button onclick=chamarPaciente("'+url_enviar_ficha+'","'+id_totem+'","'+medico_poltrona+'","'+toten_sala_id+'")>Chamar</button></td>';
                                                        <?}?> 
                                                         var  botao_cancelar = '<td><a onclick="javascript: return confirm('+"'Deseja realmente cancelar?'"+')" href="<?= base_url();?>/ambulatorio/laudo/cancelarpoltrona/'+hora_agendamento_id+'" target="_blank"><button>Cancelar</button></a></td>';   
                                                     //  console.log(url_enviar_ficha);
                                                        var medico_id = $('#medico_id').val();
                                                        if (medico_id == j[c].operador_id) {
                                                            //CONTRUINDO LINHAS DA TABELA COM O NOME DO MEDICO TURNO E TEMPO DE ATENDIMENTO
                                                            options += ' <tr id="teste"> <td> <b style="color:' + j[c].cor_mapa + ';">' + j[c].medico + ' </b></td> <td> ' + turno + ' </td><td> ' + j[c].tempo_atendimento + ' </td><td> ' + j[c].paciente + ' </td>'+botao_chamar+''+botao_cancelar+'</tr> ';
                                                        } else {
                                                            optionstodos += ' <tr id="teste"> <td> <b style="color:' + j[c].cor_mapa + ';">' + j[c].medico + ' </b></td> <td> ' + turno + ' </td><td> ' + j[c].tempo_atendimento + ' </td><td> ' + j[c].paciente + ' </td>'+botao_chamar+''+botao_cancelar+'</tr> ';
                                                        }

                                                    }
                                                }
                                            }

                                            ///////////////////////////////////////
                                            $('#tabelapol #teste').remove(); //REMOVENDO TODAS AS LINHAS DA TABELA
                                            $('#tabelapol').append(options); //MANDANDO AS LINHAS DA TABELA
                                            $("#tabelapol").trigger("chosen:updated"); //ATULIZANDO


                                           ////////////////////////////////////////
                                            $('#tabelapoltodos #teste').remove();
                                            $('#tabelapoltodos').append(optionstodos); //MANDANDO AS LINHAS DA TABELA
                                            $("#tabelapoltodos").trigger("chosen:updated");

                                            $('.carregando').hide();
                                        });
                                    } else {

                                    }
                                    
                                    if ($("#data").val()) {
                                        $.getJSON('<?= base_url() ?>autocomplete/listarpoltronascompletetarde', {data: $("#data").val(), medico_id: $('#medico_id').val()}, function (j) {
                                            // alert(j[0].total);
                                            var options = '';
                                            var tempo_total_hora = 0;
                                            var tempo_total_hora_min = 0;
                                            var tempo_total_min = 0;
                                            var total_min = 0;
                                            var total_verificar_min = 0;
                                            var hora_tarde = 0;
                                            var hora_manha = 0;
                                            var hora_total = 0;
                                            var min_tarde_mais_tarde = 0;
                                            var min_tarde = 0;

                                            for (var c = 0; c < j.length; c++) {

                                                var tempo_total = j[c].tempo_atendimento.substr(0, 2) * 1;
                                                tempo_total_hora_min = tempo_total * 60;
                                                tempo_total_min = j[c].tempo_atendimento.substr(3, 2) * 1;
                                                total_min = parseInt(tempo_total_min) + parseInt(tempo_total_hora_min);
                                                total_verificar_min = parseInt(total_verificar_min) + parseInt(total_min);

//                        var hora_tarde_hora = j[c].hora_tarde.substr(0, 2) * 1;
//                        var hora_tarde_hora_min = hora_tarde_hora * 60;
//                        var hora_tarde_min = j[c].hora_tarde.substr(3, 2) * 1;
//                        var min_tarde = parseInt(hora_tarde_min) + parseInt(hora_tarde_hora_min);


                                                var hora_tarde_hora = j[c].hora_tarde.substr(0, 2) * 1;
                                                var hora_tarde_hora_min = hora_tarde_hora * 60;
                                                var hora_tarde_min = j[c].hora_tarde.substr(3, 2) * 1;
                                                min_tarde = parseInt(hora_tarde_min) + parseInt(hora_tarde_hora_min);

                                                min_tarde_mais_tarde = parseInt(min_tarde) - parseInt(total_verificar_min);

                                            }

                                            if (j.length <= 0) {
                                                min_tarde_mais_tarde = "Ainda não foi usado nenhum minuto!";
                                            }

                                            options += '<tr id="teste2"> <td><b> Minutos restantes para Tarde: </b>  </td>   <td> ' + min_tarde_mais_tarde + ' </td> </tr> ';

                                            $('#tabelapolmanha #teste2').remove();
                                            $('#tabelapolmanha').append(options);
                                            $("#tabelapolmanha").trigger("chosen:updated");
                                            $('.carregando').hide();
                                        });
                                    } else {

                                    }
                                    
                                     if ($("#data").val()) {
                                        $.getJSON('<?= base_url() ?>autocomplete/listarpoltronascompletemanha', {data: $("#data").val(), medico_id: $('#medico_id').val()}, function (j) {
                                            // alert(j[0].total);
                                            var options = '';
                                            var tempo_total_hora = 0;
                                            var tempo_total_hora_min = 0;
                                            var tempo_total_min = 0;
                                            var total_min = 0;
                                            var total_verificar_min = 0;
                                            var hora_tarde = 0;
                                            var hora_manha = 0;
                                            var hora_total = 0;
                                            var min_tarde_mais_manha = 0;
                                            var min_manha = 0;

                                            for (var c = 0; c < j.length; c++) {

                                                var tempo_total = j[c].tempo_atendimento.substr(0, 2) * 1;
                                                tempo_total_hora_min = tempo_total * 60;
                                                tempo_total_min = j[c].tempo_atendimento.substr(3, 2) * 1;
                                                total_min = parseInt(tempo_total_min) + parseInt(tempo_total_hora_min);
                                                total_verificar_min = parseInt(total_verificar_min) + parseInt(total_min);

//                        var hora_tarde_hora = j[c].hora_tarde.substr(0, 2) * 1;
//                        var hora_tarde_hora_min = hora_tarde_hora * 60;
//                        var hora_tarde_min = j[c].hora_tarde.substr(3, 2) * 1;
//                        var min_tarde = parseInt(hora_tarde_min) + parseInt(hora_tarde_hora_min);


                                                var hora_manha_hora = j[c].hora_manha.substr(0, 2) * 1;
                                                var hora_manha_hora_min = hora_manha_hora * 60;
                                                var hora_manha_min = j[c].hora_manha.substr(3, 2) * 1;
                                                min_manha = parseInt(hora_manha_min) + parseInt(hora_manha_hora_min);

                                                min_tarde_mais_manha = parseInt(min_manha) - parseInt(total_verificar_min);
//                        var trasn_min_resto = parseInt(min_tarde_mais_manha)%60; CASO QUIRAM EM HORAS 00:00
//                        var trans_hora = parseInt(min_tarde_mais_manha - trasn_min_resto)/60; CASO QUIRAM EM HORAS 00:00

                                            }

                                            if (j.length <= 0) {
                                                min_tarde_mais_manha = "Ainda não foi usado nenhum minuto!";
                                            }
                                            options += ' <tr id="teste"> <td><b> Minutos restantes para Manhã: </b></td>   <td> ' + min_tarde_mais_manha + ' </td> </tr> ';

                                            $('#tabelapolmanha #teste').remove();
                                            $('#tabelapolmanha').prepend(options);
                                            $("#tabelapolmanha").trigger("chosen:updated");
                                            $('.carregando').hide();
                                        });
                                    } else {

                                    }
                                    if ($("#data").val()) {
                                        $.getJSON('<?= base_url() ?>autocomplete/listarpoltronascompletehorasdia', {data: $('#data').val(), medico_id: $('#medico_id').val()}, function (j) { 
                                            var options = '';  
                                            for (var c = 0; c < j.length; c++) {
                                                //CONTRUINDO LINHAS DA TABELA COM O NOME DO MEDICO TURNO E TEMPO DE ATENDIMENTO
                                                if (j[c].hora_manha == null) {
                                                    j[c].hora_manha = "00:00";
                                                }


                                                if (j[c].hora_tarde == null) {
                                                    j[c].hora_tarde = "00:00";
                                                }
 
                                                options += ' <tr id="teste">  <td> ' + j[c].hora_manha + ' </td><td> ' + j[c].hora_tarde + ' </td></tr> ';

                                            }
                                            if (j.length <= 0) {
                                                $('#tabeladehoras #teste').remove();
                                            }

                                            $('#tabeladehoras #teste').remove(); //REMOVENDO TODAS AS LINHAS DA TABELA
                                            $('#tabeladehoras').append(options); //MANDANDO AS LINHAS DA TABELA
                                            $("#tabeladehoras").trigger("chosen:updated"); //ATULIZANDO
 
                                            $('.carregando').hide();
                                        });
                                    } else {

                                    } 
}


                            jQuery("#tempo_atendimento")
                                    .mask("99:99")
                                    .focusout(function (event) {
                                        var target, phone, element;
                                        target = (event.currentTarget) ? event.currentTarget : event.srcElement;
                                        phone = target.value.replace(/\D/g, '');
                                        element = $(target);
                                        element.unmask();
                                        if (phone.length > 10) {
                                            element.mask("99:99");
                                        } else {
                                            element.mask("99:99");
                                        }
                                    });


                            $(function () {
                                $('#data').change(function () {
                                    if ($(this).val()) {
                                        $.getJSON('<?= base_url() ?>autocomplete/listarpoltronascomplete', {data: $(this).val(), medico_id: $('#medico_id').val()}, function (j) {

                                            var options = '';
                                            var optionstodos = '';
                                            var tempo_total_hora_min = 0;
                                            var tempo_total_min = 0;
                                            var total_min = 0;
                                            var total_verificar_min = 0;
                                            var min_tarde_mais_manha;
                                            for (var t = 1; t <= 2; t++) {
                                                if (t == 1) {
                                                    var turnoordem = "Manhã";
                                                } else if (t == 2) {
                                                    var turnoordem = "Tarde";
                                                } else {
                                                }

                                                if (j.length > 0) {
                                                    options += ' <tr id="teste"> <td colspan="4" ><center> <b style="color:black;"> ' + turnoordem + ' </b></center></td>   </tr> ';
                                                }
                                                if (j.length > 0) {
                                                    optionstodos += ' <tr id="teste"> <td colspan="4" ><center> <b style="color:black;"> ' + turnoordem + ' </b></center></td>   </tr> ';
                                                }
                                                for (var c = 0; c < j.length; c++) {
                                                    var nome_paciente = "";
                                                    if (j[c].paciente != "") {
                                                        nome_paciente = j[c].paciente;
                                                    }
                                                    var cpf =  "null";
                                                    if (j[c].cpf != "") {
                                                        cpf = j[c].cpf;
                                                    }
                                                    var medico_poltrona = "null";
                                                    if (j[c].operador_id != "") {
                                                        medico_poltrona = j[c].operador_id;
                                                    }
                                                    var toten_sala_id = "null"; 
                                                    if (j[c].toten_sala_id != "") {
                                                        toten_sala_id = j[c].toten_sala_id;
                                                    }
                                                    console.log(j[c].toten_sala_id);
                                                    var toten_fila_id = "null";
                                                    if (j[c].toten_fila_id != "") {
                                                        toten_fila_id = j[c].toten_fila_id;
                                                    }
                                                 
                                                   
                                                    var tempo_total = j[c].tempo_atendimento.substr(0, 2) * 1; //QUANTIDADE DE HORAS ##:00, onde ## = Horas pegadas
                                                    tempo_total_hora_min = tempo_total * 60; // CONVERTENDO HORAS EM MINUTOS
                                                    tempo_total_min = j[c].tempo_atendimento.substr(3, 2) * 1; //QUANTIDADE DE MINUTOS 00:##, onde ## = Minutos pegados
                                                    total_min = parseInt(tempo_total_min) + parseInt(tempo_total_hora_min); //SOMANDO MINUTOS horas+minutos 
                                                    total_verificar_min = parseInt(total_verificar_min) + parseInt(total_min); //saber qual a quantidade total de minutos de atedimento do medico

                                                    var hora_tarde_hora = j[c].hora_tarde.substr(0, 2) * 1; //PEGANDO QUANTIDADE DE HORAS
                                                    var hora_tarde_hora_min = hora_tarde_hora * 60; //CONVERTENDO HORAS EM MINUTOS
                                                    var hora_tarde_min = j[c].hora_tarde.substr(3, 2) * 1; //PEGANDO MINUTOS
                                                    var min_tarde = parseInt(hora_tarde_min) + parseInt(hora_tarde_hora_min); //SOMANDO TOTAL DE MINUTOS DA TARDE


                                                    var hora_manha_hora = j[c].hora_manha.substr(0, 2) * 1; //PEGANDO HORAS DA MANHA
                                                    var hora_manha_hora_min = hora_manha_hora * 60; //CONVERTENDO HORAS DA MANHA EM MINUTOS
                                                    var hora_manha_min = j[c].hora_manha.substr(3, 2) * 1; //PEGANDO MINUTOS DA MANHA
                                                    var min_manha = parseInt(hora_manha_min) + parseInt(hora_manha_hora_min); //SOMANDO TOTAL DE MINUTOS DA MANHA

                                                    min_tarde_mais_manha = parseInt(min_manha) + parseInt(min_tarde); //SOMANDO MUNITOS DA TARDE+MANHA

                                                    if (j[c].turno == "manha") { //ISSO É SÓ PRA CORRIGIR O NOME PARA FICAR MAIS BONITO
                                                        var turno = "Manhã";
                                                        var indent = 1;
                                                    } 
                                                    if (j[c].turno == "tarde") { //ISSO É SÓ PRA CORRIGIR O NOME PARA FICAR MAIS BONITO
                                                        var turno = "Tarde";
                                                        var indent = 2;
                                                    }
                                                    
                                                     var str = nome_paciente; 
                                                     var res = str.replace(/\s{2,}/g, ' ').replace(/\s/g, '@'); 
//                                                   console.log(res);
                                                    
                                                    //CONTRUINDO LINHAS DA TABELA COM O NOME DO MEDICO TURNO E TEMPO DE ATENDIMENTO
                                                    if (indent == t) {//serve para separar Os turnos  operador_id
                                                          
                                                         var url_enviar_ficha =   endereco_toten+'/webService/telaAtendimento/enviarFicha/'+id_totem+'/'+res+'/'+cpf+'/'+medico_poltrona+'/'+medico_poltrona;
                                                         var botao_chamar = "";
                                                         var hora_agendamento_id = j[c].hora_agendamento_id;
                                                        <? if ($endereco != '') { ?> 
                                                          botao_chamar = '<td><button onclick=chamarPaciente("'+url_enviar_ficha+'","'+id_totem+'","'+medico_poltrona+'","'+toten_sala_id+'")>Chamar</button></td>';
                                                        <?}?> 
                                                         var  botao_cancelar = '<td><a onclick="javascript: return confirm('+"'Deseja realmente cancelar?'"+')" href="<?= base_url();?>/ambulatorio/laudo/cancelarpoltrona/'+hora_agendamento_id+'" target="_blank"><button>Cancelar</button></a></td>';   
                                                     //  console.log(url_enviar_ficha);
                                                        var medico_id = $('#medico_id').val();
                                                        if (medico_id == j[c].operador_id) {
                                                            //CONTRUINDO LINHAS DA TABELA COM O NOME DO MEDICO TURNO E TEMPO DE ATENDIMENTO
                                                            options += ' <tr id="teste"> <td> <b style="color:' + j[c].cor_mapa + ';">' + j[c].medico + ' </b></td> <td> ' + turno + ' </td><td> ' + j[c].tempo_atendimento + ' </td><td> ' + j[c].paciente + ' </td>'+botao_chamar+''+botao_cancelar+'</tr> ';
                                                        } else {
                                                            optionstodos += ' <tr id="teste"> <td> <b style="color:' + j[c].cor_mapa + ';">' + j[c].medico + ' </b></td> <td> ' + turno + ' </td><td> ' + j[c].tempo_atendimento + ' </td><td> ' + j[c].paciente + ' </td>'+botao_chamar+''+botao_cancelar+'</tr> ';
                                                        }

                                                    }
                                                }
                                            }

                                            ///////////////////////////////////////
                                            $('#tabelapol #teste').remove(); //REMOVENDO TODAS AS LINHAS DA TABELA
                                            $('#tabelapol').append(options); //MANDANDO AS LINHAS DA TABELA
                                            $("#tabelapol").trigger("chosen:updated"); //ATULIZANDO


                                           ////////////////////////////////////////
                                            $('#tabelapoltodos #teste').remove();
                                            $('#tabelapoltodos').append(optionstodos); //MANDANDO AS LINHAS DA TABELA
                                            $("#tabelapoltodos").trigger("chosen:updated");

                                            $('.carregando').hide();
                                        });
                                    } else {

                                    }

                                });
                            });



                            $("#hora_fim").mask("99:99");
                            $(function () {
                                $("#data").datepicker({
                                    autosize: true,
                                    changeYear: true,
                                    changeMonth: true, 
                                    numberOfMonths: 2,
                                    dateFormat: 'dd/mm/yy',
                                    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
                                    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
                                    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
                                    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
                                    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']

                                });
                            });













                            $(function () {
                                $('#data').change(function () {
                                    if ($(this).val()) {
                                        $.getJSON('<?= base_url() ?>autocomplete/listarpoltronascompletetarde', {data: $(this).val(), medico_id: $('#medico_id').val()}, function (j) {
                                            // alert(j[0].total);
                                            var options = '';
                                            var tempo_total_hora = 0;
                                            var tempo_total_hora_min = 0;
                                            var tempo_total_min = 0;
                                            var total_min = 0;
                                            var total_verificar_min = 0;
                                            var hora_tarde = 0;
                                            var hora_manha = 0;
                                            var hora_total = 0;
                                            var min_tarde_mais_tarde = 0;
                                            var min_tarde = 0;

                                            for (var c = 0; c < j.length; c++) {

                                                var tempo_total = j[c].tempo_atendimento.substr(0, 2) * 1;
                                                tempo_total_hora_min = tempo_total * 60;
                                                tempo_total_min = j[c].tempo_atendimento.substr(3, 2) * 1;
                                                total_min = parseInt(tempo_total_min) + parseInt(tempo_total_hora_min);
                                                total_verificar_min = parseInt(total_verificar_min) + parseInt(total_min);

//                        var hora_tarde_hora = j[c].hora_tarde.substr(0, 2) * 1;
//                        var hora_tarde_hora_min = hora_tarde_hora * 60;
//                        var hora_tarde_min = j[c].hora_tarde.substr(3, 2) * 1;
//                        var min_tarde = parseInt(hora_tarde_min) + parseInt(hora_tarde_hora_min);


                                                var hora_tarde_hora = j[c].hora_tarde.substr(0, 2) * 1;
                                                var hora_tarde_hora_min = hora_tarde_hora * 60;
                                                var hora_tarde_min = j[c].hora_tarde.substr(3, 2) * 1;
                                                min_tarde = parseInt(hora_tarde_min) + parseInt(hora_tarde_hora_min);

                                                min_tarde_mais_tarde = parseInt(min_tarde) - parseInt(total_verificar_min);

                                            }

                                            if (j.length <= 0) {
                                                min_tarde_mais_tarde = "Ainda não foi usado nenhum minuto!";
                                            }

                                            options += '<tr id="teste2"> <td><b> Minutos restantes para Tarde: </b>  </td>   <td> ' + min_tarde_mais_tarde + ' </td> </tr> ';

                                            $('#tabelapolmanha #teste2').remove();
                                            $('#tabelapolmanha').append(options);
                                            $("#tabelapolmanha").trigger("chosen:updated");
                                            $('.carregando').hide();
                                        });
                                    } else {

                                    }

                                });
                            });



                            $(function () {
                                $('#data').change(function () {
                                    if ($(this).val()) {
                                        $.getJSON('<?= base_url() ?>autocomplete/listarpoltronascompletemanha', {data: $(this).val(), medico_id: $('#medico_id').val()}, function (j) {
                                            // alert(j[0].total);
                                            var options = '';
                                            var tempo_total_hora = 0;
                                            var tempo_total_hora_min = 0;
                                            var tempo_total_min = 0;
                                            var total_min = 0;
                                            var total_verificar_min = 0;
                                            var hora_tarde = 0;
                                            var hora_manha = 0;
                                            var hora_total = 0;
                                            var min_tarde_mais_manha = 0;
                                            var min_manha = 0;

                                            for (var c = 0; c < j.length; c++) {

                                                var tempo_total = j[c].tempo_atendimento.substr(0, 2) * 1;
                                                tempo_total_hora_min = tempo_total * 60;
                                                tempo_total_min = j[c].tempo_atendimento.substr(3, 2) * 1;
                                                total_min = parseInt(tempo_total_min) + parseInt(tempo_total_hora_min);
                                                total_verificar_min = parseInt(total_verificar_min) + parseInt(total_min);

//                        var hora_tarde_hora = j[c].hora_tarde.substr(0, 2) * 1;
//                        var hora_tarde_hora_min = hora_tarde_hora * 60;
//                        var hora_tarde_min = j[c].hora_tarde.substr(3, 2) * 1;
//                        var min_tarde = parseInt(hora_tarde_min) + parseInt(hora_tarde_hora_min);


                                                var hora_manha_hora = j[c].hora_manha.substr(0, 2) * 1;
                                                var hora_manha_hora_min = hora_manha_hora * 60;
                                                var hora_manha_min = j[c].hora_manha.substr(3, 2) * 1;
                                                min_manha = parseInt(hora_manha_min) + parseInt(hora_manha_hora_min);

                                                min_tarde_mais_manha = parseInt(min_manha) - parseInt(total_verificar_min);
//                        var trasn_min_resto = parseInt(min_tarde_mais_manha)%60; CASO QUIRAM EM HORAS 00:00
//                        var trans_hora = parseInt(min_tarde_mais_manha - trasn_min_resto)/60; CASO QUIRAM EM HORAS 00:00

                                            }

                                            if (j.length <= 0) {
                                                min_tarde_mais_manha = "Ainda não foi usado nenhum minuto!";
                                            }
                                            options += ' <tr id="teste"> <td><b> Minutos restantes para Manhã: </b></td>   <td> ' + min_tarde_mais_manha + ' </td> </tr> ';

                                            $('#tabelapolmanha #teste').remove();
                                            $('#tabelapolmanha').prepend(options);
                                            $("#tabelapolmanha").trigger("chosen:updated");
                                            $('.carregando').hide();
                                        });
                                    } else {

                                    }

                                });



                            });







                            $(function () {
                                $('#data').change(function () {
                                    if ($(this).val()) {
                                        $.getJSON('<?= base_url() ?>autocomplete/listarpoltronascompletehorasdia', {data: $(this).val(), medico_id: $('#medico_id').val()}, function (j) { 
                                            var options = '';  
                                            for (var c = 0; c < j.length; c++) {
                                                //CONTRUINDO LINHAS DA TABELA COM O NOME DO MEDICO TURNO E TEMPO DE ATENDIMENTO
                                                if (j[c].hora_manha == null) {
                                                    j[c].hora_manha = "00:00";
                                                }


                                                if (j[c].hora_tarde == null) {
                                                    j[c].hora_tarde = "00:00";
                                                }
 
                                                options += ' <tr id="teste">  <td> ' + j[c].hora_manha + ' </td><td> ' + j[c].hora_tarde + ' </td></tr> ';

                                            }
                                            if (j.length <= 0) {
                                                $('#tabeladehoras #teste').remove();
                                            }

                                            $('#tabeladehoras #teste').remove(); //REMOVENDO TODAS AS LINHAS DA TABELA
                                            $('#tabeladehoras').append(options); //MANDANDO AS LINHAS DA TABELA
                                            $("#tabeladehoras").trigger("chosen:updated"); //ATULIZANDO
 
                                            $('.carregando').hide();
                                        });
                                    } else {

                                    }

                                });
                            });
 
                            $(function () {
                                $("#txtNome").autocomplete({
                                    source: "<?= base_url() ?>index.php?c=autocomplete&m=pacientepoltrona",
                                    minLength: 10, // Todas as telas de agendamento eu coloquei esse comentario. Quando for alterar esse valor, basta ir em "Localizar em Projetos" e pesquisar por ele.
                                    focus: function (event, ui) {
                                        $("#txtNome").val(ui.item.label);
                                        return false;
                                    },
                                    select: function (event, ui) {
                                        $("#txtNome").val(ui.item.value);
                                        $("#txtNomeid").val(ui.item.id);
                                        $("#txtCelular").val(ui.item.celular);
                                        $("#telefone").val(ui.item.itens);
                                        $("#nascimento").val(ui.item.valor);
                                        $("#prontu_antigo").val(ui.item.prontuario_antigo);
                                        $("#prontu_antigo_v").val(ui.item.prontuario_antigo);
                                        $("#txtEnd").val(ui.item.endereco);
                                        return false;
                                    }

                                });
                            });


                            $(function () {
                                $("#nascimento").autocomplete({
                                    source: "<?= base_url() ?>index.php?c=autocomplete&m=pacientenascimento",
                                    minLength: 3,
                                    focus: function (event, ui) {
                                        $("#nascimento").val(ui.item.label);
                                        return false;
                                    },
                                    select: function (event, ui) {
                                        $("#txtNome").val(ui.item.value);
                                        $("#txtNomeid").val(ui.item.id);
                                        $("#telefone").val(ui.item.itens);
                                        $("#nascimento").val(ui.item.valor);
                                        $("#prontu_antigo").val(ui.item.prontu_antigo);
                                        $("#prontu_antigo_v").val(ui.item.prontu_antigo);
                                        return false;
                                    }
                                });
                            });


                            $(function () {
                                $("#prontu_antigo").autocomplete({
                                    source: "<?= base_url() ?>index.php?c=autocomplete&m=pacienteprotuarioantigo",
                                    minLength: 2,
                                    focus: function (event, ui) {
                                        $("#prontu_antigo").val(ui.item.label);
                                        return false;
                                    },
                                    select: function (event, ui) {
                                        $("#txtNome").val(ui.item.nome);
                                        $("#txtNomeid").val(ui.item.id);
                                        $("#telefone").val(ui.item.itens);
                                        $("#prontu_antigo").val(ui.item.valor);
                                        $("#nascimento").val(ui.item.nascimento);
                                        $("#prontu_antigo_v").val(ui.item.valor);
                                        return false;
                                    }
                                });
                            });
                            
<? if (($endereco != '')) { ?>                            
                            function enviarChamadaPainel(url, toten_fila_id, medico_id, toten_sala_id) {
                               
                                $.ajax({
                                    type: "POST",
                                    data: {teste: 'teste'},
                                    //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                    url: "<?= $endereco ?>/webService/telaChamado/proximo/" + medico_id + '/' + toten_fila_id + '/' + toten_sala_id,
                                    success: function (data) { 
                                        alert('Operação efetuada com sucesso'); 
                                    },
                                    error: function (data) {
                                        console.log(data);
                                        alert('Erro ao chamar paciente');
                                    }
                                });
                                $.ajax({
                                    type: "POST",
                                    data: {teste: 'teste'},
                                    //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                    url: "<?= $endereco ?>/webService/telaChamado/cancelar/" + toten_fila_id,
                                    success: function (data) {

                                        //                            alert('Operação efetuada com sucesso');


                                    },
                                    error: function (data) {
//                                        console.log(data);
                                        //                            alert('Erro ao chamar paciente');
                                    }
                                });

                            }
                            function chamarPaciente(url, toten_fila_id, medico_id, toten_sala_id){ 
                              var url = url.replace(/@/g, ' '); 
                              var sala = $("#sala_totem").val();
                              var url = url+'/'+sala+'/false';
                              console.log(url);
                                $.ajax({
                                type: "POST",
                                data: {teste: 'teste'},
                                //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                url: url,
                                success: function (data) {
//                                                    console.log(data);
                                    //                    alert(data.id);  
                                    $("#idChamada").val(data.id); 
                                },
                                error: function (data) {
//                                    console.log(data); 
                                }
                                });
                                setTimeout(enviarChamadaPainel, 1000, url, toten_fila_id, medico_id, sala); 
                            }
<? } ?>

                            jQuery("#nascimento").mask("99/99/9999");
                            jQuery("#horarios").mask("99:99");




</script>



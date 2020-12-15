<meta charset="UTF-8">
<title>Relatório Agenda</title>
<div class="content"> <!-- Inicio da DIV content -->
    <? if (count($empresa) > 0) { ?>
        <h4><?= $empresa[0]->razao_social; ?></h4>
    <? } else { ?>
        <h4>TODAS AS CLINICAS</h4>
    <? } ?>
        <h4><? if ($_POST['tipoRelatorio'] == '0') {
                        echo "Agenda Consulta";
            }elseif($_POST['tipoRelatorio'] == '1'){
                 echo "Agenda Exame";
            }elseif($_POST['tipoRelatorio'] == '3'){
                echo "Agenda Especialidade";
            }elseif($_POST['tipoRelatorio'] == '4'){
                echo "Todos";
            } 
       ?></h4>
    <h4>PERÍODO: <?= str_replace("-","/",date("d-m-Y", strtotime($txtdata_inicio) ) ); ?> até <?= str_replace("-","/",date("d-m-Y", strtotime($txtdata_fim) ) ); ?></h4>
    <h4>MÉDICO: <? 
    if(count(@$medico) > 0 && $medico != null){
       echo $medico[0]->operador; 
    }else{
        echo 'TODOS';
    }
                        
    ?></h4>
    <? if (count(@$_POST['operador']) > 0 && !in_array('0', @$_POST['operador'])) { ?>
        <h4>Operador: <?
            @$cont_virgula = count($operador);

            foreach ($operador as $value) {
                echo $value->nome;
                if (@$cont_virgula <= 1) {
                    
                } else {
                    @$cont_v++;
                    if (@$cont_v == @$cont_virgula) {
                        
                    } else {
                        echo ",";
                    }
                }
            }
            ?></h4>
    <? } ?>
     <? if (count(@$_POST['operador']) == 0 || in_array('0', @$_POST['operador'])) { ?>
        <h3>Operador: TODOS</h3>
    <? } ?>
         <h4>Convênio: <?= (@$convenios > 0) ? $convenios[0]->nome : 'TODOS'; ?></h4>
    <hr>
    
        <?
    $empresa_id = $this->session->userdata('empresa_id');
    $data['retorno'] = $this->exame->permisoesempresa($empresa_id);
    $agenda_modificada = $data['retorno'][0]->agenda_modificada;
    ?>

     <style>
        
        .background{
            background-color: #d3d3d3;
        }

    </style>
    
    <?php 
        if (count($relatorio) > 0) {

            if(@$permissaoempresa[0]->agendahias == "t"){
            ?>
            <table border="1" cellspacing=0 cellpadding=2 width="100%">
        <thead>
            <tr  class="background">
                <th class="tabela_header" width="20px;">Nº</th>
                <th class="tabela_header" width="250px;">Nome</th>
                <th class="tabela_header" width="70px;">Pront.</th>
                <th class="tabela_header" width="70px;">Data Nasc.</th> 
                <th class="tabela_header" width="70px;">Telefone</th>
                <th class="tabela_header" width="70px;">Data</th>
                <th class="tabela_header" width="70px;">Cartão SUS</th>
                <th class="tabela_header" width="70px;">Médico</th>   
                <th class="tabela_header" width="150px;">Procedimento</th>
                <th class="tabela_header">Endereço</th>
                <th class="tabela_header" width="250px;">Setores</th>
        </tr>
        </thead>
        <tbody>
        <? $contador = 0;
        foreach($relatorio as $item){
            if($_POST['agendados'] == 'sim'){
            if($item->paciente_id == ''){
                continue;
            }
        }
            $contador++;
        ?>
        <tr>
        <td align="center"><b><?=$contador?></b></td>

        <td><?=$item->paciente?></td>

        <td><?=$item->prontuario_antigo?></td>

        <td><?= substr($item->data_nascimento, 8, 2) . "/" . substr($item->data_nascimento, 5, 2) . "/" . substr($item->data_nascimento, 0, 4); ?></td>
        
        <td><? if($item->telefone != ''){
            echo $item->telefone;
        }else{
            echo $item->celular;
        }
        ?></td>
    <td><?= substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4); ?></td>
        <td><?=$item->convenionumero?></td>
        <td><?=$item->medicoagenda?></td>

        <td><?=$item->procedimento?></td>

        <td><?=
        $item->logradouro.', '.$item->numero.'<br> BAIRRO:'.$item->bairro.' - '.$item->municipio.'<br> CEP:'.$item->cep
        ?>
        </td>

        <td><?= $item->setor?></td>
        </tr>
        <?
        }?>
        <tbody>

            <?  
            }else{
    ?>
    <table border="1" cellspacing=0 cellpadding=2 width="100%">
        <thead>
            <tr  class="background">
               <?
                if ($agenda_modificada == 't') {
                    ?>
                    <th class="tabela_header" width="20px;">Nº</th>
                    <?
                } else {
                    ?>
                    <th class="tabela_header" width="70px;">Status</th>
                    <?
                }
                ?>
                <th class="tabela_header" width="250px;">Nome</th>
                <th class="tabela_header" width="70px;">Resp.</th>
                <? if (@$_POST['data_mostrar'] == 'SIM') { ?>
                <th class="tabela_header" width="70px;">Data</th>
                <? } ?>
                
                <?
                if (!($agenda_modificada == 't')) {
                    ?>
                <th class="tabela_header" width="70px;">Dia</th>
                <?
                }  
                 ?>
                    

                    
                  <?
                if ($data['retorno'][0]->agenda_modificada == 't') {
                    ?>
                    <th class="tabela_header" width="70px;">Horário</th>
                    <?
                } else {
                    ?>
                    <th class="tabela_header" width="70px;">Agenda</th>   
                    <?
                }
                ?>
                <th class="tabela_header" width="150px;">Médico</th>
                <th class="tabela_header" width="150px;">Convênio</th>
                <th class="tabela_header">Telefone</th>
                <th class="tabela_header" width="250px;">Observa&ccedil;&otilde;es</th>
                <th class="tabela_header"><center>Status</center></th>
                  <?
        if (!($data['retorno'][0]->agenda_modificada == 't')) {
             if ($_POST['procedimento'] == 'SIM') {
                ?>
                <th class="tabela_header" width="150px;">Procedimentos</th>
                <?
             }                        
        }  
       ?>


        </tr>
        </thead>
        <tbody>
            <?php
            $paciente = "";
            if (count($relatorio) > 0) {
                foreach ($relatorio as $item) {
                    $dataFuturo = date("Y-m-d H:i:s");
                    $dataAtual = $item->data_atualizacao;

                    if ($item->celular != "") {
                        $telefone = $item->celular;
                    } elseif ($item->telefone != "") {
                        $telefone = $item->telefone;
                    } else {
                        $telefone = "";
                    }

                    $date_time = new DateTime($dataAtual);
                    $diff = $date_time->diff(new DateTime($dataFuturo));
                    $teste = $diff->format('%H:%I:%S');

                    if ($item->paciente == "" && $item->bloqueado == 't') {
                        $situacao = "Bloqueado";
                        $paciente = "Bloqueado";
                        $verifica = 5;
                    } else {
                        $paciente = "";

                        if ($item->realizada == 't' && $item->situacaolaudo != 'FINALIZADO') {
                            $situacao = "Aguardando";
                            $verifica = 2;
                        } elseif ($item->realizada == 't' && $item->situacaolaudo == 'FINALIZADO') {
                            $situacao = "Finalizado";
                            $verifica = 4;
                        } elseif ($item->confirmado == 'f') {
                            if ($item->encaixe == 't') {
                                $situacao = "Encaixe";
                            } else {
                                $situacao = "Agenda";
                            }

                            $verifica = 1;
                        } else {
                            $situacao = "espera";
                            $verifica = 3;
                        }
                    }
                    if ($item->paciente == "" && $item->bloqueado == 'f') {
                        $paciente = "vago";
                    }
                    $data = $item->data;
                    $dia = strftime("%A", strtotime($data));

                    switch ($dia) {
                        case"Sunday": $dia = "Domingo";
                            break;
                        case"Monday": $dia = "Segunda";
                            break;
                        case"Tuesday": $dia = "Terça";
                            break;
                        case"Wednesday": $dia = "Quarta";
                            break;
                        case"Thursday": $dia = "Quinta";
                            break;
                        case"Friday": $dia = "Sexta";
                            break;
                        case"Saturday": $dia = "Sabado";
                            break;
                    }
                    ?>
                    <tr>
                        <td ><b> 
                            <?
                                if ($agenda_modificada == 't') {
                                    echo @$numero = 1 + $numero;
                                } else {
                                    echo @$situacao;
                                }
                                ?>
                            </b></td>
                        <td <b><?= $item->paciente; ?></b></td>
                        <td ><?= substr($item->secretaria, 0, 9); ?></td>
                         <? if (@$_POST['data_mostrar'] == 'SIM') { ?>
                        <td><?= substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4); ?></td>
                         <?php }?>
                        <?
                        if (!($agenda_modificada == 't')) {
                             echo "<td>";
                            echo substr($dia, 0, 3);
                            echo " </td>";
                        } 
                        ?> 
                        <td ><?= $item->inicio; ?></td>
                        <td  width="150px;">
                            <? 
                             if (!($agenda_modificada == 't')) {
                                 if ($_POST['sala'] == 'SIM') {
                                    echo $item->sala . " - ";
                                }
                            }  
                            echo substr($item->medicoagenda, 0, 15); ?></td>
                        <td ><?= $item->convenio; ?></td>
                        <td ><?= $telefone; ?></td>
                        <td ><?= $item->observacoes; ?></td>
                        <? if ($item->bloqueado == 't') { ?>
                            <td width="60px;"> Bloqueado</td>
                            <?
                        }elseif ($item->telefonema == 't') {
                            ?>
                            <td width="60px;">Confirmado</td>
                        <? }else{?>
                            <td width="60px;"></td>
                       <? }
                        ?>
                         <?
                        if (!($agenda_modificada == 't')) {
                             if ($_POST['procedimento'] == 'SIM') { ?>
                            <td ><?=$item->procedimento; ?></td>  
                            <?
                             }
                        } 
                        ?>
                    </tr>

                </tbody>
                <?php
            }
        }
        ?>

    </table>
  <?php } }else {
            echo "<h4>Não há resultados para esta consulta.</h4>";
        }?>
</div> <!-- Final da DIV content -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript">
    $(function() {
        $("#accordion").accordion();
    });
</script>